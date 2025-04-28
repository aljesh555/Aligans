<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Login to Sports Management System</h2>
      
      <div v-if="error" class="mb-4 p-4 bg-red-100 text-red-700 rounded">
        {{ error }}
      </div>
      
      <form @submit.prevent="handleLogin">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
            Email
          </label>
          <input 
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
            id="email" 
            type="email" 
            placeholder="Email"
            v-model="formData.email"
            required
          >
        </div>
        
        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
            Password
          </label>
          <input 
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" 
            id="password" 
            type="password" 
            placeholder="******************"
            v-model="formData.password"
            required
          >
        </div>
        
        <div class="flex items-center justify-between">
          <button 
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full" 
            type="submit"
            :disabled="loading"
          >
            {{ loading ? 'Logging in...' : 'Sign In' }}
          </button>
        </div>
        
        <div class="text-center mt-6">
          <p class="text-sm text-gray-600">
            Don't have an account? 
            <router-link to="/register" class="text-blue-600 hover:text-blue-800">
              Register
            </router-link>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

export default {
  name: 'Login',
  setup() {
    const store = useStore()
    const router = useRouter()
    
    const formData = ref({
      email: '',
      password: ''
    })
    
    const error = ref(null)
    const loading = ref(false)
    
    const handleLogin = async () => {
      try {
        loading.value = true
        error.value = null
        
        await store.dispatch('login', formData.value)
        router.push({ name: 'Home' })
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to login. Please check your credentials.'
      } finally {
        loading.value = false
      }
    }
    
    return {
      formData,
      error,
      loading,
      handleLogin
    }
  }
}
</script> 