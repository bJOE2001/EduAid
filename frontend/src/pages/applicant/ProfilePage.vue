<template>
  <q-page class="q-pa-md">
    <div class="text-h4 q-mb-md">My Profile</div>
    
    <q-card v-if="applicant">
      <q-card-section>
        <q-tabs v-model="tab" class="text-grey" active-color="primary" indicator-color="primary" align="left" narrow-indicator>
          <q-tab name="personal" label="Personal Information" />
          <q-tab name="academic" label="Academic Information" />
          <q-tab name="family" label="Family Information" />
        </q-tabs>

        <q-tab-panels v-model="tab" animated>
          <q-tab-panel name="personal">
            <q-form @submit="onSubmit" class="q-gutter-md">
              <div class="row q-col-gutter-md">
                <div class="col-12 col-md-4">
                  <q-input v-model="form.first_name" label="First Name *" outlined :rules="[val => !!val || 'Required']" />
                </div>
                <div class="col-12 col-md-4">
                  <q-input v-model="form.middle_name" label="Middle Name" outlined />
                </div>
                <div class="col-12 col-md-4">
                  <q-input v-model="form.last_name" label="Last Name *" outlined :rules="[val => !!val || 'Required']" />
                </div>
                <div class="col-12 col-md-4">
                  <q-input v-model="form.birth_date" label="Birth Date *" type="date" outlined :rules="[val => !!val || 'Required']" />
                </div>
                <div class="col-12 col-md-4">
                  <q-select v-model="form.gender" label="Gender *" :options="['male', 'female', 'other']" outlined :rules="[val => !!val || 'Required']" />
                </div>
                <div class="col-12 col-md-4">
                  <q-input v-model="form.phone" label="Phone" outlined />
                </div>
                <div class="col-12">
                  <q-input v-model="form.address" label="Address *" outlined :rules="[val => !!val || 'Required']" />
                </div>
                <div class="col-12 col-md-4">
                  <q-input v-model="form.city" label="City *" outlined :rules="[val => !!val || 'Required']" />
                </div>
                <div class="col-12 col-md-4">
                  <q-input v-model="form.province" label="Province *" outlined :rules="[val => !!val || 'Required']" />
                </div>
                <div class="col-12 col-md-4">
                  <q-input v-model="form.zip_code" label="Zip Code *" outlined :rules="[val => !!val || 'Required']" />
                </div>
              </div>
              <q-btn type="submit" color="primary" label="Save Personal Information" :loading="loading" />
            </q-form>
          </q-tab-panel>

          <q-tab-panel name="academic">
            <q-form @submit="onSubmit" class="q-gutter-md">
              <div class="row q-col-gutter-md">
                <div class="col-12 col-md-6">
                  <q-input v-model="form.student_number" label="Student Number" outlined />
                </div>
                <div class="col-12 col-md-6">
                  <q-input v-model="form.school_name" label="School Name *" outlined :rules="[val => !!val || 'Required']" />
                </div>
                <div class="col-12 col-md-6">
                  <q-input v-model="form.course" label="Course *" outlined :rules="[val => !!val || 'Required']" />
                </div>
                <div class="col-12 col-md-6">
                  <q-input v-model="form.year_level" label="Year Level *" type="number" outlined :rules="[val => !!val || 'Required']" />
                </div>
                <div class="col-12 col-md-6">
                  <q-input v-model="form.gwa" label="GWA (General Weighted Average)" type="number" step="0.01" outlined />
                </div>
              </div>
              <q-btn type="submit" color="primary" label="Save Academic Information" :loading="loading" />
            </q-form>
          </q-tab-panel>

          <q-tab-panel name="family">
            <q-form @submit="onSubmit" class="q-gutter-md">
              <div class="row q-col-gutter-md">
                <div class="col-12 col-md-6">
                  <q-input v-model="form.family_income" label="Family Income *" type="number" outlined :rules="[val => !!val || 'Required']" />
                </div>
                <div class="col-12 col-md-6">
                  <q-input v-model="form.family_members" label="Family Members *" type="number" outlined :rules="[val => !!val || 'Required']" />
                </div>
                <div class="col-12 col-md-6">
                  <q-input v-model="form.father_name" label="Father's Name" outlined />
                </div>
                <div class="col-12 col-md-6">
                  <q-input v-model="form.father_occupation" label="Father's Occupation" outlined />
                </div>
                <div class="col-12 col-md-6">
                  <q-input v-model="form.mother_name" label="Mother's Name" outlined />
                </div>
                <div class="col-12 col-md-6">
                  <q-input v-model="form.mother_occupation" label="Mother's Occupation" outlined />
                </div>
                <div class="col-12 col-md-6">
                  <q-input v-model="form.guardian_name" label="Guardian's Name" outlined />
                </div>
                <div class="col-12 col-md-6">
                  <q-input v-model="form.guardian_relationship" label="Guardian's Relationship" outlined />
                </div>
                <div class="col-12 col-md-6">
                  <q-input v-model="form.guardian_contact" label="Guardian's Contact" outlined />
                </div>
              </div>
              <q-btn type="submit" color="primary" label="Save Family Information" :loading="loading" />
            </q-form>
          </q-tab-panel>
        </q-tab-panels>
      </q-card-section>
    </q-card>

    <q-card v-else class="q-mt-md">
      <q-card-section>
        <div class="text-h6">No Profile Found</div>
        <div class="text-body2 q-mt-sm">Please complete your applicant profile to apply for scholarships.</div>
        <q-btn color="primary" label="Create Profile" @click="createProfile" class="q-mt-md" />
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { api } from '../../boot/axios'
import { useAuthStore } from '../../stores/auth'
import { Notify } from 'quasar'

