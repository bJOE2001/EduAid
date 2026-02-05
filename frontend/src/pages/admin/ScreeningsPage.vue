<template>
  <q-page class="q-pa-md">
    <div class="row items-center q-mb-md">
      <div class="text-h4">Screening Management</div>
      <q-space />
      <q-btn color="primary" icon="add" label="New Screening" @click="createScreening" />
    </div>

    <q-table
      :rows="screenings"
      :columns="columns"
      row-key="id"
      :loading="loading"
    >
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <q-btn flat dense icon="visibility" @click="viewScreening(props.row)" />
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
  name: 'AdminScreeningsPage',
  setup() {
    const router = useRouter()
    const screenings = ref([])
    const loading = ref(false)

    const columns = [
      { name: 'id', label: 'ID', field: 'id' },
      { name: 'name', label: 'Name', field: 'name' },
      { name: 'scholarship', label: 'Scholarship', field: row => row.scholarship?.name },
      { name: 'status', label: 'Status', field: 'status' },
      { name: 'screening_date', label: 'Date', field: 'screening_date', format: val => val ? new Date(val).toLocaleDateString() : '-' },
      { name: 'actions', label: 'Actions', align: 'center' }
    ]

    const fetchScreenings = async () => {
      loading.value = true
      try {
        const response = await api.get('/screenings')
        screenings.value = response.data.data || response.data || []
      } catch (error) {
        console.error('Error fetching screenings:', error)
      } finally {
        loading.value = false
      }
    }

    const viewScreening = (screening) => {
      router.push(`/admin/screenings/${screening.id}`)
    }

    const createScreening = () => {
      // Open dialog or navigate to form
      router.push('/admin/screenings/new')
    }

    onMounted(() => {
      fetchScreenings()
    })

    return {
      screenings,
      loading,
      columns,
      viewScreening,
      createScreening
    }
  }
})
</script>
