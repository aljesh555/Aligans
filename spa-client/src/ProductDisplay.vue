<template>
  <div class="flex flex-col items-center">
    <!-- Buttons to load data -->
    <div class="flex flex-wrap gap-4 justify-center mb-8">
      <button
        @click="loadProducts"
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg font-medium"
      >
        {{ products.length ? 'Reload Products' : 'Load Products' }}
      </button>
      
      <button
        @click="loadCategories"
        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-lg font-medium"
      >
        {{ categories.length ? 'Reload Categories' : 'Load Categories' }}
      </button>
    </div>

    <!-- Loading indicator -->
    <div v-if="loading" class="text-center py-8 w-full">
      <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600 mx-auto"></div>
      <p class="mt-4 text-lg">Loading data...</p>
    </div>

    <div v-else class="w-full">
      <!-- Categories display -->
      <div v-if="categories.length" class="mb-12">
        <h2 class="text-2xl font-bold mb-6 text-center">Categories from Database</h2>
        
        <!-- Active filter indicator -->
        <div v-if="selectedCategoryId" class="text-center mb-4">
          <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full">
            <span>Filtering by: {{ getCategoryName(selectedCategoryId) }}</span>
            <button 
              @click="clearCategoryFilter" 
              class="ml-2 text-blue-600 hover:text-blue-800"
            >
              ✕
            </button>
          </div>
        </div>
        
        <!-- Category cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
          <div 
            v-for="category in categories" 
            :key="category.id" 
            @click="selectCategory(category.id)"
            class="bg-white p-6 rounded-lg shadow-md text-center hover:shadow-lg transition cursor-pointer"
            :class="{'ring-2 ring-blue-500': selectedCategoryId === category.id}"
          >
            <div class="w-16 h-16 rounded-full bg-green-100 mx-auto mb-4 flex items-center justify-center">
              <span class="text-green-600 text-xl font-bold">{{ category.name.charAt(0) }}</span>
            </div>
            <h3 class="text-lg font-bold">{{ category.name }}</h3>
            <p class="text-gray-500 text-sm mt-1">ID: {{ category.id }}</p>
          </div>
        </div>
      </div>
      
      <!-- Products display -->
      <div v-if="filteredProducts.length" class="w-full">
        <h2 class="text-2xl font-bold mb-6 text-center">
          {{ selectedCategoryId ? getCategoryName(selectedCategoryId) + ' Products' : 'All Products' }}
        </h2>
        
        <!-- Product cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="product in filteredProducts" 
            :key="product.id" 
            class="bg-white p-6 rounded-lg shadow-md"
          >
            <h3 class="text-xl font-bold mb-2">{{ product.name }}</h3>
            <p class="text-blue-600 font-bold text-lg mb-2">${{ product.price }}</p>
            <p class="text-gray-600 mb-4">{{ product.description }}</p>
            <p class="text-gray-500 text-sm">
              Category: {{ getCategoryName(product.category_id) }}
            </p>
          </div>
        </div>
      </div>
      
      <!-- No products match filter -->
      <div v-else-if="products.length && selectedCategoryId" class="text-center py-8">
        <p class="text-lg">No products found in this category</p>
        <button 
          @click="clearCategoryFilter" 
          class="mt-4 px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300"
        >
          Show All Products
        </button>
      </div>

      <!-- Empty state -->
      <div v-if="!products.length && !categories.length" class="text-center py-8">
        <p class="text-lg">Click a button above to load data</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ProductDisplay',
  data() {
    return {
      loading: false,
      products: [],
      categories: [],
      selectedCategoryId: null
    }
  },
  computed: {
    filteredProducts() {
      if (!this.selectedCategoryId) {
        return this.products;
      }
      
      return this.products.filter(product => product.category_id === this.selectedCategoryId);
    }
  },
  methods: {
    async loadProducts() {
      try {
        this.loading = true;
        const response = await axios.get('http://localhost:8000/api/storefront/products');
        this.products = response.data.products || [];
        console.log('Products loaded:', this.products);
        this.loading = false;
      } catch (error) {
        console.error('Error loading products:', error);
        alert('Failed to load products. Please check if the API server is running.');
        this.loading = false;
      }
    },
    
    async loadCategories() {
      try {
        this.loading = true;
        const response = await axios.get('http://localhost:8000/api/storefront/categories');
        this.categories = response.data.categories || [];
        console.log('Categories loaded:', this.categories);
        this.loading = false;
      } catch (error) {
        console.error('Error loading categories:', error);
        alert('Failed to load categories. Please check if the API server is running.');
        this.loading = false;
      }
    },
    
    selectCategory(categoryId) {
      // Toggle the selection if clicking the same category again
      this.selectedCategoryId = this.selectedCategoryId === categoryId ? null : categoryId;
      
      // If no products are loaded yet, load them
      if (!this.products.length) {
        this.loadProducts();
      }
    },
    
    clearCategoryFilter() {
      this.selectedCategoryId = null;
    },
    
    getCategoryName(categoryId) {
      if (!this.categories.length) return `ID: ${categoryId}`;
      
      const category = this.categories.find(cat => cat.id === categoryId);
      return category ? category.name : `Unknown (ID: ${categoryId})`;
    }
  }
}
</script>

<style scoped>
/* Add a subtle hover effect to category cards */
.cursor-pointer:hover {
  transform: translateY(-2px);
  transition: transform 0.2s ease;
}
</style> 