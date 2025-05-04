<template>
  <div>
    <!-- Include the Header component -->
    <Header />
    
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-2xl font-bold mb-6">Search Results for "{{ searchQuery }}"</h1>
      
      <!-- Loading state -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
      </div>
      
      <!-- Error state -->
      <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline"> {{ error }}</span>
      </div>
      
      <!-- No results found across all categories -->
      <div v-else-if="totalResultsCount === 0" class="text-center py-12">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h2 class="text-xl font-semibold mb-2">No results found</h2>
        <p class="text-gray-600 mb-4">We couldn't find anything matching "{{ searchQuery }}"</p>
        <router-link to="/products" class="text-blue-600 hover:text-blue-800 font-medium">
          View all products
        </router-link>
      </div>
      
      <!-- Search results -->
      <div v-else>
        <p class="mb-6 text-gray-600">Found {{ totalResultsCount }} results across products, brands, and categories</p>
        
        <!-- Brands Section -->
        <div v-if="brands.length > 0" class="mb-10">
          <h2 class="text-xl font-bold mb-4 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Brands ({{ brands.length }})
          </h2>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div v-for="brand in brands" :key="'brand-'+brand.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
              <router-link :to="'/brands/' + brand.id">
                <div class="h-32 relative flex items-center justify-center p-4 bg-gray-50">
                  <img 
                    v-if="brand.logo_url || brand.image_url" 
                    :src="getFullImageUrl(brand.logo_url || brand.image_url)" 
                    :alt="brand.name" 
                    class="h-full max-w-full object-contain"
                    @error="handleImageError"
                  >
                  <div v-else class="text-xl font-bold text-gray-400">{{ brand.name }}</div>
                </div>
                
                <div class="p-4">
                  <h3 class="font-medium text-gray-900 text-center">{{ brand.name }}</h3>
                </div>
              </router-link>
            </div>
          </div>
        </div>
        
        <!-- Categories Section -->
        <div v-if="categories.length > 0" class="mb-10">
          <h2 class="text-xl font-bold mb-4 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            Categories ({{ categories.length }})
          </h2>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div v-for="category in categories" :key="'category-'+category.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
              <router-link :to="'/category/' + category.id">
                <div class="h-32 relative flex items-center justify-center p-4 bg-gray-50">
                  <img 
                    v-if="category.image_url || category.thumbnail" 
                    :src="getFullImageUrl(category.image_url || category.thumbnail)" 
                    :alt="category.name" 
                    class="h-full max-w-full object-cover"
                    @error="handleImageError"
                  >
                  <div v-else class="flex flex-col items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                  </div>
                </div>
                
                <div class="p-4">
                  <h3 class="font-medium text-gray-900 text-center">{{ category.name }}</h3>
                </div>
              </router-link>
            </div>
          </div>
        </div>
        
        <!-- Products Section -->
        <div v-if="products.length > 0" class="mb-10">
          <h2 class="text-xl font-bold mb-4 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            Products ({{ products.length }})
          </h2>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div v-for="product in products" :key="'product-'+product.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
              <router-link :to="'/products/' + product.id">
                <div class="h-48 relative">
                  <img 
                    :src="getProductImage(product)" 
                    :alt="product.name" 
                    class="h-full w-full object-cover"
                    @error="handleImageError"
                  >
                  
                  <!-- Product badges -->
                  <div class="absolute top-2 left-2 flex flex-col gap-1">
                    <span v-if="product.on_sale" class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">SALE</span>
                    <span v-if="product.is_new_arrival" class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">NEW</span>
                    <span v-if="product.featured" class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">FEATURED</span>
                  </div>
                </div>
                
                <div class="p-4">
                  <h3 class="font-medium text-gray-900 mb-1">{{ product.name }}</h3>
                  <p v-if="product.category" class="text-gray-500 text-sm mb-2">{{ product.category.name }}</p>
                  
                  <div class="flex justify-between items-center">
                    <div class="flex items-center gap-1">
                      <span v-if="product.on_sale" class="text-red-600 font-bold">${{ parseFloat(product.discount_price || product.sale_price).toFixed(2) }}</span>
                      <span 
                        :class="{ 'text-gray-600 line-through text-sm': product.on_sale, 'text-gray-900 font-bold': !product.on_sale }"
                      >
                        ${{ parseFloat(product.price).toFixed(2) }}
                      </span>
                    </div>
                    
                    <div class="text-sm text-gray-500">
                      <span v-if="isInStock(product)" class="text-green-600">In Stock</span>
                      <span v-else class="text-red-600">Out of Stock</span>
                    </div>
                  </div>
                </div>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Include the Footer component -->
    <Footer />
  </div>
