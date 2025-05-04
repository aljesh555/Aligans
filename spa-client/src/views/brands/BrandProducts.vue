<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <Header />

    <div class="container mx-auto px-4 py-8">
      <!-- Loading State -->
      <div v-if="loading" class="py-16">
        <div class="animate-pulse space-y-6">
          <div class="flex items-center space-x-4">
            <div class="h-20 w-32 bg-gray-200 rounded"></div>
            <div class="space-y-2 flex-1">
              <div class="h-8 bg-gray-200 rounded w-1/3"></div>
              <div class="h-4 bg-gray-200 rounded w-2/3"></div>
            </div>
          </div>
          
          <div class="h-4 bg-gray-200 rounded w-full"></div>
          
          <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div v-for="i in 8" :key="i" class="bg-white rounded-lg overflow-hidden">
              <div class="h-48 bg-gray-200"></div>
              <div class="p-4 space-y-2">
                <div class="h-5 bg-gray-200 rounded"></div>
                <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                <div class="h-10 bg-gray-200 rounded"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Error State -->
      <div v-else-if="error" class="text-center py-16">
        <p class="text-red-500 text-lg mb-4">{{ error }}</p>
        <button @click="fetchBrandProducts" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Try Again
        </button>
      </div>
      
      <!-- Brand and Products -->
      <div v-else>
        <!-- Brand Header -->
        <div class="mb-8 flex flex-col md:flex-row items-center gap-6 bg-white p-6 rounded-lg shadow-sm">
          <div class="w-32 h-24 flex items-center justify-center">
            <img 
              :src="getBrandLogo(brand)" 
              :alt="brand.name" 
              class="max-h-full max-w-full object-contain"
              @error="handleLogoError"
            >
          </div>
          <div class="flex-1 text-center md:text-left">
            <h1 class="text-3xl font-bold mb-2">{{ brand.name }}</h1>
            <p v-if="brand.description" class="text-gray-600">{{ brand.description }}</p>
          </div>
        </div>
        
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-6 text-sm">
          <router-link to="/" class="text-gray-500 hover:text-blue-600">Home</router-link>
          <span class="mx-2 text-gray-500">/</span>
          <router-link to="/brands" class="text-gray-500 hover:text-blue-600">Brands</router-link>
          <span class="mx-2 text-gray-500">/</span>
          <span class="text-blue-600">{{ brand.name }}</span>
        </nav>
        
        <!-- No Products State -->
        <div v-if="!products.length" class="text-center py-12">
          <p class="text-gray-500 text-lg mb-4">No products available for this brand at the moment.</p>
          <router-link to="/products" class="text-blue-600 hover:text-blue-700">
            Browse all products
          </router-link>
        </div>
        
        <!-- Products Grid -->
        <div v-else>
          <h2 class="text-2xl font-bold mb-6">{{ brand.name }} Products</h2>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Product Card -->
            <div 
              v-for="product in products" 
              :key="product.id" 
              class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden"
            >
              <!-- Product Image -->
              <div class="h-48 bg-gray-100 relative">
                <img 
                  v-if="product.image_url" 
                  :src="getProductImage(product)" 
                  :alt="product.name" 
                  class="h-full w-full object-cover"
                  @click="navigateToProduct(product)"
                  style="cursor: pointer;"
                />
                <div v-else class="flex items-center justify-center h-full text-gray-400">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                
                <!-- Sale Badge -->
                <div 
                  v-if="product.on_sale" 
                  class="absolute top-0 right-0 bg-red-600 text-white font-bold px-3 py-1 rounded-bl-lg"
                >
                  SALE
                </div>
              </div>
              
              <!-- Product Info -->
              <div class="p-4 flex flex-col min-h-[200px]">
                <h3 
                  class="text-lg font-bold mb-2 cursor-pointer hover:text-blue-600" 
                  @click="navigateToProduct(product)"
                >
                  {{ product.name }}
                </h3>
                
                <!-- Price -->
                <div class="mb-2">
                  <template v-if="product.on_sale && product.discount_price">
                    <span class="text-gray-400 line-through">Rs {{ parseFloat(product.price).toFixed(2) }}</span>
                    <span class="text-red-600 font-bold text-xl ml-2">Rs {{ parseFloat(product.discount_price).toFixed(2) }}</span>
                  </template>
                  <span v-else class="text-xl font-bold text-gray-900">Rs {{ parseFloat(product.price).toFixed(2) }}</span>
                </div>
                
                <div class="text-gray-600 text-sm mb-4 line-clamp-2 flex-grow" v-html="cleanDescription(product.description)"></div>
                
                <div class="flex justify-between items-center mt-auto">
                  <router-link 
                    :to="`/products/${product.id}`" 
                    class="text-blue-500 hover:text-blue-700 font-medium"
                  >
                    View Details
                  </router-link>
                  
                  <button 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm"
                    @click="addToCart(product)"
                  >
                    Add to Cart
                  </button>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Pagination -->
          <div v-if="pagination && pagination.total > pagination.per_page" class="mt-8 flex justify-center">
            <nav class="flex items-center space-x-2">
              <button 
                @click="goToPage(pagination.current_page - 1)" 
                :disabled="pagination.current_page === 1"
                :class="{'opacity-50 cursor-not-allowed': pagination.current_page === 1}"
                class="px-2 py-1 border rounded"
              >
                Previous
              </button>
              
              <span class="px-2 py-1">
                Page {{ pagination.current_page }} of {{ pagination.last_page }}
              </span>
              
              <button 
                @click="goToPage(pagination.current_page + 1)"
                :disabled="pagination.current_page === pagination.last_page"
                :class="{'opacity-50 cursor-not-allowed': pagination.current_page === pagination.last_page}"
                class="px-2 py-1 border rounded"
              >
                Next
              </button>
            </nav>
          </div>
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
  name: 'BrandProducts',
  components: {
    Header,
    Footer
  },
  data() {
    return {
      brand: {},
      products: [],
      loading: true,
      error: null,
      pagination: null,
      apiBaseUrl: 'http://127.0.0.1:8000'
    };
  },
  created() {
    this.fetchBrandProducts();
  },
  methods: {
    async fetchBrandProducts(page = 1) {
      this.loading = true;
      this.error = null;
      
      try {
        const brandSlug = this.$route.params.slug;
        const response = await axios.get(`${this.apiBaseUrl}/api/brands/${brandSlug}?page=${page}`);
        
        if (response.data && response.data.brand && response.data.products) {
          this.brand = response.data.brand;
          this.products = response.data.products.data || response.data.products;
          this.pagination = response.data.products.meta || response.data.products;
          
          document.title = `${this.brand.name} Products | Aligans Sports`;
        } else {
          this.error = 'Unable to load brand products';
        }
      } catch (err) {
        console.error('Error fetching brand products:', err);
        this.error = 'Failed to load brand products';
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
    getProductImage(product) {
      if (!product.image_url) {
        return 'https://via.placeholder.com/800x600?text=No+Image';
      }
      
      // If it's already a full URL, return it as is
      if (product.image_url.startsWith('http://') || product.image_url.startsWith('https://')) {
        return product.image_url;
      }
      
      // If it's a storage path
      if (product.image_url.includes('storage/')) {
        return `${this.apiBaseUrl}/${product.image_url.startsWith('/') ? product.image_url.substring(1) : product.image_url}`;
      }
      
      // Default: assume it's a relative path to storage
      return `${this.apiBaseUrl}/storage/${product.image_url}`;
    },
    navigateToProduct(product) {
      if (product.id) {
        this.$router.push(`/products/${product.id}`);
      } else if (product.slug) {
        this.$router.push(`/products/${product.slug}`);
      }
    },
    goToPage(page) {
      if (page < 1 || (this.pagination && page > this.pagination.last_page)) return;
      
      this.fetchBrandProducts(page);
      // Scroll to top
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    handleLogoError(event) {
      event.target.src = 'https://via.placeholder.com/200x100?text=No+Logo';
    },
    addToCart(product) {
      // Example implementation
      alert(`Added ${product.name} to cart`);
      
      // Get existing cart
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      
      // Check if product is already in cart
      const existingItem = cart.find(item => item.id === product.id);
      
      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        cart.push({
          id: product.id,
          name: product.name,
          price: product.on_sale && product.discount_price ? parseFloat(product.discount_price) : parseFloat(product.price),
          image: this.getProductImage(product),
          quantity: 1
        });
      }
      
      // Save cart
      localStorage.setItem('cart', JSON.stringify(cart));
      
      // Dispatch event for other components to update
      document.dispatchEvent(new Event('cart-updated'));
    },
    cleanDescription(description) {
      if (!description) return '';
      
      // Strip HTML tags but preserve spacing
      const textOnly = description.replace(/<[^>]*>/g, '');
      
      // Remove extra spaces and &nbsp; entities
      return textOnly.replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim();
    }
  }
};
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 