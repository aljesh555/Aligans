<template>
  <div class="bg-gray-50 min-h-screen">
    <Header />
    
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-6">Your Cart</h1>
      
      <!-- Missing attributes modal -->
      <div v-if="showMissingAttributesModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showMissingAttributesModal = false"></div>
          
          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
          
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                  <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                  <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                    Size and Color Required
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500">
                      Please select both size and color for all products in your cart before proceeding to checkout. 
                      Return to the product page to make your selections.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button @click="goToProductPage" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                Go to Product Page
              </button>
              <button @click="showMissingAttributesModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
      
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
    const showMissingAttributesModal = ref(false);
    
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
      
      // Check if any product is missing size or color selection
      const missingRequiredAttributes = cartItems.value.filter(item => {
        // More robust check for missing size/color
        const needsSize = !item.size || item.size === null || item.size === '';
        const needsColor = !item.color || item.color === null || item.color === '';
        return needsSize || needsColor;
      });
      
      if (missingRequiredAttributes.length > 0) {
        // Show modal instead of alert
        showMissingAttributesModal.value = true;
        return;
      }
      
      // If logged in, proceed to checkout
      router.push('/checkout/shipping');
    };
    
    // Navigate to the product page of the first item with missing attributes
    const goToProductPage = () => {
      const missingAttributesItems = cartItems.value.filter(item => {
        const needsSize = !item.size || item.size === null || item.size === '';
        const needsColor = !item.color || item.color === null || item.color === '';
        return needsSize || needsColor;
      });
      
      if (missingAttributesItems.length > 0) {
        // Navigate to the first product that needs attributes
        const productId = missingAttributesItems[0].id || missingAttributesItems[0].product_id;
        router.push(`/products/${productId}`);
      } else {
        // Fallback to products page if no specific item found
        router.push('/products');
      }
      
      // Close the modal
      showMissingAttributesModal.value = false;
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
      proceedToCheckout,
      showMissingAttributesModal,
      goToProductPage
    };
  }
};
</script> 