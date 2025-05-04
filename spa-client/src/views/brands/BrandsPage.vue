<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <Header />

    <div class="container mx-auto px-4 py-8">
      <!-- Page Title -->
      <div class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Our Brands</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">Discover quality products from top sports and fitness brands.</p>
      </div>
      
      <!-- Loading State -->
      <div v-if="loading" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
        <div v-for="i in 10" :key="i" class="animate-pulse">
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="h-20 bg-gray-200 rounded mb-4"></div>
            <div class="h-4 bg-gray-200 rounded w-2/3 mx-auto mb-2"></div>
            <div class="h-3 bg-gray-200 rounded w-1/2 mx-auto"></div>
          </div>
        </div>
      </div>
      
      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <p class="text-red-500 text-lg mb-4">{{ error }}</p>
        <button @click="fetchBrands" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Try Again
        </button>
      </div>
      
      <!-- No Brands State -->
      <div v-else-if="!brands.length" class="text-center py-12">
        <p class="text-gray-500 text-lg">No brands available at the moment.</p>
      </div>
      
      <!-- Brands Grid -->
      <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
        <div 
          v-for="brand in brands" 
          :key="brand.id"
          @click="navigateToBrand(brand)"
          class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 cursor-pointer text-center"
        >
          <div class="h-20 flex items-center justify-center mb-4">
            <img 
              :src="getBrandLogo(brand)" 
              :alt="brand.name" 
              class="h-full object-contain max-w-full"
              @error="handleLogoError"
            >
          </div>
          <h3 class="font-medium text-gray-800 mb-1">{{ brand.name }}</h3>
          <p v-if="brand.product_count" class="text-sm text-gray-500">
            {{ brand.product_count }} products
          </p>
        </div>
      </div>
    </div>
    
    <!-- Footer -->
    <Footer />
  </div>
</template>

<script>
import axios from 'axios';
import Header from '@/components/Header.vue';
import Footer from '@/components/layout/Footer.vue';

export default {
  name: 'BrandsPage',
  components: {
    Header,
    Footer
  },
  data() {
    return {
      brands: [],
      loading: true,
      error: null,
      apiBaseUrl: 'http://127.0.0.1:8000'
    };
  },
  created() {
    this.fetchBrands();
  },
  methods: {
    async fetchBrands() {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await axios.get(`${this.apiBaseUrl}/api/brands`);
        
        if (response.data && response.data.data) {
          this.brands = response.data.data;
          document.title = 'All Brands | Aligans Sports';
        } else {
          this.error = 'No brands data available';
        }
      } catch (err) {
        console.error('Error fetching brands:', err);
        this.error = 'Failed to load brands';
      } finally {
        this.loading = false;
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
    }
  }
};
</script> 