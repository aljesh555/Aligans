<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Advanced E-commerce Header -->
    <Header />
    
    <div class="container mx-auto px-4 py-8">
      <div class="max-w-4xl mx-auto">
        <!-- Checkout Steps -->
        <div class="flex justify-between mb-8">
          <div class="flex flex-col items-center">
            <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </div>
            <span class="mt-2 text-sm font-medium text-green-600">Shipping</span>
          </div>
          <div class="relative flex items-center flex-1 mx-4">
            <div class="h-1 flex-1 bg-green-500"></div>
          </div>
          <div class="flex flex-col items-center">
            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center">2</div>
            <span class="mt-2 text-sm font-medium text-blue-600">Payment</span>
          </div>
          <div class="relative flex items-center flex-1 mx-4">
            <div class="h-1 flex-1 bg-gray-300"></div>
          </div>
          <div class="flex flex-col items-center">
            <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center">3</div>
            <span class="mt-2 text-sm font-medium text-gray-600">Confirmation</span>
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-sm">
              <div class="p-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold">Payment Method</h1>
                <p class="text-gray-600 mt-1">Choose your payment method</p>
              </div>
              
              <div class="p-6">
                <form @submit.prevent="placeOrder">
                  <div v-if="errorMessage" class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                    {{ errorMessage }}
                  </div>
                
                  <!-- Payment Options -->
                  <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Payment Method</label>
                    
                    <div class="space-y-4">
                      <!-- Cash on Delivery -->
                      <div class="border rounded-md p-4" :class="selectedPayment === 'cod' ? 'border-blue-500 bg-blue-50' : 'border-gray-300'">
                        <label class="flex items-center cursor-pointer">
                          <input 
                            type="radio" 
                            name="paymentMethod" 
                            value="cod" 
                            v-model="selectedPayment"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                          >
                          <div class="ml-3">
                            <span class="block text-sm font-medium text-gray-700">Cash on Delivery</span>
                            <span class="block text-xs text-gray-500 mt-1">Pay with cash when your order is delivered</span>
                          </div>
                        </label>
                      </div>
                      
                      <!-- eSewa -->
                      <div class="border rounded-md p-4" :class="selectedPayment === 'esewa' ? 'border-blue-500 bg-blue-50' : 'border-gray-300'">
                        <label class="flex items-center cursor-pointer">
                          <input 
                            type="radio" 
                            name="paymentMethod" 
                            value="esewa" 
                            v-model="selectedPayment"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                          >
                          <div class="ml-3 flex items-center">
                            <div>
                              <span class="block text-sm font-medium text-gray-700">eSewa</span>
                              <span class="block text-xs text-gray-500 mt-1">Pay securely with your eSewa account</span>
                            </div>
                            <img src="https://esewa.com.np/common/images/esewa_logo.png" alt="eSewa" class="h-8 ml-auto" />
                          </div>
                        </label>
                      </div>
                      
                      <!-- Khalti -->
                      <div class="border rounded-md p-4" :class="selectedPayment === 'khalti' ? 'border-blue-500 bg-blue-50' : 'border-gray-300'">
                        <label class="flex items-center cursor-pointer">
                          <input 
                            type="radio" 
                            name="paymentMethod" 
                            value="khalti" 
                            v-model="selectedPayment"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                          >
                          <div class="ml-3 flex items-center">
                            <div>
                              <span class="block text-sm font-medium text-gray-700">Khalti</span>
                              <span class="block text-xs text-gray-500 mt-1">Pay securely with your Khalti wallet</span>
                            </div>
                            <img src="https://khalti.com/static/images/logo.png" alt="Khalti" class="h-8 ml-auto" />
                          </div>
                        </label>
                      </div>
                      
                      <!-- PhonePay -->
                      <div class="border rounded-md p-4" :class="selectedPayment === 'phonepay' ? 'border-blue-500 bg-blue-50' : 'border-gray-300'">
                        <label class="flex items-center cursor-pointer">
                          <input 
                            type="radio" 
                            name="paymentMethod" 
                            value="phonepay" 
                            v-model="selectedPayment"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                          >
                          <div class="ml-3 flex items-center">
                            <div>
                              <span class="block text-sm font-medium text-gray-700">PhonePay</span>
                              <span class="block text-xs text-gray-500 mt-1">Pay securely with your PhonePay wallet</span>
                            </div>
                            <img src="https://www.phonepe.com/webstatic/9126/root/images/logo.webp" alt="PhonePay" class="h-8 ml-auto" />
                          </div>
                        </label>
                      </div>
                      
                      <!-- Credit Card (Disabled for future implementation) -->
                      <div class="border border-gray-300 rounded-md p-4 bg-gray-50 opacity-60">
                        <label class="flex items-center">
                          <input 
                            type="radio" 
                            name="paymentMethod" 
                            value="credit" 
                            disabled
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                          >
                          <div class="ml-3">
                            <div class="flex items-center">
                              <span class="block text-sm font-medium text-gray-700">Credit Card</span>
                              <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Coming Soon</span>
                            </div>
                            <span class="block text-xs text-gray-500 mt-1">Pay securely with your credit card</span>
                          </div>
                        </label>
                      </div>
                      
                      <!-- PayPal (Disabled for future implementation) -->
                      <div class="border border-gray-300 rounded-md p-4 bg-gray-50 opacity-60">
                        <label class="flex items-center">
                          <input 
                            type="radio" 
                            name="paymentMethod" 
                            value="paypal" 
                            disabled
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                          >
                          <div class="ml-3">
                            <div class="flex items-center">
                              <span class="block text-sm font-medium text-gray-700">PayPal</span>
                              <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Coming Soon</span>
                            </div>
                            <span class="block text-xs text-gray-500 mt-1">Pay securely with your PayPal account</span>
                          </div>
                        </label>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Online Payment Details (for eSewa, Khalti, PhonePay) -->
                  <div v-if="['esewa', 'khalti', 'phonepay'].includes(selectedPayment)" class="mb-6 border border-gray-300 rounded-md p-4">
                    <div class="mb-4">
                      <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                      <input 
                        v-model="onlinePaymentPhone"
                        type="tel"
                        placeholder="Enter your phone number"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        required
                      >
                      <p class="text-xs text-gray-500 mt-1">Your registered phone number with {{ getPaymentMethodName() }}</p>
                    </div>
                    
                    <div v-if="selectedPayment === 'esewa'" class="mb-4">
                      <label class="block text-sm font-medium text-gray-700 mb-1">eSewa ID *</label>
                      <input 
                        v-model="esewaId"
                        type="text"
                        placeholder="Enter your eSewa ID"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        required
                      >
                    </div>
                  </div>
                  
                  <!-- Billing Address -->
                  <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                      <label class="block text-sm font-medium text-gray-700">Billing Address</label>
                      
                      <div class="flex items-center">
                        <input 
                          id="sameAsShipping" 
                          v-model="sameAsShipping" 
                          type="checkbox" 
                          class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                        <label for="sameAsShipping" class="ml-2 block text-sm text-gray-700">Same as shipping address</label>
                      </div>
                    </div>
                    
                    <div v-if="!sameAsShipping" class="border border-gray-300 rounded-md p-4">
                      <p class="text-sm text-gray-500 mb-4">Please enter your billing address details</p>
                      
                      <!-- Billing form fields would go here - simplified for this example -->
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                          <label for="billingFirstName" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                          <input 
                            id="billingFirstName"
                            v-model="billingFirstName"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            required
                          >
                        </div>
                        
                        <div>
                          <label for="billingLastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                          <input 
                            id="billingLastName"
                            v-model="billingLastName"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            required
                          >
                        </div>
                      </div>
                      
                      <div class="mb-4">
                        <label for="billingAddress" class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                        <input 
                          id="billingAddress"
                          v-model="billingAddress"
                          type="text"
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                          required
                        >
                      </div>
                      
                      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                          <label for="billingCity" class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                          <input 
                            id="billingCity"
                            v-model="billingCity"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            required
                          >
                        </div>
                        
                        <div>
                          <label for="billingState" class="block text-sm font-medium text-gray-700 mb-1">State/Province *</label>
                          <input 
                            id="billingState"
                            v-model="billingState"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            required
                          >
                        </div>
                        
                        <div>
                          <label for="billingZipCode" class="block text-sm font-medium text-gray-700 mb-1">ZIP/Postal Code *</label>
                          <input 
                            id="billingZipCode"
                            v-model="billingZipCode"
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            required
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Form actions -->
                  <div class="flex justify-between mt-8">
                    <button 
                      type="button"
                      @click="goBack"
                      class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition"
                    >
                      Return to Shipping
                    </button>
                    
                    <button 
                      type="submit"
                      class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
                      :disabled="loading"
                    >
                      <span v-if="loading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                      </span>
                      <span v-else>Place Order</span>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          
          <!-- Order Summary -->
          <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-sm sticky top-4">
              <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold">Order Summary</h2>
              </div>
              
              <div class="p-6">
                <div v-if="cartItems.length === 0" class="text-gray-500 text-center py-4">
                  Your cart is empty
                </div>
                
                <div v-else>
                  <!-- Cart Items -->
                  <div class="space-y-4 mb-6">
                    <div v-for="(item, index) in cartItems" :key="index" class="flex">
                      <div class="w-16 h-16 rounded border overflow-hidden bg-gray-50 mr-4 flex-shrink-0">
                        <img 
                          :src="item.image" 
                          :alt="item.name"
                          class="w-full h-full object-cover"
                          @error="handleImageError($event)"
                        >
                      </div>
                      <div class="flex-1">
                        <h3 class="text-sm font-medium text-gray-800">{{ item.name }}</h3>
                        <p class="text-xs text-gray-500">Qty: {{ item.quantity }}</p>
                        <div v-if="item.size || item.color" class="text-xs text-gray-500">
                          <span v-if="item.size">Size: {{ item.size }}</span>
                          <span v-if="item.size && item.color"> | </span>
                          <span v-if="item.color">Color: {{ item.color }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-900 mt-1">Rs. {{ (item.price * item.quantity).toFixed(2) }}</p>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Price Details -->
                  <div class="border-t border-gray-200 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-600">Subtotal</span>
                      <span class="font-medium">Rs. {{ subtotal.toFixed(2) }}</span>
                    </div>
                    
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-600">Shipping</span>
                      <span class="font-medium">{{ shipping > 0 ? 'Rs. ' + shipping.toFixed(2) : 'Free' }}</span>
                    </div>
                    
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-600">Tax</span>
                      <span class="font-medium">Rs. {{ tax.toFixed(2) }}</span>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-2 mt-2">
                      <div class="flex justify-between font-bold">
                        <span>Total</span>
                        <span>Rs. {{ orderTotal.toFixed(2) }}</span>
                      </div>
                    </div>
                  </div>
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
  name: 'CheckoutPayment',
  components: {
    Footer,
    Header
  },
  setup() {
    const router = useRouter();
    const loading = ref(false);
    const errorMessage = ref('');
    const selectedPayment = ref('cod');
    const sameAsShipping = ref(true);
    const onlinePaymentPhone = ref('');
    const esewaId = ref('');
    
    // Header state
    const mobileMenuOpen = ref(false);
    const categoriesDropdownOpen = ref(false);
    const accountDropdownOpen = ref(false);
    const cartDropdownOpen = ref(false);
    const isLoggedIn = ref(false);
    const user = ref(null);
    
    // Cart items
    const cartItems = ref([]);
    
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
    
    // Shipping information (from previous step)
    const shippingInfo = ref(null);
    
    // Billing information
    const billingFirstName = ref('');
    const billingLastName = ref('');
    const billingAddress = ref('');
    const billingCity = ref('');
    const billingState = ref('');
    const billingZipCode = ref('');
    
    // Computed properties for order summary
    const subtotal = computed(() => {
      return cartItems.value.reduce((total, item) => total + (item.price * item.quantity), 0);
    });
    
    const shipping = computed(() => {
      return subtotal.value >= 100 ? 0 : 10;
    });
    
    const tax = computed(() => {
      return subtotal.value * 0.07; // 7% tax
    });
    
    const orderTotal = computed(() => {
      return subtotal.value + shipping.value + tax.value;
    });
    
    // Go back to shipping page
    const goBack = () => {
      router.push('/checkout/shipping');
    };
    
    // Handle image loading errors
    const handleImageError = (event) => {
      event.target.src = 'https://via.placeholder.com/150?text=No+Image';
    };
    
    // Check if user is logged in
    const checkLoginStatus = () => {
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        user.value = JSON.parse(storedUser);
        isLoggedIn.value = true;
      }
    };
    
    // Load cart items and shipping info
    const loadData = () => {
      // Load cart items
      const savedCartItems = localStorage.getItem('cart');
      if (savedCartItems) {
        cartItems.value = JSON.parse(savedCartItems);
      }
      
      // Load shipping info
      const savedShippingInfo = localStorage.getItem('shippingInfo');
      if (savedShippingInfo) {
        shippingInfo.value = JSON.parse(savedShippingInfo);
      } else {
        // Redirect back to shipping page if no shipping info
        router.push('/checkout/shipping');
      }
    };
    
    // Check if cart is empty
    const checkCart = () => {
      const cart = localStorage.getItem('cart');
      if (!cart || JSON.parse(cart).length === 0) {
        router.push('/cart');
      }
    };
    
    // Check if user is logged in
    const checkAuth = () => {
      const user = localStorage.getItem('user');
      if (!user) {
        router.push({
          path: '/login',
          query: { redirect: '/checkout/payment' }
        });
      }
    };
    
    // Get payment method name for display
    const getPaymentMethodName = () => {
      switch (selectedPayment.value) {
        case 'esewa': return 'eSewa';
        case 'khalti': return 'Khalti';
        case 'phonepay': return 'PhonePay';
        default: return '';
      }
    };
    
    // Place order
    const placeOrder = () => {
      if (!selectedPayment.value) {
        errorMessage.value = 'Please select a payment method';
        return;
      }
      
      // Validate online payment fields
      if (['esewa', 'khalti', 'phonepay'].includes(selectedPayment.value)) {
        if (!onlinePaymentPhone.value) {
          errorMessage.value = 'Please enter your phone number';
          return;
        }
        
        if (selectedPayment.value === 'esewa' && !esewaId.value) {
          errorMessage.value = 'Please enter your eSewa ID';
          return;
        }
      }
      
      loading.value = true;
      
      // Prepare payment data based on selected method
      let paymentDetails = {};
      if (selectedPayment.value === 'cod') {
        paymentDetails = { method: 'Cash on Delivery' };
        processOrderWithLocalStorage(paymentDetails);
      } else if (selectedPayment.value === 'esewa') {
        paymentDetails = {
          method: 'eSewa',
          phone: onlinePaymentPhone.value,
          esewaId: esewaId.value
        };
        processESewaPayment();
      } else if (selectedPayment.value === 'khalti') {
        paymentDetails = {
          method: 'Khalti',
          phone: onlinePaymentPhone.value
        };
        processOrderWithLocalStorage(paymentDetails);
      } else if (selectedPayment.value === 'phonepay') {
        paymentDetails = {
          method: 'PhonePay',
          phone: onlinePaymentPhone.value
        };
        processOrderWithLocalStorage(paymentDetails);
      }
    };
    
    // Common order processing function used for COD and other methods
    const processOrderWithLocalStorage = (paymentDetails) => {
      // Prepare order data
      const orderData = {
        paymentMethod: selectedPayment.value,
        paymentDetails,
        shippingInfo: shippingInfo.value,
        billingInfo: sameAsShipping.value ? shippingInfo.value : {
          firstName: billingFirstName.value,
          lastName: billingLastName.value,
          address: billingAddress.value,
          city: billingCity.value,
          state: billingState.value,
          zipCode: billingZipCode.value
        },
        items: cartItems.value,
        subtotal: subtotal.value,
        shipping: shipping.value,
        tax: tax.value,
        total: orderTotal.value,
        orderDate: new Date().toISOString()
      };
      
      // Save order to localStorage (for demo purposes)
      localStorage.setItem('currentOrder', JSON.stringify(orderData));
      
      // Simulate API call to process payment
      setTimeout(() => {
        loading.value = false;
        
        // Generate random order ID
        const orderId = Math.floor(100000 + Math.random() * 900000);
        localStorage.setItem('orderId', orderId);
        
        // Clear cart after successful order
        localStorage.removeItem('cart');
        
        // Redirect to confirmation page
        router.push('/checkout/confirmation');
      }, 1500);
    };
    
    // Process eSewa payment
    const processESewaPayment = () => {
      // Save order information for later retrieval
      const orderData = {
        paymentMethod: 'esewa',
        paymentDetails: {
          method: 'eSewa',
          phone: onlinePaymentPhone.value,
          esewaId: esewaId.value,
          txnId: 'ALG' + Date.now()
        },
        shippingInfo: shippingInfo.value,
        billingInfo: sameAsShipping.value ? shippingInfo.value : {
          firstName: billingFirstName.value,
          lastName: billingLastName.value,
          address: billingAddress.value,
          city: billingCity.value,
          state: billingState.value,
          zipCode: billingZipCode.value
        },
        items: cartItems.value,
        subtotal: subtotal.value,
        shipping: shipping.value,
        tax: tax.value,
        total: orderTotal.value,
        orderDate: new Date().toISOString()
      };
      
      // Save the order data to localStorage
      localStorage.setItem('pendingEsewaOrder', JSON.stringify(orderData));
      
      // Instead of redirecting to eSewa, redirect to our mock eSewa page
      router.push('/checkout/mock-esewa');
    };
    
    onMounted(() => {
      checkAuth();
      checkCart();
      checkLoginStatus();
      loadData();
      
      // Check if we have an error query parameter
      const route = router.currentRoute.value;
      if (route.query.error) {
        if (route.query.error === 'payment_failed') {
          errorMessage.value = 'Your eSewa payment was not completed. Please try again.';
        } else if (route.query.error === 'missing_order_data') {
          errorMessage.value = 'There was an error processing your order. Please try again.';
        } else if (route.query.error === 'payment_canceled') {
          errorMessage.value = 'Your payment was canceled. Please try again or choose a different payment method.';
        }
      }
      
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
      errorMessage,
      selectedPayment,
      sameAsShipping,
      cartItems,
      subtotal,
      shipping,
      tax,
      orderTotal,
      billingFirstName,
      billingLastName,
      billingAddress,
      billingCity,
      billingState,
      billingZipCode,
      handleImageError,
      goBack,
      placeOrder,
      
      // Header state
      mobileMenuOpen,
      categoriesDropdownOpen,
      accountDropdownOpen,
      cartDropdownOpen,
      isLoggedIn,
      user,
      toggleAccountDropdown,
      toggleCartDropdown,
      logout,
      onlinePaymentPhone,
      esewaId,
      getPaymentMethodName
    };
  }
};
</script> 