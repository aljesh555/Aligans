<template>
  <div>
    <Header />
    
    <div class="bg-gray-50 py-12">
      <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
          <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">Shipping Policy</h1>
          
          <div v-if="loading" class="bg-white rounded-lg shadow-sm p-6 md:p-8 text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
            <p class="mt-4">Loading shipping policy...</p>
          </div>
          
          <div v-else-if="error" class="bg-white rounded-lg shadow-sm p-6 md:p-8">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
              <strong class="font-bold">Error!</strong>
              <span class="block sm:inline"> {{ error }}</span>
            </div>
          </div>
          
          <div v-else-if="shippingPolicies && shippingPolicies.length > 0" class="bg-white rounded-lg shadow-sm p-6 md:p-8">
            <div v-for="(policy, index) in shippingPolicies" :key="index" class="shipping-policy-content">
              <h1 class="text-2xl font-bold mb-6">{{ policy.title }}</h1>
              
              <div class="text-gray-500 mb-8">
                Last updated: {{ formatDate(policy.updated_at) }}
              </div>
              
              <div class="prose prose-blue max-w-none" v-html="policy.content"></div>
              
              <hr v-if="index < shippingPolicies.length - 1" class="my-8">
            </div>
          </div>
          
          <div v-else class="bg-white rounded-lg shadow-sm p-6 md:p-8">
            <div class="text-gray-500">No shipping policy available at the moment.</div>
          </div>
        </div>
      </div>
    </div>
    
    <Footer />
  </div>
</template>

<script>
import Header from '@/components/Header.vue';
import Footer from '@/components/layout/Footer.vue';
import axios from 'axios';

export default {
  name: 'Shipping',
  components: {
    Header,
    Footer
  },
  data() {
    return {
      shippingPolicies: null,
      loading: true,
      error: null,
      apiBaseUrl: 'http://127.0.0.1:8000'
    };
  },
  mounted() {
    this.fetchShippingPolicy();
  },
  methods: {
    async fetchShippingPolicy() {
      this.loading = true;
      try {
        const response = await axios.get(`${this.apiBaseUrl}/api/shipping-policy`);
        
        if (response.data && response.data.success && response.data.data) {
          this.shippingPolicies = response.data.data;
          console.log('Shipping policy loaded successfully', this.shippingPolicies);
        } else {
          throw new Error('Invalid response format');
        }
      } catch (error) {
        console.error('Error fetching shipping policy:', error);
        this.error = 'Unable to load shipping policy. Please try again later.';
      } finally {
        this.loading = false;
      }
    },
    
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      });
    }
  },
  metaInfo: {
    title: 'Shipping Policy | Aligans'
  }
}
</script>

<style scoped>
.prose :deep(h2) {
  font-size: 1.5rem;
  font-weight: 700;
  margin-top: 2rem;
  margin-bottom: 0.75rem;
  color: #1a202c;
}

.prose :deep(h3) {
  font-size: 1.25rem;
  font-weight: 600;
  margin-top: 1.5rem;
  margin-bottom: 0.5rem;
  color: #2d3748;
}

.prose :deep(p) {
  margin-bottom: 1rem;
  line-height: 1.7;
}

.prose :deep(ul) {
  list-style-type: disc;
  padding-left: 1.5rem;
  margin-bottom: 1rem;
}

.prose :deep(li) {
  margin-bottom: 0.5rem;
}
</style> 