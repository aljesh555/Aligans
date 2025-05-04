<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Advanced E-commerce Header -->
    <Header />

    <div class="container mx-auto px-4 py-8">
      <!-- Filters and Sorting -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <h2 class="text-2xl font-bold">On Sale Products</h2>
        
        <div class="flex flex-col sm:flex-row gap-4">
          <!-- Category Filter -->
          <select 
            v-model="selectedCategory" 
            @change="filterProducts"
            class="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 min-w-[200px]"
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
            class="border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 min-w-[200px]"
          >
            <option value="discount_percent-desc">Highest Discount</option>
            <option value="price-asc">Price: Low to High</option>
            <option value="price-desc">Price: High to Low</option>
            <option value="name-asc">Name: A to Z</option>
          </select>
        </div>
      </div>
      
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-10">
        <LoadingSpinner message="Loading sale products..." />
      </div>
      
      <!-- Empty State -->
      <div v-else-if="!products.length" class="py-16">
        <EmptyState 
          title="No Sale Products Available"
          message="There are currently no items on sale. Please check back later."
          icon="tags"
        />
      </div>
      
      <!-- Product Grid -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <Card 
          v-for="product in products" 
          :key="product.id" 
          class="relative overflow-hidden border border-gray-200 rounded-lg transition-all duration-300 hover:shadow-lg"
          shadow="sm"
          hover
          padding="none"
        >
          <!-- Sale Badge -->
          <div class="absolute top-0 right-0 bg-red-600 text-white font-bold px-3 py-1 rounded-bl-lg z-10">
            {{ product.discount_percent }}% OFF
          </div>
          
          <!-- New Arrival Badge (if applicable) -->
          <div 
            v-if="product.is_new_arrival" 
            class="absolute top-0 left-0 bg-blue-600 text-white font-bold px-3 py-1 rounded-br-lg z-10"
          >
            NEW
          </div>
          
          <!-- Product Image -->
          <template #image>
            <div class="h-48 bg-gray-100 relative">
              <img 
                v-if="product.image_url" 
                :src="getImageUrl(product.image_url)" 
                :alt="product.name" 
                class="h-full w-full object-cover"
              />
              <div v-else class="flex items-center justify-center h-full text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
            </div>
          </template>
          
          <!-- Product Info -->
          <div class="p-4 flex flex-col min-h-[200px]">
            <div class="flex justify-between items-start mb-2">
              <h2 class="text-lg font-bold">{{ product.name }}</h2>
            </div>
            
            <!-- Price with Discount -->
            <div class="mb-2">
              <div class="flex items-center gap-2">
                <span class="text-gray-400 line-through">Rs {{ parseFloat(product.price).toFixed(2) }}</span>
                <span class="text-red-600 font-bold text-xl">Rs {{ parseFloat(product.discount_price).toFixed(2) }}</span>
              </div>
              <span class="text-xs text-gray-500" v-if="product.sale_ends_at">
                Sale ends {{ formatDate(product.sale_ends_at) }}
              </span>
            </div>
            
            <div class="text-gray-600 text-sm mb-4 line-clamp-2 flex-grow" v-html="cleanDescription(product.description)"></div>
            
            <div class="flex justify-between items-center mt-auto">
              <router-link 
                :to="`/products/${product.id}`" 
                class="text-red-500 hover:text-red-700 font-medium"
              >
                View Details
              </router-link>
              
              <button 
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"
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
    
    <!-- Advanced Footer from Home.vue -->
    <Footer />
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { Card } from '@/components/ui';
import { LoadingSpinner, EmptyState } from '@/components/common';
import { getCartItemCount } from '@/utils/cart';
import { getWishlistCount } from '@/utils/wishlist';
import Header from '@/components/Header.vue';
import Footer from '@/components/layout/Footer.vue';

