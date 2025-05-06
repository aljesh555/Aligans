<template>
  <section class="py-20 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
    <!-- Background elements -->
    <div class="absolute inset-0 pointer-events-none">
      <div class="absolute top-40 left-40 w-96 h-96 bg-blue-50 rounded-full opacity-20 blur-3xl"></div>
      <div class="absolute bottom-20 right-10 w-80 h-80 bg-indigo-50 rounded-full opacity-30 blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Featured Products</h2>
      </div>
      
      <!-- Loading State -->
      <div v-if="loading" class="flex gap-4 overflow-hidden">
        <div v-for="i in 5" :key="i" class="animate-pulse flex-none w-full sm:w-1/2 md:w-1/3 lg:w-1/5">
          <div class="bg-gray-200 h-48 rounded-xl mb-4"></div>
          <div class="h-4 bg-gray-200 rounded-xl w-3/4 mb-2"></div>
          <div class="h-4 bg-gray-200 rounded-xl w-1/2 mb-4"></div>
          <div class="flex justify-between">
            <div class="h-6 bg-gray-200 rounded-xl w-1/4"></div>
            <div class="h-8 w-8 bg-gray-200 rounded-full"></div>
          </div>
              </div>
            </div>
      
      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <p class="text-red-500 text-lg mb-4">{{ error }}</p>
        <button @click="fetchFeaturedProducts" class="bg-blue-600 text-white px-6 py-3 rounded-full hover:bg-blue-700 transition-all duration-300 shadow-md hover:shadow-lg">
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
          class="absolute -left-2 top-1/2 transform -translate-y-1/2 z-10 bg-white bg-opacity-80 backdrop-filter backdrop-blur-md rounded-full p-3 shadow-lg hover:bg-blue-50 focus:outline-none transition-all duration-300"
          :class="{'opacity-50 cursor-not-allowed': currentSlide === 0, 'hover:scale-110': currentSlide !== 0}"
          :disabled="currentSlide === 0"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
              </button>
        
        <div class="overflow-hidden mx-14">
          <div 
            class="flex transition-all duration-700 ease-out" 
            :style="{transform: `translateX(-${currentSlide * 20}%)`}"
          >
            <!-- Product Card -->
            <div 
              v-for="(product, index) in products" 
              :key="product.id"
              class="flex-none w-full sm:w-1/2 md:w-1/3 lg:w-1/5 px-3"
            >
              <div 
                class="product-card group bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-500 h-full overflow-hidden"
                :style="{animationDelay: `${index * 100}ms`}"
              >
                <div class="relative overflow-hidden" @click="navigateToProductDetails(product)">
                  <!-- Product Image -->
                  <div class="h-48 overflow-hidden">
            <img 
                    :src="getProductImage(product)"
                    :alt="product.name" 
                      class="w-full h-full object-cover transform group-hover:scale-110 transition-all duration-700 cursor-pointer"
                    @error="handleImageError"
                    >
                  </div>
                  
                  <!-- Overlay with quick actions -->
                  <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 flex items-center justify-center gap-3 transition-all duration-500 opacity-0 group-hover:opacity-100">
                    <button 
                      @click.stop="addToCart(product)" 
                      class="p-2 bg-white rounded-full shadow-md hover:bg-blue-50 transform translate-y-4 group-hover:translate-y-0 transition-all duration-500 delay-75"
                      title="Add to cart"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                    </button>
                    <button 
                      @click.stop="navigateToProductDetails(product)"
                      class="p-2 bg-white rounded-full shadow-md hover:bg-blue-50 transform translate-y-4 group-hover:translate-y-0 transition-all duration-500 delay-150"
                      title="View details"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>
                  </div>
                  
                  <!-- Product Badges -->
                  <div class="absolute top-2 left-2 flex flex-col gap-2">
                    <div v-if="product.on_sale" class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full transform transition-transform duration-300 group-hover:scale-110">
                      Sale
                    </div>
                    <div v-if="product.is_new_arrival" class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full transform transition-transform duration-300 group-hover:scale-110">
              New
            </div>
          </div>
                </div>
                
                <!-- Product Info -->
          <div class="p-4">
                  <!-- Title and Rating -->
                  <div class="mb-2">
                    <h3 class="text-base font-medium text-gray-900 hover:text-blue-600 transition-colors duration-300 line-clamp-1" @click="navigateToProductDetails(product)">{{ product.name }}</h3>
                    <div class="flex items-center mt-1">
              <div class="flex text-yellow-400">
                      <svg v-for="i in 5" :key="i" xmlns="http://www.w3.org/2000/svg" 
                        :class="i <= (product.rating || 5) ? 'text-yellow-400' : 'text-gray-300'"
                          class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>
                      <span class="text-xs text-gray-500 ml-1">({{ product.reviews_count || 0 }})</span>
                    </div>
            </div>
                  
                  <!-- Price and Add to Cart -->
            <div class="flex justify-between items-end">
              <div>
                      <template v-if="product.on_sale && product.discount_price">
                        <span class="text-xs text-gray-400 line-through block">Rs {{ formatPrice(product.price) }}</span>
                        <span class="text-sm font-bold text-gray-900">Rs {{ formatPrice(product.discount_price) }}</span>
                      </template>
                      <span v-else class="text-sm font-bold text-gray-900">Rs {{ formatPrice(product.price) }}</span>
              </div>
                    <button 
                      @click="addToCart(product)" 
                      class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-1.5 transition-all duration-300 transform hover:scale-110"
                      title="Add to cart"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
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
          class="absolute -right-2 top-1/2 transform -translate-y-1/2 z-10 bg-white bg-opacity-80 backdrop-filter backdrop-blur-md rounded-full p-3 shadow-lg hover:bg-blue-50 focus:outline-none transition-all duration-300"
          :class="{'opacity-50 cursor-not-allowed': currentSlide >= maxSlide, 'hover:scale-110': currentSlide < maxSlide}"
          :disabled="currentSlide >= maxSlide"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>

        <!-- Slide Indicators -->
        <div class="flex justify-center mt-8 space-x-2">
          <button 
            v-for="i in slideCount" 
            :key="i"
            @click="goToSlide(i-1)"
            class="w-2.5 h-2.5 rounded-full transition-all duration-300 focus:outline-none"
            :class="i-1 === currentSlide ? 'bg-blue-600 w-8' : 'bg-gray-300 hover:bg-gray-400'"
            :aria-label="`Go to slide ${i}`"
          ></button>
        </div>
      </div>
      
      <div class="text-center mt-12">
        <router-link 
          to="/products" 
          class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-full hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1"
        >
          View All Products
          <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
          </svg>
        </router-link>
      </div>
    </div>

    <!-- Toast Notification -->
    <div 
      v-if="showToast" 
      class="fixed bottom-6 right-6 z-50 flex items-center bg-white px-6 py-4 rounded-xl shadow-lg transform transition-all duration-500"
      :class="[toastEntering ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0']"
    >
      <div class="flex-shrink-0 bg-green-100 rounded-full p-2 mr-4">
        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <div class="flex-1">
        <p class="text-gray-800 font-medium mb-1">{{ toastMessage }}</p>
        <p class="text-sm text-gray-500" v-if="lastAddedProduct">
          {{ lastAddedProduct.name }} has been added to your cart
        </p>
      </div>
      <button @click="hideToast" class="ml-4 text-gray-400 hover:text-gray-600 focus:outline-none">
        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
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
      productsPerSlide: 5,
      showToast: false,
      toastEntering: false,
      toastMessage: '',
      toastTimeout: null,
      lastAddedProduct: null
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
    showToastMessage(message, product) {
      // Clear any existing timeouts
      if (this.toastTimeout) {
        clearTimeout(this.toastTimeout);
      }
      
      // Set toast data
      this.toastMessage = message;
      this.lastAddedProduct = product;
      this.showToast = true;
      
      // Trigger enter animation after a small delay (for proper transition)
      setTimeout(() => {
        this.toastEntering = true;
      }, 10);
      
      // Set timeout to hide the toast
      this.toastTimeout = setTimeout(() => {
        this.hideToast();
      }, 4000);
    },
    hideToast() {
      this.toastEntering = false;
      
      // Wait for exit animation to complete before hiding
      setTimeout(() => {
        this.showToast = false;
      }, 500);
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
      
      // Show styled toast notification instead of alert
      this.showToastMessage('Product added to cart', product);
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
.product-card {
  animation: fadeInUp 0.6s both;
  transform: translateZ(0);
  backface-visibility: hidden;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Smooth slide transitions */
.transition-all {
  will-change: transform;
}

/* Toast shadow with slight color */
.shadow-lg {
  box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.1), 0 10px 10px -5px rgba(59, 130, 246, 0.04);
}
</style> 