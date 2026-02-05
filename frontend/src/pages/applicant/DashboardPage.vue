<template>
  <q-page class="q-pa-md">
    <div class="text-h4 q-mb-md">Applicant Dashboard</div>
    <div class="row q-col-gutter-md">
      <div class="col-12 col-md-4">
        <q-card>
          <q-card-section>
            <div class="text-h6">My Applications</div>
            <div class="text-h3">{{ applicationsCount }}</div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-md-4">
        <q-card>
          <q-card-section>
            <div class="text-h6">Pending</div>
            <div class="text-h3">{{ pendingCount }}</div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-md-4">
        <q-card>
          <q-card-section>
            <div class="text-h6">Approved</div>
            <div class="text-h3">{{ approvedCount }}</div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { api } from '../../boot/axios'

export default defineComponent({
  name: 'ApplicantDashboardPage',
  setup() {
    const applicationsCount = ref(0)
    const pendingCount = ref(0)
    const approvedCount = ref(0)

    onMounted(async () => {
      try {
        const response = await api.get('/applications')
        const applications = response.data.data || []
        applicationsCount.value = applications.length
        pendingCount.value = applications.filter(a => a.status === 'pending').length
        approvedCount.value = applications.filter(a => a.status === 'approved').length
      } catch (error) {
        console.error('Error fetching applications:', error)
      }
    })

    return { applicationsCount, pendingCount, approvedCount }
  }
})
</script>
