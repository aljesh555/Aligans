<template>
  <div class="bg-gray-50 min-h-screen">
    <Header />
    
    <div class="container mx-auto px-4 py-8">
      <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold mb-8">Shopping Cart</h1>
        
        <div v-if="loading" class="flex justify-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
        </div>
        
        <div v-else-if="cartItems.length === 0" class="bg-white rounded-lg shadow-sm p-8 text-center">
          <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <h2 class="text-2xl font-bold mb-2">Your cart is empty</h2>
          <p class="text-gray-600 mb-6">Looks like you haven't added any products to your cart yet.</p>
          <router-link to="/products" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
            Browse Products
          </router-link>
        </div>
        
        <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Cart Items -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
              <div class="divide-y divide-gray-200">
                <!-- Header -->
                <div class="hidden md:grid md:grid-cols-12 px-6 py-4 bg-gray-50 text-sm font-medium text-gray-700">
                  <div class="col-span-7">Product</div>
                  <div class="col-span-2 text-center">Quantity</div>
                  <div class="col-span-2 text-right">Price</div>
                  <div class="col-span-1 text-right">Remove</div>
                </div>
                
                <!-- Cart Items -->
                <div v-for="(item, index) in cartItems" :key="index" class="px-6 py-4 md:grid md:grid-cols-12 md:gap-4 md:items-center">
                  <!-- Product -->
                  <div class="md:col-span-7">
                    <div class="flex items-center">
                      <router-link :to="'/products/' + item.id" class="flex items-center">
                        <img 
                          :src="item.image" 
                          :alt="item.name" 
                          class="h-16 w-16 object-cover rounded-md mr-4"
                          @error="handleImageError"
                        >
                        <div>
                          <h3 class="font-medium">{{ item.name }}</h3>
                          <div v-if="item.size" class="text-sm text-gray-500 mt-1">Size: {{ item.size }}</div>
                          <div v-if="item.color" class="text-sm text-gray-500 mt-1">Color: {{ item.color }}</div>
                          <div class="md:hidden text-sm font-medium mt-2">
                            <span v-if="item.on_sale" class="text-red-600">${{ item.price.toFixed(2) }}</span>
                            <span v-else class="text-gray-700">${{ item.price.toFixed(2) }}</span>
                            <span v-if="item.on_sale" class="text-gray-500 line-through ml-1">${{ item.original_price.toFixed(2) }}</span>
                            <span v-if="item.on_sale" class="text-green-600 text-xs ml-1">
                              Save {{ calculateDiscountPercentage(item.original_price, item.price) }}%
                            </span>
                          </div>
                        </div>
                      </router-link>
                    </div>
                  </div>
                  
                  <!-- Quantity -->
                  <div class="md:col-span-2 flex justify-center mt-4 md:mt-0">
                    <div class="flex border border-gray-300 rounded-md">
                      <button 
                        @click="decrementQuantity(index)" 
                        class="px-3 py-1 border-r border-gray-300"
                        :disabled="item.quantity <= 1"
                        :class="{ 'opacity-50 cursor-not-allowed': item.quantity <= 1 }"
                      >
                        -
                      </button>
                      <span class="px-3 py-1">{{ item.quantity }}</span>
                      <button 
                        @click="incrementQuantity(index)" 
                        class="px-3 py-1 border-l border-gray-300"
                      >
                        +
                      </button>
                    </div>
                  </div>
                  
                  <!-- Price -->
                  <div class="md:col-span-2 text-right hidden md:block">
                    <div v-if="item.on_sale" class="flex flex-col items-end">
                      <span class="text-red-600">${{ (item.price * item.quantity).toFixed(2) }}</span>
                      <span class="text-sm text-gray-500 line-through">${{ (item.original_price * item.quantity).toFixed(2) }}</span>
                      <span class="text-xs text-green-600">Save {{ calculateDiscountPercentage(item.original_price, item.price) }}%</span>
                    </div>
                    <div v-else>
                      ${{ (item.price * item.quantity).toFixed(2) }}
                    </div>
                  </div>
                  
                  <!-- Remove -->
                  <div class="md:col-span-1 text-right mt-4 md:mt-0">
                    <button @click="removeItem(index)" class="text-red-500 hover:text-red-700 transition">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="mt-4 flex">
              <router-link to="/products" class="text-blue-600 hover:text-blue-800 transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Continue Shopping
              </router-link>
            </div>
          </div>
          
          <!-- Order Summary -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
              <h2 class="text-lg font-bold mb-4">Order Summary</h2>
              
              <div class="space-y-3 mb-6">
                <div class="flex justify-between">
                  <span class="text-gray-600">Subtotal ({{ totalQuantity }} items)</span>
                  <span class="font-medium">${{ subtotal.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Shipping</span>
                  <span class="font-medium">Free</span>
                </div>
                <div v-if="discount > 0" class="flex justify-between">
                  <span class="text-gray-600">Discount</span>
                  <span class="font-medium text-green-600">-${{ discount.toFixed(2) }}</span>
                </div>
              </div>
              
              <div class="border-t border-gray-200 pt-4 mb-6">
                <div class="flex justify-between font-bold text-lg">
                  <span>Total</span>
                  <span>${{ total.toFixed(2) }}</span>
                </div>
              </div>
              
              <button 
                @click="proceedToCheckout" 
                class="w-full py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
                :disabled="loading"
              >
                Proceed to Checkout
              </button>
              
              <div class="mt-4">
                <div class="text-center text-sm text-gray-500">
                  <p>Secure Checkout</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <Footer />
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Footer from '@/components/layout/Footer.vue';
import Header from '@/components/Header.vue';

export default {
  name: 'ShoppingCart',
  components: {
    Footer,
    Header
  },
  setup() {
    const router = useRouter();
    const cartItems = ref([]);
    const loading = ref(true);
    const discount = ref(0);
    
    // Header state
    const mobileMenuOpen = ref(false);
    const categoriesDropdownOpen = ref(false);
    const accountDropdownOpen = ref(false);
    const cartDropdownOpen = ref(false);
    const isLoggedIn = ref(false);
    const user = ref(null);
    
    // Calculate discount percentage
    const calculateDiscountPercentage = (originalPrice, salePrice) => {
      const original = parseFloat(originalPrice);
      const sale = parseFloat(salePrice);
      if (original <= 0 || sale <= 0 || sale >= original) return 0;
      
      const discountPercent = Math.round(((original - sale) / original) * 100);
      return discountPercent;
    };
    
    // Check if user is logged in
    const checkLoginStatus = () => {
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        user.value = JSON.parse(storedUser);
        isLoggedIn.value = true;
      }
    };
    
    // Toggle dropdowns
    const toggleAccountDropdown = () => {
      accountDropdownOpen.value = !accountDropdownOpen.value;
      if (accountDropdownOpen.value) cartDropdownOpen.value = false;
    };
    
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
    
    // Load cart items from localStorage
    const loadCartItems = () => {
      loading.value = true;
      try {
        const savedCart = localStorage.getItem('cart');
        if (savedCart) {
          cartItems.value = JSON.parse(savedCart);
        }
      } catch (error) {
        console.error('Error loading cart:', error);
      } finally {
        loading.value = false;
      }
    };
    
    // Save cart items to localStorage
    const saveCartItems = () => {
      localStorage.setItem('cart', JSON.stringify(cartItems.value));
    };
    
    // Increment item quantity
    const incrementQuantity = (index) => {
      cartItems.value[index].quantity += 1;
      saveCartItems();
    };
    
    // Decrement item quantity
    const decrementQuantity = (index) => {
      if (cartItems.value[index].quantity > 1) {
        cartItems.value[index].quantity -= 1;
        saveCartItems();
      }
    };
    
    // Remove item from cart
    const removeItem = (index) => {
      cartItems.value.splice(index, 1);
      saveCartItems();
    };
    
    // Fallback image handler
    const handleImageError = (e) => {
      e.target.src = 'https://via.placeholder.com/150';
    };
    
    // Proceed to checkout
    const proceedToCheckout = () => {
      // Check if user is logged in
      const user = localStorage.getItem('user');
      if (!user) {
        // If not logged in, redirect to login page with return URL
        router.push({
          path: '/login',
          query: { redirect: '/checkout/shipping' }
        });
        return;
      }
      
      // If logged in, save cart to localStorage and proceed to checkout
      router.push('/checkout/shipping');
    };
    
    // Computed properties
    const subtotal = computed(() => {
      return cartItems.value.reduce((total, item) => {
        return total + (item.price * item.quantity);
      }, 0);
    });
    
    const total = computed(() => {
      return subtotal.value - discount.value;
    });
    
    const totalQuantity = computed(() => {
      return cartItems.value.reduce((total, item) => {
        return total + item.quantity;
      }, 0);
    });
    
    onMounted(() => {
      loadCartItems();
      checkLoginStatus();
      
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
      cartItems,
      loading,
      discount,
      subtotal,
      total,
      totalQuantity,
      incrementQuantity,
      decrementQuantity,
      removeItem,
      handleImageError,
      proceedToCheckout,
      mobileMenuOpen,
      categoriesDropdownOpen,
      accountDropdownOpen,
      cartDropdownOpen,
      isLoggedIn,
      user,
      toggleAccountDropdown,
      toggleCartDropdown,
      logout,
      calculateDiscountPercentage
    };
  }
};
</script> 