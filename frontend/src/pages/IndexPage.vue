<template>
  <q-page class="q-pa-lg">
    <!-- Hero Section -->
    <div class="row q-mb-xl">
      <div class="col-12">
        <div class="hero-section">
          <div class="text-h3 hero-title">Welcome to EduAid</div>
          <div class="text-h6 hero-subtitle">Government Scholarship & Financial Assistance Management System</div>
          <div class="q-mt-lg">
            <q-btn 
              unelevated 
              color="white" 
              text-color="primary" 
              size="lg"
              label="Browse Scholarships"
              to="/scholarships"
              class="q-mr-md"
              style="font-weight: 600;"
            />
            <q-btn 
              outline 
              color="white" 
              size="lg"
              label="Learn More"
              to="/guide"
              style="font-weight: 600;"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Features Section -->
    <div class="row q-col-gutter-lg q-mb-xl">
      <div class="col-12">
        <div class="text-h5 q-mb-md" style="font-weight: 700; color: #1a1a1a;">Why Choose EduAid?</div>
      </div>
      <div class="col-12 col-md-4" v-for="feature in features" :key="feature.title">
        <q-card class="feature-card">
          <div class="feature-icon">
            <q-icon :name="feature.icon" />
          </div>
          <div class="feature-title">{{ feature.title }}</div>
          <div class="feature-description">{{ feature.description }}</div>
        </q-card>
      </div>
    </div>

    <!-- Scholarships Section -->
    <div class="row">
      <div class="col-12">
        <q-card>
          <q-card-section>
            <div class="text-h5 q-mb-md" style="font-weight: 700; color: #1a1a1a;">Available Scholarships</div>
            <q-inner-loading :showing="loading">
              <q-spinner-gears size="50px" color="primary" />
            </q-inner-loading>
            <div v-if="!loading && scholarships.length > 0" class="row q-col-gutter-md">
              <div 
                v-for="scholarship in scholarships" 
                :key="scholarship.id" 
                class="col-12 col-md-6"
              >
                <q-card 
                  clickable 
                  :to="`/scholarships/${scholarship.id}`"
                  class="q-pa-md"
                  style="border-left: 4px solid #1976D2;"
                >
                  <div class="row items-center">
                    <div class="col">
                      <div class="text-h6 q-mb-xs" style="font-weight: 600; color: #1a1a1a;">
                        {{ scholarship.name }}
                      </div>
                      <div class="text-body2 text-grey-7">
                        {{ scholarship.description || 'No description available' }}
                      </div>
                    </div>
                    <div class="col-auto">
                      <q-chip 
                        :color="scholarship.is_active ? 'positive' : 'negative'" 
                        text-color="white"
                        size="sm"
                        style="font-weight: 500;"
                      >
                        {{ scholarship.is_active ? 'Active' : 'Inactive' }}
                      </q-chip>
                    </div>
                  </div>
                </q-card>
              </div>
            </div>
            <q-banner v-else-if="!loading && scholarships.length === 0" class="bg-grey-2 q-mt-md" rounded>
              <template v-slot:avatar>
                <q-icon name="info" color="primary" size="md" />
              </template>
              <div class="text-body1" style="font-weight: 500;">No active scholarships available at the moment.</div>
              <div class="text-caption q-mt-xs text-grey-7">Please check back later or contact the administrator for more information.</div>
            </q-banner>
            <q-banner v-if="error" class="bg-negative text-white q-mt-md" rounded>
              <template v-slot:avatar>
                <q-icon name="error" size="md" />
              </template>
              <div class="text-body1" style="font-weight: 500;">Unable to load scholarships</div>
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
        description: 'Apply for scholarships online with a simple and intuitive process. Complete your application in minutes.',
        icon: 'edit_note'
      },
      {
        title: 'Track Status',
        description: 'Monitor your application status in real-time. Get instant updates on your scholarship journey.',
        icon: 'track_changes'
      },
      {
        title: 'Transparent Process',
        description: 'Fair and transparent scholarship selection process. Know exactly where you stand at every step.',
        icon: 'visibility'
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
