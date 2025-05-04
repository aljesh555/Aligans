<template>
  <div>
    <Header />
    
    <div class="bg-gray-50 py-12">
      <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
          <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">Privacy & Security Policy</h1>
          
          <div v-if="loading" class="bg-white rounded-lg shadow-sm p-6 md:p-8 text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
            <p class="mt-4">Loading privacy policy...</p>
          </div>
          
          <div v-else-if="error" class="bg-white rounded-lg shadow-sm p-6 md:p-8">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
              <strong class="font-bold">Error!</strong>
              <span class="block sm:inline"> {{ error }}</span>
            </div>
          </div>
          
          <div v-else-if="privacyPolicies && privacyPolicies.length > 0" class="bg-white rounded-lg shadow-sm p-6 md:p-8">
            <div v-for="(policy, index) in privacyPolicies" :key="index" class="privacy-policy-content">
              <h1 class="text-2xl font-bold mb-6">{{ policy.title }}</h1>
              
              <div class="text-gray-500 mb-8">
                Last updated: {{ formatDate(policy.updated_at) }}
              </div>
              
              <div class="prose prose-blue max-w-none" v-html="policy.content"></div>
              
              <hr v-if="index < privacyPolicies.length - 1" class="my-8">
            </div>
          </div>
          
          <div v-else class="bg-white rounded-lg shadow-sm p-6 md:p-8">
            <div class="text-gray-500">No privacy policy available at the moment.</div>
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
  name: 'Privacy',
  components: {
    Header,
    Footer
  },
  data() {
    return {
      privacyPolicies: null,
      loading: true,
      error: null,
      apiBaseUrl: 'http://127.0.0.1:8000'
    };
  },
  mounted() {
    this.fetchPrivacyPolicy();
  },
  methods: {
    async fetchPrivacyPolicy() {
      this.loading = true;
      try {
        // Fetch privacy policy from static_pages table
        const response = await axios.get(`${this.apiBaseUrl}/api/static-pages/privacy-policy`);
        
        console.log('API Response:', response.data);
        
        if (response.data && response.data.success && response.data.data) {
          // Convert single policy object into array to maintain compatibility with existing template
          const policyData = response.data.data;
          this.privacyPolicies = [{
            title: policyData.title || 'Privacy Policy',
            content: policyData.content,
            updated_at: policyData.updated_at
          }];
          
          console.log('Privacy policy loaded successfully', this.privacyPolicies);
        } else {
          console.error('Invalid response format:', response.data);
          this.error = response.data?.message || 'Failed to load privacy policy data.';
        }
      } catch (error) {
        console.error('Error fetching privacy policy:', error);
        if (error.response) {
          // The request was made and the server responded with a status code
          // that falls out of the range of 2xx
          console.error('Response data:', error.response.data);
          console.error('Response status:', error.response.status);
          this.error = `Error ${error.response.status}: ${error.response.data?.message || 'Unable to load privacy policy'}`;
        } else if (error.request) {
          // The request was made but no response was received
          console.error('No response received:', error.request);
          this.error = 'No response from server. Please check your connection and try again.';
        } else {
          // Something happened in setting up the request that triggered an Error
          console.error('Request setup error:', error.message);
        this.error = 'Unable to load privacy policy. Please try again later.';
        }
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
    title: 'Privacy Policy | Aligans'
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