import axios from 'axios'
import emitter from '@/config/emitter'
import { useAuthStore } from '@/stores/auth'

const headers = {
  Accept: 'application/json'
}

const ApiInstance = axios.create({
  baseURL: import.meta.env.VITE_APP_API_URL,
  headers,
  withCredentials: true
})

ApiInstance.interceptors.request.use(requestInterceptor, Promise.reject)
ApiInstance.interceptors.response.use(
  response => response,
  errorHandler
)

function requestInterceptor(config) {
  const store = useAuthStore()

  if (store.logged && store.user?.token) {
    config.headers.Authorization = `Bearer ${store.user.token}`
  }

  return config
}

function errorHandler(error) {

  if (
    error.response?.status === 401 &&
    error.config?.url?.includes('/login')
  ) {
    return Promise.reject(error)
  }

  if (error.response?.status === 401) {
    const store = useAuthStore()
    store.logout()

    window.location.href = '/'
    return
  }

  // 📣 Erros gerais
  const message =
    error.response?.data?.message ||
    'Não foi possível completar a requisição'

  emitter.emit('error', message)

  return Promise.reject(error)
}

export default ApiInstance
