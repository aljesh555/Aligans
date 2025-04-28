<template>
  <footer class="bg-white border-t border-gray-200 py-8">
    <div class="container mx-auto px-4">
      <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="mb-4 md:mb-0">
          <div class="flex items-center gap-2 mb-2">
            <!-- Dynamic Logo from API -->
            <div v-if="logoData && logoData.base64_image" class="flex items-center">
              <img 
                :src="logoData.base64_image" 
                alt="Aligans Logo" 
                class="h-8 w-auto" 
                @error="handleLogoError"
              />
            </div>
            <div v-else-if="logoData && logoData.image_path" class="flex items-center">
              <img 
                :src="getLogoImageUrl(logoData)" 
                alt="Aligans Logo" 
                class="h-8 w-auto" 
                @error="handleLogoError"
              />
            </div>
            <!-- Fallback text logo if no image is available -->
            <div v-else>
              <span class="flex items-center justify-center h-8 w-8 bg-blue-700 text-white text-lg font-bold rounded-md">A</span>
            </div>
            <span class="text-xl font-bold text-blue-700">Aligans</span>
          </div>
          <p class="text-sm text-gray-600">&copy; {{ currentYear }} Aligans. All rights reserved.</p>
        </div>
        
        <div class="flex flex-col md:flex-row items-center gap-4">
          <router-link to="/terms" class="text-sm hover:text-blue-400 transition">Terms &amp; Conditions</router-link>
          <span class="hidden md:inline text-sm text-gray-600">|</span>
          <router-link to="/privacy" class="text-sm hover:text-blue-400 transition">Privacy Policy</router-link>
          <span class="hidden md:inline text-sm text-gray-600">|</span>
          <router-link to="/shipping" class="text-sm hover:text-blue-400 transition">Shipping Policy</router-link>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
import axios from 'axios';

export default {
  name: 'HomeFooter',
  data() {
    return {
      logoData: null
    }
  },
  computed: {
    currentYear() {
      return new Date().getFullYear();
    }
  },
  methods: {
    // Method to get the full URL for a logo image
    getLogoImageUrl(logo) {
      if (!logo || !logo.image_path) return '';
      
      // Create timestamp for cache busting
      const timestamp = new Date().getTime();
      
      // Check if it's already a full URL
      if (logo.image_path.startsWith('http://') || logo.image_path.startsWith('https://')) {
        return `${logo.image_path}?t=${timestamp}`;
      }
      
      // Check if it's a storage URL
      if (logo.image_path.includes('storage/')) {
        return `http://127.0.0.1:8000/${logo.image_path.startsWith('/') ? logo.image_path.substring(1) : logo.image_path}?t=${timestamp}`;
      }
      
      // Default: assume it's a storage path
      return `http://127.0.0.1:8000/storage/${logo.image_path.startsWith('/') ? logo.image_path.substring(1) : logo.image_path}?t=${timestamp}`;
    },
    handleLogoError(event) {
      console.error('Error loading logo image:', event);
      event.target.style.display = 'none';
    },
    // Add a method to fetch the logo
    async fetchLogo() {
      try {
        // Add a timestamp to prevent caching
        const timestamp = new Date().getTime();
        const response = await axios.get(`http://127.0.0.1:8000/api/logo/active?t=${timestamp}`);
        if (response.data) {
          this.logoData = response.data;
          console.log('Logo data loaded:', this.logoData);
        }
      } catch (error) {
        console.error('Error fetching logo:', error);
        this.logoData = null; // Reset logo data on error
      }
    }
  },
  async created() {
    await this.fetchLogo();
  },
  mounted() {
    // Fetch logo again on mount, as an additional safeguard
    this.fetchLogo();
  }
}
</script> 