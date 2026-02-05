<template>
  <q-layout view="hHh lpR fFf">
    <q-header elevated class="bg-primary text-white">
      <q-toolbar>
        <q-toolbar-title>EduAid - Applicant Portal</q-toolbar-title>
        <q-space />
        <q-btn flat dense icon="dashboard" label="Dashboard" to="/applicant/dashboard" />
        <q-btn flat dense icon="person" label="Profile" to="/applicant/profile" />
        <q-btn flat dense icon="description" label="Applications" to="/applicant/applications" />
        <q-btn flat dense icon="logout" @click="handleLogout" />
      </q-toolbar>
    </q-header>
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

export default defineComponent({
  name: 'ApplicantLayout',
  setup() {
    const authStore = useAuthStore()
    const router = useRouter()

    const handleLogout = async () => {
      await authStore.logout()
      router.push('/')
    }

    return { handleLogout }
  }
})
</script>
