<template>
  <section class="py-16 bg-white">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Products</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Discover our most popular sports equipment and apparel, selected to help you perform at your best.</p>
      </div>
      
      <!-- Loading State -->
      <div v-if="loading" class="flex gap-4 overflow-hidden">
        <div v-for="i in 5" :key="i" class="animate-pulse flex-none w-full sm:w-1/2 md:w-1/3 lg:w-1/5">
          <div class="bg-gray-200 h-64 rounded-t-lg mb-4"></div>
          <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
          <div class="h-4 bg-gray-200 rounded w-1/2 mb-4"></div>
          <div class="flex justify-between">
            <div class="h-6 bg-gray-200 rounded w-1/4"></div>
            <div class="h-8 w-8 bg-gray-200 rounded-full"></div>
          </div>
              </div>
            </div>
      
      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <p class="text-red-500 text-lg mb-4">{{ error }}</p>
        <button @click="fetchFeaturedProducts" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Try Again
              </button>
            </div>
      
      <!-- No Products State -->
      <div v-else-if="!products || products.length === 0" class="text-center py-12">
        <p class="text-gray-500 text-lg mb-4">No featured products available at the moment.</p>
        </div>

      <!-- Products Slider -->
      <div v-else class="relative">
        <!-- Slider Navigation Buttons -->
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
        
        <div class="overflow-hidden mx-10">
          <div 
            class="flex transition-transform duration-500 ease-in-out" 
            :style="{transform: `translateX(-${currentSlide * 20}%)`}"
          >
            <!-- Product Card -->
            <div 
              v-for="product in products" 
              :key="product.id"
              class="flex-none w-full sm:w-1/2 md:w-1/3 lg:w-1/5 px-2"
            >
              <div class="group bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 h-full">
          <div class="relative overflow-hidden rounded-t-lg">
            <img 
                    :src="getProductImage(product)"
                    :alt="product.name" 
                    class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300 cursor-pointer"
                    @error="handleImageError"
                    @click="navigateToProductDetails(product)"
                  >
                  <!-- Sale Badge -->
                  <div v-if="product.on_sale" class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-3 py-1 m-2 rounded">
                    Sale
                  </div>
                  <!-- New Badge -->
                  <div v-else-if="product.is_new_arrival" class="absolute top-0 right-0 bg-green-500 text-white text-xs font-bold px-3 py-1 m-2 rounded">
              New
            </div>
          </div>
          <div class="p-4">
                  <h3 class="text-lg font-semibold text-gray-900 mb-2 cursor-pointer hover:text-blue-600" @click="navigateToProductDetails(product)">{{ product.name }}</h3>
            <div class="flex items-center mb-2">
                    <!-- Star Rating - Simplified for now -->
              <div class="flex text-yellow-400">
                      <svg v-for="i in 5" :key="i" xmlns="http://www.w3.org/2000/svg" 
                        :class="i <= (product.rating || 5) ? 'text-yellow-400' : 'text-gray-300'"
                        class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>
                    <span class="text-gray-500 ml-2">({{ product.reviews_count || 0 }})</span>
            </div>
            <div class="flex justify-between items-end">
              <div>
                      <!-- Show discount price if on sale -->
                      <template v-if="product.on_sale && product.discount_price">
                        <span class="text-gray-400 line-through">Rs {{ formatPrice(product.price) }}</span>
                        <p class="text-xl font-bold text-gray-900">Rs {{ formatPrice(product.discount_price) }}</p>
                      </template>
                      <p v-else class="text-xl font-bold text-gray-900">Rs {{ formatPrice(product.price) }}</p>
              </div>
                    <button 
                      @click="addToCart(product)" 
                      class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-2 transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </button>
            </div>
          </div>
        </div>
            </div>
          </div>
        </div>
        
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

        <!-- Slide Indicators -->
        <div class="flex justify-center mt-6">
          <button 
            v-for="i in slideCount" 
            :key="i"
            @click="goToSlide(i-1)"
            class="mx-1 h-2 w-8 rounded-full transition-colors duration-300"
            :class="i-1 === currentSlide ? 'bg-blue-600' : 'bg-gray-300 hover:bg-gray-400'"
          ></button>
        </div>
      </div>
      
      <div class="text-center mt-12">
        <router-link 
          to="/products" 
          class="inline-flex items-center text-blue-600 font-medium hover:text-blue-800 transition-colors"
        >
          View All Products
          <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
          </svg>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script>
import axios from 'axios';

