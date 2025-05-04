<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Header from Home.vue -->
    <Header />

    <div class="container mx-auto px-4 py-8">
      <!-- New Arrivals Banner -->
      <div class="relative mb-8 rounded-lg overflow-hidden shadow-lg">
        <div class="bg-gradient-to-r from-green-600 to-blue-700 h-64 flex items-center justify-center">
          <div class="absolute inset-0 opacity-20 bg-pattern"></div>
          <div class="text-center px-6 relative z-10 text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">NEW ARRIVALS</h1>
            <p class="text-xl md:text-2xl max-w-2xl mx-auto">Discover our latest collection of sports equipment and apparel</p>
            <div class="mt-6 inline-block bg-white text-blue-600 font-bold px-6 py-3 rounded-full text-lg">
              Fresh & New
            </div>
          </div>
        </div>
      </div>

      <!-- Filters and Sorting -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <h2 class="text-2xl font-bold">New Arrivals</h2>
        
        <div class="flex flex-col sm:flex-row gap-4">
          <!-- Category Filter -->
          <select 
            v-model="selectedCategory" 
            @change="filterProducts"
            class="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[200px]"
          >
            <option :value="null">All Categories</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
          
          <!-- Sort Options -->
          <select 
            v-model="sortOption" 
            @change="filterProducts"
            class="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[200px]"
          >
            <option value="created_at-desc">Newest First</option>
            <option value="price-asc">Price: Low to High</option>
            <option value="price-desc">Price: High to Low</option>
            <option value="name-asc">Name: A to Z</option>
          </select>
        </div>
      </div>
      
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-10">
        <div class="flex justify-center">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>
        <p class="mt-4 text-gray-600">Loading new arrivals...</p>
      </div>
      
      <!-- Empty State -->
      <div v-else-if="!products.length" class="py-16 text-center">
        <div class="mb-6">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-700 mb-2">No New Arrivals Available</h2>
        <p class="text-gray-500 mb-6">There are currently no new arrivals. Please check back later.</p>
        <router-link to="/products" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
          Browse All Products
        </router-link>
      </div>
      
      <!-- Product Grid -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div 
          v-for="product in products" 
          :key="product.id" 
          class="group bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-200"
        >
          <!-- New Badge -->
          <div class="absolute top-0 left-0 bg-green-600 text-white font-bold px-3 py-1 rounded-br-lg z-10">
            NEW
          </div>
          
          <!-- Sale Badge (if applicable) -->
          <div 
            v-if="product.on_sale" 
            class="absolute top-0 right-0 bg-red-600 text-white font-bold px-3 py-1 rounded-bl-lg z-10"
          >
            SALE
          </div>
          
          <!-- Product Image -->
          <div class="h-48 bg-gray-100 relative overflow-hidden">
            <img 
              :src="getImageUrl(product.image_url)" 
              :alt="product.name" 
              class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500"
              @error="handleImageError"
              @click="navigateToProductDetails(product)"
            />
          </div>
          
          <!-- Product Info -->
          <div class="p-4 flex flex-col min-h-[200px]">
            <h3 
              class="text-lg font-semibold text-gray-900 mb-2 cursor-pointer hover:text-blue-600" 
              @click="navigateToProductDetails(product)"
            >
              {{ product.name }}
            </h3>
            
            <!-- Rating Stars -->
            <div class="flex items-center mb-2">
              <div class="flex text-yellow-400">
                <svg v-for="i in 5" :key="i" xmlns="http://www.w3.org/2000/svg" 
                  :class="i <= (product.rating || 5) ? 'text-yellow-400' : 'text-gray-300'"
                  class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>
              <span class="text-gray-500 ml-2">({{ product.reviews_count || 0 }})</span>
            </div>
            
            <!-- Price -->
            <div class="mb-3">
              <template v-if="product.on_sale && product.discount_price">
                <span class="text-gray-400 line-through">Rs {{ formatPrice(product.price) }}</span>
                <span class="text-xl font-bold text-blue-600 ml-2">Rs {{ formatPrice(product.discount_price) }}</span>
              </template>
              <span v-else class="text-xl font-bold text-gray-900">Rs {{ formatPrice(product.price) }}</span>
            </div>
            
            <!-- Description -->
            <div class="text-gray-600 text-sm mb-4 line-clamp-2 flex-grow" v-html="cleanDescription(product.description)"></div>
            
            <!-- Actions -->
            <div class="flex justify-between items-center mt-auto">
              <router-link 
                :to="`/products/${product.id}`" 
                class="text-blue-500 hover:text-blue-700 font-medium"
              >
                View Details
              </router-link>
              
              <button 
                @click="addToCart(product)" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm"
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
    
    <!-- Footer from Home.vue -->
    <Footer />
  </div>
</template>

<script>
import axios from 'axios';
import Header from '@/components/Header.vue';
import Footer from '@/components/layout/Footer.vue';

