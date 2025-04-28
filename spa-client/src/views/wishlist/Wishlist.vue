<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Advanced E-commerce Header -->
    <Header />

    <!-- Wishlist Content -->
    <div class="container mx-auto px-4 py-8">
      <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">My Wishlist</h1>

        <!-- Loading state -->
        <div v-if="loading" class="flex justify-center py-16">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <!-- Empty wishlist state -->
        <div v-else-if="wishlistItems.length === 0" class="text-center py-16 bg-white rounded-lg shadow">
          <div class="mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-gray-700 mb-2">Your wishlist is empty</h2>
          <p class="text-gray-500 mb-6">Looks like you haven't added any products to your wishlist yet.</p>
          <router-link to="/products" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Browse Products
          </router-link>
        </div>

        <!-- Wishlist items -->
        <div v-else class="bg-white rounded-lg shadow overflow-hidden">
          <!-- Wishlist header -->
          <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="grid grid-cols-12 text-sm font-medium text-gray-500">
              <div class="col-span-6 md:col-span-7">Product</div>
              <div class="col-span-3 md:col-span-2 text-center">Price</div>
              <div class="col-span-3 md:col-span-3 text-right">Actions</div>
            </div>
          </div>

          <!-- Wishlist items -->
          <div class="divide-y divide-gray-200">
            <div v-for="(item, index) in wishlistItems" :key="index" class="px-6 py-4">
              <div class="grid grid-cols-12 items-center gap-4">
                <!-- Product info -->
                <div class="col-span-6 md:col-span-7">
                  <div class="flex items-center">
                    <div class="h-16 w-16 flex-shrink-0 bg-gray-100 rounded-md overflow-hidden">
                      <img 
                        :src="item.image" 
                        :alt="item.name" 
                        class="h-full w-full object-cover"
                        @error="handleImageError"
                      >
                    </div>
                    <div class="ml-4">
                      <router-link :to="`/products/${item.id}`" class="font-medium text-gray-800 hover:text-blue-600">
                        {{ item.name }}
                      </router-link>
                      <p class="text-sm text-gray-500">Added {{ formatDate(item.addedAt) }}</p>
                    </div>
                  </div>
                </div>

                <!-- Price -->
                <div class="col-span-3 md:col-span-2 font-medium text-center">
                  ${{ item.price.toFixed(2) }}
                </div>

                <!-- Actions -->
                <div class="col-span-3 md:col-span-3 flex justify-end space-x-2">
                  <button 
                    @click="addItemToCart(item)"
                    class="p-2 text-blue-600 hover:text-blue-800 rounded-md hover:bg-blue-50"
                    title="Add to cart"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                  </button>
                  <button 
                    @click="removeFromWishlist(item.id)"
                    class="p-2 text-red-600 hover:text-red-800 rounded-md hover:bg-red-50"
                    title="Remove from wishlist"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Wishlist footer -->
          <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
              <button 
                @click="clearWishlist" 
                class="text-sm text-red-600 hover:text-red-800"
              >
                Clear Wishlist
              </button>
              <router-link to="/products" class="text-sm text-blue-600 hover:text-blue-800">
                Continue Shopping
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Footer -->
    <Footer />
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { getCart, getCartSubtotal, addToCart, removeFromCart } from '@/utils/cart';
import { getWishlist, getWishlistSync, removeFromWishlist as removeWishlistItem, clearWishlist as clearAllWishlistItems, getWishlistCount, getWishlistCountSync } from '@/utils/wishlist';
import Header from '@/components/Header.vue';
import Footer from '@/components/layout/Footer.vue';

