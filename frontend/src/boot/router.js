import { boot } from 'quasar/wrappers'
import { setupRouterGuard } from '../router'

export default boot(() => {
  // Set up router guard after Pinia is initialized (pinia boot runs first)
  setupRouterGuard()
})
