<template>
  <q-page class="q-pa-md">
    <div class="text-h4 q-mb-md">Reports & Analytics</div>

    <div class="row q-col-gutter-md">
      <div class="col-12 col-md-6">
        <q-card>
          <q-card-section>
            <div class="text-h6">Applicants Report</div>
            <q-btn color="primary" label="Generate Report" @click="generateApplicantsReport" class="q-mt-md" />
            <q-btn color="secondary" label="Export PDF" @click="exportApplicantsPDF" class="q-mt-sm q-ml-sm" />
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-md-6">
        <q-card>
          <q-card-section>
            <div class="text-h6">Scholars Report</div>
            <q-btn color="primary" label="Generate Report" @click="generateScholarsReport" class="q-mt-md" />
            <q-btn color="secondary" label="Export PDF" @click="exportScholarsPDF" class="q-mt-sm q-ml-sm" />
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-md-6">
        <q-card>
          <q-card-section>
            <div class="text-h6">Financial Report</div>
            <q-btn color="primary" label="Generate Report" @click="generateFinancialReport" class="q-mt-md" />
            <q-btn color="secondary" label="Export Excel" @click="exportFinancialExcel" class="q-mt-sm q-ml-sm" />
          </q-card-section>
        </q-card>
      </div>

      <div class="col-12 col-md-6">
        <q-card>
          <q-card-section>
            <div class="text-h6">Statistics</div>
            <q-btn color="primary" label="View Statistics" @click="viewStatistics" class="q-mt-md" />
          </q-card-section>
        </q-card>
      </div>
    </div>

    <q-card v-if="reportData" class="q-mt-md">
      <q-card-section>
        <div class="text-h6">{{ reportTitle }}</div>
        <q-table
          :rows="reportData"
          :columns="reportColumns"
          row-key="id"
        />
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { api } from '../../boot/axios'
import { useQuasar } from 'quasar'

export default defineComponent({
  name: 'AdminReportsPage',
  setup() {
    const $q = useQuasar()
    const reportData = ref(null)
    const reportTitle = ref('')
    const reportColumns = ref([])

    const generateApplicantsReport = async () => {
      try {
        const response = await api.get('/reports/applicants')
        reportData.value = response.data
        reportTitle.value = 'Applicants Report'
        reportColumns.value = [
          { name: 'id', label: 'ID', field: 'id' },
          { name: 'applicant', label: 'Applicant', field: row => row.applicant?.user?.name },
          { name: 'scholarship', label: 'Scholarship', field: row => row.scholarship?.name },
          { name: 'status', label: 'Status', field: 'status' },
          { name: 'created_at', label: 'Date', field: 'created_at', format: val => new Date(val).toLocaleDateString() }
        ]
        $q.notify({
          type: 'positive',
          message: 'Report generated',
          position: 'top'
        })
      } catch (error) {
        $q.notify({
          type: 'negative',
          message: 'Error generating report',
          position: 'top'
        })
      }
    }

    const generateScholarsReport = async () => {
      try {
        const response = await api.get('/reports/scholars')
        reportData.value = response.data
        reportTitle.value = 'Scholars Report'
        reportColumns.value = [
          { name: 'id', label: 'ID', field: 'id' },
          { name: 'scholar_number', label: 'Scholar Number', field: 'scholar_number' },
          { name: 'applicant', label: 'Scholar', field: row => row.applicant?.user?.name },
          { name: 'scholarship', label: 'Scholarship', field: row => row.scholarship?.name },
          { name: 'status', label: 'Status', field: 'status' },
          { name: 'current_gwa', label: 'GWA', field: 'current_gwa' }
        ]
        $q.notify({
          type: 'positive',
          message: 'Report generated',
          position: 'top'
        })
      } catch (error) {
        $q.notify({
          type: 'negative',
          message: 'Error generating report',
          position: 'top'
        })
      }
    }

    const generateFinancialReport = async () => {
      try {
        const response = await api.get('/reports/financial')
        reportData.value = response.data.disbursements || []
        reportTitle.value = 'Financial Report'
        reportColumns.value = [
          { name: 'reference_number', label: 'Reference', field: 'reference_number' },
          { name: 'scholar', label: 'Scholar', field: row => row.scholar?.applicant?.user?.name },
          { name: 'amount', label: 'Amount', field: 'amount', format: val => `₱${new Intl.NumberFormat('en-US').format(val)}` },
          { name: 'type', label: 'Type', field: 'type' },
          { name: 'released_date', label: 'Released', field: 'released_date', format: val => val ? new Date(val).toLocaleDateString() : '-' }
        ]
        $q.notify({
          type: 'positive',
          message: 'Report generated',
          position: 'top'
        })
      } catch (error) {
        $q.notify({
          type: 'negative',
          message: 'Error generating report',
          position: 'top'
        })
      }
    }

    const exportApplicantsPDF = () => {
      $q.notify({
        type: 'info',
        message: 'PDF export feature coming soon',
        position: 'top'
      })
    }

    const exportScholarsPDF = () => {
      $q.notify({
        type: 'info',
        message: 'PDF export feature coming soon',
        position: 'top'
      })
    }

    const exportFinancialExcel = () => {
      Notify.create({
        type: 'info',
        message: 'Excel export feature coming soon',
        position: 'top'
      })
    }

    const viewStatistics = async () => {
      try {
        const response = await api.get('/reports/statistics')
        $q.notify({
          type: 'info',
          message: 'Statistics loaded',
          position: 'top'
        })
        console.log('Statistics:', response.data)
      } catch (error) {
        Notify.create({
          type: 'negative',
          message: 'Error loading statistics',
          position: 'top'
        })
      }
    }

    return {
      reportData,
      reportTitle,
      reportColumns,
      generateApplicantsReport,
      generateScholarsReport,
      generateFinancialReport,
      exportApplicantsPDF,
      exportScholarsPDF,
      exportFinancialExcel,
      viewStatistics
    }
  }
})
</script>
