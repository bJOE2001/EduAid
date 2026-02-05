<template>
  <q-page class="q-pa-lg">
    <div class="row items-center q-mb-lg">
      <div class="col">
        <div class="text-h4 q-mb-xs" style="font-weight: 700; color: #1a1a1a;">Admin Dashboard</div>
        <div class="text-body2 text-grey-7">Welcome back! Here's what's happening with your scholarship program.</div>
      </div>
    </div>
    
    <!-- KPI Cards -->
    <div class="row q-col-gutter-md q-mb-lg">
      <div class="col-12 col-sm-6 col-md-3">
        <q-card class="kpi-card bg-primary text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
          <q-card-section class="q-pa-lg">
            <q-icon name="people" class="kpi-icon" />
            <div class="kpi-label q-mb-xs">Total Applicants</div>
            <div class="kpi-value text-h3">{{ stats.total_applicants }}</div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <q-card class="kpi-card bg-positive text-white" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
          <q-card-section class="q-pa-lg">
            <q-icon name="school" class="kpi-icon" />
            <div class="kpi-label q-mb-xs">Active Scholars</div>
            <div class="kpi-value text-h3">{{ stats.total_scholars }}</div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <q-card class="kpi-card bg-info text-white" style="background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);">
          <q-card-section class="q-pa-lg">
            <q-icon name="account_balance_wallet" class="kpi-icon" />
            <div class="kpi-label q-mb-xs">Total Budget</div>
            <div class="kpi-value text-h3">₱{{ formatNumber(stats.total_budget) }}</div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <q-card class="kpi-card bg-warning text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
          <q-card-section class="q-pa-lg">
            <q-icon name="trending_up" class="kpi-icon" />
            <div class="kpi-label q-mb-xs">Used Budget</div>
            <div class="kpi-value text-h3">₱{{ formatNumber(stats.used_budget) }}</div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row q-col-gutter-md q-mb-lg">
      <div class="col-12 col-md-6">
        <q-card>
          <q-card-section>
            <div class="text-h6 q-mb-md" style="font-weight: 600; color: #1a1a1a;">Applications by Status</div>
            <div id="applications-chart" style="height: 300px;"></div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-md-6">
        <q-card>
          <q-card-section>
            <div class="text-h6 q-mb-md" style="font-weight: 600; color: #1a1a1a;">Scholars by Status</div>
            <div id="scholars-chart" style="height: 300px;"></div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
      <div class="col-12">
        <q-card>
          <q-card-section>
            <div class="text-h6 q-mb-md" style="font-weight: 600; color: #1a1a1a;">Recent Applications</div>
            <q-table
              :rows="recentApplications"
              :columns="applicationColumns"
              row-key="id"
              :pagination="{ rowsPerPage: 5 }"
              flat
              class="modern-table"
            >
              <template v-slot:body-cell-status="props">
                <q-td :props="props">
                  <q-chip 
                    :color="getStatusColor(props.value)" 
                    text-color="white" 
                    size="sm"
                    style="font-weight: 500;"
                  >
                    {{ props.value }}
                  </q-chip>
                </q-td>
              </template>
            </q-table>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue'
import { api } from '../../boot/axios'
import ApexCharts from 'apexcharts'

export default defineComponent({
  name: 'AdminDashboardPage',
  setup() {
    const stats = ref({
      total_applicants: 0,
      total_scholars: 0,
      total_budget: 0,
      used_budget: 0,
      pending_applications: 0,
      approved_applications: 0
    })
    const recentApplications = ref([])
    const applicationColumns = [
      { name: 'id', label: 'ID', field: 'id' },
      { name: 'scholarship', label: 'Scholarship', field: row => row.scholarship?.name },
      { name: 'applicant', label: 'Applicant', field: row => row.applicant?.user?.name },
      { name: 'status', label: 'Status', field: 'status' },
      { name: 'created_at', label: 'Date', field: 'created_at', format: val => new Date(val).toLocaleDateString() }
    ]

    const formatNumber = (num) => {
      return new Intl.NumberFormat('en-US').format(num || 0)
    }

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

    const fetchDashboardData = async () => {
      try {
        const [dashboardRes, statsRes, applicationsRes] = await Promise.all([
          api.get('/reports/dashboard'),
          api.get('/reports/statistics'),
          api.get('/applications?per_page=5')
        ])

        stats.value = dashboardRes.data
        recentApplications.value = applicationsRes.data.data || applicationsRes.data || []

        // Create charts
        if (statsRes.data.applications_by_status) {
          createApplicationsChart(statsRes.data.applications_by_status)
        }
        if (statsRes.data.scholars_by_status) {
          createScholarsChart(statsRes.data.scholars_by_status)
        }
      } catch (error) {
        console.error('Error fetching dashboard data:', error)
      }
    }

    const createApplicationsChart = (data) => {
      const options = {
        chart: { type: 'donut', height: 300 },
        series: Object.values(data),
        labels: Object.keys(data),
        colors: ['#FF9800', '#2196F3', '#9C27B0', '#4CAF50', '#F44336']
      }
      new ApexCharts(document.querySelector('#applications-chart'), options).render()
    }

    const createScholarsChart = (data) => {
      const options = {
        chart: { type: 'pie', height: 300 },
        series: Object.values(data),
        labels: Object.keys(data),
        colors: ['#4CAF50', '#FF9800', '#2196F3', '#F44336']
      }
      new ApexCharts(document.querySelector('#scholars-chart'), options).render()
    }

    onMounted(() => {
      fetchDashboardData()
    })

    return {
      stats,
      recentApplications,
      applicationColumns,
      formatNumber,
      getStatusColor
    }
  }
})
</script>
