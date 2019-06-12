import bearer from '@websanova/vue-auth/drivers/auth/bearer'
import axios from '@websanova/vue-auth/drivers/http/axios.1.x'
import router from '@websanova/vue-auth/drivers/router/vue-router.2.x'

// Auth base configuration some of this options
// can be override in method calls
export default {
  auth: bearer,
  http: axios,
  router: router,
  tokenDefaultName: 'thr_com',
  tokenStore: ['localStorage'],
  rolesVar: 'Role',
  authRedirect: {path: '/signin'},
  registerData: {url: '/api/v1/auth/register', method: 'POST', redirect: '/signin'},
  loginData: {url: '/api/v1/auth/login', method: 'POST', redirect: '/', fetchUser: true},
  logoutData: {url: '/api/v1/auth/logout', method: 'POST', redirect: '/', makeRequest: true},
  fetchData: {url: '/api/v1/auth/user', method: 'GET', enabled: true},
  refreshData: {url: '/api/v1/auth/refresh', method: 'GET', enabled: true, interval: 30}
}
