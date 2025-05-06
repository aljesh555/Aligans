<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Advanced E-commerce Header -->
    <Header />

    <div class="container mx-auto px-4 py-8">
      <!-- Breadcrumb Navigation -->
      <nav class="flex mb-4 text-sm">
        <a href="/" class="text-gray-500 hover:text-blue-600">Home</a>
        <span class="mx-2 text-gray-500">/</span>
        <a href="/products" class="text-gray-500 hover:text-blue-600">Products</a>
        <span class="mx-2 text-gray-500">/</span>
        <span v-if="selectedCategory === 1" class="text-blue-600 font-medium">Team Jerseys</span>
        <span v-else-if="currentCategoryName" class="text-blue-600">{{ currentCategoryName }}</span>
        <span v-else class="text-blue-600">All Products</span>
      </nav>
      
      <div class="flex flex-col md:flex-row justify-between items-center mb-4">
        <h1 v-if="selectedCategory === 1" class="text-3xl font-bold text-blue-800">
          Team Jerseys
          <span class="ml-2 text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Official Collection</span>
        </h1>
        <h1 v-else-if="currentCategoryName" class="text-3xl font-bold">
          {{ currentCategoryName }}
        </h1>
        <h1 v-else class="text-3xl font-bold">
          All Products
        </h1>
        
        <div v-if="selectedCategory === 1" class="mt-2 md:mt-0 text-blue-600 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <span class="font-medium">Official Products</span>
        </div>
        <div v-else-if="currentCategoryName" class="mt-2 md:mt-0 text-gray-600">
          Browsing: <span class="font-semibold text-blue-600">{{ currentCategoryName }}</span>
        </div>
      </div>
      
      <!-- Special intro for Jerseys category -->
      <div 
        v-if="selectedCategory === 1 && !loading && !error && products.length > 0" 
        class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-white rounded-lg shadow-sm border-l-4 border-blue-500"
      >
        <h2 class="text-xl font-bold text-blue-800 mb-2">Official Team Jerseys</h2>
        <div class="flex flex-wrap items-center gap-2 mb-3">
          <img src="https://via.placeholder.com/40" alt="Team Logo" class="w-10 h-10 rounded-full">
          <p class="text-gray-700">Support your favorite team with our authentic, high-quality jerseys.</p>
        </div>
        <div class="flex flex-wrap gap-2">
          <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Premium Quality</span>
          <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Official Design</span>
          <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">100% Authentic</span>
        </div>
      </div>
      
      <!-- Categories and Search -->
      <div class="mb-8 flex flex-col md:flex-row justify-between">
        <div class="mb-4 md:mb-0">
          <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
          <select 
            v-model="selectedCategory" 
            @change="filterProducts"
            class="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[200px]"
          >
            <option :value="null">All Categories</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
          <div class="flex">
            <input 
              v-model="searchQuery" 
              type="text" 
              placeholder="Search products..." 
              class="p-2 border rounded-l-md"
              @keyup.enter="performSearch"
            />
            <button 
              @click="performSearch" 
              class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600"
            >
              Search
            </button>
          </div>
        </div>
      </div>
      
      <!-- Brand Filter -->
      <div class="mb-8 flex flex-col md:flex-row justify-between">
        <div class="mb-4 md:mb-0">
          <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
          <select 
            v-model="selectedBrand" 
            @change="filterProducts"
            class="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[200px]"
          >
            <option :value="null">All Brands</option>
            <option v-for="brand in brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
          </select>
        </div>
        
        <!-- Sort Filter -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Sort</label>
          <select 
            v-model="sortOption" 
            @change="filterProducts"
            class="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[200px]"
          >
            <option value="newest">Newest First</option>
            <option value="price_low">Price: Low to High</option>
            <option value="price_high">Price: High to Low</option>
            <option value="name_asc">Name: A to Z</option>
            <option value="name_desc">Name: Z to A</option>
          </select>
        </div>
      </div>
      
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-10">
        <LoadingSpinner message="Loading products..." />
      </div>
      
      <!-- Empty State -->
      <div v-else-if="!products.length">
        <EmptyState 
          title="No Products Found"
          message="Try changing your search or category filters."
          :actionText="selectedCategory ? 'Show All Products' : null"
          :actionHandler="selectedCategory ? () => resetFilters() : null"
        />
      </div>
      
      <!-- Product Grid -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        <div 
          v-for="(product, index) in products" 
          :key="product.id" 
          class="product-card group bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1"
          :style="{animationDelay: `${index * 50}ms`}"
        >
          <!-- Product Image with Overlay Actions -->
          <div class="relative overflow-hidden product-image-container">
            <!-- Main Image -->
            <div class="h-40 bg-gray-100 overflow-hidden">
              <img 
                v-if="product.image_url" 
                :src="getImageUrl(product.image_url)" 
                :alt="product.name" 
                class="h-full w-full object-cover transform group-hover:scale-110 transition-all duration-500"
              />
              <div v-else class="flex items-center justify-center h-full text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
            </div>
            
            <!-- Category Badge -->
            <div 
              v-if="product.category_id" 
              class="absolute top-0 left-0 bg-blue-500 text-white text-xs font-bold px-2 py-1 m-2 rounded-md opacity-80 z-10"
            >
              {{ getCategoryName(product.category_id) }}
            </div>
            
            <!-- Sale Badge (if applicable) -->
            <div 
              v-if="product.on_sale" 
              class="absolute top-0 right-0 bg-red-600 text-white text-xs font-bold px-2 py-1 m-2 rounded-md z-10"
            >
              SALE
            </div>
            
            <!-- Action Buttons (always visible, enhanced on hover) -->
            <div class="absolute bottom-0 left-0 right-0 flex justify-center py-2 action-buttons-container transform transition-transform duration-300 translate-y-1/2 group-hover:translate-y-0">
              <div class="px-3 py-2 bg-white bg-opacity-90 rounded-full shadow-lg flex gap-4 action-buttons-wrapper">
                <!-- Cart Button -->
                <button 
                  @click.prevent.stop="addToCart(product)"
                  class="product-action-btn w-8 h-8 flex items-center justify-center rounded-full bg-white hover:bg-blue-500 hover:text-white transition-colors duration-300"
                  title="Add to Cart"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                </button>
                
                <!-- Wishlist Button -->
                <button 
                  @click.prevent.stop="toggleWishlist(product)"
                  class="product-action-btn w-8 h-8 flex items-center justify-center rounded-full"
                  :class="isInWishlist(product.id) ? 'bg-red-500 text-white' : 'bg-white hover:bg-red-500 hover:text-white'"
                  title="Add to Wishlist"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
                    <path 
                      :fill="isInWishlist(product.id) ? '#ffffff' : 'none'" 
                      :stroke="isInWishlist(product.id) ? '#ffffff' : 'currentColor'" 
                      stroke-width="2" 
                      stroke-linecap="round" 
                      stroke-linejoin="round" 
                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" 
                    />
                  </svg>
                </button>
                
                <!-- View Details Button -->
                <router-link 
                  :to="`/products/${product.id}`" 
                  class="product-action-btn w-8 h-8 flex items-center justify-center rounded-full bg-white hover:bg-blue-500 hover:text-white transition-colors duration-300"
                  title="View Details"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </router-link>
              </div>
            </div>
          </div>
          
          <!-- Product Info -->
          <div class="p-3">
            <!-- Title and Brand -->
            <div class="mb-1">
              <h3 class="text-sm font-bold line-clamp-1 group-hover:text-blue-600 transition-colors duration-200">
                {{ product.name }}
              </h3>
              <div v-if="product.brand" class="text-xs text-gray-500">{{ product.brand.name }}</div>
            </div>
            
            <!-- Price -->
            <div class="flex items-center gap-2 mb-1">
              <span v-if="product.on_sale" class="line-through text-gray-400 text-xs">Rs {{ parseFloat(product.regular_price || product.price).toFixed(2) }}</span>
              <span class="font-bold text-blue-700">Rs {{ parseFloat(product.price).toFixed(2) }}</span>
            </div>
            
            <!-- Rating (if available) -->
            <div v-if="product.average_rating" class="flex items-center mb-1">
              <div class="flex text-yellow-400">
                <svg v-for="i in Math.floor(product.average_rating)" :key="`star-${i}`" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-xs ml-1 text-gray-600">{{ product.average_rating.toFixed(1) }}</span>
              </div>
            </div>
            
            <!-- Tags/Features (if available) -->
            <div class="flex flex-wrap gap-1 mt-1 mb-1">
              <span v-if="product.is_new" class="bg-green-100 text-green-800 text-xs px-1.5 py-0.5 rounded">New</span>
              <span v-if="product.is_featured" class="bg-purple-100 text-purple-800 text-xs px-1.5 py-0.5 rounded">Featured</span>
              <span v-if="product.in_stock === false" class="bg-red-100 text-red-800 text-xs px-1.5 py-0.5 rounded">Out of Stock</span>
            </div>
            
            <!-- Quick Add Button (visible on small screens) -->
              <button 
                @click="addToCart(product)"
              class="md:hidden w-full mt-2 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded transition-colors duration-200"
              >
                Add to Cart
              </button>
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
    
    <!-- Custom Toast Notification -->
    <div 
      v-if="showToast" 
      class="fixed bottom-6 right-6 z-50 flex items-center px-6 py-4 rounded-xl shadow-lg transform transition-all duration-500"
      :class="[
        toastEntering ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0',
        toastType === 'success' ? 'bg-white border-l-4 border-green-500' : 
        toastType === 'error' ? 'bg-white border-l-4 border-red-500' : 
        toastType === 'info' ? 'bg-white border-l-4 border-blue-500' : 'bg-white'
      ]"
    >
      <div class="flex-shrink-0 mr-4">
        <svg v-if="toastType === 'success'" class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <svg v-else-if="toastType === 'error'" class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <svg v-else class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <div class="flex-1">
        <p class="text-gray-800 font-medium">{{ toastMessage }}</p>
      </div>
      <button @click="hideToast" class="ml-4 text-gray-400 hover:text-gray-600 focus:outline-none">
        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    
    <!-- Footer from Home.vue -->
    <Footer />
  </div>
