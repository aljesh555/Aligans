<template>
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
    
    <!-- No results found -->
    <div v-else-if="products.length === 0" class="text-center py-12">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <h2 class="text-xl font-semibold mb-2">No products found</h2>
      <p class="text-gray-600 mb-4">We couldn't find any products matching "{{ searchQuery }}"</p>
      <router-link to="/products" class="text-blue-600 hover:text-blue-800 font-medium">
        View all products
      </router-link>
    </div>
    
    <!-- Search results grid -->
    <div v-else>
      <p class="mb-4 text-gray-600">Found {{ products.length }} products</p>
      
      <!-- Products grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div v-for="product in products" :key="product.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
          <router-link :to="'/products/' + product.id">
            <div class="h-48 relative">
              <img 
                :src="product.image_url || 'https://via.placeholder.com/300x300'" 
                :alt="product.name" 
                class="h-full w-full object-cover"
                @error="handleImageError"
              >
              
              <!-- Product badges -->
              <div class="absolute top-2 left-2 flex flex-col gap-1">
                <span v-if="product.on_sale" class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">SALE</span>
                <span v-if="product.is_new" class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">NEW</span>
                <span v-if="product.is_featured" class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded">FEATURED</span>
              </div>
            </div>
            
            <div class="p-4">
              <h3 class="font-medium text-gray-900 mb-1">{{ product.name }}</h3>
              <p v-if="product.category" class="text-gray-500 text-sm mb-2">{{ product.category.name }}</p>
              
              <div class="flex justify-between items-center">
                <div class="flex items-center gap-1">
                  <span v-if="product.on_sale" class="text-red-600 font-bold">${{ parseFloat(product.sale_price).toFixed(2) }}</span>
                  <span 
                    :class="{ 'text-gray-600 line-through text-sm': product.on_sale, 'text-gray-900 font-bold': !product.on_sale }"
                  >
                    ${{ parseFloat(product.price).toFixed(2) }}
                  </span>
                </div>
                
                <div class="text-sm text-gray-500">
                  <span v-if="product.in_stock" class="text-green-600">In Stock</span>
                  <span v-else class="text-red-600">Out of Stock</span>
                </div>
              </div>
            </div>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ProductService from '@/services/product.service';

export default {
  name: 'SearchResults',
  data() {
    return {
      searchQuery: '',
      products: [],
      loading: true,
      error: null
    };
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
      
      console.log('Searching for products with query:', this.searchQuery);
      
      try {
        // Use direct search method first
        const result = await ProductService.searchProducts(this.searchQuery);
        console.log('Search API response:', result);
        
        // Handle all possible response formats
        if (result && result.data) {
          // Standard format with data property
          this.products = result.data;
          console.log(`Found ${this.products.length} products matching "${this.searchQuery}"`);
        } else if (result && Array.isArray(result)) {
          // Direct array response
          this.products = result;
          console.log(`Found ${this.products.length} products matching "${this.searchQuery}" (array response)`);
        } else if (result && result.products) {
          // Format with products property
          this.products = result.products;
          console.log(`Found ${this.products.length} products matching "${this.searchQuery}" (products property)`);
        } else if (result && typeof result === 'object') {
          // Try to extract any array from the response
          const possibleArrays = Object.values(result).filter(val => Array.isArray(val));
          if (possibleArrays.length > 0) {
            // Use the largest array (most likely to be products)
            this.products = possibleArrays.reduce((a, b) => a.length > b.length ? a : b);
            console.log(`Found ${this.products.length} products from extracted array`);
          } else {
            console.warn('No product arrays found in response:', result);
            this.error = 'Could not find products in the server response';
          }
        } else {
          console.warn('Unexpected API response format:', result);
          this.error = 'Received unexpected response format from server';
        }
      } catch (error) {
        console.error('Error searching products:', error);
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
      event.target.src = 'https://via.placeholder.com/300x300';
    }
  }
};
</script> 