import { boot } from 'quasar/wrappers'
import router from '../router'

export default boot(({ app }) => {
  // Set up router guard after Pinia is initialized (pinia boot runs first)
  router.beforeEach(async (to, from, next) => {
    try {
      // Dynamically import useAuthStore to ensure Pinia is initialized
      const { useAuthStore } = await import('../stores/auth')
      const authStore = useAuthStore()

      // If user has token but no user data, try to fetch it
      if (authStore.token && !authStore.user) {
        try {
          await authStore.fetchUser()
        } catch (error) {
          // If fetch fails, continue with navigation check
          console.warn('Failed to fetch user on route guard:', error)
        }
      }

      if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next({ name: 'login', query: { redirect: to.fullPath } })
      } else if (to.meta.role) {
        const userRole = authStore.user?.role?.slug
        const allowedRoles = Array.isArray(to.meta.role) ? to.meta.role : [to.meta.role]

        if (!userRole || !allowedRoles.includes(userRole)) {
          // User doesn't have required role, redirect to home
          next({ name: 'home' })
        } else {
          next()
        }
      } else {
        next()
      }
    } catch (error) {
      console.warn('Auth store not available during navigation, allowing navigation:', error)
      next()
    }
  })
})
