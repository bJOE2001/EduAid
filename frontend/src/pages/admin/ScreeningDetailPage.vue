<template>
  <q-page class="q-pa-md">
    <div class="text-h4 q-mb-md">Screening Details</div>
    <q-card v-if="screening">
      <q-card-section>
        <div class="text-h6">{{ screening.name }}</div>
        <div class="text-body2">{{ screening.description }}</div>
      </q-card-section>
      <q-card-section>
        <q-table
          :rows="applications"
          :columns="columns"
          row-key="id"
        >
          <template v-slot:body-cell-actions="props">
            <q-td :props="props">
              <q-btn flat dense icon="edit" @click="scoreApplication(props.row)" />
            </q-td>
          </template>
        </q-table>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { api } from '../../boot/axios'
import { useRoute } from 'vue-router'

export default defineComponent({
  name: 'AdminScreeningDetailPage',
  setup() {
    const route = useRoute()
    const screening = ref(null)
    const applications = ref([])

    const columns = [
      { name: 'id', label: 'ID', field: 'id' },
      { name: 'applicant', label: 'Applicant', field: row => row.applicant?.user?.name },
      { name: 'total_score', label: 'Score', field: 'total_score' },
      { name: 'rank', label: 'Rank', field: 'rank' },
      { name: 'actions', label: 'Actions', align: 'center' }
    ]

    const fetchScreening = async () => {
      try {
        const response = await api.get(`/screenings/${route.params.id}`)
        screening.value = response.data
        // Fetch applications for this screening
        const appsResponse = await api.get('/applications', {
          params: { scholarship_id: screening.value.scholarship_id }
        })
        applications.value = appsResponse.data.data || appsResponse.data || []
      } catch (error) {
        console.error('Error fetching screening:', error)
      }
    }

    const scoreApplication = (application) => {
      // Open scoring dialog
      console.log('Score application:', application)
    }

    onMounted(() => {
      fetchScreening()
    })

    return {
      screening,
      applications,
      columns,
      scoreApplication
    }
  }
})
</script>
