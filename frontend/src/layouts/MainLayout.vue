<template>
  <q-layout view="hHh lpR fFf">
    <q-header elevated class="bg-primary text-white">
      <q-toolbar>
        <q-toolbar-title class="row items-center no-wrap">
          <q-avatar size="32px" class="q-mr-sm">
            <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg" alt="EduAid Logo">
          </q-avatar>
          <span class="text-h6">EduAid</span>
        </q-toolbar-title>

        <q-space />

        <div class="row items-center q-gutter-xs gt-xs">
          <q-btn flat dense icon="home" label="Home" to="/" class="q-px-sm" />
          <q-btn flat dense icon="school" label="Scholarships" to="/scholarships" class="q-px-sm" />
          <q-btn flat dense icon="help" label="Guide" to="/guide" class="q-px-sm" />
        </div>
        
        <q-separator vertical spaced class="gt-xs" />
        
        <div class="row items-center q-gutter-xs">
          <q-btn v-if="!authStore.isAuthenticated" flat dense icon="login" label="Login" to="/auth/login" class="q-px-sm" />
          <q-btn v-else flat dense :to="dashboardRoute" class="q-px-sm">
            <q-avatar size="24px" class="q-mr-xs">
              <q-icon name="account_circle" />
            </q-avatar>
            <span class="gt-xs">{{ authStore.user?.name || 'User' }}</span>
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
        </div>

        <q-btn
          flat
          dense
          round
          icon="menu"
          class="lt-sm"
          @click="drawer = !drawer"
        />
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="drawer"
      side="right"
      overlay
      bordered
      class="bg-grey-1"
    >
      <q-list>
        <q-item-label header>Navigation</q-item-label>
        <q-item clickable v-ripple to="/">
          <q-item-section avatar>
            <q-icon name="home" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Home</q-item-label>
          </q-item-section>
        </q-item>
        <q-item clickable v-ripple to="/scholarships">
          <q-item-section avatar>
            <q-icon name="school" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Scholarships</q-item-label>
          </q-item-section>
        </q-item>
        <q-item clickable v-ripple to="/guide">
          <q-item-section avatar>
            <q-icon name="help" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Guide</q-item-label>
          </q-item-section>
        </q-item>
        <q-separator />
        <q-item v-if="!authStore.isAuthenticated" clickable v-ripple to="/auth/login">
          <q-item-section avatar>
            <q-icon name="login" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Login</q-item-label>
          </q-item-section>
        </q-item>
        <template v-else>
          <q-item clickable v-ripple :to="dashboardRoute">
            <q-item-section avatar>
              <q-icon name="dashboard" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Dashboard</q-item-label>
              <q-item-label caption>{{ authStore.user?.name }}</q-item-label>
            </q-item-section>
          </q-item>
        </template>
      </q-list>
    </q-drawer>

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
import { defineComponent, computed, ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

function getAuthFromStorage() {
  const token = localStorage.getItem('token')
  let user = null
  try {
    const saved = localStorage.getItem('user')
    if (saved) user = JSON.parse(saved)
  } catch (_) {}
  return { isAuthenticated: !!token, user }
}

export default defineComponent({
  name: 'MainLayout',
  setup() {
    const router = useRouter()
    const drawer = ref(false)
    const auth = ref(getAuthFromStorage())

    const dashboardRoute = computed(() => {
      const role = auth.value.user?.role?.slug
      if (role === 'applicant') return '/applicant/dashboard'
      if (['admin', 'staff', 'committee', 'accounting', 'viewer'].includes(role)) return '/admin/dashboard'
      return '/'
    })

    const handleLogout = async () => {
      try {
        const authStore = useAuthStore()
        await authStore.logout()
      } catch (_) {}
      auth.value = getAuthFromStorage()
      drawer.value = false
      router.push('/')
    }

    onMounted(() => {
      auth.value = getAuthFromStorage()
    })

    return {
      authStore: auth,
      dashboardRoute,
      handleLogout,
      drawer
    }
  }
})
</script>
