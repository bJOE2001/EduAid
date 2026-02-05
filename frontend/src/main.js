import { createApp } from 'vue'
import { Quasar } from 'quasar'
import { createPinia } from 'pinia'
import router from './router'

// Import icon libraries
import '@quasar/extras/material-icons/material-icons.css'
import '@quasar/extras/fontawesome-v6/fontawesome-v6.css'

// Import Quasar css
import 'quasar/src/css/index.sass'

// Import App component
import App from './App.vue'

const myApp = createApp(App)

// Install Quasar with plugins (plugins are configured in quasar.config.js)
// But we need to explicitly enable them here for useQuasar() to work
myApp.use(Quasar, {
  plugins: {
    Notify: {},
    Dialog: {},
    Loading: {}
  },
  config: {
    brand: {
      primary: '#1976D2',
      secondary: '#26A69A',
      accent: '#9C27B0',
      dark: '#1d1d1d',
      positive: '#21BA45',
      negative: '#C10015',
      info: '#31CCEC',
      warning: '#F2C037'
    }
  }
})

// Install Pinia
const pinia = createPinia()
myApp.use(pinia)

// Install Router
myApp.use(router)

// Initialize auth store
import { useAuthStore } from './stores/auth'
const authStore = useAuthStore()
authStore.initializeAuth()

// Mount the app
myApp.mount('#app')
