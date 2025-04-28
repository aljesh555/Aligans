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
      if (isLoggedIn.value) {
        router.push('/checkout/shipping');
      } else {
        showLoginPrompt.value = true;
      }
    };
    
    return {
      cartItems,
      cartItemCount,
      cartSubtotal,
      isCartEmpty,
      loading,
      isLoggedIn,
      showLoginPrompt,
      increaseQuantity,
      decreaseQuantity,
      removeItem,
      clearCart,
      getImageUrl,
      proceedToCheckout
    };
  }
};
</script> 