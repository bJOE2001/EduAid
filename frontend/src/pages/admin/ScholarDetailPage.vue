<template>
  <q-page class="q-pa-md">
    <div class="text-h4 q-mb-md">Scholar Details</div>
    <q-card v-if="scholar">
      <q-card-section>
        <div class="text-h6">{{ scholar.applicant?.user?.name }}</div>
        <div class="text-body2">Scholar Number: {{ scholar.scholar_number }}</div>
      </q-card-section>

      <q-card-section>
        <q-tabs v-model="tab">
          <q-tab name="info" label="Information" />
          <q-tab name="grades" label="Grades" />
          <q-tab name="disbursements" label="Disbursements" />
        </q-tabs>

        <q-tab-panels v-model="tab">
          <q-tab-panel name="info">
            <q-list>
              <q-item>
                <q-item-section>
                  <q-item-label caption>Status</q-item-label>
                  <q-item-label>
                    <q-chip :color="getStatusColor(scholar.status)" text-color="white">
                      {{ scholar.status }}
                    </q-chip>
                  </q-item-label>
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section>
                  <q-item-label caption>Current GWA</q-item-label>
                  <q-item-label>{{ scholar.current_gwa || 'N/A' }}</q-item-label>
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section>
                  <q-item-label caption>Start Date</q-item-label>
                  <q-item-label>{{ formatDate(scholar.start_date) }}</q-item-label>
                </q-item-section>
              </q-item>
            </q-list>
          </q-tab-panel>

          <q-tab-panel name="grades">
            <div class="row items-center q-mb-md">
              <div class="text-h6">Academic Grades</div>
              <q-space />
              <q-btn color="primary" icon="add" label="Add Grade" @click="addGrade" />
            </div>
            <q-table
              :rows="grades"
              :columns="gradeColumns"
              row-key="id"
            />
          </q-tab-panel>

          <q-tab-panel name="disbursements">
            <q-table
              :rows="disbursements"
              :columns="disbursementColumns"
              row-key="id"
            />
          </q-tab-panel>
        </q-tab-panels>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { api } from '../../boot/axios'
import { useRoute } from 'vue-router'

export default defineComponent({
  name: 'AdminScholarDetailPage',
  setup() {
    const route = useRoute()
    const scholar = ref(null)
    const grades = ref([])
    const disbursements = ref([])
    const tab = ref('info')

    const gradeColumns = [
      { name: 'subject_code', label: 'Code', field: 'subject_code' },
      { name: 'subject_name', label: 'Subject', field: 'subject_name' },
      { name: 'grade', label: 'Grade', field: 'grade' },
      { name: 'units', label: 'Units', field: 'units' },
      { name: 'semester', label: 'Semester', field: 'semester' },
      { name: 'school_year', label: 'School Year', field: 'school_year' }
    ]

    const disbursementColumns = [
      { name: 'reference_number', label: 'Reference', field: 'reference_number' },
      { name: 'amount', label: 'Amount', field: 'amount', format: val => `₱${new Intl.NumberFormat('en-US').format(val)}` },
      { name: 'type', label: 'Type', field: 'type' },
      { name: 'status', label: 'Status', field: 'status' },
      { name: 'released_date', label: 'Released', field: 'released_date', format: val => val ? new Date(val).toLocaleDateString() : '-' }
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

    const formatDate = (date) => {
      return new Date(date).toLocaleDateString()
    }

    const fetchScholar = async () => {
      try {
        const response = await api.get(`/scholars/${route.params.id}`)
        scholar.value = response.data
        grades.value = response.data.grades || []
        disbursements.value = response.data.disbursements || []
      } catch (error) {
        console.error('Error fetching scholar:', error)
      }
    }

    const addGrade = () => {
      console.log('Add grade')
    }

    onMounted(() => {
      fetchScholar()
    })

    return {
      scholar,
      grades,
      disbursements,
      tab,
      gradeColumns,
      disbursementColumns,
      getStatusColor,
      formatDate,
      addGrade
    }
  }
})
</script>
