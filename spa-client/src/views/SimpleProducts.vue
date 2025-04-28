<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Our Products</h1>
    
    <!-- Simple button to load products -->
    <div class="text-center mb-8">
      <button
        @click="loadProducts"
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all hover:scale-105"
      >
        {{ products.length ? 'Refresh Products' : 'Load Products' }}
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-10">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
      <p class="mt-4">Loading products...</p>
    </div>
    
    <!-- Empty State -->
    <div v-else-if="!products.length" class="text-center py-10 bg-gray-50 rounded-lg">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <p class="text-xl mt-4">No products loaded yet.</p>
      <p class="text-gray-500">Click the button above to load products from the API.</p>
    </div>
    
    <!-- Product Grid -->
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <div 
        v-for="product in products" 
        :key="product.id" 
        class="border rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300"
      >
        <!-- Product Image (placeholder if no image) -->
        <div class="h-48 bg-gray-200 flex items-center justify-center">
          <img 
            v-if="product.image_url" 
            :src="getImageUrl(product.image_url)" 
            :alt="product.name" 
            class="h-full w-full object-cover"
          />
          <div v-else class="text-gray-400 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>No image</span>
          </div>
        </div>
        
        <!-- Product Info -->
        <div class="p-4">
          <div class="flex justify-between items-start mb-2">
            <h2 class="text-xl font-bold">{{ product.name }}</h2>
            <span class="text-lg font-bold text-blue-600">${{ product.price.toFixed(2) }}</span>
          </div>
          
          <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ product.description }}</p>
          
          <div class="flex justify-between items-center">
            <router-link 
              :to="`/products/${product.id}`" 
              class="text-blue-500 hover:text-blue-700 font-medium"
            >
              View Details
            </router-link>
            
            <button 
              class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm"
              @click="addToCart(product)"
            >
              Add to Cart
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { API_URL } from '@/services/config';

export default {
  name: 'SimpleProducts',
  data() {
    return {
      loading: false,
      products: []
    }
  },
  methods: {
    async loadProducts() {
      try {
        this.loading = true;
        const response = await axios.get(`${API_URL}/storefront/products`);
        this.products = response.data.products || [];
        this.loading = false;
      } catch (error) {
        console.error('Error fetching products:', error);
        this.loading = false;
        alert('Failed to load products. Please try again.');
      }
    },
    
    getImageUrl(imagePath) {
      // If the path is a full URL, return it directly
      if (imagePath.startsWith('http')) {
        return imagePath;
      }
      
      // Otherwise, assume it's a relative path on the server
      return `http://localhost:8000/storage/${imagePath}`;
    },
    
    addToCart(product) {
      alert(`Added ${product.name} to cart!`);
    }
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 