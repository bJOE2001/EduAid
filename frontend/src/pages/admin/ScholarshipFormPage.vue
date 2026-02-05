<template>
  <q-page class="q-pa-md">
    <div class="text-h4 q-mb-md">{{ isEdit ? 'Edit Scholarship' : 'Create Scholarship' }}</div>
    
    <q-card>
      <q-card-section>
        <q-form @submit="onSubmit" class="q-gutter-md">
          <div class="row q-col-gutter-md">
            <div class="col-12">
              <q-input v-model="form.name" label="Scholarship Name *" outlined :rules="[val => !!val || 'Required']" />
            </div>
            <div class="col-12">
              <q-input v-model="form.description" label="Description *" type="textarea" rows="4" outlined :rules="[val => !!val || 'Required']" />
            </div>
            <div class="col-12 col-md-6">
              <q-select v-model="form.type" label="Type *" :options="['merit', 'need-based', 'sports', 'academic', 'other']" outlined :rules="[val => !!val || 'Required']" />
            </div>
            <div class="col-12 col-md-6">
              <q-input v-model="form.budget" label="Total Budget *" type="number" outlined :rules="[val => !!val || 'Required']" />
            </div>
            <div class="col-12 col-md-6">
              <q-input v-model="form.allowance_per_month" label="Monthly Allowance" type="number" outlined />
            </div>
            <div class="col-12 col-md-6">
              <q-input v-model="form.max_slots" label="Maximum Slots *" type="number" outlined :rules="[val => !!val || 'Required']" />
            </div>
            <div class="col-12 col-md-6">
              <q-input v-model="form.application_start" label="Application Start *" type="date" outlined :rules="[val => !!val || 'Required']" />
            </div>
            <div class="col-12 col-md-6">
              <q-input v-model="form.application_end" label="Application End *" type="date" outlined :rules="[val => !!val || 'Required']" />
            </div>
            <div class="col-12">
              <q-toggle v-model="form.is_active" label="Active" />
            </div>
          </div>

          <div class="row q-mt-lg">
            <q-space />
            <q-btn label="Cancel" flat @click="$router.back()" />
            <q-btn label="Save" type="submit" color="primary" :loading="loading" />
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted, computed } from 'vue'
import { api } from '../../boot/axios'
import { useRouter, useRoute } from 'vue-router'
import { useQuasar } from 'quasar'

export default defineComponent({
  name: 'AdminScholarshipFormPage',
  setup() {
    const $q = useQuasar()
    const router = useRouter()
    const route = useRoute()
    const isEdit = computed(() => !!route.params.id)
    const loading = ref(false)
    const form = ref({
      name: '',
      description: '',
      type: 'merit',
      budget: '',
      allowance_per_month: '',
      max_slots: '',
      application_start: '',
      application_end: '',
      is_active: true
    })

    const fetchScholarship = async () => {
      if (!isEdit.value) return
      try {
        const response = await api.get(`/scholarships/${route.params.id}`)
        Object.keys(form.value).forEach(key => {
          if (response.data[key] !== undefined) {
            form.value[key] = response.data[key]
          }
        })
        if (response.data.application_start) {
          form.value.application_start = response.data.application_start.split('T')[0]
        }
        if (response.data.application_end) {
          form.value.application_end = response.data.application_end.split('T')[0]
        }
      } catch (error) {
        $q.notify({
          type: 'negative',
          message: 'Error loading scholarship',
          position: 'top'
        })
      }
    }

    const onSubmit = async () => {
      loading.value = true
      try {
        if (isEdit.value) {
          await api.put(`/scholarships/${route.params.id}`, form.value)
          $q.notify({
            type: 'positive',
            message: 'Scholarship updated successfully',
            position: 'top'
          })
        } else {
          await api.post('/scholarships', form.value)
          $q.notify({
            type: 'positive',
            message: 'Scholarship created successfully',
            position: 'top'
          })
        }
        router.push('/admin/scholarships')
      } catch (error) {
        $q.notify({
          type: 'negative',
          message: error.response?.data?.message || 'Error saving scholarship',
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
      isEdit,
      form,
      loading,
      onSubmit
    }
  }
})
</script>
