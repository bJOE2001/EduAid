import { defineStore } from 'pinia'
import { api } from '../boot/axios'
import { Notify } from 'quasar'

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
        
        Notify.create({
          type: 'positive',
          message: 'Login successful!',
          position: 'top'
        })
        
        return response.data
      } catch (error) {
        Notify.create({
          type: 'negative',
          message: error.response?.data?.message || 'Login failed',
          position: 'top'
        })
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
        
        Notify.create({
          type: 'positive',
          message: 'Registration successful!',
          position: 'top'
        })
        
        return response.data
      } catch (error) {
        Notify.create({
          type: 'negative',
          message: error.response?.data?.message || 'Registration failed',
          position: 'top'
        })
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
