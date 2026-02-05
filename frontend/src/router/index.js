import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', name: 'home', component: () => import('pages/IndexPage.vue') },
      { path: 'scholarships', name: 'scholarships', component: () => import('pages/ScholarshipsPage.vue') },
      { path: 'scholarships/:id', name: 'scholarship-detail', component: () => import('pages/ScholarshipDetailPage.vue') },
      { path: 'guide', name: 'guide', component: () => import('pages/ApplicationGuidePage.vue') },
    ]
  },
  {
    path: '/auth',
    component: () => import('layouts/AuthLayout.vue'),
    children: [
      { path: 'login', name: 'login', component: () => import('pages/LoginPage.vue') },
      { path: 'register', name: 'register', component: () => import('pages/RegisterPage.vue') },
    ]
  },
  {
    path: '/applicant',
    component: () => import('layouts/ApplicantLayout.vue'),
    meta: { requiresAuth: true, role: 'applicant' },
    children: [
      { path: 'dashboard', name: 'applicant-dashboard', component: () => import('pages/applicant/DashboardPage.vue') },
      { path: 'profile', name: 'applicant-profile', component: () => import('pages/applicant/ProfilePage.vue') },
      { path: 'applications', name: 'applicant-applications', component: () => import('pages/applicant/ApplicationsPage.vue') },
      { path: 'applications/new', name: 'applicant-application-new', component: () => import('pages/applicant/ApplicationFormPage.vue') },
      { path: 'applications/:id', name: 'applicant-application-detail', component: () => import('pages/applicant/ApplicationDetailPage.vue') },
    ]
  },
  {
    path: '/admin',
    component: () => import('layouts/AdminLayout.vue'),
    meta: { requiresAuth: true, role: ['admin', 'staff', 'committee', 'accounting', 'viewer'] },
    children: [
      { path: 'dashboard', name: 'admin-dashboard', component: () => import('pages/admin/DashboardPage.vue') },
      { path: 'scholarships', name: 'admin-scholarships', component: () => import('pages/admin/ScholarshipsPage.vue') },
      { path: 'scholarships/new', name: 'admin-scholarship-new', component: () => import('pages/admin/ScholarshipFormPage.vue') },
      { path: 'scholarships/:id', name: 'admin-scholarship-detail', component: () => import('pages/admin/ScholarshipDetailPage.vue') },
      { path: 'applications', name: 'admin-applications', component: () => import('pages/admin/ApplicationsPage.vue') },
      { path: 'applications/:id', name: 'admin-application-detail', component: () => import('pages/admin/ApplicationDetailPage.vue') },
      { path: 'screenings', name: 'admin-screenings', component: () => import('pages/admin/ScreeningsPage.vue') },
      { path: 'screenings/:id', name: 'admin-screening-detail', component: () => import('pages/admin/ScreeningDetailPage.vue') },
      { path: 'scholars', name: 'admin-scholars', component: () => import('pages/admin/ScholarsPage.vue') },
      { path: 'scholars/:id', name: 'admin-scholar-detail', component: () => import('pages/admin/ScholarDetailPage.vue') },
      { path: 'disbursements', name: 'admin-disbursements', component: () => import('pages/admin/DisbursementsPage.vue') },
      { path: 'reports', name: 'admin-reports', component: () => import('pages/admin/ReportsPage.vue') },
    ]
  },
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Router guard function - will be called after Pinia is initialized via boot file
export function setupRouterGuard() {
  router.beforeEach(async (to, from, next) => {
    try {
      // Dynamically import store - Pinia should be initialized by now via boot file
      const { useAuthStore } = await import('../stores/auth')
      const authStore = useAuthStore()
      
      // If user has token but no user data, try to fetch it
      if (authStore.token && !authStore.user) {
        try {
          await authStore.fetchUser()
        } catch (error) {
          // If fetch fails, continue with navigation check
          console.warn('Failed to fetch user on route guard:', error)
        }
      }
      
      if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next({ name: 'login', query: { redirect: to.fullPath } })
      } else if (to.meta.role) {
        const userRole = authStore.user?.role?.slug
        const allowedRoles = Array.isArray(to.meta.role) ? to.meta.role : [to.meta.role]
        
        if (!userRole || !allowedRoles.includes(userRole)) {
          // User doesn't have required role, redirect to home
          next({ name: 'home' })
        } else {
          next()
        }
      } else {
        next()
      }
    } catch (error) {
      // If Pinia/store is not available yet, allow navigation
      console.warn('Auth store not available, allowing navigation:', error)
      next()
    }
  })
}

export default router
