<template>
  <q-page class="q-pa-md">
    <div class="text-h4 q-mb-md">Scholar Management</div>

    <q-card>
      <q-card-section>
        <div class="row q-col-gutter-md q-mb-md">
          <div class="col-12 col-md-4">
            <q-select v-model="filters.status" label="Status" :options="statusOptions" outlined clearable emit-value map-options />
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
          :rows="scholars"
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
              <q-btn flat dense icon="visibility" @click="viewScholar(props.row)" />
              <q-btn flat dense icon="edit" @click="editScholar(props.row)" />
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

export default defineComponent({
  name: 'AdminScholarsPage',
  setup() {
    const router = useRouter()
    const scholars = ref([])
    const loading = ref(false)
    const filters = ref({
      status: null,
      search: ''
    })

    const statusOptions = [
      { label: 'Active', value: 'active' },
      { label: 'Suspended', value: 'suspended' },
      { label: 'Graduated', value: 'graduated' },
      { label: 'Terminated', value: 'terminated' }
    ]

    const columns = [
      { name: 'id', label: 'ID', field: 'id' },
      { name: 'scholar_number', label: 'Scholar Number', field: 'scholar_number' },
      { name: 'applicant', label: 'Scholar', field: row => row.applicant?.user?.name },
      { name: 'scholarship', label: 'Scholarship', field: row => row.scholarship?.name },
      { name: 'current_gwa', label: 'GWA', field: 'current_gwa' },
      { name: 'status', label: 'Status', field: 'status' },
      { name: 'actions', label: 'Actions', align: 'center' }
    ]

    const getStatusColor = (status) => {
      const colors = {
        active: 'green',
        suspended: 'orange',
        graduated: 'blue',
        terminated: 'red'
      }
      return colors[status] || 'grey'
    }

    const fetchScholars = async () => {
      loading.value = true
      try {
        const params = {}
        if (filters.value.status) params.status = filters.value.status

        const response = await api.get('/scholars', { params })
        scholars.value = response.data.data || response.data || []
      } catch (error) {
        console.error('Error fetching scholars:', error)
      } finally {
        loading.value = false
      }
    }

    const viewScholar = (scholar) => {
      router.push(`/admin/scholars/${scholar.id}`)
    }

    const editScholar = (scholar) => {
      router.push(`/admin/scholars/${scholar.id}/edit`)
    }

    watch(filters, () => {
      fetchScholars()
    }, { deep: true })

    onMounted(() => {
      fetchScholars()
    })

    return {
      scholars,
      loading,
      filters,
      statusOptions,
      columns,
      getStatusColor,
      viewScholar,
      editScholar
    }
  }
})
</script>
