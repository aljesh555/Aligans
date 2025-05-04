<template>
  <!-- Advanced E-commerce Header -->
  <Header />

  <div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb Navigation -->
    <nav class="flex mb-6 text-sm items-center">
      <router-link to="/" class="text-gray-500 hover:text-blue-600 transition-colors duration-200 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Home
      </router-link>
      <span class="mx-2 text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </span>
      <router-link to="/products" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">Products</router-link>
      <span class="mx-2 text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </span>
      <span class="text-blue-600 font-medium px-2 py-1 bg-blue-50 rounded-md">{{ categoryName }}</span>
    </nav>
    
    <!-- Category Title -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
      <div class="flex items-center">
        <div v-if="category && category.image_url" class="h-12 w-12 rounded-full overflow-hidden mr-4">
          <img :src="getImageUrl(category.image_url)" :alt="categoryName" class="h-full w-full object-cover">
        </div>
        <div v-else class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mr-4 animate-pulse">
          <span class="text-blue-600 font-bold text-xl">{{ categoryName.charAt(0) }}</span>
        </div>
        <div>
          <h1 class="text-3xl font-bold text-gray-800">
            {{ categoryName }}
            <span v-if="categoryId === 1" class="ml-2 text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Official Collection</span>
          </h1>
          <p class="text-gray-600 mt-1">{{ category && category.description ? category.description : 'Browse our collection of quality products' }}</p>
        </div>
      </div>
      
      <div v-if="categoryId === 1" class="mt-4 md:mt-0 text-blue-600 flex items-center bg-blue-50 px-4 py-2 rounded-lg shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <span class="font-medium">Official Products</span>
      </div>
    </div>
    
    <!-- Loading State -->
    <div v-if="loading" class="text-center py-10">
      <LoadingSpinner message="Loading category data..." />
    </div>
    
    <!-- Error State -->
    <div v-else-if="error" class="text-center py-10">
      <ErrorMessage 
        :error="error" 
        title="Unable to load category" 
        :retry-action="fetchCategoryData" 
      />
    </div>
    
    <!-- Subcategories Section -->
    <div v-if="subcategories.length > 0" class="mb-8">
      <h2 class="text-xl font-bold mb-4">Subcategories</h2>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <div 
          v-for="subcategory in subcategories" 
          :key="subcategory.id"
          class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 cursor-pointer transform hover:-translate-y-1 group"
          @click="navigateToSubcategory(subcategory.id)"
        >
          <div class="p-4 text-center relative overflow-hidden">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-200 transition-colors duration-300 transform group-hover:scale-110">
              <span class="text-blue-600 font-bold text-xl">{{ subcategory.name.charAt(0) }}</span>
            </div>
            <div class="absolute inset-0 bg-blue-500 opacity-0 group-hover:opacity-10 transition-opacity duration-300 rounded-lg"></div>
            <h3 class="font-medium text-gray-900 group-hover:text-blue-700 transition-colors duration-300">{{ subcategory.name }}</h3>
            <div class="h-1 w-0 bg-blue-500 mx-auto mt-2 group-hover:w-1/2 transition-all duration-300"></div>
            <p class="text-xs text-gray-500 mt-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">View products</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Special intro for Jerseys category -->
    <div 
      v-if="categoryId === 1 && !loading && !error && products.length > 0" 
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
    
    <!-- Products Section Title -->
    <h2 v-if="!loading && !error && products.length > 0" class="text-xl font-bold mb-4">Products</h2>
    
    <!-- Products Loading State -->
    <div v-if="loadingProducts" class="text-center py-10">
      <LoadingSpinner message="Loading products..." />
    </div>
    
    <!-- Products Error State -->
    <div v-else-if="productsError" class="text-center py-10">
      <ErrorMessage 
        :error="productsError" 
        title="Unable to load products" 
        :retry-action="fetchProducts" 
      />
    </div>
    
    <!-- Empty State -->
    <div v-else-if="products.length === 0 && !loading && !loadingProducts" class="text-center py-10">
      <EmptyState 
        title="No Products Found"
        message="There are no products in this category."
      />
    </div>
    
    <!-- Add this before the main gallery -->
    <div v-if="isDevelopment" class="bg-gray-100 p-4 mb-4 rounded">
      <h3 class="font-bold">Debug Info:</h3>
      <pre>Has additional images: {{ hasAdditionalImages }}</pre>
      <pre>Current image: {{ currentImage }}</pre>
      <pre>Additional images: {{ JSON.stringify(productAdditionalImages, null, 2) }}</pre>
    </div>
    
    <!-- Product Grid -->
    <div v-else-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <Card 
        v-for="product in products" 
        :key="product.id" 
        :class="[
          'overflow-hidden',
          categoryId === 1 ? 'jersey-card border-t-4 border-blue-600' : ''
        ]"
        shadow="md"
        hover
        padding="none"
      >
        <!-- Product Image -->
        <template #image>
          <div :class="[
            'flex items-center justify-center',
            categoryId === 1 ? 'h-64 bg-gradient-to-b from-blue-50 to-gray-100' : 'h-48 bg-gray-200'
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
        <div class="p-4" :class="{ 'bg-gradient-to-r from-blue-50 to-white': categoryId === 1 }">
          <div v-if="categoryId === 1" class="text-xs text-blue-600 font-semibold mb-1">OFFICIAL JERSEY</div>
          <div class="flex justify-between items-start mb-2">
            <h2 class="text-xl font-bold">{{ product.name }}</h2>
            <span :class="[
              'font-bold', 
              categoryId === 1 ? 'text-lg text-blue-700 bg-blue-50 px-2 py-1 rounded' : 'text-lg text-blue-600'
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
                categoryId === 1 ? 'bg-blue-700 hover:bg-blue-800' : 'bg-blue-500 hover:bg-blue-600'
              ]"
              @click="addToCart(product)"
            >
              Add to Cart
            </button>
          </div>
        </div>
      </Card>
    </div>
  </div>

  <!-- Footer -->
  <Footer />