</template>

<script>
import ProductService from '@/services/product.service';
import Header from '@/components/Header.vue';
import Footer from '@/components/layout/Footer.vue';

export default {
  name: 'SearchResults',
  components: {
    Header,
    Footer
  },
  data() {
    return {
      searchQuery: '',
      products: [],
      brands: [],
      categories: [],
      loading: true,
      error: null,
      apiBaseUrl: 'http://localhost:8000' // Add base URL for API server
    };
  },
  computed: {
    totalResultsCount() {
      return this.products.length + this.brands.length + this.categories.length;
    }
  },
  created() {
    // Get the search query from URL parameter
    this.searchQuery = this.$route.query.q || '';
    this.fetchSearchResults();
  },
  watch: {
    // Re-fetch if the query parameter changes
    '$route.query.q'(newQuery) {
      this.searchQuery = newQuery || '';
      this.fetchSearchResults();
    }
  },
  methods: {
    async fetchSearchResults() {
      // If no search query is provided, redirect to products page
      if (!this.searchQuery.trim()) {
        this.$router.push('/products');
        return;
      }
      
      this.loading = true;
      this.error = null;
      this.products = [];
      this.brands = [];
      this.categories = [];
      
      console.log('Performing comprehensive search with query:', this.searchQuery);
      
      try {
        // Use comprehensive search method
        const result = await ProductService.comprehensiveSearch(this.searchQuery);
        console.log('Comprehensive search results:', result);
        
        // Update data with search results
        if (result) {
          this.products = result.products || [];
          this.brands = result.brands || [];
          this.categories = result.categories || [];
          
          console.log(`Found ${this.totalResultsCount} results matching "${this.searchQuery}"`);
        } else {
          console.warn('Unexpected API response format:', result);
          this.error = 'Received unexpected response format from server';
        }
      } catch (error) {
        console.error('Error searching:', error);
        if (error.response) {
          console.error('API error response:', error.response.data);
          this.error = `Server error: ${error.response.status} - ${error.response.data.message || 'Unknown error'}`;
        } else if (error.request) {
          console.error('No response received:', error.request);
          this.error = 'No response received from server. Please check your connection.';
        } else {
          this.error = `Failed to load search results: ${error.message}`;
        }
      } finally {
        this.loading = false;
      }
    },
    
    handleImageError(event) {
      console.log('Image failed to load, using placeholder:', event.target.src);
      event.target.src = 'https://via.placeholder.com/300x300?text=Product+Image';
    },
    
    // Helper method to determine if product is in stock based on multiple stock indicators
    isInStock(product) {
      // Check product status field first (most reliable)
      if (product.status === 'out_of_stock') {
        return false;
      }
      
      // Then check direct stock field - if it exists and is greater than 0, product is in stock
      if (typeof product.stock !== 'undefined' && product.stock > 0) {
        return true;
      }
      
      // If product has an in_stock boolean property, use it
      if (typeof product.in_stock !== 'undefined') {
        return product.in_stock;
      }
      
      // Default to assuming product is in stock if no indicators suggest otherwise
      return true;
    },
    
    // Helper method to get the best available product image
    getProductImage(product) {
      // Try different possible image properties in order of preference
      if (product.image_url && product.image_url !== 'null' && product.image_url !== '') {
        return this.getFullImageUrl(product.image_url);
      } 
      
      if (product.image && product.image !== 'null' && product.image !== '') {
        return this.getFullImageUrl(product.image);
      }
      
      if (product.thumbnail && product.thumbnail !== 'null' && product.thumbnail !== '') {
        return this.getFullImageUrl(product.thumbnail);
      }
      
      // If product has images array with main property
      if (product.images && product.images.main) {
        return this.getFullImageUrl(product.images.main);
      }
      
      // If we have a product object with nested images
      if (product.product && product.product.image_url) {
        return this.getFullImageUrl(product.product.image_url);
      }
      
      // Return a placeholder as fallback
      return 'https://via.placeholder.com/300x300';
    },
    
    // Convert relative image paths to full URLs
    getFullImageUrl(path) {
      if (!path) return 'https://via.placeholder.com/300x300';
      
      // If it's already a full URL, return it as is
      if (path.startsWith('http://') || path.startsWith('https://')) {
        return path;
      }
      
      // If it's a storage path
      if (path.includes('storage/')) {
        return `${this.apiBaseUrl}/${path.startsWith('/') ? path.substring(1) : path}`;
      }
      
      // Default: assume it's a relative path to storage
      return `${this.apiBaseUrl}/storage/${path}`;
    }
  }
};
</script> 

<style scoped>
/* Optional styling here */
</style> 