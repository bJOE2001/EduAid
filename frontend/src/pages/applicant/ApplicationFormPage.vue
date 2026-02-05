<template>
  <q-page class="q-pa-md">
    <div class="text-h4 q-mb-md">Submit Application</div>

    <q-card v-if="scholarship">
      <q-card-section>
        <div class="text-h6">{{ scholarship.name }}</div>
        <div class="text-body2 q-mt-sm">{{ scholarship.description }}</div>
      </q-card-section>

      <q-card-section>
        <q-form @submit="onSubmit" class="q-gutter-md">
          <div class="text-h6 q-mb-md">Required Documents</div>
          
          <div v-for="requirement in requirements" :key="requirement.id" class="q-mb-md">
            <q-file
              v-model="documents[requirement.id]"
              :label="requirement.name"
              :hint="requirement.description"
              outlined
              :required="requirement.is_required"
              accept=".pdf,.jpg,.jpeg,.png"
              max-file-size="10485760"
            >
              <template v-slot:prepend>
                <q-icon name="attach_file" />
              </template>
            </q-file>
          </div>

          <div class="row q-mt-lg">
            <q-space />
            <q-btn label="Cancel" flat @click="$router.back()" />
            <q-btn label="Submit Application" type="submit" color="primary" :loading="loading" />
          </div>
        </q-form>
      </q-card-section>
    </q-card>

    <q-inner-loading :showing="loading" />
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { api } from '../../boot/axios'
import { useRouter, useRoute } from 'vue-router'
import { Notify } from 'quasar'

export default defineComponent({
  name: 'ApplicantApplicationFormPage',
  setup() {
    const router = useRouter()
    const route = useRoute()
    const scholarship = ref(null)
    const requirements = ref([])
    const documents = ref({})
    const loading = ref(false)

    const fetchScholarship = async () => {
      const scholarshipId = route.query.scholarship_id
      if (!scholarshipId) {
        Notify.create({
          type: 'negative',
          message: 'No scholarship selected',
          position: 'top'
        })
        router.push('/scholarships')
        return
      }

      try {
        const response = await api.get(`/scholarships/${scholarshipId}`)
        scholarship.value = response.data
        requirements.value = response.data.requirements || []
        
        // Initialize documents object
        requirements.value.forEach(req => {
          documents.value[req.id] = null
        })
      } catch (error) {
        Notify.create({
          type: 'negative',
          message: 'Error loading scholarship',
          position: 'top'
        })
        router.push('/scholarships')
      }
    }

    const onSubmit = async () => {
      // Validate required documents
      const missingDocs = requirements.value.filter(req => 
        req.is_required && !documents.value[req.id]
      )

      if (missingDocs.length > 0) {
        Notify.create({
          type: 'negative',
          message: 'Please upload all required documents',
          position: 'top'
        })
        return
      }

      loading.value = true
      try {
        const formData = new FormData()
        formData.append('scholarship_id', scholarship.value.id)
        
        const docsArray = []
        requirements.value.forEach(req => {
          if (documents.value[req.id]) {
            docsArray.push({
              requirement_id: req.id,
              file: documents.value[req.id]
            })
          }
        })
        
        formData.append('documents', JSON.stringify(docsArray.map(d => ({ requirement_id: d.requirement_id }))))
        
        docsArray.forEach((doc, index) => {
          formData.append(`documents[${index}][file]`, doc.file)
          formData.append(`documents[${index}][requirement_id]`, doc.requirement_id)
        })

        // Use multipart/form-data
        await api.post('/applications', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        Notify.create({
          type: 'positive',
          message: 'Application submitted successfully!',
          position: 'top'
        })

        router.push('/applicant/applications')
      } catch (error) {
        Notify.create({
          type: 'negative',
          message: error.response?.data?.message || 'Error submitting application',
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
      documents,
      loading,
      onSubmit
    }
  }
})
</script>