export default {
  name: 'WishlistView',
  components: {
    Header,
    Footer
  },
  setup() {
    const router = useRouter();
    const loading = ref(true);
    const wishlistItems = ref([]);
    
    // Header state
    const mobileMenuOpen = ref(false);
    const categoriesDropdownOpen = ref(false);
    const accountDropdownOpen = ref(false);
    const cartDropdownOpen = ref(false);
    const wishlistDropdownOpen = ref(false);
    const mobileCategoriesOpen = ref(false);
    const isLoggedIn = ref(false);
    const user = ref(null);
    
    // Cart data
    const cartItems = ref([]);
    const cartItemsCount = computed(() => {
      return cartItems.value.reduce((total, item) => total + item.quantity, 0);
    });
    const cartSubtotal = computed(() => {
      return cartItems.value.reduce((total, item) => total + (item.price * item.quantity), 0);
    });
    
    // Wishlist count
    const wishlistCount = computed(() => {
      return wishlistItems.value.length;
    });

    // Handle image errors
    const handleImageError = (event) => {
      event.target.src = 'https://via.placeholder.com/150?text=No+Image';
    };
    
    // Toggle account dropdown
    const toggleAccountDropdown = () => {
      accountDropdownOpen.value = !accountDropdownOpen.value;
      if (accountDropdownOpen.value) cartDropdownOpen.value = false;
    };
    
    // Toggle cart dropdown
    const toggleCartDropdown = () => {
      cartDropdownOpen.value = !cartDropdownOpen.value;
      if (cartDropdownOpen.value) accountDropdownOpen.value = false;
    };
    
    // Logout function
    const logout = () => {
      localStorage.removeItem('user');
      localStorage.removeItem('token');
      isLoggedIn.value = false;
      user.value = null;
      accountDropdownOpen.value = false;
      router.push('/login');
    };

    // Format date
    const formatDate = (dateString) => {
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
      }).format(date);
    };
    
    // Load wishlist items
    const loadWishlistItems = async () => {
      loading.value = true;
      try {
        // First load from localStorage for immediate display
        wishlistItems.value = getWishlistSync();
        
        // Then update with the true values from the API
        wishlistItems.value = await getWishlist();
        loading.value = false;
      } catch (error) {
        console.error('Error loading wishlist items:', error);
        loading.value = false;
      }
    };
    
    // Update local cart items
    const updateLocalCart = () => {
      cartItems.value = getCart();
    };
    
    // Update local wishlist items
    const updateLocalWishlist = async () => {
      wishlistItems.value = await getWishlist();
    };
    
    // Remove item from wishlist
    const removeFromWishlist = async (productId) => {
      await removeWishlistItem(productId);
      // Update local state
      wishlistItems.value = wishlistItems.value.filter(item => item.id !== productId);
    };
    
    // Clear wishlist
    const clearWishlist = async () => {
      if (confirm('Are you sure you want to clear your wishlist?')) {
        await clearAllWishlistItems();
        wishlistItems.value = [];
      }
    };
    
    // Add item to cart from wishlist
    const addItemToCart = (item) => {
      const productData = {
        id: item.id,
        name: item.name,
        price: item.price,
        image: item.image,
        stock: 100 // Default stock
      };
      
      addToCart(productData, 1);
      
      // Show confirmation
      alert(`${item.name} added to cart!`);
      
      // Refresh cart items
      cartItems.value = getCart();
    };
    
    // Remove cart item
    const removeCartItem = (index) => {
      removeFromCart(index);
      updateLocalCart();
    };
    
    // Calculate cart subtotal
    const calculateSubtotal = () => {
      return cartItems.value.reduce((total, item) => total + (parseFloat(item.price) * item.quantity), 0);
    };
    
    // Load data on component mount
    onMounted(() => {
      // Check if user is logged in
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        user.value = JSON.parse(storedUser);
        isLoggedIn.value = true;
      }
      
      // Load cart and wishlist items
      updateLocalCart();
      loadWishlistItems();
      
      // Add event listeners for cart and wishlist updates
      document.addEventListener('cart-updated', () => {
        updateLocalCart();
      });
      
      document.addEventListener('wishlist-updated', () => {
        updateLocalWishlist();
      });
      
      // Close dropdowns when clicking outside
      document.addEventListener('click', (e) => {
        const accountDropdown = document.querySelector('.account-dropdown');
        const cartDropdown = document.querySelector('.cart-dropdown');
        
        if (accountDropdown && !accountDropdown.contains(e.target)) {
          accountDropdownOpen.value = false;
        }
        
        if (cartDropdown && !cartDropdown.contains(e.target)) {
          cartDropdownOpen.value = false;
        }
      });
    });
    
    return {
      loading,
      wishlistItems,
      mobileMenuOpen,
      categoriesDropdownOpen,
      accountDropdownOpen,
      cartDropdownOpen,
      wishlistDropdownOpen,
      mobileCategoriesOpen,
      isLoggedIn,
      user,
      cartItems,
      cartItemsCount,
      cartSubtotal,
      wishlistCount,
      handleImageError,
      toggleAccountDropdown,
      toggleCartDropdown,
      logout,
      formatDate,
      removeFromWishlist,
      clearWishlist,
      addItemToCart,
      updateLocalCart,
      updateLocalWishlist,
      removeCartItem,
      calculateSubtotal
    };
  }
};
</script>

<style>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 