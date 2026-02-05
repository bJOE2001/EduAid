<template>
  <q-layout view="hHh lpR fFf">
    <q-header elevated class="bg-primary text-white">
      <q-toolbar class="q-px-lg">
        <q-toolbar-title class="row items-center no-wrap">
          <q-avatar size="40px" class="q-mr-sm" style="background: rgba(255, 255, 255, 0.2);">
            <q-icon name="school" size="24px" />
          </q-avatar>
          <span class="text-h6" style="font-weight: 700;">EduAid</span>
        </q-toolbar-title>

        <q-space />

        <div class="row items-center q-gutter-sm gt-xs">
          <q-btn 
            flat 
            dense 
            icon="home" 
            label="Home" 
            to="/" 
            class="q-px-md"
            style="border-radius: 8px;"
          />
          <q-btn 
            flat 
            dense 
            icon="school" 
            label="Scholarships" 
            to="/scholarships" 
            class="q-px-md"
            style="border-radius: 8px;"
          />
          <q-btn 
            flat 
            dense 
            icon="help" 
            label="Guide" 
            to="/guide" 
            class="q-px-md"
            style="border-radius: 8px;"
          />
        </div>
        
        <q-separator vertical spaced class="gt-xs q-mx-sm" />
        
        <div class="row items-center q-gutter-sm">
          <q-btn 
            v-if="!authStore.isAuthenticated" 
            unelevated 
            color="white" 
            text-color="primary"
            icon="login" 
            label="Login" 
            to="/auth/login" 
            class="q-px-md"
            style="font-weight: 600; border-radius: 8px;"
          />
          <q-btn 
            v-else 
            flat 
            dense 
            :to="dashboardRoute" 
            class="q-px-md"
            style="border-radius: 8px;"
          >
            <q-avatar size="28px" class="q-mr-sm" style="background: rgba(255, 255, 255, 0.2);">
              <q-icon name="account_circle" />
            </q-avatar>
            <span class="gt-xs" style="font-weight: 500;">{{ authStore.user?.name || 'User' }}</span>
            <q-menu 
              style="border-radius: 12px; margin-top: 8px;"
              class="q-pa-sm"
            >
              <q-list style="min-width: 220px">
                <q-item clickable v-close-popup :to="dashboardRoute" class="q-pa-md" style="border-radius: 8px;">
                  <q-item-section avatar>
                    <q-icon name="dashboard" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label style="font-weight: 500;">Dashboard</q-item-label>
                  </q-item-section>
                </q-item>
                <q-separator />
                <q-item clickable v-close-popup @click="handleLogout" class="q-pa-md" style="border-radius: 8px;">
                  <q-item-section avatar>
                    <q-icon name="logout" color="negative" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label style="font-weight: 500;" class="text-negative">Logout</q-item-label>
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
          class="lt-sm q-ml-sm"
          @click="drawer = !drawer"
          style="color: white;"
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
          <q-item clickable v-ripple @click="handleLogout">
            <q-item-section avatar>
              <q-icon name="logout" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Logout</q-item-label>
            </q-item-section>
          </q-item>
        </template>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>

    <q-footer elevated class="bg-grey-8 text-white">
      <q-toolbar class="q-px-lg">
        <q-toolbar-title>
          <div class="text-body2" style="font-weight: 500;">© 2024 EduAid - Government Scholarship Management System</div>
        </q-toolbar-title>
        <div class="row q-gutter-md gt-xs">
          <q-btn flat dense label="Privacy Policy" class="text-grey-4" />
          <q-btn flat dense label="Terms of Service" class="text-grey-4" />
          <q-btn flat dense label="Contact" class="text-grey-4" />
        </div>
      </q-toolbar>
    </q-footer>
  </q-layout>
</template>

<script>
import { defineComponent, computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

export default defineComponent({
  name: 'MainLayout',
  setup() {
    const router = useRouter()
    const drawer = ref(false)
    const authStore = useAuthStore()

    const dashboardRoute = computed(() => {
      const role = authStore.user?.role?.slug
      if (role === 'applicant') return '/applicant/dashboard'
      if (['admin', 'staff', 'committee', 'accounting', 'viewer'].includes(role)) return '/admin/dashboard'
      return '/'
    })

    const handleLogout = async () => {
      await authStore.logout()
      drawer.value = false
      router.push('/')
    }

    return {
      authStore,
      dashboardRoute,
      handleLogout,
      drawer
    }
  }
})
</script>
