<template>
  <q-page class="q-pa-md">
    <div class="row items-center q-mb-md">
      <div class="text-h4">My Applications</div>
      <q-space />
      <q-btn color="primary" icon="add" label="New Application" to="/applicant/applications/new" />
    </div>

    <q-table
      :rows="applications"
      :columns="columns"
      row-key="id"
      :loading="loading"
      :filter="filter"
    >
      <template v-slot:top>
        <q-input
          v-model="filter"
          placeholder="Search applications..."
          outlined
          dense
          class="q-mr-md"
        >
          <template v-slot:prepend>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>

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
        </q-td>
      </template>
    </q-table>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { api } from '../../boot/axios'
import { useRouter } from 'vue-router'

export default defineComponent({
  name: 'ApplicantApplicationsPage',
  setup() {
    const router = useRouter()
    const applications = ref([])
    const loading = ref(false)
    const filter = ref('')

    const columns = [
      { name: 'id', label: 'ID', field: 'id', align: 'left' },
      { name: 'scholarship', label: 'Scholarship', field: row => row.scholarship?.name, align: 'left' },
      { name: 'status', label: 'Status', field: 'status', align: 'center' },
      { name: 'created_at', label: 'Date Applied', field: 'created_at', format: val => new Date(val).toLocaleDateString() },
      { name: 'actions', label: 'Actions', align: 'center' }
    ]

    const getStatusColor = (status) => {
      const colors = {
        pending: 'orange',
        under_review: 'blue',
        for_screening: 'purple',
        approved: 'green',
        rejected: 'red',
        on_hold: 'grey'
      }
      return colors[status] || 'grey'
    }

    const viewApplication = (application) => {
      router.push(`/applicant/applications/${application.id}`)
    }

    const fetchApplications = async () => {
      loading.value = true
      try {
        const response = await api.get('/applications')
        applications.value = response.data.data || response.data || []
      } catch (error) {
        console.error('Error fetching applications:', error)
      } finally {
        loading.value = false
      }
    }

    onMounted(() => {
      fetchApplications()
    })

    return {
      applications,
      loading,
      filter,
      columns,
      getStatusColor,
      viewApplication
    }
  }
})
</script>
