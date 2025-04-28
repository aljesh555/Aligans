<template>
  <div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Test Orders API</h1>
    
    <div class="mb-4">
      <button @click="testApi" class="px-4 py-2 bg-blue-600 text-white rounded-md mr-2">
        Test Orders API
      </button>
      <button @click="testApiWithToken" class="px-4 py-2 bg-green-600 text-white rounded-md">
        Test with Auth Token
      </button>
    </div>
    
    <div v-if="loading" class="bg-blue-50 p-4 rounded-md mb-4">
      Loading...
    </div>
    
    <div v-if="error" class="bg-red-50 p-4 rounded-md mb-4">
      <h2 class="font-bold">Error:</h2>
      <pre class="mt-2 whitespace-pre-wrap">{{ error }}</pre>
    </div>
    
    <div v-if="response" class="bg-green-50 p-4 rounded-md mb-4">
      <h2 class="font-bold">Response:</h2>
      <pre class="mt-2 whitespace-pre-wrap overflow-auto max-h-96">{{ prettyResponse }}</pre>
    </div>
    
    <div class="bg-gray-50 p-4 rounded-md">
      <h2 class="font-bold mb-2">API Information</h2>
      <p><strong>Base URL:</strong> {{ baseUrl }}</p>
      <p><strong>Token:</strong> {{ token ? (token.substring(0, 10) + '...') : 'No token found' }}</p>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue';
import axios from 'axios';

export default {
  name: 'TestOrders',
  setup() {
    const loading = ref(false);
    const error = ref(null);
    const response = ref(null);
    const baseUrl = ref(process.env.VUE_APP_API_URL || window.location.origin);
    const token = ref(localStorage.getItem('token') || '');
    
    const prettyResponse = computed(() => {
      return JSON.stringify(response.value, null, 2);
    });
    
    const testApi = async () => {
      loading.value = true;
      error.value = null;
      response.value = null;
      
      try {
        console.log('Testing API without authentication...');
        const res = await axios.get('/api/orders');
        console.log('API Response:', res);
        response.value = res.data;
      } catch (err) {
        console.error('API Error:', err);
        if (err.response) {
          error.value = `Status: ${err.response.status}\nData: ${JSON.stringify(err.response.data, null, 2)}`;
        } else if (err.request) {
          error.value = 'No response received from server';
        } else {
          error.value = err.message;
        }
      } finally {
        loading.value = false;
      }
    };
    
    const testApiWithToken = async () => {
      loading.value = true;
      error.value = null;
      response.value = null;
      
      const localToken = localStorage.getItem('token');
      
      if (!localToken) {
        error.value = 'No authentication token found in localStorage';
        loading.value = false;
        return;
      }
      
      try {
        console.log('Testing API with authentication...');
        const res = await axios.get('/api/orders', {
          headers: {
            'Authorization': `Bearer ${localToken}`,
            'Accept': 'application/json'
          }
        });
        console.log('API Response:', res);
        response.value = res.data;
      } catch (err) {
        console.error('API Error:', err);
        if (err.response) {
          error.value = `Status: ${err.response.status}\nData: ${JSON.stringify(err.response.data, null, 2)}`;
        } else if (err.request) {
          error.value = 'No response received from server';
        } else {
          error.value = err.message;
        }
      } finally {
        loading.value = false;
      }
    };
    
    return {
      loading,
      error,
      response,
      baseUrl,
      token,
      prettyResponse,
      testApi,
      testApiWithToken
    };
  }
};
</script> 