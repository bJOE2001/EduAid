<template>
  <q-layout view="hHh lpR fFf">
    <q-header elevated class="bg-primary text-white">
      <q-toolbar class="q-px-lg">
        <q-btn 
          flat 
          dense 
          round 
          @click="leftDrawerOpen = !leftDrawerOpen"
          class="q-mr-md"
          style="min-width: 40px; color: white;"
        >
          <q-icon 
            name="menu" 
            style="font-size: 24px; color: white !important; display: block !important;" 
          />
        </q-btn>
        <q-toolbar-title class="row items-center no-wrap q-mr-md">
          <q-avatar size="36px" class="q-mr-sm" style="background: rgba(255, 255, 255, 0.2);">
            <q-icon name="school" size="20px" />
          </q-avatar>
          <span class="text-no-wrap" style="font-weight: 700;">EduAid - Admin Portal</span>
        </q-toolbar-title>
        <q-space />
        <!-- Desktop View -->
        <div class="row items-center q-gutter-sm gt-xs">
          <q-chip 
            color="white" 
            text-color="primary" 
            icon="admin_panel_settings"
            size="sm"
            style="font-weight: 600; border-radius: 16px;"
          >
            {{ authStore.user?.role?.name || 'Admin' }}
          </q-chip>
          <q-btn 
            flat 
            dense 
            :label="authStore.user?.name || 'User'" 
            icon="account_circle"
            class="q-px-md"
            style="border-radius: 8px; font-weight: 500;"
          >
            <q-menu 
              style="border-radius: 12px; margin-top: 8px;"
              class="q-pa-sm"
            >
              <q-list style="min-width: 240px">
                <q-item class="q-pa-md">
                  <q-item-section avatar>
                    <q-avatar color="primary" text-color="white" size="40px">
                      <q-icon name="account_circle" />
                    </q-avatar>
                  </q-item-section>
                  <q-item-section>
                    <q-item-label style="font-weight: 600;">{{ authStore.user?.name }}</q-item-label>
                    <q-item-label caption style="font-size: 0.75rem;">{{ authStore.user?.email }}</q-item-label>
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
        <!-- Mobile View -->
        <q-btn 
          flat 
          dense 
          icon="logout" 
          @click="handleLogout"
          class="lt-sm q-ml-xs"
          round
          style="color: white;"
        />
      </q-toolbar>
    </q-header>

    <q-drawer 
      v-model="leftDrawerOpen" 
      :breakpoint="1024"
      :width="280"
      bordered
      show-if-above
      class="bg-grey-1"
    >
      <q-list class="q-pa-sm">
        <q-item 
          clickable 
          v-ripple 
          to="/admin/dashboard"
          active-class="bg-primary text-white"
          exact
          class="q-pa-md q-mb-xs"
          style="border-radius: 12px; margin: 4px 8px;"
        >
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="dashboard" size="22px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1" style="font-weight: 500;">Dashboard</q-item-label>
          </q-item-section>
        </q-item>
        <q-item 
          clickable 
          v-ripple 
          to="/admin/scholarships"
          active-class="bg-primary text-white"
          class="q-pa-md q-mb-xs"
          style="border-radius: 12px; margin: 4px 8px;"
        >
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="school" size="22px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1" style="font-weight: 500;">Scholarships</q-item-label>
          </q-item-section>
        </q-item>
        <q-item 
          clickable 
          v-ripple 
          to="/admin/applications"
          active-class="bg-primary text-white"
          class="q-pa-md q-mb-xs"
          style="border-radius: 12px; margin: 4px 8px;"
        >
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="description" size="22px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1" style="font-weight: 500;">Applications</q-item-label>
          </q-item-section>
        </q-item>
        <q-item 
          clickable 
          v-ripple 
          to="/admin/screenings"
          active-class="bg-primary text-white"
          class="q-pa-md q-mb-xs"
          style="border-radius: 12px; margin: 4px 8px;"
        >
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="assessment" size="22px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1" style="font-weight: 500;">Screenings</q-item-label>
          </q-item-section>
        </q-item>
        <q-item 
          clickable 
          v-ripple 
          to="/admin/scholars"
          active-class="bg-primary text-white"
          class="q-pa-md q-mb-xs"
          style="border-radius: 12px; margin: 4px 8px;"
        >
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="people" size="22px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1" style="font-weight: 500;">Scholars</q-item-label>
          </q-item-section>
        </q-item>
        <q-item 
          clickable 
          v-ripple 
          to="/admin/disbursements"
          active-class="bg-primary text-white"
          class="q-pa-md q-mb-xs"
          style="border-radius: 12px; margin: 4px 8px;"
        >
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="payments" size="22px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1" style="font-weight: 500;">Disbursements</q-item-label>
          </q-item-section>
        </q-item>
        <q-item 
          clickable 
          v-ripple 
          to="/admin/reports"
          active-class="bg-primary text-white"
          class="q-pa-md q-mb-xs"
          style="border-radius: 12px; margin: 4px 8px;"
        >
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="bar_chart" size="22px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1" style="font-weight: 500;">Reports</q-item-label>
          </q-item-section>
        </q-item>
        <q-separator class="q-mt-md q-mb-xs" />
        <q-item 
          clickable 
          v-ripple 
          @click="handleLogout" 
          class="q-pa-md q-mt-xs"
          style="border-radius: 12px; margin: 4px 8px;"
        >
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="logout" color="negative" size="22px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1 text-negative" style="font-weight: 500;">Logout</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

export default defineComponent({
  name: 'AdminLayout',
  setup() {
    const authStore = useAuthStore()
    const router = useRouter()
    // Drawer should be open by default on desktop, closed on mobile
    const leftDrawerOpen = ref(true)

    const handleLogout = async () => {
      await authStore.logout()
      router.push('/')
    }

    return { leftDrawerOpen, handleLogout, authStore }
  }
})
</script>
