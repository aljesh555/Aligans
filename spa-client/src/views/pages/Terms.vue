<template>
  <div>
    <Header />
    
    <div class="bg-gray-50 py-12">
      <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
          <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">Terms of service</h1>
          
          <div v-if="loading" class="bg-white rounded-lg shadow-sm p-6 md:p-8">
            <div class="animate-pulse">
              <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
              <div class="h-4 bg-gray-200 rounded w-1/2 mb-6"></div>
              <div class="h-4 bg-gray-200 rounded w-5/6 mb-4"></div>
              <div class="h-4 bg-gray-200 rounded w-4/6 mb-4"></div>
              <div class="h-4 bg-gray-200 rounded w-3/6 mb-6"></div>
              <div class="h-4 bg-gray-200 rounded w-5/6 mb-4"></div>
              <div class="h-4 bg-gray-200 rounded w-4/6 mb-4"></div>
              <div class="h-4 bg-gray-200 rounded w-3/6 mb-4"></div>
            </div>
          </div>
          
          <div v-else-if="error" class="bg-white rounded-lg shadow-sm p-6 md:p-8 text-center">
            <p class="text-red-500 mb-4">{{ error }}</p>
            <button @click="fetchTerms" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
              Try Again
            </button>
          </div>
          
          <div v-else-if="termsList && termsList.length > 0">
            <div v-for="(terms, index) in termsList" :key="index" class="bg-white rounded-lg shadow-sm p-6 md:p-8 mb-6">
              <div class="prose prose-blue max-w-none">
                <h2 class="text-xl font-bold mb-2">{{ terms.title }}</h2>
                <p class="text-sm text-gray-500 mb-4">Last updated: {{ formatDate(terms.updated_at) }}</p>
                
                <div v-html="terms.content"></div>
              </div>
            </div>
          </div>
          
          <div v-else class="bg-white rounded-lg shadow-sm p-6 md:p-8 text-center">
            <p>No terms and conditions found.</p>
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
  name: 'Terms',
  components: {
    Header,
    Footer
  },
  data() {
    return {
      termsList: [],
      loading: true,
      error: null,
      apiBaseUrl: 'http://127.0.0.1:8000'
    };
  },
  metaInfo() {
    return {
      title: 'Terms & Conditions | Aligans'
    };
  },
  created() {
    this.fetchTerms();
  },
  methods: {
    async fetchTerms() {
      this.loading = true;
      this.error = null;
      
      try {
        // Fetch terms and conditions from static_pages table
        const response = await axios.get(`${this.apiBaseUrl}/api/static-pages/terms-conditions`);
        
        console.log('API Response:', response.data);
        
        if (response.data && response.data.success && response.data.data) {
          // Convert single terms object into array to maintain compatibility with existing template
          const termsData = response.data.data;
          this.termsList = [{
            title: termsData.title || 'Terms & Conditions',
            content: termsData.content,
            updated_at: termsData.updated_at
          }];
          
          console.log('Terms loaded successfully', this.termsList);
        } else {
          console.error('Invalid response format:', response.data);
          this.error = response.data?.message || 'Failed to load terms and conditions data.';
          
          // Try fallback methods only if we get an explicit "not found" message
          if (response.data?.message === 'Page not found') {
            await this.tryFallbackMethods();
          }
        }
      } catch (error) {
        console.error('Error fetching terms:', error);
        if (error.response) {
          console.error('Response data:', error.response.data);
          console.error('Response status:', error.response.status);
          this.error = `Error ${error.response.status}: ${error.response.data?.message || 'Unable to load terms and conditions'}`;
          
          // Try fallback methods on 404
          if (error.response.status === 404) {
            await this.tryFallbackMethods();
          }
        } else if (error.request) {
          console.error('No response received:', error.request);
          this.error = 'No response from server. Please check your connection and try again.';
        } else {
          console.error('Request setup error:', error.message);
          this.error = 'Unable to load terms and conditions. Please try again later.';
        }
      } finally {
        this.loading = false;
      }
    },
    
    async tryFallbackMethods() {
      try {
        // Try alternative endpoints if static_pages fails
        console.log('Trying fallback methods...');
        
        // Try the dedicated endpoint
        const response = await axios.get(`${this.apiBaseUrl}/api/terms`);
        
        if (response.data && response.data.success && response.data.data) {
          this.termsList = response.data.data;
          console.log('Terms loaded from dedicated endpoint:', this.termsList);
          this.error = null;
          return true;
        }
        
        // Try the settings endpoint
          const fallbackResponse = await axios.get(`${this.apiBaseUrl}/api/settings/terms-conditions`);
          
          if (fallbackResponse.data && fallbackResponse.data.success && fallbackResponse.data.data) {
            // Convert to array if single object
            this.termsList = Array.isArray(fallbackResponse.data.data) 
              ? fallbackResponse.data.data 
              : [fallbackResponse.data.data];
            console.log('Terms loaded from settings endpoint:', this.termsList);
          this.error = null;
          return true;
        }
        
        return false;
      } catch (err) {
        console.error('All fallback methods failed:', err);
        return false;
      }
    },
    
    formatDate(dateString) {
      if (!dateString) return 'Not specified';
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      });
    }
  }
}
</script> 