</template>

<script>
import productService from '@/services/product.service';
import { LoadingSpinner, EmptyState } from '@/components/common';
import { Card } from '@/components/ui';
import axios from 'axios';
import { useStore } from 'vuex';
import { computed } from 'vue';
import { getCartItemCount, addToCart as addItemToCart } from '@/utils/cart';
import { getWishlistCount, addToWishlist as addItemToWishlist, removeFromWishlist, isInWishlistSync, toggleWishlistItem } from '@/utils/wishlist';
import Header from '@/components/Header.vue';
import Footer from '@/components/layout/Footer.vue';

export default {
  name: 'ProductsView',
  components: {
    LoadingSpinner,
    EmptyState,
    Card,
    Header,
    Footer
  },
  data() {
    return {
      loading: true,
      products: [],
      filteredProducts: [],
      error: null,
      sortOption: 'newest',
      selectedCategory: null,
      selectedBrand: null,
      brands: [],
      categories: [],
      priceRange: [0, 50000],
      pagination: null,
      apiBaseUrl: 'http://127.0.0.1:8000',
      currentPage: 1,
      currentCategoryName: null,
      // Header state
      mobileMenuOpen: false,
      accountDropdownOpen: false,
      cartDropdownOpen: false,
      categoriesDropdownOpen: false,
      mobileCategoriesOpen: false,
      searchQuery: '',
      showToast: false,
      toastMessage: '',
      toastType: 'success',
      toastTimeout: null,
      toastEntering: false,
    }
  },
  setup() {
    const store = useStore();
    
    // Get user and authentication state from Vuex store
    const isLoggedIn = computed(() => store.getters['auth/isLoggedIn']);
    const user = computed(() => store.getters['auth/user']);
    
    return {
      isLoggedIn,
      user
    };
  },
  async mounted() {
    await this.fetchCategories();
    
    // Check if category is specified in the URL
    const urlParams = new URLSearchParams(window.location.search);
    const categoryId = urlParams.get('category_id');
    
    if (categoryId) {
      // Convert to number
      const categoryIdNum = parseInt(categoryId, 10);
      this.selectedCategory = categoryIdNum;
      
      // Log for debugging
      console.log(`Category selected: ${categoryIdNum}`);
      
      if (categoryIdNum === 1) {
        console.log('Jersey category selected');
        document.title = 'Team Jerseys | Aligans Sports';
      }
    }
    
    await this.fetchProducts();
    
    // Listen for cart updates
    document.addEventListener('cart-updated', () => {
      this.$forceUpdate(); // Force update to recalculate cartItemCount
    });
    
    // Listen for wishlist updates
    document.addEventListener('wishlist-updated', () => {
      this.$forceUpdate(); // Force update to recalculate wishlistCount
    });
  },
  beforeUnmount() {
    // Remove event listeners
    document.removeEventListener('cart-updated', () => {});
    document.removeEventListener('wishlist-updated', () => {});
  },
  methods: {
    // Header methods
    toggleAccountDropdown() {
      this.accountDropdownOpen = !this.accountDropdownOpen;
      // Close other dropdowns
      this.cartDropdownOpen = false;
    },
    
    toggleCartDropdown() {
      this.cartDropdownOpen = !this.cartDropdownOpen;
      // Close other dropdowns
      this.accountDropdownOpen = false;
    },
    
    logout() {
      // Call logout action from Vuex store
      this.$store.dispatch('auth/logout');
      this.accountDropdownOpen = false;
    },
    
    async fetchProducts() {
      this.loading = true;
      this.error = null;
      
      try {
        // Fetch categories first
        await this.fetchCategories();
        
        // Fetch brands
        await this.fetchBrands();
        
        let result;
        
        // Use direct ProductService with search and category filtering
        if (this.searchQuery) {
          console.log(`Searching for products with query: "${this.searchQuery}" and category: ${this.selectedCategory}`);
          result = await productService.getProducts(this.selectedCategory, this.searchQuery, this.currentPage);
        } else {
          console.log(`Fetching products for category: ${this.selectedCategory || 'all'}`);
          result = await productService.getProducts(this.selectedCategory, null, this.currentPage);
        }
        
        console.log('API response:', result);
        
        if (result && result.data) {
          this.products = result.data;
        } else if (result && result.products) {
          this.products = result.products;
        } else if (result && Array.isArray(result)) {
          this.products = result;
        } else {
          console.warn('Unexpected API response format:', result);
          this.products = [];
        }
        
        this.pagination = result.pagination || result.meta || null;
        
        // Get category name if a category is selected
        if (this.selectedCategory) {
          const category = this.categories.find(c => c.id === this.selectedCategory);
          this.currentCategoryName = category ? category.name : null;
          
          if (this.selectedCategory === 1) {
            console.log('Showing Jersey products:', this.products);
            document.title = 'Jerseys | Aligans Sports';
          }
        } else {
          this.currentCategoryName = null;
        }
        
        this.loading = false;
      } catch (error) {
        console.error('Error fetching products, using mock data:', error);
        this.products = this.mockProducts;
        
        // Filter mock products by search query if needed
        if (this.searchQuery) {
          const query = this.searchQuery.toLowerCase();
          this.products = this.mockProducts.filter(p => 
            p.name.toLowerCase().includes(query) || 
            p.description.toLowerCase().includes(query)
          );
        }
        
        // Set pagination and category name even with mock data
        this.pagination = {
          current_page: 1,
          last_page: 1,
          per_page: 12,
          total: this.products.length
        };
        
        if (this.selectedCategory) {
          // Filter mock products by category if needed
          this.products = this.products.filter(p => p.category_id === this.selectedCategory);
          // Find mock category name
          const mockCategoryNames = {
            1: "Basketball",
            2: "Soccer",
            3: "Tennis",
            4: "Running",
            5: "Swimming",
            6: "Fitness"
          };
          this.currentCategoryName = mockCategoryNames[this.selectedCategory] || null;
        }
        
        this.loading = false;
      }
    },
    
    async fetchCategories() {
      try {
        const response = await axios.get(`${this.apiBaseUrl}/api/categories`);
        if (response.data && response.data.data) {
          this.categories = response.data.data;
        }
      } catch (err) {
        console.error('Error fetching categories:', err);
      }
    },
    
    async fetchBrands() {
      try {
        const response = await axios.get(`${this.apiBaseUrl}/api/brands`);
        if (response.data && response.data.data) {
          this.brands = response.data.data;
        }
      } catch (err) {
        console.error('Error fetching brands:', err);
      }
    },
    
    async fetchCategoryName() {
      if (!this.selectedCategory) return;
      
      try {
        const category = this.categories.find(c => c.id === this.selectedCategory);
        if (category) {
          this.currentCategoryName = category.name;
          document.title = `${category.name} | Aligans Sports`;
        }
      } catch (error) {
        console.error('Error getting category name:', error);
      }
    },
    
    filterProducts() {
      let url = `${this.apiBaseUrl}/api/products?page=${this.currentPage}`;
      
      if (this.selectedCategory) {
        url += `&category_id=${this.selectedCategory}`;
      }
      
      if (this.selectedBrand) {
        url += `&brand_id=${this.selectedBrand}`;
      }
      
      if (this.priceRange[0] > 0 || this.priceRange[1] < 50000) {
        url += `&min_price=${this.priceRange[0]}&max_price=${this.priceRange[1]}`;
      }
      
      url += `&sort=${this.sortOption}`;
      
      this.fetchFilteredProducts(url);
    },
    
    goToPage(page) {
      if (page < 1 || page > this.pagination.last_page) return;
      this.currentPage = page;
      this.fetchProducts();
      // Scroll to top when changing pages
      window.scrollTo({ top: 0, behavior: 'smooth' });
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
      try {
        // Create a product object with the correct image URL
        const productWithCorrectImage = {
          ...product,
          image: this.getImageUrl(product.image_url), // Ensure we're using the same image URL displayed in the UI
          image_url: this.getImageUrl(product.image_url) // Add both formats to be safe
        };
        
        // Log the image URL being used
        console.log('Adding product to cart with image URL:', productWithCorrectImage.image);
        
        addItemToCart(productWithCorrectImage);
        
        // Show toast notification
        this.showToastMessage(`${product.name} added to cart!`, 'success');
        
        // Dispatch custom event to update cart count in header
        document.dispatchEvent(new CustomEvent('cart-updated'));
        
        // Force component update to reflect changes
        this.$forceUpdate();
      } catch (error) {
        console.error('Error adding to cart:', error);
        this.showToastMessage('Failed to add item to cart', 'error');
      }
    },
    
    resetFilters() {
      this.selectedCategory = null;
      this.searchQuery = '';
      this.currentPage = 1;
      this.fetchProducts();
    },
    
    performSearch() {
      // Don't perform an empty search
      if (!this.searchQuery.trim()) {
        return;
      }
      
      console.log('Performing search with query:', this.searchQuery.trim());
      
      // Navigate to search results page with query parameter
      this.$router.push({
        path: '/search',
        query: { q: this.searchQuery.trim() }
      }).catch(err => {
        // Ignore navigation errors to same route
        if (err.name !== 'NavigationDuplicated') {
          console.error('Navigation error:', err);
        }
      });
    },
    
    cleanDescription(description) {
      if (!description) return '';
      
      // Strip HTML tags but preserve spacing
      const textOnly = description.replace(/<[^>]*>/g, '');
      
      // Remove extra spaces and &nbsp; entities
      return textOnly.replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim();
    },
    
    getCategoryName(categoryId) {
      if (!categoryId) return '';
      const category = this.categories.find(c => c.id === categoryId);
      return category ? category.name : '';
    },
    
    // Toggle item in wishlist
    async toggleWishlist(product) {
      try {
        // Create a product object with the correct image URL
        const productWithCorrectImage = {
          ...product,
          image: this.getImageUrl(product.image_url), // Ensure we're using the same image URL displayed in the UI
          image_url: this.getImageUrl(product.image_url) // Add both formats to be safe
        };
        
        console.log('Adding/removing product to wishlist with image URL:', productWithCorrectImage.image);
        
        // Check current state
        const isCurrentlyInWishlist = isInWishlistSync(product.id);
        
        // Update local UI immediately for better responsiveness
        const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        
        if (isCurrentlyInWishlist) {
          // Remove from localStorage wishlist immediately for UI update
          const updatedWishlist = wishlist.filter(item => item.id !== product.id);
          localStorage.setItem('wishlist', JSON.stringify(updatedWishlist));
        } else {
          // Add to localStorage wishlist immediately for UI update
          wishlist.push({
            id: product.id,
            name: product.name,
            price: product.price,
            image: productWithCorrectImage.image,
            addedAt: new Date().toISOString()
          });
          localStorage.setItem('wishlist', JSON.stringify(wishlist));
        }
        
        // Force component update to reflect changes immediately
        this.$forceUpdate();
        
        // Then perform the actual API operation in the background
        const wasAdded = await toggleWishlistItem(productWithCorrectImage);
        
        // Show toast notification
        if (wasAdded) {
          this.showToastMessage(`${product.name} added to wishlist!`, 'success');
        } else {
          this.showToastMessage(`${product.name} removed from wishlist!`, 'info');
        }
        
        // Force component update again after API operation completes
        this.$forceUpdate();
        
        // Dispatch event to notify all components of wishlist changes
        document.dispatchEvent(new CustomEvent('wishlist-updated'));
      } catch (error) {
        console.error('Error toggling wishlist item:', error);
        this.showToastMessage('Error updating wishlist. Please try again.', 'error');
      }
    },
    
    showToastMessage(message, type = 'success') {
      // Clear any existing timeouts
      if (this.toastTimeout) {
        clearTimeout(this.toastTimeout);
      }
      
      // Set toast data
      this.toastMessage = message;
      this.toastType = type;
      this.showToast = true;
      
      // Trigger enter animation after a small delay
      setTimeout(() => {
        this.toastEntering = true;
      }, 10);
      
      // Set timeout to hide the toast
      this.toastTimeout = setTimeout(() => {
        this.hideToast();
      }, 3000);
    },
    
    hideToast() {
      this.toastEntering = false;
      
      // Wait for exit animation to complete before hiding
      setTimeout(() => {
        this.showToast = false;
      }, 500);
    },
    
    isInWishlist(productId) {
      return isInWishlistSync(productId);
    },
  },
  computed: {
    cartItemCount() {
      return getCartItemCount();
    },
    wishlistCount() {
      return getWishlistCount();
    },
  }
}
</script>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Product Card Animations */
.product-card {
  animation: fadeInUp 0.5s ease-out forwards;
  opacity: 0;
  transition: all 0.3s ease;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
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

/* Jersey card styles */
.jersey-card {
  border-top: 3px solid #1d4ed8;
}

/* Product image container */
.product-image-container {
  position: relative;
  z-index: 1;
}

/* Improved action buttons styling */
.action-buttons-container {
  z-index: 50;
  pointer-events: none; /* The container is non-interactive */
  will-change: transform;
}

.action-buttons-wrapper {
  pointer-events: auto; /* But the buttons wrapper is interactive */
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.product-action-btn {
  position: relative;
  z-index: 60; /* Very high z-index to ensure visibility */
  cursor: pointer;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
  transform: scale(1);
  will-change: transform;
  isolation: isolate;
  transition: all 0.2s ease;
  border: 1px solid rgba(0, 0, 0, 0.05);
}

.product-action-btn:hover {
  z-index: 70;
  transform: scale(1.15);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.product-action-btn:active {
  transform: scale(0.95);
}

/* Ensure SVG is properly displayed */
.product-action-btn svg {
  position: relative;
  z-index: 65;
  pointer-events: none;
}

/* Add some custom animation */
@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.05); }
  100% { transform: scale(1); }
}

.bg-red-500 {
  animation: pulse 2s infinite;
}
</style> 