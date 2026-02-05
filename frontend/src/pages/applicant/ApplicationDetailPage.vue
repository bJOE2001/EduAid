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

        <q-tabs v-model="tab" class="text-grey" active-color="primary">
          <q-tab name="info" label="Information" />
          <q-tab name="documents" label="Documents" />
          <q-tab name="timeline" label="Timeline" />
        </q-tabs>

        <q-tab-panels v-model="tab" animated>
          <q-tab-panel name="info">
            <q-list>
              <q-item>
                <q-item-section>
                  <q-item-label caption>Application ID</q-item-label>
                  <q-item-label>{{ application.id }}</q-item-label>
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
              <q-item>
                <q-item-section>
                  <q-item-label caption>Date Applied</q-item-label>
                  <q-item-label>{{ formatDate(application.created_at) }}</q-item-label>
                </q-item-section>
              </q-item>
              <q-item v-if="application.total_score !== null">
                <q-item-section>
                  <q-item-label caption>Total Score</q-item-label>
                  <q-item-label>{{ application.total_score }}</q-item-label>
                </q-item-section>
              </q-item>
              <q-item v-if="application.rank">
                <q-item-section>
                  <q-item-label caption>Rank</q-item-label>
                  <q-item-label>#{{ application.rank }}</q-item-label>
                </q-item-section>
              </q-item>
              <q-item v-if="application.remarks">
                <q-item-section>
                  <q-item-label caption>Remarks</q-item-label>
                  <q-item-label>{{ application.remarks }}</q-item-label>
                </q-item-section>
              </q-item>
            </q-list>
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
                  <q-chip :color="doc.is_verified ? 'positive' : 'orange'" text-color="white" size="sm">
                    {{ doc.is_verified ? 'Verified' : 'Pending' }}
                  </q-chip>
                </q-item-section>
                <q-item-section side>
                  <q-btn flat icon="download" @click="downloadDocument(doc)" />
                </q-item-section>
              </q-item>
            </q-list>
          </q-tab-panel>

          <q-tab-panel name="timeline">
            <q-timeline color="primary">
              <q-timeline-entry
                title="Application Submitted"
                :subtitle="formatDate(application.created_at)"
                icon="send"
              />
              <q-timeline-entry
                v-if="application.reviewed_at"
                title="Under Review"
                :subtitle="formatDate(application.reviewed_at)"
                icon="visibility"
              />
              <q-timeline-entry
                v-if="application.approved_at"
                title="Approved"
                :subtitle="formatDate(application.approved_at)"
                icon="check_circle"
                color="positive"
              />
            </q-timeline>
          </q-tab-panel>
        </q-tab-panels>
      </q-card-section>
    </q-card>

    <q-inner-loading :showing="loading" />
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { api } from '../../boot/axios'
import { useRoute } from 'vue-router'

export default defineComponent({
  name: 'ApplicantApplicationDetailPage',
  setup() {
    const route = useRoute()
    const application = ref(null)
    const loading = ref(false)
    const tab = ref('info')

    const getStatusColor = (status) => {
      const colors = {
        pending: 'orange',
        under_review: 'blue',
        for_screening: 'purple',
        approved: 'green',
        rejected: 'red',
        on_hold: 'grey'
      }
      return colors[status] || 'grey'
    }

    const formatDate = (date) => {
      return new Date(date).toLocaleString()
    }

    const downloadDocument = (doc) => {
      window.open(`${api.defaults.baseURL.replace('/api', '')}/storage/${doc.file_path}`, '_blank')
    }

    const fetchApplication = async () => {
      loading.value = true
      try {
        const response = await api.get(`/applications/${route.params.id}`)
        application.value = response.data
      } catch (error) {
        console.error('Error fetching application:', error)
      } finally {
        loading.value = false
      }
    }

    onMounted(() => {
      fetchApplication()
    })

    return {
      application,
      loading,
      tab,
      getStatusColor,
      formatDate,
      downloadDocument
    }
  }
})
</script>
