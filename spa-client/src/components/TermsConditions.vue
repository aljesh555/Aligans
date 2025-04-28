<template>
  <div class="terms-conditions-container">
    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    
    <div v-else-if="error" class="alert alert-danger" role="alert">
      {{ error }}
    </div>
    
    <div v-else-if="termsList && termsList.length > 0" class="terms-list">
      <div v-for="(terms, index) in termsList" :key="index" class="terms-content mb-5">
        <h1 class="mb-4">{{ terms.title }}</h1>
        
        <div class="mb-3 text-muted">
          Last updated: {{ formatDate(terms.updated_at) }}
        </div>
        
        <div class="content" v-html="terms.content"></div>
        <hr v-if="index < termsList.length - 1" class="my-5">
      </div>
    </div>
    
    <div v-else class="alert alert-info">
      No terms and conditions found
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'TermsConditions',
  
  data() {
    return {
      termsList: null,
      loading: true,
      error: null,
      apiBaseUrl: 'http://127.0.0.1:8000'
    };
  },
  
  mounted() {
    this.fetchTerms();
  },
  
  methods: {
    async fetchTerms() {
      this.loading = true;
      try {
        // Try direct API endpoint first
        const response = await axios.get(`${this.apiBaseUrl}/api/terms`);
        
        if (response.data && response.data.success && response.data.data) {
          this.termsList = response.data.data;
          console.log('Terms loaded successfully', this.termsList);
        } else {
          throw new Error('Invalid response format');
        }
      } catch (error) {
        console.error('Error fetching terms:', error);
        
        try {
          // Try fallback to settings endpoint
          const fallbackResponse = await axios.get(`${this.apiBaseUrl}/api/settings/terms-conditions`);
          
          if (fallbackResponse.data && fallbackResponse.data.success && fallbackResponse.data.data) {
            // If it's a single object from the old endpoint, convert to array
            this.termsList = Array.isArray(fallbackResponse.data.data) 
              ? fallbackResponse.data.data 
              : [fallbackResponse.data.data];
            console.log('Terms loaded from fallback endpoint');
          } else {
            this.error = 'Failed to load terms and conditions';
          }
        } catch (fallbackErr) {
          console.error('Error fetching from fallback:', fallbackErr);
          this.error = 'Unable to load terms and conditions. Please try again later.';
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
  }
};
</script>

<style scoped>
.terms-conditions-container {
  max-width: 900px;
  margin: 0 auto;
  padding: 2rem;
}

.terms-content {
  background-color: #fff;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.content :deep(h2) {
  margin-top: 2rem;
  margin-bottom: 1rem;
  font-weight: 600;
}

.content :deep(p) {
  margin-bottom: 1rem;
  line-height: 1.6;
}
</style> 