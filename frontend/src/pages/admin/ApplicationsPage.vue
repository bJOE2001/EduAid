<template>
  <q-page class="q-pa-md">
    <div class="text-h4 q-mb-md">Application Management</div>

    <q-card>
      <q-card-section>
        <div class="row q-col-gutter-md q-mb-md">
          <div class="col-12 col-md-4">
            <q-select v-model="filters.status" label="Status" :options="statusOptions" outlined clearable emit-value map-options />
          </div>
          <div class="col-12 col-md-4">
            <q-select v-model="filters.scholarship_id" label="Scholarship" :options="scholarshipOptions" outlined clearable emit-value map-options />
          </div>
          <div class="col-12 col-md-4">
            <q-input v-model="filters.search" label="Search" outlined clearable>
              <template v-slot:prepend>
                <q-icon name="search" />
              </template>
            </q-input>
          </div>
        </div>

        <q-table
          :rows="applications"
          :columns="columns"
          row-key="id"
          :loading="loading"
        >
          <template v-slot:body-cell-status="props">
            <q-td :props="props">
              <q-chip :color="getStatusColor(props.value)" text-color="white" size="sm">
                {{ props.value }}
              </q-chip>
            </q-td>
          </template>

          <template v-slot:body-cell-actions="props">
            <q-td :props="props">
              <q-btn flat dense icon="visibility" @click="viewApplication(props.row)" />
              <q-btn v-if="canApprove(props.row)" flat dense icon="check" color="positive" @click="approveApplication(props.row)" />
              <q-btn v-if="canReject(props.row)" flat dense icon="close" color="negative" @click="rejectApplication(props.row)" />
            </q-td>
          </template>
        </q-table>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted, watch } from 'vue'
import { api } from '../../boot/axios'
import { useRouter } from 'vue-router'
import { Notify, Dialog } from 'quasar'

export default defineComponent({
  name: 'AdminApplicationsPage',
  setup() {
    const router = useRouter()
    const applications = ref([])
    const scholarships = ref([])
    const loading = ref(false)
    const filters = ref({
      status: null,
      scholarship_id: null,
      search: ''
    })

    const statusOptions = [
      { label: 'Pending', value: 'pending' },
      { label: 'Under Review', value: 'under_review' },
      { label: 'For Screening', value: 'for_screening' },
      { label: 'Approved', value: 'approved' },
      { label: 'Rejected', value: 'rejected' }
    ]

    const scholarshipOptions = ref([])

    const columns = [
      { name: 'id', label: 'ID', field: 'id' },
      { name: 'applicant', label: 'Applicant', field: row => row.applicant?.user?.name },
      { name: 'scholarship', label: 'Scholarship', field: row => row.scholarship?.name },
      { name: 'status', label: 'Status', field: 'status' },
      { name: 'created_at', label: 'Date', field: 'created_at', format: val => new Date(val).toLocaleDateString() },
      { name: 'actions', label: 'Actions', align: 'center' }
    ]

    const getStatusColor = (status) => {
      const colors = {
        pending: 'orange',
        under_review: 'blue',
        for_screening: 'purple',
        approved: 'green',
        rejected: 'red'
      }
      return colors[status] || 'grey'
    }

    const canApprove = (application) => {
      return ['under_review', 'for_screening'].includes(application.status)
    }

    const canReject = (application) => {
      return ['pending', 'under_review', 'for_screening'].includes(application.status)
    }

    const fetchApplications = async () => {
      loading.value = true
      try {
        const params = {}
        if (filters.value.status) params.status = filters.value.status
        if (filters.value.scholarship_id) params.scholarship_id = filters.value.scholarship_id
        
        const response = await api.get('/applications', { params })
        applications.value = response.data.data || response.data || []
      } catch (error) {
        console.error('Error fetching applications:', error)
      } finally {
        loading.value = false
      }
    }

    const fetchScholarships = async () => {
      try {
        const response = await api.get('/scholarships')
        scholarships.value = response.data.data || response.data || []
        scholarshipOptions.value = scholarships.value.map(s => ({
          label: s.name,
          value: s.id
        }))
      } catch (error) {
        console.error('Error fetching scholarships:', error)
      }
    }

    const viewApplication = (application) => {
      router.push(`/admin/applications/${application.id}`)
    }

    const approveApplication = (application) => {
      Dialog.create({
        title: 'Approve Application',
        message: `Approve application from ${application.applicant?.user?.name}?`,
        cancel: true
      }).onOk(async () => {
        try {
          await api.put(`/applications/${application.id}`, { status: 'approved' })
          Notify.create({
            type: 'positive',
            message: 'Application approved',
            position: 'top'
          })
          fetchApplications()
        } catch (error) {
          Notify.create({
            type: 'negative',
            message: 'Error approving application',
            position: 'top'
          })
        }
      })
    }

    const rejectApplication = (application) => {
      Dialog.create({
        title: 'Reject Application',
        message: `Reject application from ${application.applicant?.user?.name}?`,
        prompt: {
          model: '',
          type: 'text',
          label: 'Reason (optional)'
        },
        cancel: true
      }).onOk(async (reason) => {
        try {
          await api.put(`/applications/${application.id}`, {
            status: 'rejected',
            remarks: reason
          })
          Notify.create({
            type: 'positive',
            message: 'Application rejected',
            position: 'top'
          })
          fetchApplications()
        } catch (error) {
          Notify.create({
            type: 'negative',
            message: 'Error rejecting application',
            position: 'top'
          })
        }
      })
    }

    watch(filters, () => {
      fetchApplications()
    }, { deep: true })

    onMounted(() => {
      fetchApplications()
      fetchScholarships()
    })

    return {
      applications,
      loading,
      filters,
      statusOptions,
      scholarshipOptions,
      columns,
      getStatusColor,
      canApprove,
      canReject,
      viewApplication,
      approveApplication,
      rejectApplication
    }
  }
})
</script>
