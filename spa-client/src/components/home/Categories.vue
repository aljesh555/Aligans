<template>
  <section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Shop By Category</h2>
        <p class="text-gray-600 max-w-xl mx-auto">Explore our wide range of product categories designed to meet all your sporting needs</p>
      </div>
      
      <!-- Loading state -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
      </div>
      
      <!-- Error state -->
      <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ error }}
        <button @click="fetchCategories" class="ml-3 bg-red-200 px-3 py-1 rounded hover:bg-red-300">
          Retry
        </button>
      </div>
      
      <!-- Categories grid -->
      <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div
          v-for="category in categories"
          :key="category.id"
          class="category-card group relative rounded-lg overflow-hidden bg-white shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
        >
          <router-link :to="`/category/${category.id}`">
            <div class="relative h-48 overflow-hidden">
              <!-- Display category image if available -->
              <img
                v-if="category.image_url"
                :src="getImageUrl(category.image_url)"
                :alt="category.name"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                @error="handleImageError($event, category)"
              />
              <!-- Display fallback if no image -->
              <div v-else class="w-full h-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
                <span class="text-white text-6xl font-bold">{{ category.name.charAt(0) }}</span>
              </div>
              <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-70"></div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
              <h3 class="text-lg font-semibold">{{ category.name }}</h3>
              <p class="text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                {{ category.products_count || 0 }} Products
              </p>
            </div>
          </router-link>
        </div>
      </div>
      
      <div class="mt-10 text-center">
        <router-link
          to="/categories"
          class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-300"
        >
          View All Categories
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Categories',
  data() {
    return {
      categories: [],
      loading: true,
      error: null
    };
  },
  mounted() {
    this.fetchCategories();
  },
  methods: {
    async fetchCategories() {
      try {
        this.loading = true;
        this.error = null;
        
        const response = await axios.get('/api/storefront/categories');
        
        // Handle different API response structures
        if (response.data.data) {
          this.categories = response.data.data;
        } else if (Array.isArray(response.data)) {
          this.categories = response.data;
        } else {
          this.categories = response.data.categories || [];
        }
        
        // Limit to top 8 categories for display
        this.categories = this.categories.slice(0, 8);
        
        console.log('Categories loaded:', this.categories);
      } catch (err) {
        console.error('Error fetching categories:', err);
        this.error = 'Failed to load categories. Please try again.';
      } finally {
        this.loading = false;
      }
    },
    
    // Function to handle image URL formatting
    getImageUrl(imagePath) {
      if (!imagePath) return '';
      
      // If the path is a full URL, return it directly
      if (imagePath.startsWith('http')) {
        return imagePath;
      }
      
      // Handle paths that might already have "storage/" in them
      if (imagePath.startsWith('storage/')) {
        return `http://localhost:8000/${imagePath}`;
      }
      
      // Otherwise, assume it's a relative path on the server
      return `http://localhost:8000/storage/${imagePath}`;
    },
    
    // Function to handle image loading errors
    handleImageError(event, category) {
      console.error(`Error loading image for category ${category.name}:`, event);
      // Replace with a colorful fallback with the first letter
      const parentDiv = event.target.parentElement;
      if (parentDiv) {
        parentDiv.innerHTML = `
          <div class="w-full h-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
            <span class="text-white text-6xl font-bold">${category.name.charAt(0)}</span>
          </div>
        `;
      }
    }
  }
};
</script>

<style scoped>
.category-card::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 50%);
  z-index: 1;
  opacity: 0.7;
  transition: opacity 0.3s ease;
}

.category-card:hover::after {
  opacity: 0.9;
}

.category-card h3, .category-card p {
  position: relative;
  z-index: 2;
}
</style> 