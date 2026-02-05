<template>
  <q-layout view="hHh lpR fFf">
    <q-header elevated class="bg-primary text-white">
      <q-toolbar class="q-px-md">
        <q-btn 
          flat 
          dense 
          round 
          @click="leftDrawerOpen = !leftDrawerOpen"
          class="q-mr-sm"
          style="min-width: 40px;"
        >
          <q-icon 
            name="menu" 
            class="text-white" 
            style="font-size: 24px; color: white !important; display: block !important;" 
          />
        </q-btn>
        <q-toolbar-title class="row items-center no-wrap q-mr-md">
          <q-icon name="school" size="md" class="q-mr-sm" />
          <span class="text-no-wrap">EduAid - Admin Portal</span>
        </q-toolbar-title>
        <q-space />
        <!-- Desktop View -->
        <div class="row items-center q-gutter-xs gt-xs">
          <q-chip 
            color="white" 
            text-color="primary" 
            icon="admin_panel_settings"
            size="sm"
            class="q-mr-xs"
          >
            {{ authStore.user?.role?.name || 'Admin' }}
          </q-chip>
          <q-btn 
            flat 
            dense 
            :label="authStore.user?.name || 'User'" 
            icon="account_circle"
            class="q-px-sm"
          >
            <q-menu>
              <q-list style="min-width: 200px">
                <q-item>
                  <q-item-section avatar>
                    <q-icon name="account_circle" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>{{ authStore.user?.name }}</q-item-label>
                    <q-item-label caption>{{ authStore.user?.email }}</q-item-label>
                  </q-item-section>
                </q-item>
                <q-separator />
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
        <!-- Mobile View -->
        <q-btn 
          flat 
          dense 
          icon="logout" 
          @click="handleLogout"
          class="lt-sm q-ml-xs"
          round
        />
      </q-toolbar>
    </q-header>

    <q-drawer 
      v-model="leftDrawerOpen" 
      :breakpoint="1024"
      :width="260"
      bordered
      show-if-above
      class="bg-grey-1"
    >
      <q-list>
        <q-item 
          clickable 
          v-ripple 
          to="/admin/dashboard"
          active-class="bg-primary text-white"
          exact
          class="q-pa-md"
        >
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="dashboard" size="20px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1">Dashboard</q-item-label>
          </q-item-section>
        </q-item>
        <q-item 
          clickable 
          v-ripple 
          to="/admin/scholarships"
          active-class="bg-primary text-white"
          class="q-pa-md"
        >
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="school" size="20px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1">Scholarships</q-item-label>
          </q-item-section>
        </q-item>
        <q-item 
          clickable 
          v-ripple 
          to="/admin/applications"
          active-class="bg-primary text-white"
          class="q-pa-md"
        >
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="description" size="20px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1">Applications</q-item-label>
          </q-item-section>
        </q-item>
        <q-item 
          clickable 
          v-ripple 
          to="/admin/screenings"
          active-class="bg-primary text-white"
          class="q-pa-md"
        >
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="assessment" size="20px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1">Screenings</q-item-label>
          </q-item-section>
        </q-item>
        <q-item 
          clickable 
          v-ripple 
          to="/admin/scholars"
          active-class="bg-primary text-white"
          class="q-pa-md"
        >
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="people" size="20px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1">Scholars</q-item-label>
          </q-item-section>
        </q-item>
        <q-item 
          clickable 
          v-ripple 
          to="/admin/disbursements"
          active-class="bg-primary text-white"
          class="q-pa-md"
        >
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="payments" size="20px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1">Disbursements</q-item-label>
          </q-item-section>
        </q-item>
        <q-item 
          clickable 
          v-ripple 
          to="/admin/reports"
          active-class="bg-primary text-white"
          class="q-pa-md"
        >
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="bar_chart" size="20px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1">Reports</q-item-label>
          </q-item-section>
        </q-item>
        <q-separator class="q-mt-md" />
        <q-item clickable v-ripple @click="handleLogout" class="q-pa-md">
          <q-item-section avatar class="q-mr-md" style="min-width: 40px;">
            <q-icon name="logout" color="negative" size="20px" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-body1 text-negative">Logout</q-item-label>
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