export default {
  name: 'SaleView',
  components: {
    LoadingSpinner,
    EmptyState,
    Card,
    Header,
    Footer
  },
  setup() {
    // Sale Page Data
    const products = ref([]);
    const categories = ref([]);
    const loading = ref(true);
    const pagination = ref(null);
    const selectedCategory = ref(null);
    const sortOption = ref('discount_percent-desc');
    
    // Header State (from Home.vue)
    const mobileMenuOpen = ref(false);
    const mobileCategoriesOpen = ref(false);
    const categoriesDropdownOpen = ref(false);
    const accountDropdownOpen = ref(false);
    const cartDropdownOpen = ref(false);
    const isLoggedIn = ref(false);
    const user = ref(null);
    const cartItemCount = ref(getCartItemCount());
    const wishlistCount = ref(getWishlistCount());
    
    // Check if user is logged in
    const checkAuthentication = () => {
      try {
        const token = localStorage.getItem('token');
        const userData = localStorage.getItem('user');
        
        if (token && userData) {
          isLoggedIn.value = true;
          user.value = JSON.parse(userData);
          console.log('User authenticated:', user.value);
        } else {
          isLoggedIn.value = false;
          user.value = null;
        }
      } catch (error) {
        console.error('Error checking authentication:', error);
        isLoggedIn.value = false;
        user.value = null;
      }
    };
    
    // Header Methods (from Home.vue)
    const toggleAccountDropdown = () => {
      accountDropdownOpen.value = !accountDropdownOpen.value;
    };
    
    const toggleCartDropdown = () => {
      cartDropdownOpen.value = !cartDropdownOpen.value;
    };
    
    const logout = () => {
      // Implement logout functionality
      console.log('Logging out...');
    };
    
    // Sale page methods
    const fetchProducts = async (page = 1) => {
      try {
        loading.value = true;
        
        // Build query parameters
        const params = new URLSearchParams();
        
        // Add pagination
        params.append('page', page);
        
        // Add category filter if selected
        if (selectedCategory.value) {
          params.append('category_id', selectedCategory.value);
        }
        
        // Add sort options
        if (sortOption.value) {
          const [field, direction] = sortOption.value.split('-');
          params.append('sort_by', field);
          params.append('sort_direction', direction);
        }
        
        const url = `/api/storefront/sale-products?${params.toString()}`;
        const response = await axios.get(url);
        
        products.value = response.data.products || [];
        pagination.value = response.data.pagination || null;
        
        loading.value = false;
      } catch (error) {
        console.error('Error fetching sale products:', error);
        loading.value = false;
        products.value = [];
      }
    };
    
    const fetchCategories = async () => {
      try {
        const response = await axios.get('/api/storefront/categories');
        categories.value = response.data.data || [];
      } catch (error) {
        console.error('Error fetching categories:', error);
        categories.value = [];
      }
    };
    
    const filterProducts = () => {
      fetchProducts(1); // Reset to first page when filtering
    };
    
    const goToPage = (page) => {
      if (page < 1 || (pagination.value && page > pagination.value.last_page)) return;
      fetchProducts(page);
      // Scroll to top when changing pages
      window.scrollTo({ top: 0, behavior: 'smooth' });
    };
    
    const getImageUrl = (imagePath) => {
      // If the path is a full URL, return it directly
      if (imagePath.startsWith('http')) {
        return imagePath;
      }
      
      // Otherwise, assume it's a relative path on the server
      return `http://localhost:8000/storage/${imagePath}`;
    };
    
    const addToCart = (product) => {
      // To be implemented - can dispatch to Vuex store or call cart service
      alert(`Added ${product.name} to cart!`);
      // Update cart count after adding item
      updateCartCount();
    };
    
    // Update cart and wishlist counts
    const updateCartCount = () => {
      cartItemCount.value = getCartItemCount();
    };
    
    const updateWishlistCount = () => {
      wishlistCount.value = getWishlistCount();
    };
    
    const formatDate = (dateString) => {
      const options = { year: 'numeric', month: 'short', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    };
    
    const cleanDescription = (description) => {
      if (!description) return '';
      
      // Strip HTML tags but preserve spacing
      const textOnly = description.replace(/<[^>]*>/g, '');
      
      // Remove extra spaces and &nbsp; entities
      return textOnly.replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim();
    };
    
    onMounted(() => {
      document.title = 'Sale | Aligans Sports';
      fetchCategories();
      fetchProducts();
      
      // Check authentication status
      checkAuthentication();
      
      // Add event listeners for cart and wishlist updates
      window.addEventListener('cart-updated', updateCartCount);
      window.addEventListener('wishlist-updated', updateWishlistCount);
      
      // Initialize counts
      updateCartCount();
      updateWishlistCount();
    });
    
    return {
      // Sale page data and methods
      products,
      categories,
      loading,
      pagination,
      selectedCategory,
      sortOption,
      filterProducts,
      goToPage,
      getImageUrl,
      addToCart,
      formatDate,
      cleanDescription,
      
      // Header data and methods
      mobileMenuOpen,
      mobileCategoriesOpen,
      categoriesDropdownOpen,
      accountDropdownOpen,
      cartDropdownOpen,
      isLoggedIn,
      user,
      toggleAccountDropdown,
      toggleCartDropdown,
      logout,
      wishlistCount,
      cartItemCount
    };
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

.bg-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
</style> 