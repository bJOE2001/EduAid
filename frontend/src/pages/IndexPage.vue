<template>
  <q-page class="q-pa-md">
    <div class="row q-col-gutter-md">
      <div class="col-12">
        <q-banner class="bg-primary text-white q-mb-md">
          <div class="text-h4">Welcome to EduAid</div>
          <div class="text-subtitle1">Government Scholarship & Financial Assistance Management System</div>
        </q-banner>
      </div>

      <div class="col-12 col-md-4" v-for="feature in features" :key="feature.title">
        <q-card>
          <q-card-section>
            <div class="text-h6">{{ feature.title }}</div>
            <div class="text-body2 q-mt-sm">{{ feature.description }}</div>
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 q-mt-md">
        <q-card>
          <q-card-section>
            <div class="text-h6">Available Scholarships</div>
            <q-inner-loading :showing="loading" />
            <q-list v-if="!loading && scholarships.length > 0">
              <q-item v-for="scholarship in scholarships" :key="scholarship.id" clickable :to="`/scholarships/${scholarship.id}`">
                <q-item-section>
                  <q-item-label>{{ scholarship.name }}</q-item-label>
                  <q-item-label caption>{{ scholarship.description }}</q-item-label>
                </q-item-section>
                <q-item-section side>
                  <q-chip :color="scholarship.is_active ? 'positive' : 'negative'">
                    {{ scholarship.is_active ? 'Active' : 'Inactive' }}
                  </q-chip>
                </q-item-section>
              </q-item>
            </q-list>
            <q-banner v-else-if="!loading && scholarships.length === 0" class="bg-grey-3 q-mt-md">
              <template v-slot:avatar>
                <q-icon name="info" color="primary" />
              </template>
              <div class="text-body1">No active scholarships available at the moment.</div>
              <div class="text-caption q-mt-xs">Please check back later or contact the administrator for more information.</div>
            </q-banner>
            <q-banner v-if="error" class="bg-negative text-white q-mt-md">
              <template v-slot:avatar>
                <q-icon name="error" />
              </template>
              <div class="text-body1">Unable to load scholarships</div>
              <div class="text-caption q-mt-xs">{{ error }}</div>
            </q-banner>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { api } from '../boot/axios'

export default defineComponent({
  name: 'IndexPage',
  setup() {
    const scholarships = ref([])
    const loading = ref(false)
    const error = ref(null)
    const features = [
      {
        title: 'Easy Application',
        description: 'Apply for scholarships online with a simple and intuitive process.'
      },
      {
        title: 'Track Status',
        description: 'Monitor your application status in real-time.'
      },
      {
        title: 'Transparent Process',
        description: 'Fair and transparent scholarship selection process.'
      }
    ]

    const fetchScholarships = async () => {
      loading.value = true
      error.value = null
      try {
        const response = await api.get('/scholarships', {
          params: { is_active: 1 }
        })
        // Handle different response formats
        scholarships.value = response.data?.data || response.data || []
      } catch (err) {
        console.error('Error fetching scholarships:', err)
        error.value = err.response?.data?.message || 'Failed to load scholarships. Please try again later.'
        scholarships.value = []
      } finally {
        loading.value = false
      }
    }

    onMounted(() => {
      fetchScholarships()
    })

    return {
      scholarships,
      features,
      loading,
      error
    }
  }
})
</script>