export default {
  name: 'NewArrivals',
  components: {
    Header,
    Footer
  },
  data() {
    return {
      loading: true,
      error: null,
      products: [],
      categories: [],
      selectedCategory: null,
      sortOption: 'created_at-desc',
      pagination: null,
      currentPage: 1,
      apiBaseUrl: 'http://127.0.0.1:8000',
      mobileMenuOpen: false,
      cartItemsCount: 0,
      searchQuery: '',
      // Header related data
      accountDropdownOpen: false,
      wishlistDropdownOpen: false,
      cartDropdownOpen: false,
      categoriesDropdownOpen: false,
      mobileCategoriesOpen: false,
      cartItems: [],
      cartTotal: 0
    };
  },
  created() {
    this.fetchCategories();
    this.fetchNewArrivals();
    this.updateCartItems();
    
    // Listen for cart updates
    document.addEventListener('cart-updated', this.updateCartItems);
    
    // Add event listener for closing dropdowns when clicking outside
    document.addEventListener('click', this.closeDropdownsOnClickOutside);
  },
  beforeUnmount() {
    // Remove event listeners
    document.removeEventListener('cart-updated', this.updateCartItems);
    document.removeEventListener('click', this.closeDropdownsOnClickOutside);
  },
  methods: {
    // Header related methods
    toggleAccountDropdown() {
      this.accountDropdownOpen = !this.accountDropdownOpen;
      this.wishlistDropdownOpen = false;
      this.cartDropdownOpen = false;
    },
    toggleWishlistDropdown() {
      this.wishlistDropdownOpen = !this.wishlistDropdownOpen;
      this.accountDropdownOpen = false;
      this.cartDropdownOpen = false;
    },
    toggleCartDropdown() {
      this.cartDropdownOpen = !this.cartDropdownOpen;
      this.accountDropdownOpen = false;
      this.wishlistDropdownOpen = false;
    },
    toggleCategoriesDropdown() {
      this.categoriesDropdownOpen = !this.categoriesDropdownOpen;
    },
    closeDropdownsOnClickOutside(event) {
      // Account dropdown
      if (this.accountDropdownOpen && 
          !event.target.closest('.account-dropdown-container')) {
        this.accountDropdownOpen = false;
      }
      
      // Wishlist dropdown
      if (this.wishlistDropdownOpen && 
          !event.target.closest('.wishlist-dropdown-container')) {
        this.wishlistDropdownOpen = false;
      }
      
      // Cart dropdown
      if (this.cartDropdownOpen && 
          !event.target.closest('.cart-dropdown-container')) {
        this.cartDropdownOpen = false;
      }
      
      // Categories dropdown
      if (this.categoriesDropdownOpen && 
          !event.target.closest('.categories-dropdown-container')) {
        this.categoriesDropdownOpen = false;
      }
    },
    updateCartItems() {
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      this.cartItems = cart;
      this.cartItemsCount = cart.reduce((total, item) => total + item.quantity, 0);
      this.cartTotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    },
    removeFromCart(index) {
      const cart = [...this.cartItems];
      cart.splice(index, 1);
      localStorage.setItem('cart', JSON.stringify(cart));
      
      // Update cart data
      this.updateCartItems();
      
      // Dispatch event to notify other components
      document.dispatchEvent(new Event('cart-updated'));
    },
    
    // Existing methods
    async fetchCategories() {
      try {
        const response = await axios.get(`${this.apiBaseUrl}/api/categories`);
        this.categories = response.data.data || [];
      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    },
    async fetchNewArrivals() {
      this.loading = true;
      this.error = null;
      
      try {
        // Try fetching from a dedicated new arrivals endpoint first
        let endpoint = `${this.apiBaseUrl}/api/products/new-arrivals`;
        let params = {
          page: this.currentPage
        };
        
        if (this.selectedCategory) {
          params.category_id = this.selectedCategory;
        }
        
        // Add sort parameters
        if (this.sortOption) {
          const [sortField, sortDirection] = this.sortOption.split('-');
          params.sort_by = sortField;
          params.sort_direction = sortDirection;
        }
        
        // Try the dedicated endpoint
        try {
          const response = await axios.get(endpoint, { params });
          if (response.data && (response.data.data || response.data.products)) {
            this.products = response.data.data || response.data.products;
            this.pagination = response.data.meta || response.data.pagination;
          } else {
            throw new Error('Invalid response format');
          }
        } catch (err) {
          // Fallback: fetch all products and filter for is_new_arrival = true
          console.log('Falling back to filtering all products');
          
          endpoint = `${this.apiBaseUrl}/api/products`;
          params.is_new_arrival = true;
          
          const response = await axios.get(endpoint, { params });
          
          if (response.data && response.data.data) {
            this.products = response.data.data;
            this.pagination = response.data.meta || response.data.pagination;
          } else {
            this.error = 'Unable to fetch new arrivals';
          }
        }
      } catch (err) {
        console.error('Error fetching new arrivals:', err);
        this.error = 'Failed to load new arrivals';
      } finally {
        this.loading = false;
      }
    },
    filterProducts() {
      this.currentPage = 1;
      this.fetchNewArrivals();
    },
    goToPage(page) {
      if (page >= 1 && (!this.pagination || page <= this.pagination.last_page)) {
        this.currentPage = page;
        this.fetchNewArrivals();
      }
    },
    getImageUrl(path) {
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
          image: this.getImageUrl(product.image_url),
          quantity: 1
        });
      }
      
      localStorage.setItem('cart', JSON.stringify(cart));
      
      // Update cart data
      this.updateCartItems();
      
      // Dispatch an event to notify other components
      document.dispatchEvent(new Event('cart-updated'));
      
      // Show a quick toast or alert
      alert('Product added to cart');
    },
    navigateToProductDetails(product) {
      if (product.id) {
        this.$router.push(`/products/${product.id}`);
      } else if (product.slug) {
        this.$router.push(`/products/${product.slug}`);
      } else {
        console.error('Product has no ID or slug for navigation', product);
      }
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
.bg-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.2'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 