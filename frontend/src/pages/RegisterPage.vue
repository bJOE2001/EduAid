<template>
  <q-page class="flex flex-center bg-grey-2">
    <q-card class="q-pa-md" style="min-width: 400px">
      <q-card-section>
        <div class="text-h5 text-center q-mb-md">Register for EduAid</div>
        <q-form @submit="onSubmit" class="q-gutter-md">
          <q-input v-model="name" label="Full Name" :rules="[val => !!val || 'Name is required']" outlined />
          <q-input v-model="email" label="Email" type="email" :rules="[val => !!val || 'Email is required']" outlined />
          <q-input v-model="password" label="Password" type="password" :rules="[val => !!val || 'Password is required', val => val.length >= 8 || 'Password must be at least 8 characters']" outlined />
          <q-input v-model="passwordConfirmation" label="Confirm Password" type="password" :rules="[val => !!val || 'Please confirm password', val => val === password || 'Passwords do not match']" outlined />
          <q-btn label="Register" type="submit" color="primary" class="full-width" :loading="loading" />
          <div class="text-center">
            <q-btn flat label="Already have an account? Login" to="/auth/login" />
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

export default defineComponent({
  name: 'RegisterPage',
  setup() {
    const authStore = useAuthStore()
    const router = useRouter()
    const name = ref('')
    const email = ref('')
    const password = ref('')
    const passwordConfirmation = ref('')
    const loading = ref(false)

    const onSubmit = async () => {
      loading.value = true
      try {
        await authStore.register({
          name: name.value,
          email: email.value,
          password: password.value,
          password_confirmation: passwordConfirmation.value
        })
        router.push('/applicant/profile')
      } catch (error) {
        console.error('Registration error:', error)
      } finally {
        loading.value = false
      }
    }

    return { name, email, password, passwordConfirmation, loading, onSubmit }
  }
})
</script>
