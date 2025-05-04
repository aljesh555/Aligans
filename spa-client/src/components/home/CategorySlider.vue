<template>
  <div class="relative">
    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="text-center py-8">
      <p class="text-red-600 mb-4">{{ error }}</p>
      <button 
        @click="fetchCategories" 
        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
      >
        Try Again
      </button>
    </div>

    <!-- Categories Slider -->
    <div v-else class="relative overflow-hidden">
      <button 
        @click="slideCategoriesLeft" 
        class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white/80 hover:bg-white shadow-md rounded-full p-2 text-blue-600 hover:text-blue-800 transition-all"
        :disabled="categorySlidePosition === 0"
        :class="{ 'opacity-50 cursor-not-allowed': categorySlidePosition === 0 }"
        aria-label="Previous categories"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>

      <div class="overflow-hidden px-12">
        <div 
          ref="categorySlider"
          class="flex gap-4 transition-transform duration-300 ease-out"
          :style="{ transform: `translateX(-${categorySlidePosition}px)` }"
        >
          <div 
            v-for="category in categories" 
            :key="category.id" 
            class="flex-shrink-0 w-[220px] bg-white rounded-lg shadow-md overflow-hidden group hover:shadow-lg transition"
          >
            <router-link :to="`/category/${category.id}`" class="block text-center">
              <div class="h-40 bg-gray-100 overflow-hidden">
                <div v-if="category.image_url" class="w-full h-full">
                  <img 
                    :src="getImageUrl(category.image_url)" 
                    :alt="category.name"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    @error="handleImageError($event, category)"
                  />
                </div>
                <div v-else class="w-full h-full flex items-center justify-center bg-blue-100">
                  <span class="text-blue-600 text-4xl font-bold">{{ category.name.charAt(0) }}</span>
                </div>
              </div>
              <div class="p-4">
                <h3 class="font-medium text-gray-900 group-hover:text-blue-600 transition text-sm md:text-base">{{ category.name }}</h3>
                <p v-if="category.products_count" class="text-sm text-gray-500 mt-1">
                  {{ category.products_count }} Products
                </p>
              </div>
            </router-link>
          </div>
        </div>
      </div>

      <button 
        @click="slideCategoriesRight" 
        class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white/80 hover:bg-white shadow-md rounded-full p-2 text-blue-600 hover:text-blue-800 transition-all"
        aria-label="Next categories"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'CategorySlider',
  data() {
    return {
      categories: [],
      loading: false,
      error: null,
      categorySlidePosition: 0,
      slidingInterval: null
    };
  },
  created() {
    this.fetchCategories();
  },
  mounted() {
    // Optional: Set up automatic sliding
    this.startAutoSlide();
    
    // Reset slide position when window resizes
    window.addEventListener('resize', this.resetSlidePosition);
  },
  beforeUnmount() {
    window.removeEventListener('resize', this.resetSlidePosition);
    this.stopAutoSlide();
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

        // Filter to only include parent categories (top level)
        this.categories = this.categories.filter(cat => !cat.parent_id);
          
        console.log('Categories loaded:', this.categories);
      } catch (error) {
        console.error('Error fetching categories:', error);
        this.error = 'Failed to load categories. Please try again later.';
      } finally {
        this.loading = false;
      }
    },
    slideCategoriesLeft() {
      const slider = this.$refs.categorySlider;
      if (!slider) return;
      
      const itemWidth = 220 + 16; // card width + gap
      const visibleWidth = slider.parentElement.offsetWidth;
      const slideAmount = Math.min(this.categorySlidePosition, itemWidth * 2);
      
      this.categorySlidePosition = Math.max(0, this.categorySlidePosition - slideAmount);
    },
    slideCategoriesRight() {
      const slider = this.$refs.categorySlider;
      if (!slider) return;
      
      const itemWidth = 220 + 16; // card width + gap
      const visibleWidth = slider.parentElement.offsetWidth;
      const maxPosition = slider.scrollWidth - visibleWidth;
      const slideAmount = itemWidth * 2;
      
      this.categorySlidePosition = Math.min(maxPosition, this.categorySlidePosition + slideAmount);
    },
    resetSlidePosition() {
      this.categorySlidePosition = 0;
    },
    startAutoSlide() {
      this.slidingInterval = setInterval(() => {
        const slider = this.$refs.categorySlider;
        if (!slider) return;
        
        const visibleWidth = slider.parentElement.offsetWidth;
        const maxPosition = slider.scrollWidth - visibleWidth;
        
        if (this.categorySlidePosition >= maxPosition) {
          // If we're at the end, go back to the start
          this.categorySlidePosition = 0;
        } else {
          // Otherwise slide right
          this.slideCategoriesRight();
        }
      }, 5000); // Slide every 5 seconds
    },
    stopAutoSlide() {
      if (this.slidingInterval) {
        clearInterval(this.slidingInterval);
      }
    },
    handleImageError(event, category) {
      // Replace broken image with a fallback
      event.target.style.display = 'none';
      event.target.parentElement.innerHTML = `
        <div class="w-full h-full flex items-center justify-center bg-blue-100">
          <span class="text-blue-600 text-4xl font-bold">${category.name.charAt(0)}</span>
        </div>
      `;
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
    }
  }
};
</script> 