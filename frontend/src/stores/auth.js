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
        this.logout()
        throw error
      }
    },

    initializeAuth() {
      if (this.token) {
        api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        this.fetchUser().catch(() => {
          // If fetch fails, clear auth
          this.logout()
        })
      }
    }
  }
})
