<template>
  <q-page class="q-pa-md">
    <q-card v-if="scholarship">
      <q-card-section>
        <div class="row items-center q-mb-md">
          <q-btn flat icon="arrow_back" @click="$router.back()" />
          <div class="text-h4 q-ml-md">{{ scholarship.name }}</div>
        </div>
        <q-chip :color="scholarship.is_active ? 'positive' : 'negative'" text-color="white">
          {{ scholarship.is_active ? 'Active' : 'Inactive' }}
        </q-chip>
        <q-chip color="primary" text-color="white" class="q-ml-sm">
          {{ scholarship.type }}
        </q-chip>
      </q-card-section>

      <q-card-section>
        <div class="text-h6 q-mb-sm">Description</div>
        <div class="text-body1">{{ scholarship.description }}</div>
      </q-card-section>

      <q-card-section>
        <div class="row q-col-gutter-md">
          <div class="col-12 col-md-6">
            <div class="text-h6 q-mb-sm">Program Details</div>
            <q-list>
              <q-item>
                <q-item-section>
                  <q-item-label caption>Budget</q-item-label>
                  <q-item-label>₱{{ formatNumber(scholarship.budget) }}</q-item-label>
                </q-item-section>
              </q-item>
              <q-item v-if="scholarship.allowance_per_month">
                <q-item-section>
                  <q-item-label caption>Monthly Allowance</q-item-label>
                  <q-item-label>₱{{ formatNumber(scholarship.allowance_per_month) }}</q-item-label>
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section>
                  <q-item-label caption>Available Slots</q-item-label>
                  <q-item-label>{{ scholarship.current_slots }} / {{ scholarship.max_slots }}</q-item-label>
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section>
                  <q-item-label caption>Application Period</q-item-label>
                  <q-item-label>{{ formatDate(scholarship.application_start) }} - {{ formatDate(scholarship.application_end) }}</q-item-label>
                </q-item-section>
              </q-item>
            </q-list>
          </div>
        </div>
      </q-card-section>

      <q-card-section v-if="requirements.length > 0">
        <div class="text-h6 q-mb-sm">Requirements</div>
        <q-list>
          <q-item v-for="req in requirements" :key="req.id">
            <q-item-section avatar>
              <q-icon name="description" color="primary" />
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ req.name }}</q-item-label>
              <q-item-label caption v-if="req.description">{{ req.description }}</q-item-label>
            </q-item-section>
            <q-item-section side>
              <q-chip v-if="req.is_required" color="negative" text-color="white" size="sm">Required</q-chip>
            </q-item-section>
          </q-item>
        </q-list>
      </q-card-section>

      <q-card-actions v-if="canApply" align="right">
        <q-btn
          color="primary"
          label="Apply Now"
          @click="handleApply"
          :disable="!isApplicationOpen"
        />
      </q-card-actions>
    </q-card>

    <q-inner-loading :showing="loading" />
  </q-page>
</template>

<script>
import { defineComponent, ref, computed, onMounted } from 'vue'
import { api } from '../boot/axios'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'
import { useQuasar } from 'quasar'

export default defineComponent({
  name: 'ScholarshipDetailPage',
  setup() {
    const $q = useQuasar()
    const authStore = useAuthStore()
    const router = useRouter()
    const scholarship = ref(null)
    const requirements = ref([])
    const loading = ref(false)

    const canApply = computed(() => {
      return authStore.isAuthenticated && authStore.userRole === 'applicant'
    })

    const isApplicationOpen = computed(() => {
      if (!scholarship.value) return false
      const now = new Date()
      const start = new Date(scholarship.value.application_start)
      const end = new Date(scholarship.value.application_end)
      return now >= start && now <= end && scholarship.value.is_active
    })

    const formatNumber = (num) => {
      return new Intl.NumberFormat('en-US').format(num || 0)
    }

    const formatDate = (date) => {
      return new Date(date).toLocaleDateString()
    }

    const handleApply = () => {
      if (!authStore.isAuthenticated) {
        router.push('/auth/login')
        return
      }
      router.push(`/applicant/applications/new?scholarship_id=${scholarship.value.id}`)
    }

    const fetchScholarship = async () => {
      loading.value = true
      try {
        const id = router.currentRoute.value.params.id
        const response = await api.get(`/scholarships/${id}`)
        scholarship.value = response.data
        requirements.value = response.data.requirements || []
      } catch (error) {
        $q.notify({
          type: 'negative',
          message: 'Error loading scholarship details',
          position: 'top'
        })
      } finally {
        loading.value = false
      }
    }

    onMounted(() => {
      fetchScholarship()
    })

    return {
      scholarship,
      requirements,
      loading,
      canApply,
      isApplicationOpen,
      formatNumber,
      formatDate,
      handleApply
    }
  }
})
</script>
