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
        // Try the new dedicated endpoint first
        const response = await axios.get(`${this.apiBaseUrl}/api/terms`);
        
        if (response.data && response.data.success && response.data.data) {
          this.termsList = response.data.data;
          console.log('Terms loaded from dedicated endpoint:', this.termsList);
        } else {
          // Fallback to the old endpoint
          const fallbackResponse = await axios.get(`${this.apiBaseUrl}/api/settings/terms-conditions`);
          
          if (fallbackResponse.data && fallbackResponse.data.success && fallbackResponse.data.data) {
            // Convert to array if single object
            this.termsList = Array.isArray(fallbackResponse.data.data) 
              ? fallbackResponse.data.data 
              : [fallbackResponse.data.data];
            console.log('Terms loaded from settings endpoint:', this.termsList);
          } else {
            this.error = 'Unable to load terms and conditions';
          }
        }
      } catch (err) {
        console.error('Error fetching terms:', err);
        try {
          // Try fallback endpoint if first one fails
          const fallbackResponse = await axios.get(`${this.apiBaseUrl}/api/settings/terms-conditions`);
          
          if (fallbackResponse.data && fallbackResponse.data.success && fallbackResponse.data.data) {
            // Convert to array if single object
            this.termsList = Array.isArray(fallbackResponse.data.data) 
              ? fallbackResponse.data.data 
              : [fallbackResponse.data.data];
            console.log('Terms loaded from settings endpoint after error:', this.termsList);
          } else {
            this.error = 'Failed to load terms and conditions';
          }
        } catch (fallbackErr) {
          console.error('Error fetching terms from fallback:', fallbackErr);
          this.error = 'Failed to load terms and conditions';
        }
      } finally {
        this.loading = false;
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