export default defineComponent({
  name: 'ApplicantProfilePage',
  setup() {
    const authStore = useAuthStore()
    const applicant = ref(null)
    const tab = ref('personal')
    const loading = ref(false)
    const form = ref({
      first_name: '',
      middle_name: '',
      last_name: '',
      birth_date: '',
      gender: '',
      phone: '',
      address: '',
      city: '',
      province: '',
      zip_code: '',
      student_number: '',
      school_name: '',
      course: '',
      year_level: '',
      gwa: null,
      family_income: '',
      family_members: '',
      father_name: '',
      father_occupation: '',
      mother_name: '',
      mother_occupation: '',
      guardian_name: '',
      guardian_relationship: '',
      guardian_contact: ''
    })

    const fetchProfile = async () => {
      try {
        const response = await api.get('/applicants')
        const applicants = response.data.data || response.data || []
        const userApplicant = applicants.find(a => a.user_id === authStore.user?.id)
        
        if (userApplicant) {
          applicant.value = userApplicant
          Object.keys(form.value).forEach(key => {
            if (userApplicant[key] !== undefined) {
              form.value[key] = userApplicant[key]
            }
          })
          if (userApplicant.birth_date) {
            form.value.birth_date = userApplicant.birth_date.split('T')[0]
          }
        }
      } catch (error) {
        console.error('Error fetching profile:', error)
      }
    }

    const createProfile = async () => {
      loading.value = true
      try {
        const response = await api.post('/applicants', form.value)
        applicant.value = response.data
        Notify.create({
          type: 'positive',
          message: 'Profile created successfully!',
          position: 'top'
        })
      } catch (error) {
        Notify.create({
          type: 'negative',
          message: error.response?.data?.message || 'Error creating profile',
          position: 'top'
        })
      } finally {
        loading.value = false
      }
    }

    const onSubmit = async () => {
      loading.value = true
      try {
        if (applicant.value) {
          await api.put(`/applicants/${applicant.value.id}`, form.value)
          Notify.create({
            type: 'positive',
            message: 'Profile updated successfully!',
            position: 'top'
          })
          await fetchProfile()
        } else {
          await createProfile()
        }
      } catch (error) {
        Notify.create({
          type: 'negative',
          message: error.response?.data?.message || 'Error saving profile',
          position: 'top'
        })
      } finally {
        loading.value = false
      }
    }

    onMounted(() => {
      fetchProfile()
    })

    return {
      applicant,
      tab,
      form,
      loading,
      onSubmit,
      createProfile
    }
  }
})
</script>
