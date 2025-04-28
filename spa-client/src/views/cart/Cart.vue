<template>
  <div class="bg-gray-50 min-h-screen">
    <Header />
    
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-6">Your Cart</h1>
      
      <div v-if="loading" class="py-20 text-center">
        <div class="w-16 h-16 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-gray-600">Loading your cart...</p>
      </div>
      
      <div v-else-if="cartItems.length === 0" class="bg-white rounded-lg shadow-sm p-8 text-center">
        <div class="w-20 h-20 mx-auto mb-6 text-gray-400">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
        </div>
        <h2 class="text-2xl font-bold mb-2">Your cart is empty</h2>
        <p class="text-gray-600 mb-6">Looks like you haven't added any products to your cart yet.</p>
        <router-link
          to="/products"
          class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md inline-block transition"
        >
          Start Shopping
        </router-link>
      </div>
      
      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cart items -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
            <div class="divide-y divide-gray-200">
              <div 
                v-for="item in cartItems" 
                :key="item.product_id" 
                class="p-6"
              >
                <div class="flex items-start">
                  <div class="w-20 h-20 bg-gray-100 rounded-md flex-shrink-0">
                    <img
                      v-if="item.image"
                      :src="item.image"
                      :alt="item.name"
                      class="w-full h-full object-cover rounded-md"
                    />
                  </div>
                  
                  <div class="ml-4 flex-grow">
                    <div class="flex justify-between">
                      <router-link
                        :to="`/products/${item.product_id}`"
                        class="font-medium hover:text-blue-600 transition"
                      >
                        {{ item.name }}
                      </router-link>
                      <span class="font-medium">${{ (item.price * item.quantity).toFixed(2) }}</span>
                    </div>
                    
                    <p v-if="item.size" class="text-sm text-gray-600 mt-1">Size: {{ item.size }}</p>
                    
                    <div class="flex items-center justify-between mt-4">
                      <div class="flex items-center border border-gray-300 rounded-md">
                        <button 
                          @click="updateQuantity(item.product_id, Math.max(1, item.quantity - 1))"
                          class="px-3 py-1 text-gray-600 hover:bg-gray-100 focus:outline-none"
                          :disabled="item.quantity <= 1"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                          </svg>
                        </button>
                        <span class="px-3 py-1 min-w-[40px] text-center">{{ item.quantity }}</span>
                        <button 
                          @click="updateQuantity(item.product_id, item.quantity + 1)"
                          class="px-3 py-1 text-gray-600 hover:bg-gray-100 focus:outline-none"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                          </svg>
                        </button>
                      </div>
                      
                      <button 
                        @click="removeItem(item.product_id)"
                        class="text-red-600 hover:text-red-800 text-sm font-medium focus:outline-none transition"
                      >
                        Remove
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="flex justify-between">
            <router-link
              to="/products"
              class="text-blue-600 hover:text-blue-800 font-medium flex items-center focus:outline-none transition"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Continue Shopping
            </router-link>
            
            <button
              @click="clearCart"
              class="text-gray-600 hover:text-gray-800 font-medium focus:outline-none transition"
            >
              Clear Cart
            </button>
          </div>
        </div>
        
        <!-- Order summary -->
        <div>
          <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold mb-6">Order Summary</h2>
            
            <div class="space-y-4 mb-6">
              <div class="flex justify-between">
                <span class="text-gray-600">Subtotal ({{ totalItems }} items)</span>
                <span>${{ cartSubtotal.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Shipping</span>
                <span>Free</span>
              </div>
              <div v-if="discount > 0" class="flex justify-between">
                <span class="text-gray-600">Discount</span>
                <span class="text-green-600">-${{ discount.toFixed(2) }}</span>
              </div>
            </div>
            
            <div class="border-t border-gray-200 pt-4 mb-6">
              <div class="flex justify-between font-semibold text-lg">
                <span>Total</span>
                <span>${{ cartTotal.toFixed(2) }}</span>
              </div>
            </div>
            
            <button
              @click="proceedToCheckout"
              class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition mb-4"
              :disabled="cartItems.length === 0"
            >
              Proceed to Checkout
            </button>
            
            <!-- Promo code input -->
            <div>
              <div class="flex mb-2">
                <input
                  v-model="promoCode"
                  type="text"
                  class="flex-grow border border-gray-300 px-3 py-2 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                  placeholder="Promo code"
                />
                <button
                  @click="applyPromoCode"
                  class="bg-gray-200 hover:bg-gray-300 px-4 py-2 text-gray-800 font-medium rounded-r-md transition"
                >
                  Apply
                </button>
              </div>
              <p v-if="promoError" class="text-red-600 text-sm">{{ promoError }}</p>
              <p v-if="promoSuccess" class="text-green-600 text-sm">{{ promoSuccess }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import Header from '@/components/Header.vue';

export default {
  name: 'Cart',
  components: {
    Header
  },
  setup() {
    const router = useRouter();
    const store = useStore();
    
    const loading = ref(true);
    const cartItems = ref([]);
    const promoCode = ref('');
    const promoError = ref('');
    const promoSuccess = ref('');
    const discount = ref(0);
    
    // Get cart items from localStorage
    const loadCartItems = () => {
      loading.value = true;
      try {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        cartItems.value = cart;
      } catch (error) {
        console.error('Error loading cart data:', error);
        cartItems.value = [];
      } finally {
        loading.value = false;
      }
    };
    
    // Save cart items to localStorage
    const saveCartItems = () => {
      localStorage.setItem('cart', JSON.stringify(cartItems.value));
    };
    
    // Computed properties
    const totalItems = computed(() => {
      return cartItems.value.reduce((total, item) => total + item.quantity, 0);
    });
    
    const cartSubtotal = computed(() => {
      return cartItems.value.reduce((total, item) => total + (item.price * item.quantity), 0);
    });
    
    const cartTotal = computed(() => {
      return cartSubtotal.value - discount.value;
    });
    
    // Methods
    const updateQuantity = (productId, quantity) => {
      const itemIndex = cartItems.value.findIndex(item => item.product_id === productId);
      if (itemIndex !== -1) {
        cartItems.value[itemIndex].quantity = quantity;
        saveCartItems();
      }
    };
    
    const removeItem = (productId) => {
      cartItems.value = cartItems.value.filter(item => item.product_id !== productId);
      saveCartItems();
    };
    
    const clearCart = () => {
      cartItems.value = [];
      saveCartItems();
    };
    
    const applyPromoCode = () => {
      // Reset messages
      promoError.value = '';
      promoSuccess.value = '';
      
      // Simple promo code validation and application
      if (!promoCode.value) {
        promoError.value = 'Please enter a promo code';
        return;
      }
      
      // Example promo codes
      if (promoCode.value.toUpperCase() === 'DISCOUNT10') {
        discount.value = cartSubtotal.value * 0.1;
        promoSuccess.value = '10% discount applied successfully!';
      } else if (promoCode.value.toUpperCase() === 'FREESHIP') {
        // Shipping is already free, but we can acknowledge the code
        promoSuccess.value = 'Free shipping promo code applied!';
      } else {
        promoError.value = 'Invalid promo code';
      }
    };
    
    const proceedToCheckout = () => {
      // Check if user is logged in
      const isLoggedIn = store.getters['auth/isAuthenticated'];
      
      // If not logged in, redirect to login page with return URL
      if (!isLoggedIn) {
        router.push({ 
          path: '/auth/login', 
          query: { redirect: '/checkout/shipping' } 
        });
        return;
      }
      
      // If logged in, proceed to checkout
      router.push('/checkout/shipping');
    };
    
    // Load cart items on component mount
    onMounted(() => {
      loadCartItems();
    });
    
    return {
      loading,
      cartItems,
      promoCode,
      promoError,
      promoSuccess,
      discount,
      totalItems,
      cartSubtotal,
      cartTotal,
      updateQuantity,
      removeItem,
      clearCart,
      applyPromoCode,
      proceedToCheckout
    };
  }
};
</script> 