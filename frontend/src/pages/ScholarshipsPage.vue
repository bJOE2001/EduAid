<template>
  <q-page class="q-pa-md">
    <div class="row q-col-gutter-md">
      <div class="col-12">
        <div class="text-h4 q-mb-md">Available Scholarships</div>
        <q-input
          v-model="search"
          placeholder="Search scholarships..."
          outlined
          dense
          class="q-mb-md"
        >
          <template v-slot:prepend>
            <q-icon name="search" />
          </template>
        </q-input>
      </div>

      <div class="col-12 col-md-6 col-lg-4" v-for="scholarship in filteredScholarships" :key="scholarship.id">
        <q-card class="cursor-pointer" @click="$router.push(`/scholarships/${scholarship.id}`)">
          <q-card-section>
            <div class="text-h6">{{ scholarship.name }}</div>
            <div class="text-caption text-grey-7 q-mt-xs">{{ scholarship.type }}</div>
          </q-card-section>
          <q-card-section class="q-pt-none">
            <div class="text-body2">{{ truncateText(scholarship.description, 100) }}</div>
            <div class="row q-mt-md q-gutter-sm">
              <q-chip color="primary" text-color="white" icon="people">
                {{ scholarship.current_slots }}/{{ scholarship.max_slots }} slots
              </q-chip>
              <q-chip :color="scholarship.is_active ? 'positive' : 'negative'" text-color="white">
                {{ scholarship.is_active ? 'Active' : 'Inactive' }}
              </q-chip>
            </div>
          </q-card-section>
          <q-card-section v-if="scholarship.allowance_per_month" class="q-pt-none">
            <div class="text-body2">
              <q-icon name="attach_money" /> Allowance: ₱{{ formatNumber(scholarship.allowance_per_month) }}/month
            </div>
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12" v-if="filteredScholarships.length === 0">
        <q-banner class="bg-grey-3">
          <template v-slot:avatar>
            <q-icon name="info" color="primary" />
          </template>
          No scholarships found
        </q-banner>
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref, computed, onMounted } from 'vue'
import { api } from '../boot/axios'

export default defineComponent({
  name: 'ScholarshipsPage',
  setup() {
    const scholarships = ref([])
    const search = ref('')

    const filteredScholarships = computed(() => {
      if (!search.value) return scholarships.value
      const searchLower = search.value.toLowerCase()
      return scholarships.value.filter(s => 
        s.name.toLowerCase().includes(searchLower) ||
        s.description.toLowerCase().includes(searchLower) ||
        s.type.toLowerCase().includes(searchLower)
      )
    })

    const formatNumber = (num) => {
      return new Intl.NumberFormat('en-US').format(num || 0)
    }

    const truncateText = (text, length) => {
      if (!text) return ''
      return text.length > length ? text.substring(0, length) + '...' : text
    }

    const fetchScholarships = async () => {
      try {
        const response = await api.get('/scholarships', {
          params: { is_active: 1 }
        })
        scholarships.value = response.data.data || response.data || []
      } catch (error) {
        console.error('Error fetching scholarships:', error)
      }
    }

    onMounted(() => {
      fetchScholarships()
    })

    return {
      scholarships,
      search,
      filteredScholarships,
      formatNumber,
      truncateText
    }
  }
})
</script>
