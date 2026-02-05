<template>
  <q-page class="q-pa-md">
    <div class="row items-center q-mb-md">
      <div class="text-h4">Scholarship Management</div>
      <q-space />
      <q-btn color="primary" icon="add" label="New Scholarship" to="/admin/scholarships/new" />
    </div>

    <q-table
      :rows="scholarships"
      :columns="columns"
      row-key="id"
      :loading="loading"
      :filter="filter"
    >
      <template v-slot:top>
        <q-input
          v-model="filter"
          placeholder="Search scholarships..."
          outlined
          dense
          class="q-mr-md"
        >
          <template v-slot:prepend>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>

      <template v-slot:body-cell-is_active="props">
        <q-td :props="props">
          <q-toggle v-model="props.row.is_active" @update:model-value="updateStatus(props.row)" />
        </q-td>
      </template>

      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <q-btn flat dense icon="edit" @click="editScholarship(props.row)" />
          <q-btn flat dense icon="delete" color="negative" @click="deleteScholarship(props.row)" />
        </q-td>
      </template>
    </q-table>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { api } from '../../boot/axios'
import { useRouter } from 'vue-router'
import { Notify, Dialog } from 'quasar'

export default defineComponent({
  name: 'AdminScholarshipsPage',
  setup() {
    const router = useRouter()
    const scholarships = ref([])
    const loading = ref(false)
    const filter = ref('')

    const columns = [
      { name: 'id', label: 'ID', field: 'id', align: 'left' },
      { name: 'name', label: 'Name', field: 'name', align: 'left' },
      { name: 'type', label: 'Type', field: 'type', align: 'left' },
      { name: 'slots', label: 'Slots', field: row => `${row.current_slots}/${row.max_slots}`, align: 'center' },
      { name: 'budget', label: 'Budget', field: 'budget', format: val => `₱${new Intl.NumberFormat('en-US').format(val)}` },
      { name: 'is_active', label: 'Active', field: 'is_active', align: 'center' },
      { name: 'actions', label: 'Actions', align: 'center' }
    ]

    const fetchScholarships = async () => {
      loading.value = true
      try {
        const response = await api.get('/scholarships')
        scholarships.value = response.data.data || response.data || []
      } catch (error) {
        console.error('Error fetching scholarships:', error)
      } finally {
        loading.value = false
      }
    }

    const editScholarship = (scholarship) => {
      router.push(`/admin/scholarships/${scholarship.id}`)
    }

    const updateStatus = async (scholarship) => {
      try {
        await api.put(`/scholarships/${scholarship.id}`, { is_active: scholarship.is_active })
        Notify.create({
          type: 'positive',
          message: 'Status updated',
          position: 'top'
        })
      } catch (error) {
        scholarship.is_active = !scholarship.is_active
        Notify.create({
          type: 'negative',
          message: 'Error updating status',
          position: 'top'
        })
      }
    }

    const deleteScholarship = (scholarship) => {
      Dialog.create({
        title: 'Confirm Delete',
        message: `Are you sure you want to delete "${scholarship.name}"?`,
        cancel: true,
        persistent: true
      }).onOk(async () => {
        try {
          await api.delete(`/scholarships/${scholarship.id}`)
          Notify.create({
            type: 'positive',
            message: 'Scholarship deleted',
            position: 'top'
          })
          fetchScholarships()
        } catch (error) {
          Notify.create({
            type: 'negative',
            message: 'Error deleting scholarship',
            position: 'top'
          })
        }
      })
    }

    onMounted(() => {
      fetchScholarships()
    })

    return {
      scholarships,
      loading,
      filter,
      columns,
      editScholarship,
      updateStatus,
      deleteScholarship
    }
  }
})
</script>
