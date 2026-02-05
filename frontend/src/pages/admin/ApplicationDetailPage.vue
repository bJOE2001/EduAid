<template>
  <q-page class="q-pa-md">
    <div class="row items-center q-mb-md">
      <q-btn flat icon="arrow_back" @click="$router.back()" />
      <div class="text-h4">Application Details</div>
    </div>

    <q-card v-if="application">
      <q-card-section>
        <div class="row items-center q-mb-md">
          <div class="text-h6">{{ application.scholarship?.name }}</div>
          <q-space />
          <q-chip :color="getStatusColor(application.status)" text-color="white">
            {{ application.status }}
          </q-chip>
        </div>

        <q-tabs v-model="tab">
          <q-tab name="info" label="Information" />
          <q-tab name="documents" label="Documents" />
        </q-tabs>

        <q-tab-panels v-model="tab">
          <q-tab-panel name="info">
            <q-list>
              <q-item>
                <q-item-section>
                  <q-item-label caption>Applicant</q-item-label>
                  <q-item-label>{{ application.applicant?.user?.name }}</q-item-label>
                </q-item-section>
              </q-item>
              <q-item>
                <q-item-section>
                  <q-item-label caption>Status</q-item-label>
                  <q-item-label>
                    <q-chip :color="getStatusColor(application.status)" text-color="white">
                      {{ application.status }}
                    </q-chip>
                  </q-item-label>
                </q-item-section>
              </q-item>
              <q-item v-if="application.remarks">
                <q-item-section>
                  <q-item-label caption>Remarks</q-item-label>
                  <q-item-label>{{ application.remarks }}</q-item-label>
                </q-item-section>
              </q-item>
            </q-list>

            <div class="row q-mt-md" v-if="canApprove || canReject">
              <q-space />
              <q-btn v-if="canApprove" color="positive" label="Approve" @click="approveApplication" />
              <q-btn v-if="canReject" color="negative" label="Reject" @click="rejectApplication" class="q-ml-sm" />
            </div>
          </q-tab-panel>

          <q-tab-panel name="documents">
            <q-list>
              <q-item v-for="doc in application.documents" :key="doc.id">
                <q-item-section avatar>
                  <q-icon name="description" color="primary" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>{{ doc.requirement?.name }}</q-item-label>
                  <q-item-label caption>{{ doc.file_name }}</q-item-label>
                </q-item-section>
                <q-item-section side>
                  <q-toggle
                    v-model="doc.is_verified"
                    @update:model-value="verifyDocument(doc)"
                    label="Verified"
                  />
                </q-item-section>
                <q-item-section side>
                  <q-btn flat icon="download" @click="downloadDocument(doc)" />
                </q-item-section>
              </q-item>
            </q-list>
          </q-tab-panel>
        </q-tab-panels>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { defineComponent, ref, computed, onMounted } from 'vue'
import { api } from '../../boot/axios'
import { useRoute } from 'vue-router'
import { Notify, Dialog } from 'quasar'

export default defineComponent({
  name: 'AdminApplicationDetailPage',
  setup() {
    const route = useRoute()
    const application = ref(null)
    const tab = ref('info')

    const canApprove = computed(() => {
      return application.value && ['under_review', 'for_screening'].includes(application.value.status)
    })

    const canReject = computed(() => {
      return application.value && ['pending', 'under_review', 'for_screening'].includes(application.value.status)
    })

    const getStatusColor = (status) => {
      const colors = {
        pending: 'orange',
        under_review: 'blue',
        for_screening: 'purple',
        approved: 'green',
        rejected: 'red'
      }
      return colors[status] || 'grey'
    }

    const fetchApplication = async () => {
      try {
        const response = await api.get(`/applications/${route.params.id}`)
        application.value = response.data
      } catch (error) {
        console.error('Error fetching application:', error)
      }
    }

    const verifyDocument = async (doc) => {
      try {
        await api.post(`/applications/${application.value.id}/documents/${doc.id}/verify`, {
          is_verified: doc.is_verified
        })
        Notify.create({
          type: 'positive',
          message: 'Document verification updated',
          position: 'top'
        })
      } catch (error) {
        doc.is_verified = !doc.is_verified
        Notify.create({
          type: 'negative',
          message: 'Error updating verification',
          position: 'top'
        })
      }
    }

    const approveApplication = () => {
      Dialog.create({
        title: 'Approve Application',
        message: 'Are you sure you want to approve this application?',
        cancel: true
      }).onOk(async () => {
        try {
          await api.put(`/applications/${application.value.id}`, { status: 'approved' })
          Notify.create({
            type: 'positive',
            message: 'Application approved',
            position: 'top'
          })
          await fetchApplication()
        } catch (error) {
          Notify.create({
            type: 'negative',
            message: 'Error approving application',
            position: 'top'
          })
        }
      })
    }

    const rejectApplication = () => {
      Dialog.create({
        title: 'Reject Application',
        message: 'Are you sure you want to reject this application?',
        prompt: {
          model: '',
          type: 'text',
          label: 'Reason (optional)'
        },
        cancel: true
      }).onOk(async (reason) => {
        try {
          await api.put(`/applications/${application.value.id}`, {
            status: 'rejected',
            remarks: reason
          })
          Notify.create({
            type: 'positive',
            message: 'Application rejected',
            position: 'top'
          })
          await fetchApplication()
        } catch (error) {
          Notify.create({
            type: 'negative',
            message: 'Error rejecting application',
            position: 'top'
          })
        }
      })
    }

    const downloadDocument = (doc) => {
      window.open(`${api.defaults.baseURL.replace('/api', '')}/storage/${doc.file_path}`, '_blank')
    }

    onMounted(() => {
      fetchApplication()
    })

    return {
      application,
      tab,
      canApprove,
      canReject,
      getStatusColor,
      verifyDocument,
      approveApplication,
      rejectApplication,
      downloadDocument
    }
  }
})
</script>
