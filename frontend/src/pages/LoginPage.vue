<template>
  <q-page class="flex flex-center bg-grey-2">
    <q-card class="q-pa-md" style="min-width: 400px">
      <q-card-section>
        <div class="text-h5 text-center q-mb-md">Login to EduAid</div>
        <q-form @submit="onSubmit" class="q-gutter-md">
          <q-input
            v-model="email"
            label="Email"
            type="email"
            :rules="[val => !!val || 'Email is required']"
            outlined
          />
          <q-input
            v-model="password"
            label="Password"
            type="password"
            :rules="[val => !!val || 'Password is required']"
            outlined
          />
          <div>
            <q-btn
              label="Login"
              type="submit"
              color="primary"
              class="full-width"
              :loading="loading"
            />
          </div>
          <div class="text-center">
            <q-btn flat label="Don't have an account? Register" to="/auth/register" />
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { defineComponent, ref, getCurrentInstance } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter, useRoute } from 'vue-router'

export default defineComponent({
  name: 'LoginPage',
  setup() {
    const instance = getCurrentInstance()
    const $q = instance?.appContext.config.globalProperties.$q
    const authStore = useAuthStore()
    const router = useRouter()
    const route = useRoute()
    
    const email = ref('')
    const password = ref('')
    const loading = ref(false)

    const showNotify = (type, message) => {
      if ($q && typeof $q.notify === 'function') {
        $q.notify({ type, message, position: 'top' })
      } else {
        // Fallback to console if notify is not available
        console[type === 'positive' ? 'log' : 'error'](message)
      }
    }

    const onSubmit = async () => {
      loading.value = true
      try {
        await authStore.login({
          email: email.value,
          password: password.value
        })
        
        showNotify('positive', 'Login successful!')
        
        const redirect = route.query.redirect || (authStore.userRole === 'applicant' ? '/applicant/dashboard' : '/admin/dashboard')
        router.push(redirect)
      } catch (error) {
        showNotify('negative', error.response?.data?.message || 'Login failed. Please check your credentials.')
        console.error('Login error:', error)
      } finally {
        loading.value = false
      }
    }

    return {
      email,
      password,
      loading,
      onSubmit
    }
  }
})
</script>