</template>

<script>
import axios from 'axios';
import { LoadingSpinner, EmptyState, ErrorMessage } from '@/components/common';
import { Card } from '@/components/ui';
import { mapGetters, mapActions } from 'vuex';
import { getCartItemCount, getCart, removeFromCart, getCartSubtotal } from '@/utils/cart';
import { getWishlistCount, getWishlist, removeFromWishlist } from '@/utils/wishlist';
import Header from '@/components/Header.vue';
import Footer from '@/components/layout/Footer.vue';

export default {
  name: 'CategoryProducts',
  components: {
    LoadingSpinner,
    EmptyState,
    ErrorMessage,
    Card,
    Header,
    Footer
  },
  props: {
    id: {
      type: [Number, String],
      required: true
    }
  },
  data() {
    return {
      categoryId: null,
      categoryName: 'Category',
      category: null,
      subcategories: [],
      products: [],
      loading: true,
      error: null,
      loadingProducts: false,
      productsError: null,
      // Header-related data
      mobileMenuOpen: false,
      mobileCategoriesOpen: false,
      categoriesDropdownOpen: false,
      accountDropdownOpen: false,
      cartDropdownOpen: false,
      wishlistDropdownOpen: false,
      cartItemsLocal: [], // Local reactive cart items
      wishlistItemsLocal: [], // Local reactive wishlist items
      isDevelopment: false,
      hasAdditionalImages: false,
      currentImage: '',
      productAdditionalImages: [],
    };
  },
  created() {
    // Add debug logging
    console.log('CategoryProducts created. Route:', this.$route);
    console.log('Props:', this.$props);
    
    // Get ID from either props or route params
    let categoryId;
    
    // Check if id prop is provided
    if (this.id) {
      categoryId = parseInt(this.id, 10);
      console.log('Using ID from props:', categoryId);
    } 
    // Otherwise get from route params
    else if (this.$route && this.$route.params && this.$route.params.id) {
      categoryId = parseInt(this.$route.params.id, 10);
      console.log('Using ID from route params:', categoryId);
    } 
    // Fallback to URL search params
    else {
      const urlParams = new URLSearchParams(window.location.search);
      const categoryParam = urlParams.get('category_id') || urlParams.get('id');
      categoryId = categoryParam ? parseInt(categoryParam, 10) : null;
      console.log('Using ID from URL search params:', categoryId);
    }
    
    if (!categoryId) {
      this.error = "No category ID provided";
      this.loading = false;
      return;
    }
    
    this.categoryId = categoryId;
    console.log('Category ID detected:', this.categoryId);
    
    // Fetch category data and products
    this.fetchCategoryData();
  },
  mounted() {
    console.log('CategoryProducts component mounted');
    console.log('Route params:', this.$route.params);
    document.title = 'Category Products | Aligans Sports';

    // Initialize reactive local arrays for header
    this.updateLocalCart();
    this.updateLocalWishlist();
    
    // Listen for wishlist updates
    document.addEventListener('wishlist-updated', () => {
      this.updateLocalWishlist();
      this.$forceUpdate(); // Force update to recalculate wishlistCount
    });
    
    // Listen for cart updates
    document.addEventListener('cart-updated', () => {
      this.updateLocalCart();
      this.$forceUpdate(); // Force update to recalculate cartItemCount
    });
  },
  watch: {
    // Watch for route changes to handle navigation between categories
    '$route.params.id': function(newId) {
      console.log('Route param id changed to:', newId);
      if (newId) {
        this.categoryId = parseInt(newId, 10);
        this.fetchCategoryData();
      }
    }
  },
  methods: {
    async fetchCategoryData() {
      this.loading = true;
      this.error = null;
      
      try {
        console.log('Fetching category data for ID:', this.categoryId);
        const response = await axios.get('/api/storefront/categories');
        
        // Handle the updated API response structure
        let categories = [];
        if (response.data.data) {
          categories = response.data.data;
        } else {
          categories = response.data.categories || [];
        }
        
        console.log('All categories:', categories);
        
        // Find the category by ID - first check in top-level categories
        const category = categories.find(c => c.id === this.categoryId);
        
        if (category) {
          this.category = category;
          this.categoryName = category.name;
          document.title = `${category.name} | Aligans Sports`;
          
          // Check if this category has subcategories
          if (category.subcategories && category.subcategories.length > 0) {
            this.subcategories = category.subcategories;
            console.log(`Found ${this.subcategories.length} subcategories for ${category.name}`);
          } else {
            this.subcategories = [];
          }
          
          console.log(`Category found: ${category.name}`);
        } else {
          // If not found in top-level, it might be a subcategory
          // Look through subcategories of all categories
          let foundAsSubcategory = false;
          
          for (const parentCategory of categories) {
            if (parentCategory.subcategories && parentCategory.subcategories.length > 0) {
              const subcategory = parentCategory.subcategories.find(s => s.id === this.categoryId);
              if (subcategory) {
                this.category = subcategory;
                this.categoryName = subcategory.name;
                document.title = `${subcategory.name} | Aligans Sports`;
                this.subcategories = []; // Subcategories generally don't have their own subcategories
                foundAsSubcategory = true;
                console.log(`Found as subcategory of ${parentCategory.name}: ${subcategory.name}`);
                break;
              }
            }
          }
          
          if (!foundAsSubcategory) {
            console.warn(`Category with ID ${this.categoryId} not found`);
            this.error = "Category not found";
          }
        }
        
        // Now fetch products for this category
        this.fetchProducts();
      } catch (error) {
        console.error('Error fetching category data:', error);
        this.error = "Failed to load category data";
      } finally {
        this.loading = false;
      }
    },
    
    async fetchProducts() {
      this.loadingProducts = true;
      this.productsError = null;
      
      try {
        console.log(`Fetching products for category ID: ${this.categoryId}`);
        
        // Make sure we're using the correct parameter name that the API expects
        const url = `/api/storefront/products?category_id=${this.categoryId}`;
        console.log('API Request URL:', url);
        
        const response = await axios.get(url);
        console.log('API Response:', response.data);
        
        if (response.data && response.data.products) {
          this.products = response.data.products;
          console.log(`Found ${this.products.length} products in category ${this.categoryId}`);
        } else {
          console.warn('API response did not contain products array', response.data);
          this.products = [];
        }
      } catch (error) {
        console.error('Error fetching products:', error);
        this.productsError = 'Failed to load products. Please try again.';
      } finally {
        this.loadingProducts = false;
      }
    },
    
    navigateToSubcategory(subcategoryId) {
      // Navigate to the subcategory
      this.$router.push(`/category/${subcategoryId}`);
    },
    
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
    
    addToCart(product) {
      // To be implemented - can dispatch to Vuex store or call cart service
      alert(`Added ${product.name} to cart!`);
    },

    // Header-related methods
    toggleAccountDropdown() {
      this.accountDropdownOpen = !this.accountDropdownOpen;
    },
    toggleCartDropdown() {
      this.cartDropdownOpen = !this.cartDropdownOpen;
    },
    updateLocalCart() {
      this.cartItemsLocal = getCart();
    },
    updateLocalWishlist() {
      this.wishlistItemsLocal = getWishlist();
    },
    removeCartItem(index) {
      // Remove directly from our local array for immediate UI update
      this.cartItemsLocal.splice(index, 1);
      
      // Update localStorage
      localStorage.setItem('cart', JSON.stringify(this.cartItemsLocal));
      
      // Notify other components
      document.dispatchEvent(new Event('cart-updated'));
    },
    removeWishlistItem(index) {
      // Remove directly from our local array for immediate UI update
      const productId = this.wishlistItemsLocal[index].id;
      this.wishlistItemsLocal.splice(index, 1);
      
      // Update localStorage
      localStorage.setItem('wishlist', JSON.stringify(this.wishlistItemsLocal));
      
      // Notify other components
      document.dispatchEvent(new Event('wishlist-updated'));
    },
    handleImageError(event) {
      event.target.src = 'https://picsum.photos/id/26/100/100';
    },
    calculateSubtotal() {
      return this.cartItemsLocal.reduce((total, item) => total + (item.price * item.quantity), 0);
    },
    ...mapActions({
      logoutAction: 'auth/logout'
    }),
    logout() {
      this.logoutAction();
      this.accountDropdownOpen = false;
      this.$router.push('/login');
    }
  },
  computed: {
    // Header-related computed properties
    ...mapGetters({
      isLoggedIn: 'auth/isLoggedIn',
      user: 'auth/user'
    }),
    wishlistCount() {
      return getWishlistCount();
    },
    cartItemCount() {
      return getCartItemCount();
    }
  },
  beforeUnmount() {
    // Remove event listeners when component is unmounted
    document.removeEventListener('wishlist-updated', () => {});
    document.removeEventListener('cart-updated', () => {});
  }
}
</script>

<style scoped>
.jersey-card {
  position: relative;
}

.jersey-card::before {
  content: "";
  position: absolute;
  top: 0;
  right: 10px;
  width: 30px;
  height: 5px;
  background-color: #2563eb;
  border-radius: 0 0 4px 4px;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Animation keyframes */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes scaleUp {
  from { transform: scale(0.9); }
  to { transform: scale(1); }
}

/* Apply animations */
.animate-fade-in {
  animation: fadeIn 0.5s ease-out;
}

.animate-scale-up {
  animation: scaleUp 0.3s ease-out;
}

/* Category hover effect */
.category-hover:hover {
  box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.1), 0 4px 6px -2px rgba(59, 130, 246, 0.05);
  transform: translateY(-4px);
}

/* Pulse animation for category icon */
@keyframes gentle-pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.05); }
  100% { transform: scale(1); }
}

.animate-pulse {
  animation: gentle-pulse 2s infinite ease-in-out;
}
</style> 