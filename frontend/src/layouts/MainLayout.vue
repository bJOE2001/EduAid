<template>
  <q-layout view="hHh lpR fFf">
    <q-header elevated class="bg-primary text-white">
      <q-toolbar>
        <q-toolbar-title>
          <q-avatar>
            <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg">
          </q-avatar>
          EduAid
        </q-toolbar-title>

        <q-space />

        <q-btn flat dense icon="home" label="Home" to="/" />
        <q-btn flat dense icon="school" label="Scholarships" to="/scholarships" />
        <q-btn flat dense icon="help" label="Guide" to="/guide" />
        
        <q-btn v-if="!authStore.isAuthenticated" flat dense icon="login" label="Login" to="/auth/login" />
        <q-btn v-else flat dense :label="authStore.user?.name" :to="dashboardRoute">
          <q-menu>
            <q-list style="min-width: 200px">
              <q-item clickable v-close-popup :to="dashboardRoute">
                <q-item-section avatar>
                  <q-icon name="dashboard" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>Dashboard</q-item-label>
                </q-item-section>
              </q-item>
              <q-item clickable v-close-popup @click="handleLogout">
                <q-item-section avatar>
                  <q-icon name="logout" />
                </q-item-section>
                <q-item-section>
                  <q-item-label>Logout</q-item-label>
                </q-item-section>
              </q-item>
            </q-list>
          </q-menu>
        </q-btn>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>

    <q-footer elevated class="bg-grey-8 text-white">
      <q-toolbar>
        <q-toolbar-title>
          <div class="text-caption">© 2024 EduAid - Government Scholarship Management System</div>
        </q-toolbar-title>
      </q-toolbar>
    </q-footer>
  </q-layout>
</template>

<script>
import { defineComponent, computed } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

export default defineComponent({
  name: 'MainLayout',
  setup() {
    const authStore = useAuthStore()
    const router = useRouter()

    const dashboardRoute = computed(() => {
      if (authStore.userRole === 'applicant') {
        return '/applicant/dashboard'
      } else if (['admin', 'staff', 'committee', 'accounting', 'viewer'].includes(authStore.userRole)) {
        return '/admin/dashboard'
      }
      return '/'
    })

    const handleLogout = async () => {
      await authStore.logout()
      router.push('/')
    }

    return {
      authStore,
      dashboardRoute,
      handleLogout
    }
  }
})
</script>
