import { boot } from 'quasar/wrappers'
import { createPinia } from 'pinia'

export default boot(({ app }) => {
  const pinia = createPinia()
  app.use(pinia)
  
  // Initialize auth store after Pinia is set up
  import('../stores/auth').then(({ useAuthStore }) => {
    const authStore = useAuthStore()
    authStore.initializeAuth()
  })
})
