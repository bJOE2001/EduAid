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
import { defineComponent, ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter, useRoute } from 'vue-router'
import { useQuasar, Notify } from 'quasar'

export default defineComponent({
  name: 'LoginPage',
  setup() {
    const $q = useQuasar()
    const authStore = useAuthStore()
    const router = useRouter()
    const route = useRoute()
    
    const email = ref('')
    const password = ref('')
    const loading = ref(false)

    // Helper function to show notifications
    const showNotify = (type, message) => {
      const options = {
        type,
        message,
        position: 'top'
      }
      
      // Use Notify.create directly - this should work with Quasar CLI
      try {
        if (Notify && typeof Notify.create === 'function') {
          Notify.create(options)
        } else if ($q && typeof $q.notify === 'function') {
          $q.notify(options)
        } else {
          // Fallback: console log
          console[type === 'positive' ? 'log' : 'error'](message)
        }
      } catch (err) {
        console.error('Notification error:', err)
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
        
        // Determine redirect based on user role
        // The user data should be set immediately after login
        const userRole = authStore.user?.role?.slug
        let redirect = route.query.redirect
        
        if (!redirect) {
          // Check role and redirect accordingly
          if (userRole === 'applicant') {
            redirect = '/applicant/dashboard'
          } else if (userRole && ['admin', 'staff', 'committee', 'accounting', 'viewer'].includes(userRole)) {
            redirect = '/admin/dashboard'
          } else {
            // If role is not set or unknown, try to get it from the store getter
            const roleFromGetter = authStore.userRole
            if (roleFromGetter === 'applicant') {
              redirect = '/applicant/dashboard'
            } else if (roleFromGetter && ['admin', 'staff', 'committee', 'accounting', 'viewer'].includes(roleFromGetter)) {
              redirect = '/admin/dashboard'
            } else {
              // Default to home if role is still unknown
              console.warn('Unknown user role, redirecting to home:', { userRole, roleFromGetter, user: authStore.user })
              redirect = '/'
            }
          }
        }
        
        router.push(redirect)
      } catch (error) {
        const errorMessage = error.response?.data?.message || 
                           error.response?.data?.errors?.email?.[0] ||
                           'Login failed. Please check your credentials.'
        showNotify('negative', errorMessage)
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
