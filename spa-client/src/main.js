import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './assets/css/tailwind.css'
import axios from 'axios'
import store from './store'

// Configure Axios with explicit base URL to ensure correct connection
axios.defaults.baseURL = 'http://127.0.0.1:8000'
axios.defaults.withCredentials = true
axios.defaults.headers.common['Content-Type'] = 'application/json'
axios.defaults.headers.common['Accept'] = 'application/json'

// Add more detailed logging for connection issues
axios.interceptors.request.use(config => {
  console.log(`Making request to: ${config.baseURL}${config.url}`);
  return config;
}, error => {
  console.error('Request error:', error);
  return Promise.reject(error);
});

// Add auth token to requests if available
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Add response interceptor to handle auth errors and improve error logging
axios.interceptors.response.use(
  response => {
    console.log('Response received successfully');
    return response;
  },
  error => {
    if (error.response) {
      console.error('Server responded with:', error.response.status, error.response.data);
      // Handle unauthorized (401) responses
      if (error.response.status === 401) {
        // Clear auth state
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        store.commit('auth/logout');
        
        // Redirect to login if not already there
        if (router.currentRoute.value.name !== 'Login') {
          router.push({
            name: 'Login',
            query: { redirect: router.currentRoute.value.fullPath }
          });
        }
      }
      
      // Handle forbidden (403) responses
      if (error.response.status === 403) {
        console.error('Permission denied:', error.response.data);
      }
    } else if (error.request) {
      console.error('No response received from server. Network error details:', error.request);
      // Check if CORS might be an issue
      console.error('Potential CORS issue or server not running. Check that API server is running at http://127.0.0.1:8000');
    } else {
      console.error('Error setting up request:', error.message);
    }
    
    return Promise.reject(error);
  }
);

const app = createApp(App)

// Use router and store
app.use(router)
app.use(store)

// Initialize authentication state
store.dispatch('auth/initAuth').then(() => {
  console.log('Auth state initialized');
})

// Global error handler
app.config.errorHandler = (err, vm, info) => {
  console.error('Global error:', err);
  console.error('Info:', info);
}

app.mount('#app') 