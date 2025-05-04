<template>
  <div class="relative">
    <!-- Loading State -->
    <div v-if="loading" class="flex gap-4 overflow-hidden">
      <div v-for="i in 5" :key="i" class="animate-pulse flex-none w-full sm:w-1/2 md:w-1/3 lg:w-1/5">
        <div class="bg-gray-200 h-24 rounded mb-2"></div>
        <div class="h-4 bg-gray-200 rounded w-3/4 mx-auto"></div>
      </div>
    </div>
    
    <!-- Error State -->
    <div v-else-if="error" class="text-center py-4">
      <p class="text-red-500">{{ error }}</p>
    </div>
    
    <!-- No Brands State -->
    <div v-else-if="!brands || brands.length === 0" class="text-center py-4">
      <p class="text-gray-500">No brands available at the moment.</p>
    </div>
    
    <!-- Brands Slider -->
    <div v-else class="relative">
      <!-- Previous Button -->
      <button 
        @click="prevSlide" 
        class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-md hover:bg-gray-100 focus:outline-none"
        :class="{'opacity-50 cursor-not-allowed': currentSlide === 0}"
        :disabled="currentSlide === 0"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      
      <!-- Slider Content -->
      <div class="overflow-hidden mx-10">
        <div 
          class="flex transition-transform duration-500 ease-in-out" 
          :style="{transform: `translateX(-${currentSlide * 20}%)`}"
        >
          <!-- Brand Item -->
          <div 
            v-for="brand in brands" 
            :key="brand.id"
            class="flex-none w-full sm:w-1/2 md:w-1/3 lg:w-1/5 px-2"
          >
            <div 
              @click="navigateToBrand(brand)"
              class="bg-white rounded-lg shadow-sm hover:shadow-md p-4 text-center cursor-pointer transition-all duration-300 h-full"
            >
              <div class="h-16 flex items-center justify-center mb-3">
                <img 
                  :src="getBrandLogo(brand)" 
                  :alt="brand.name" 
                  class="h-full object-contain max-w-full"
                  @error="handleLogoError"
                >
              </div>
              <h3 class="font-medium text-gray-800">{{ brand.name }}</h3>
              <p v-if="brand.product_count" class="text-sm text-gray-500 mt-1">
                {{ brand.product_count }} products
              </p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Next Button -->
      <button 
        @click="nextSlide" 
        class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-white rounded-full p-2 shadow-md hover:bg-gray-100 focus:outline-none"
        :class="{'opacity-50 cursor-not-allowed': currentSlide >= maxSlide}"
        :disabled="currentSlide >= maxSlide"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'BrandSlider',
  data() {
    return {
      brands: [],
      loading: true,
      error: null,
      currentSlide: 0,
      apiBaseUrl: 'http://127.0.0.1:8000',
      brandsPerSlide: 5
    };
  },
  computed: {
    maxSlide() {
      return Math.max(0, Math.ceil(this.brands.length / this.brandsPerSlide) - 1);
    }
  },
  created() {
    this.fetchFeaturedBrands();
    window.addEventListener('resize', this.handleResize);
  },
  beforeUnmount() {
    window.removeEventListener('resize', this.handleResize);
  },
  methods: {
    async fetchFeaturedBrands() {
      this.loading = true;
      this.error = null;
      
      try {
        console.log('Fetching brands from API:', `${this.apiBaseUrl}/api/brands/featured`);
        
        // Fetch featured brands first
        const response = await axios.get(`${this.apiBaseUrl}/api/brands/featured`);
        console.log('Featured brands API response:', response.data);
        
        if (response.data && response.data.data) {
          this.brands = response.data.data;
          console.log(`Found ${this.brands.length} featured brands`);
          
          // If there are less than 5 featured brands, fetch more regular brands
          if (this.brands.length < 5) {
            console.log('Fetching additional non-featured brands');
            const allBrandsResponse = await axios.get(`${this.apiBaseUrl}/api/brands`);
            console.log('All brands API response:', allBrandsResponse.data);
            
            if (allBrandsResponse.data && allBrandsResponse.data.data) {
              // Filter out brands that are already in the featured list
              const featuredIds = this.brands.map(brand => brand.id);
              const additionalBrands = allBrandsResponse.data.data
                .filter(brand => !featuredIds.includes(brand.id))
                .slice(0, 5 - this.brands.length);
              
              console.log(`Adding ${additionalBrands.length} additional non-featured brands`);
              this.brands = [...this.brands, ...additionalBrands];
            }
          }
        } else if (response.data && Array.isArray(response.data)) {
          // Handle case where API might return direct array without data wrapper
          console.log('API returned direct array format');
          this.brands = response.data;
        } else {
          // If no featured brands or wrong response format, try getting all brands
          console.log('No featured brands found or wrong response format, trying all brands');
          const allBrandsResponse = await axios.get(`${this.apiBaseUrl}/api/brands`);
          
          if (allBrandsResponse.data && allBrandsResponse.data.data) {
            this.brands = allBrandsResponse.data.data.slice(0, 5);
            console.log(`Using ${this.brands.length} brands from all brands endpoint`);
          } else if (allBrandsResponse.data && Array.isArray(allBrandsResponse.data)) {
            this.brands = allBrandsResponse.data.slice(0, 5);
            console.log(`Using ${this.brands.length} brands from direct array response`);
          } else {
            this.error = 'No brands data available';
            console.warn('No brand data received from API');
          }
        }
      } catch (err) {
        console.error('Error fetching brands:', err);
        this.error = 'Failed to load brands';
        
        // Try an alternative approach if the API call fails
        try {
          console.log('Trying alternative API endpoint for brands');
          const alternativeResponse = await axios.get(`${this.apiBaseUrl}/api/brands`);
          
          if (alternativeResponse.data && (alternativeResponse.data.data || Array.isArray(alternativeResponse.data))) {
            const data = alternativeResponse.data.data || alternativeResponse.data;
            this.brands = Array.isArray(data) ? data.slice(0, 5) : [];
            console.log(`Recovered with ${this.brands.length} brands from alternative endpoint`);
            this.error = null;
          }
        } catch (altErr) {
          console.error('Alternative approach also failed:', altErr);
        }
      } finally {
        this.loading = false;
        console.log(`Final brands count: ${this.brands.length}`);
      }
    },
    getBrandLogo(brand) {
      if (!brand.logo_url) {
        return 'https://via.placeholder.com/200x100?text=No+Logo';
      }
      
      // If it's already a full URL, return it as is
      if (brand.logo_url.startsWith('http://') || brand.logo_url.startsWith('https://')) {
        return brand.logo_url;
      }
      
      // If it's a storage path
      if (brand.logo_url.includes('storage/')) {
        return `${this.apiBaseUrl}/${brand.logo_url.startsWith('/') ? brand.logo_url.substring(1) : brand.logo_url}`;
      }
      
      // Default: assume it's a relative path to storage
      return `${this.apiBaseUrl}/storage/${brand.logo_url}`;
    },
    handleLogoError(event) {
      event.target.src = 'https://via.placeholder.com/200x100?text=No+Logo';
    },
    navigateToBrand(brand) {
      this.$router.push(`/brands/${brand.slug}`);
    },
    nextSlide() {
      if (this.currentSlide < this.maxSlide) {
        this.currentSlide++;
      }
    },
    prevSlide() {
      if (this.currentSlide > 0) {
        this.currentSlide--;
      }
    },
    handleResize() {
      // Reset to first slide when window is resized
      this.currentSlide = 0;
      
      // Adjust brands per slide based on screen width
      if (window.innerWidth < 640) {
        this.brandsPerSlide = 1;
      } else if (window.innerWidth < 768) {
        this.brandsPerSlide = 2;
      } else if (window.innerWidth < 1024) {
        this.brandsPerSlide = 3;
      } else {
        this.brandsPerSlide = 5;
      }
    }
  }
};
</script> 