export default {
  name: 'FeaturedProductsSection',
  data() {
    return {
      loading: true,
      error: null,
      products: [],
      apiBaseUrl: 'http://127.0.0.1:8000',
      currentSlide: 0,
      productsPerSlide: 5
    };
  },
  computed: {
    maxSlide() {
      return Math.max(0, Math.ceil(this.products.length / this.productsPerSlide) - 1);
    },
    slideCount() {
      return Math.ceil(this.products.length / this.productsPerSlide);
    }
  },
  created() {
    this.fetchFeaturedProducts();
    window.addEventListener('resize', this.handleResize);
  },
  beforeUnmount() {
    window.removeEventListener('resize', this.handleResize);
  },
  methods: {
    async fetchFeaturedProducts() {
      this.loading = true;
      this.error = null;
      
      try {
        // Try fetching from the storefront endpoint first
        const response = await axios.get(`${this.apiBaseUrl}/api/storefront`);
        
        if (response.data && response.data.featuredProducts) {
          this.products = response.data.featuredProducts;
          console.log('Featured products:', this.products);
        } else {
          // Fallback to the dedicated featured products endpoint
          const featuredResponse = await axios.get(`${this.apiBaseUrl}/api/storefront/featured-products`);
          
          if (featuredResponse.data && featuredResponse.data.featuredProducts) {
            this.products = featuredResponse.data.featuredProducts;
          } else {
            // Last resort: fetch all products and filter for featured=true
            const allProductsResponse = await axios.get(`${this.apiBaseUrl}/api/products`);
            
            if (allProductsResponse.data && allProductsResponse.data.data) {
              this.products = allProductsResponse.data.data.filter(product => product.featured === true || product.featured === 1);
            } else {
              this.error = 'Unable to fetch featured products';
            }
          }
        }
      } catch (err) {
        console.error('Error fetching featured products:', err);
        this.error = 'Failed to load featured products';
      } finally {
        this.loading = false;
      }
    },
    getProductImage(product) {
      // Handle various image field formats
      if (product.image_url) {
        return this.getFullImageUrl(product.image_url);
      } else if (product.image) {
        return this.getFullImageUrl(product.image);
      } else if (product.images && product.images.length > 0) {
        return this.getFullImageUrl(product.images[0]);
      } else {
        // Fallback placeholder image
        return 'https://via.placeholder.com/800x600?text=No+Image';
      }
    },
    getFullImageUrl(path) {
      if (!path) return 'https://via.placeholder.com/800x600?text=No+Image';
      
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
    },
    handleImageError(event) {
      // Set a fallback image if the product image fails to load
      event.target.src = 'https://via.placeholder.com/800x600?text=Product+Image';
    },
    formatPrice(price) {
      return parseFloat(price).toFixed(2);
    },
    addToCart(product) {
      // Implement cart functionality
      console.log('Adding to cart:', product);
      
      // Example: Simple cart implementation using localStorage
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      
      // Check if the product is already in the cart
      const existingItem = cart.find(item => item.id === product.id);
      
      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        cart.push({
          id: product.id,
          name: product.name,
          price: product.on_sale && product.discount_price ? product.discount_price : product.price,
          image: this.getProductImage(product),
          quantity: 1
        });
      }
      
      localStorage.setItem('cart', JSON.stringify(cart));
      
      // Dispatch an event to notify other components (like Header)
      document.dispatchEvent(new Event('cart-updated'));
      
      // Show a quick toast message
      alert('Product added to cart');
    },
    navigateToProductDetails(product) {
      // Navigate to product details page
      if (product.id) {
        this.$router.push(`/products/${product.id}`);
      } else if (product.slug) {
        this.$router.push(`/products/${product.slug}`);
      } else {
        console.error('Product has no ID or slug for navigation', product);
      }
    },
    // New slider methods
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
    goToSlide(index) {
      this.currentSlide = index;
    },
    handleResize() {
      // Reset to first slide when window is resized
      this.currentSlide = 0;
      
      // Adjust products per slide based on screen width
      if (window.innerWidth < 640) {
        this.productsPerSlide = 1;
      } else if (window.innerWidth < 768) {
        this.productsPerSlide = 2;
      } else if (window.innerWidth < 1024) {
        this.productsPerSlide = 3;
      } else {
        this.productsPerSlide = 5;
      }
    }
  }
};
</script> 

<style scoped>
/* Optional styles */
.overflow-hidden {
  overflow: hidden;
}
</style> 