<template>
  <q-page class="q-pa-md">
    <div class="row items-center q-mb-md">
      <div class="text-h4">Disbursement Management</div>
      <q-space />
      <q-btn color="primary" icon="add" label="New Disbursement" @click="createDisbursement" />
    </div>

    <q-card>
      <q-card-section>
        <div class="row q-col-gutter-md q-mb-md">
          <div class="col-12 col-md-4">
            <q-select v-model="filters.status" label="Status" :options="statusOptions" outlined clearable emit-value map-options />
          </div>
          <div class="col-12 col-md-4">
            <q-select v-model="filters.type" label="Type" :options="typeOptions" outlined clearable emit-value map-options />
          </div>
        </div>

        <q-table
          :rows="disbursements"
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
              <q-btn v-if="props.row.status === 'pending'" flat dense icon="check" color="positive" @click="releaseDisbursement(props.row)" />
              <q-btn flat dense icon="visibility" @click="viewDisbursement(props.row)" />
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
import { useQuasar } from 'quasar'

export default defineComponent({
  name: 'AdminDisbursementsPage',
  setup() {
    const $q = useQuasar()
    const disbursements = ref([])
    const loading = ref(false)
    const filters = ref({
      status: null,
      type: null
    })

    const statusOptions = [
      { label: 'Pending', value: 'pending' },
      { label: 'Processing', value: 'processing' },
      { label: 'Released', value: 'released' },
      { label: 'Cancelled', value: 'cancelled' }
    ]

    const typeOptions = [
      { label: 'Allowance', value: 'allowance' },
      { label: 'Tuition', value: 'tuition' },
      { label: 'Book', value: 'book' },
      { label: 'Other', value: 'other' }
    ]

    const columns = [
      { name: 'id', label: 'ID', field: 'id' },
      { name: 'reference_number', label: 'Reference', field: 'reference_number' },
      { name: 'scholar', label: 'Scholar', field: row => row.scholar?.applicant?.user?.name },
      { name: 'amount', label: 'Amount', field: 'amount', format: val => `₱${new Intl.NumberFormat('en-US').format(val)}` },
      { name: 'type', label: 'Type', field: 'type' },
      { name: 'status', label: 'Status', field: 'status' },
      { name: 'scheduled_date', label: 'Scheduled', field: 'scheduled_date', format: val => new Date(val).toLocaleDateString() },
      { name: 'actions', label: 'Actions', align: 'center' }
    ]

    const getStatusColor = (status) => {
      const colors = {
        pending: 'orange',
        processing: 'blue',
        released: 'green',
        cancelled: 'red'
      }
      return colors[status] || 'grey'
    }

    const fetchDisbursements = async () => {
      loading.value = true
      try {
        const params = {}
        if (filters.value.status) params.status = filters.value.status
        if (filters.value.type) params.type = filters.value.type

        const response = await api.get('/disbursements', { params })
        disbursements.value = response.data.data || response.data || []
      } catch (error) {
        console.error('Error fetching disbursements:', error)
      } finally {
        loading.value = false
      }
    }

    const createDisbursement = () => {
      // Open dialog or navigate to form
      console.log('Create disbursement')
    }

    const releaseDisbursement = async (disbursement) => {
      try {
        await api.post(`/disbursements/${disbursement.id}/release`)
        $q.notify({
          type: 'positive',
          message: 'Disbursement released',
          position: 'top'
        })
        fetchDisbursements()
      } catch (error) {
        $q.notify({
          type: 'negative',
          message: 'Error releasing disbursement',
          position: 'top'
        })
      }
    }

    const viewDisbursement = (disbursement) => {
      console.log('View disbursement:', disbursement)
    }

    watch(filters, () => {
      fetchDisbursements()
    }, { deep: true })

    onMounted(() => {
      fetchDisbursements()
    })

    return {
      disbursements,
      loading,
      filters,
      statusOptions,
      typeOptions,
      columns,
      getStatusColor,
      createDisbursement,
      releaseDisbursement,
      viewDisbursement
    }
  }
})
</script>
