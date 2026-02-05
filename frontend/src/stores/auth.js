import { defineStore } from 'pinia'
import { api } from '../boot/axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    userRole: (state) => state.user?.role?.slug,
  },

  actions: {
    async login(credentials) {
      try {
        const response = await api.post('/login', credentials)
        this.token = response.data.token
        this.user = response.data.user
        localStorage.setItem('token', this.token)
        
        // Set default authorization header
        api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        
        // Notification will be handled by the component
        
        return response.data
      } catch (error) {
        // Error will be thrown and handled by the component
        throw error
      }
    },

    async register(userData) {
      try {
        const response = await api.post('/register', userData)
        this.token = response.data.token
        this.user = response.data.user
        localStorage.setItem('token', this.token)
        
        api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        
        // Notification will be handled by the component
        
        return response.data
      } catch (error) {
        // Error will be thrown and handled by the component
        throw error
      }
    },

    async logout() {
      try {
        await api.post('/logout')
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.token = null
        this.user = null
        localStorage.removeItem('token')
        delete api.defaults.headers.common['Authorization']
      }
    },

    async fetchUser() {
      try {
        const response = await api.get('/me')
        this.user = response.data
        return response.data
      } catch (error) {
        // If fetch fails, clear auth but don't throw
        if (error.response?.status === 401) {
          this.logout()
        }
        throw error
      }
    },

    initializeAuth() {
      if (this.token) {
        // Token is already set in localStorage, axios interceptor will add it to requests
        // Try to fetch user to verify token is still valid
        this.fetchUser().catch(() => {
          // If fetch fails, token is invalid, clear auth silently
          // Don't call logout() here to avoid redirect loops
          this.token = null
          this.user = null
          localStorage.removeItem('token')
        })
      }
    }
  }
})
