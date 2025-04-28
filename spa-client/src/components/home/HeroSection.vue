<template>
  <section class="relative">
    <!-- Dynamic Background Image that's clickable (no text overlay) -->
    <a 
      :href="heroData.image_link || '#'" 
      class="block relative w-full h-[500px] overflow-hidden"
    >
      <img 
        :src="heroData.image_url || fallbackImage" 
        alt="Hero Banner"
        class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-105"
      />
    
      <!-- Loading State -->
      <div v-if="isLoading" class="absolute inset-0 bg-gray-900/50 flex items-center justify-center">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
      </div>
    </a>
  </section>
</template>

<script>
import axios from 'axios';

export default {
  name: 'HeroSection',
  props: {
    fallbackImage: {
      type: String,
      default: 'https://images.unsplash.com/photo-1517466787929-bc90951d0974?ixlib=rb-4.0.3'
    }
  },
  data() {
    return {
      isLoading: true,
      heroData: {
        image_url: '',
        image_link: ''
      },
      error: null
    }
  },
  mounted() {
    this.fetchHeroData();
  },
  methods: {
    async fetchHeroData() {
      this.isLoading = true;
      try {
        // Fetch active hero section from API
        const response = await axios.get('/api/hero-section/active');
        
        // Update heroData with response from server
        if (response.data && response.data.success) {
          this.heroData = response.data.data;
        }
      } catch (error) {
        console.error('Failed to fetch hero data:', error);
        this.error = 'Failed to load hero section data';
      } finally {
        this.isLoading = false;
      }
    }
  }
}
</script>

<style scoped>
/* Simple fade-in animation */
@keyframes fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}

section {
  animation: fade-in 0.8s ease;
}
</style> 