<template>
  <div class="bg-gray-50 min-h-screen">
    <Header />
    
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-8">Your Shopping Cart</h1>
      
      <!-- Loading state -->
      <div v-if="loading" class="text-center py-16">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
        <p class="mt-4 text-gray-600">Loading your cart...</p>
      </div>
      
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
      
      <!-- Empty cart state -->
      <div v-else-if="isCartEmpty" class="text-center py-16 bg-white rounded-lg shadow-sm">
        <div class="inline-block rounded-full bg-gray-100 p-6 mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
        </div>
        <h2 class="text-2xl font-semibold mb-4">Your cart is empty</h2>
        <p class="text-gray-600 mb-8">Looks like you haven't added any products to your cart yet.</p>
        <router-link to="/products" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium transition">
          Browse Products
        </router-link>
      </div>
      
      <!-- Cart with items -->
      <div v-else class="flex flex-col lg:flex-row gap-8">
        <!-- Cart items (left side) -->
        <div class="lg:w-8/12">
          <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Cart header -->
            <div class="bg-gray-50 px-6 py-4 border-b">
              <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold">
                  Cart Items ({{ cartItemCount }})
                </h2>
                <button 
                  @click="clearCart" 
                  class="text-sm text-gray-500 hover:text-red-600 transition"
                >
                  Clear Cart
                </button>
              </div>
            </div>
            
            <!-- Cart items list -->
            <div class="divide-y divide-gray-200">
              <div v-for="item in cartItems" :key="item.id" class="p-6 flex flex-col md:flex-row gap-4">
                <!-- Product image -->
                <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                  <img 
                    :src="getImageUrl(item.image_url)" 
                    :alt="item.name" 
                    class="h-full w-full object-cover object-center"
                  >
                </div>
                
                <!-- Product details -->
                <div class="flex flex-1 flex-col">
                  <div class="flex justify-between text-base font-medium text-gray-900">
                    <h3>
                      <router-link :to="`/products/${item.id}`">
                        {{ item.name }}
                      </router-link>
                    </h3>
                    <p class="ml-4">${{ (item.price * item.quantity).toFixed(2) }}</p>
                  </div>
                  <p class="mt-1 text-sm text-gray-500">Unit price: ${{ item.price.toFixed(2) }}</p>
                  
                  <div class="flex flex-1 items-end justify-between text-sm">
                    <div class="flex items-center border rounded-md">
                      <button 
                        @click="decreaseQuantity(item)" 
                        class="px-3 py-1 text-gray-600 hover:bg-gray-100"
                        :disabled="item.quantity <= 1"
                        :class="{ 'opacity-50 cursor-not-allowed': item.quantity <= 1 }"
                      >
                        -
                      </button>
                      <span class="px-3 py-1 border-x">{{ item.quantity }}</span>
                      <button 
                        @click="increaseQuantity(item)" 
                        class="px-3 py-1 text-gray-600 hover:bg-gray-100"
                      >
                        +
                      </button>
                    </div>
                    
                    <button 
                      @click="removeItem(item.id)" 
                      class="font-medium text-red-600 hover:text-red-500"
                    >
                      Remove
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Continue shopping link -->
          <div class="mt-4">
            <router-link 
              to="/products" 
              class="inline-flex items-center text-blue-600 hover:text-blue-700"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Continue Shopping
            </router-link>
          </div>
        </div>
        
        <!-- Order summary (right side) -->
        <div class="lg:w-4/12">
          <div class="bg-white rounded-lg shadow-sm p-6 sticky top-8">
            <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
            
            <div class="space-y-3 mb-6">
              <div class="flex justify-between">
                <span class="text-gray-600">Subtotal</span>
                <span>${{ cartSubtotal.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Shipping</span>
                <span>Free</span>
              </div>
              <div class="flex justify-between font-semibold pt-3 border-t border-gray-200">
                <span>Total</span>
                <span class="text-lg">${{ cartSubtotal.toFixed(2) }}</span>
              </div>
            </div>
            
            <button 
              @click="proceedToCheckout" 
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition"
            >
              Proceed to Checkout
            </button>
          </div>
        </div>
      </div>
      
      <!-- Login prompt modal (shown when guest tries to checkout) -->
      <div 
        v-if="showLoginPrompt" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
      >
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
          <h3 class="text-xl font-bold mb-4">Login Required</h3>
          <p class="text-gray-600 mb-6">
            You need to be logged in to proceed to checkout. Would you like to login or create an account?
          </p>
          <div class="flex justify-end gap-3">
            <button 
              @click="showLoginPrompt = false" 
              class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition"
            >
              Cancel
            </button>
            <router-link 
              to="/login" 
              class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition"
            >
              Login
            </router-link>
            <router-link 
              to="/register" 
              class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white rounded-md transition"
            >
              Register
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed, ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import Header from '@/components/Header.vue';

export default {
  name: 'CartPage',
  components: {
    Header
  },
  setup() {
    const store = useStore();
    const router = useRouter();
    const showLoginPrompt = ref(false);
    const showMissingAttributesModal = ref(false);
    
    // Get cart state from Vuex store
    const cartItems = computed(() => store.getters['cart/cartItems']);
    const cartItemCount = computed(() => store.getters['cart/cartItemCount']);
    const cartSubtotal = computed(() => store.getters['cart/cartSubtotal']);
    const isCartEmpty = computed(() => store.getters['cart/isCartEmpty']);
    const loading = computed(() => store.getters['cart/isLoading']);
    const isLoggedIn = computed(() => store.getters['auth/isLoggedIn']);
    
    // Handle quantity changes
    const increaseQuantity = (item) => {
      store.dispatch('cart/updateCartItem', {
        id: item.id,
        quantity: item.quantity + 1
      });
    };
    
    const decreaseQuantity = (item) => {
      if (item.quantity > 1) {
        store.dispatch('cart/updateCartItem', {
          id: item.id,
          quantity: item.quantity - 1
        });
      }
    };
    
    // Remove item from cart
    const removeItem = (productId) => {
      store.dispatch('cart/removeFromCart', productId);
    };
    
    // Clear entire cart
    const clearCart = () => {
      if (confirm('Are you sure you want to remove all items from your cart?')) {
        store.dispatch('cart/clearCart');
      }
    };
    
    // Get image URL helper
    const getImageUrl = (imagePath) => {
      if (!imagePath) {
        return 'https://via.placeholder.com/150';
      }
      
      if (imagePath.startsWith('http')) {
        return imagePath;
      }
      
      return `http://localhost:8000/storage/${imagePath}`;
    };
    
    // Handle checkout
    const proceedToCheckout = () => {
      // Check for items missing size or color
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
      
      if (isLoggedIn.value) {
        router.push('/checkout/shipping');
      } else {
        showLoginPrompt.value = true;
      }
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
        router.push(`/products/${missingAttributesItems[0].id}`);
      } else {
        // Fallback to products page if no specific item found
        router.push('/products');
      }
      
      // Close the modal
      showMissingAttributesModal.value = false;
    };
    
    return {
      cartItems,
      cartItemCount,
      cartSubtotal,
      isCartEmpty,
      loading,
      isLoggedIn,
      showLoginPrompt,
      showMissingAttributesModal,
      increaseQuantity,
      decreaseQuantity,
      removeItem,
      clearCart,
      getImageUrl,
      proceedToCheckout,
      goToProductPage
    };
  }
};
</script> 