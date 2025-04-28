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
            class="p-2 border rounded-md w-full md:w-auto"
          >
            <option :value="null">All Categories</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
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
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <Card 
          v-for="product in products" 
          :key="product.id" 
          :class="[
            'overflow-hidden',
            product.category_id === 1 ? 'jersey-card border-t-4 border-blue-600' : ''
          ]"
          shadow="md"
          hover
          padding="none"
        >
          <!-- Product Image (placeholder if no image) -->
          <template #image>
            <div :class="[
              'flex items-center justify-center',
              product.category_id === 1 ? 'h-64 bg-gradient-to-b from-blue-50 to-gray-100' : 'h-48 bg-gray-200'
            ]">
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
          </template>
          
          <!-- Product Info -->
          <div class="p-4" :class="{ 'bg-gradient-to-r from-blue-50 to-white': product.category_id === 1 }">
            <div v-if="product.category_id === 1" class="text-xs text-blue-600 font-semibold mb-1">OFFICIAL JERSEY</div>
            <div class="flex justify-between items-start mb-2">
              <h2 class="text-xl font-bold">{{ product.name }}</h2>
              <span :class="[
                'font-bold', 
                product.category_id === 1 ? 'text-lg text-blue-700 bg-blue-50 px-2 py-1 rounded' : 'text-lg text-blue-600'
              ]">${{ parseFloat(product.price).toFixed(2) }}</span>
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
                :class="[
                  'text-white px-3 py-1 rounded text-sm',
                  product.category_id === 1 ? 'bg-blue-700 hover:bg-blue-800' : 'bg-blue-500 hover:bg-blue-600'
                ]"
                @click="addToCart(product)"
              >
                Add to Cart
              </button>
            </div>
          </div>
        </Card>
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
import productService from '@/services/product.service';
import { LoadingSpinner, EmptyState } from '@/components/common';
import { Card } from '@/components/ui';
import axios from 'axios';
import { useStore } from 'vuex';
import { computed } from 'vue';
import { getCartItemCount } from '@/utils/cart';
import { getWishlistCount } from '@/utils/wishlist';
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
      categories: [],
      selectedCategory: null,
      searchQuery: '',
      pagination: null,
      currentCategoryName: null,
      // Header state
      mobileMenuOpen: false,
      accountDropdownOpen: false,
      cartDropdownOpen: false,
      categoriesDropdownOpen: false,
      mobileCategoriesOpen: false,
      // Mock products
      mockProducts: [
        {
          id: 1,
          name: "Professional Basketball",
          description: "Official size and weight basketball for professional games",
          price: 49.99,
          image_url: null,
          category_id: 1
        },
        {
          id: 2,
          name: "Training Soccer Ball",
          description: "Durable soccer ball perfect for daily training",
          price: 35.95,
          image_url: null,
          category_id: 2
        },
        {
          id: 3,
          name: "Premium Tennis Racket",
          description: "Lightweight tennis racket with perfect balance and control",
          price: 129.99,
          image_url: null,
          category_id: 3
        },
        {
          id: 4,
          name: "Running Shoes",
          description: "Comfortable running shoes with excellent cushioning",
          price: 89.95,
          image_url: null,
          category_id: 4
        },
        {
          id: 5,
          name: "Swimming Goggles",
          description: "Anti-fog swimming goggles with UV protection",
          price: 24.99,
          image_url: null,
          category_id: 5
        },
        {
          id: 6,
          name: "Fitness Mat",
          description: "Extra thick yoga and fitness mat with non-slip surface",
          price: 39.95,
          image_url: null,
          category_id: 6
        }
      ]
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
    
    async fetchProducts(page = 1) {
      try {
        this.loading = true;
        
        let result;
        
        // Use direct ProductService with search and category filtering
        if (this.searchQuery) {
          console.log(`Searching for products with query: "${this.searchQuery}" and category: ${this.selectedCategory}`);
          result = await productService.getProducts(this.selectedCategory, this.searchQuery, page);
        } else {
          console.log(`Fetching products for category: ${this.selectedCategory || 'all'}`);
          result = await productService.getProducts(this.selectedCategory, null, page);
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
        this.categories = await productService.getCategories();
      } catch (error) {
        console.error('Error fetching categories:', error);
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
      this.fetchProducts(1); // Reset to first page when filtering
    },
    
    goToPage(page) {
      if (page < 1 || page > this.pagination.last_page) return;
      this.fetchProducts(page);
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
      // To be implemented - can dispatch to Vuex store or call cart service
      alert(`Added ${product.name} to cart!`);
    },
    
    resetFilters() {
      this.selectedCategory = null;
      this.searchQuery = '';
      this.fetchProducts(1);
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
    }
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
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.jersey-card {
  border-top: 3px solid #1d4ed8;
}
</style> 