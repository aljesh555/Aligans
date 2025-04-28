export const API_URL = 'http://localhost:8000/api'

export const setupAxiosInterceptors = (axios, store, router) => {
  axios.interceptors.response.use(
    response => response,
    error => {
      if (error.response && error.response.status === 401) {
        store.dispatch('logout')
        router.push('/login')
      }
      return Promise.reject(error)
    }
  )
} 