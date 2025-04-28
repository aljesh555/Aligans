<template>
  <footer class="bg-gray-800 text-white">
    <div class="container mx-auto px-4 py-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Company info -->
        <div>
          <div class="flex items-center gap-2 mb-6">
            <!-- Dynamic Logo from API -->
            <div v-if="logoData && logoData.base64_image" class="flex items-center">
              <img 
                :src="logoData.base64_image" 
                alt="Aligans Logo" 
                class="h-10 w-auto" 
                @error="handleLogoError"
              />
            </div>
            <div v-else-if="logoData && logoData.image_path" class="flex items-center">
              <img 
                :src="getLogoImageUrl(logoData)" 
                alt="Aligans Logo" 
                class="h-10 w-auto" 
                @error="handleLogoError"
              />
            </div>
            <!-- Fallback text logo -->
            <div v-else>
              <span class="flex items-center justify-center h-10 w-10 bg-blue-700 text-white text-xl font-bold rounded-md">A</span>
            </div>
            <span class="text-2xl font-bold text-white">Aligans</span>
          </div>
          <p class="text-gray-400 mb-4">
            Your one-stop shop for quality products at affordable prices.
          </p>
          <div class="flex space-x-4">
            <a v-for="social in socialMediaLinks" 
               :key="social.platform" 
               :href="social.url" 
               target="_blank"
               class="text-gray-400 hover:text-white transition-colors"
               v-if="social.is_active">
              <span v-html="social.icon"></span>
            </a>
          </div>
        </div>
        
        <!-- Quick links -->
        <div>
          <h3 class="text-lg font-bold mb-4">Quick Links</h3>
          <ul class="space-y-2">
            <li><router-link to="/" class="text-gray-400 hover:text-white transition-colors">Home</router-link></li>
            <li><router-link to="/products" class="text-gray-400 hover:text-white transition-colors">Products</router-link></li>
            <li><router-link to="/categories" class="text-gray-400 hover:text-white transition-colors">Categories</router-link></li>
            <li><router-link to="/about" class="text-gray-400 hover:text-white transition-colors">About Us</router-link></li>
            <li><router-link to="/contact-us" class="text-gray-400 hover:text-white transition-colors">Contact Us</router-link></li>
            <li><router-link to="/blog" class="text-gray-400 hover:text-white transition-colors">Blog</router-link></li>
          </ul>
        </div>
        
        <!-- Customer service -->
        <div>
          <h3 class="text-lg font-bold mb-4">Customer Service</h3>
          <ul class="space-y-2">
            <li><router-link to="/contact-us" class="text-gray-400 hover:text-white transition-colors">Contact Us</router-link></li>
            <li><router-link to="/faq" class="text-gray-400 hover:text-white transition-colors">FAQ</router-link></li>
            <li><router-link to="/shipping" class="text-gray-400 hover:text-white transition-colors">Shipping Policy</router-link></li>
            <li><router-link to="/returns" class="text-gray-400 hover:text-white transition-colors">Returns & Refunds</router-link></li>
          </ul>
        </div>
        
        <!-- Newsletter -->
        <div>
          <h3 class="text-lg font-bold mb-4">Stay Updated</h3>
          <p class="text-gray-400 mb-4">Subscribe to our newsletter for the latest products and offers.</p>
          <form class="flex">
            <input type="email" placeholder="Your email" class="px-3 py-2 rounded-l-lg w-full focus:outline-none text-gray-800" />
            <button type="submit" class="bg-indigo-600 px-4 py-2 rounded-r-lg hover:bg-indigo-700 transition-colors">
              Subscribe
            </button>
          </form>
        </div>
      </div>
      
      <!-- Copyright -->
      <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
        <p>&copy; {{ copyright }}</p>
        <div class="mt-3">
          <router-link to="/terms" class="text-gray-400 hover:text-white transition-colors">Terms & Conditions</router-link>
          <span class="mx-2">|</span>
          <router-link to="/privacy" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</router-link>
          <span class="mx-2">|</span>
          <router-link to="/shipping" class="text-gray-400 hover:text-white transition-colors">Shipping Policy</router-link>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Footer',
  data() {
    return {
      socialMediaLinks: [],
      logoData: null,
      copyright: `© ${new Date().getFullYear()} Aligans. All rights reserved.`
    };
  },
  async created() {
    await Promise.all([
      this.fetchSocialMediaLinks(),
      this.fetchLogo()
    ]);
  },
  methods: {
    async fetchSocialMediaLinks() {
      try {
        const response = await axios.get('http://localhost:8000/api/settings/social-media');
        this.socialMediaLinks = response.data;
      } catch (error) {
        console.error('Error fetching social media links:', error);
      }
    },
    
    // Method to fetch the logo
    async fetchLogo() {
      try {
        // Add a timestamp to prevent caching
        const timestamp = new Date().getTime();
        const response = await axios.get(`http://localhost:8000/api/logo/active?t=${timestamp}`);
        if (response.data) {
          this.logoData = response.data;
          console.log('Logo data loaded:', this.logoData);
        }
      } catch (error) {
        console.error('Error fetching logo:', error);
        this.logoData = null; // Reset logo data on error
      }
    },
    
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
        return `http://localhost:8000/${logo.image_path.startsWith('/') ? logo.image_path.substring(1) : logo.image_path}?t=${timestamp}`;
      }
      
      // Default: assume it's a storage path
      return `http://localhost:8000/storage/${logo.image_path.startsWith('/') ? logo.image_path.substring(1) : logo.image_path}?t=${timestamp}`;
    },
    
    // Handle logo loading errors
    handleLogoError(event) {
      console.error('Error loading logo image:', event);
      event.target.style.display = 'none';
    }
  }
}
</script> 