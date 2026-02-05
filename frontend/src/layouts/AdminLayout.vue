<template>
  <q-layout view="hHh lpR fFf">
    <q-header elevated class="bg-primary text-white">
      <q-toolbar>
        <q-btn flat dense round icon="menu" @click="leftDrawerOpen = !leftDrawerOpen" />
        <q-toolbar-title>EduAid - Admin Portal</q-toolbar-title>
        <q-space />
        <q-btn flat dense :label="authStore.user?.name" />
        <q-btn flat dense icon="logout" @click="handleLogout" />
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above bordered>
      <q-list>
        <q-item-label header>Navigation</q-item-label>
        <q-item clickable v-ripple to="/admin/dashboard">
          <q-item-section avatar><q-icon name="dashboard" /></q-item-section>
          <q-item-section><q-item-label>Dashboard</q-item-label></q-item-section>
        </q-item>
        <q-item clickable v-ripple to="/admin/scholarships">
          <q-item-section avatar><q-icon name="school" /></q-item-section>
          <q-item-section><q-item-label>Scholarships</q-item-label></q-item-section>
        </q-item>
        <q-item clickable v-ripple to="/admin/applications">
          <q-item-section avatar><q-icon name="description" /></q-item-section>
          <q-item-section><q-item-label>Applications</q-item-label></q-item-section>
        </q-item>
        <q-item clickable v-ripple to="/admin/screenings">
          <q-item-section avatar><q-icon name="assessment" /></q-item-section>
          <q-item-section><q-item-label>Screenings</q-item-label></q-item-section>
        </q-item>
        <q-item clickable v-ripple to="/admin/scholars">
          <q-item-section avatar><q-icon name="people" /></q-item-section>
          <q-item-section><q-item-label>Scholars</q-item-label></q-item-section>
        </q-item>
        <q-item clickable v-ripple to="/admin/disbursements">
          <q-item-section avatar><q-icon name="payments" /></q-item-section>
          <q-item-section><q-item-label>Disbursements</q-item-label></q-item-section>
        </q-item>
        <q-item clickable v-ripple to="/admin/reports">
          <q-item-section avatar><q-icon name="bar_chart" /></q-item-section>
          <q-item-section><q-item-label>Reports</q-item-label></q-item-section>
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
    const leftDrawerOpen = ref(false)

    const handleLogout = async () => {
      await authStore.logout()
      router.push('/')
    }

    return { leftDrawerOpen, handleLogout, authStore }
  }
})
</script>
