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
            <q-list>
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
      try {
        const response = await api.get('/scholarships', {
          params: { is_active: 1 }
        })
        scholarships.value = response.data.data || []
      } catch (error) {
        console.error('Error fetching scholarships:', error)
      }
    }

    onMounted(() => {
      fetchScholarships()
    })

    return {
      scholarships,
      features
    }
  }
})
</script>
