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
            <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </div>
            <span class="mt-2 text-sm font-medium text-green-600">Payment</span>
          </div>
          <div class="relative flex items-center flex-1 mx-4">
            <div class="h-1 flex-1 bg-green-500"></div>
          </div>
          <div class="flex flex-col items-center">
            <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </div>
            <span class="mt-2 text-sm font-medium text-green-600">Confirmation</span>
          </div>
        </div>
        
        <!-- Order Confirmation -->
        <div class="bg-white rounded-lg shadow-sm">
          <div class="p-8 text-center border-b border-gray-200">
            <div class="mb-4 flex justify-center">
              <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Order Confirmed!</h1>
            <p class="text-lg text-gray-600 mt-2">Thank you for your purchase</p>
            <p class="text-gray-500 mt-1">Order #{{ orderId }}</p>
          </div>
          
          <div class="p-6">
            <div v-if="loading" class="py-12 flex justify-center">
              <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </div>
            
            <div v-else>
              <!-- Order Details -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Order Information -->
                <div>
                  <h2 class="text-lg font-semibold mb-4">Order Information</h2>
                  
                  <div class="space-y-4">
                    <div>
                      <h3 class="text-sm font-medium text-gray-500 mb-1">Order Date</h3>
                      <p class="text-gray-800">{{ formatDate(order.orderDate) }}</p>
                    </div>
                    
                    <div>
                      <h3 class="text-sm font-medium text-gray-500 mb-1">Payment Method</h3>
                      <p class="text-gray-800">{{ formatPaymentMethod(order.paymentMethod) }}</p>
                    </div>
                    
                    <div v-if="order.paymentMethod === 'cod'">
                      <h3 class="text-sm font-medium text-gray-500 mb-1">Payment Status</h3>
                      <p class="text-gray-800">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                          Unpaid
                        </span>
                      </p>
                    </div>
                    
                    <div>
                      <h3 class="text-sm font-medium text-gray-500 mb-1">Order Status</h3>
                      <p class="text-gray-800">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                          Processing
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
                
                <!-- Shipping Information -->
                <div>
                  <h2 class="text-lg font-semibold mb-4">Shipping Information</h2>
                  
                  <div class="space-y-4">
                    <div>
                      <h3 class="text-sm font-medium text-gray-500 mb-1">Shipping Address</h3>
                      <p class="text-gray-800">
                        {{ order.shippingInfo.firstName }} {{ order.shippingInfo.lastName }}<br>
                        {{ order.shippingInfo.address }}<br>
                        <template v-if="order.shippingInfo.addressLine2">
                          {{ order.shippingInfo.addressLine2 }}<br>
                        </template>
                        {{ order.shippingInfo.city }}, {{ order.shippingInfo.state }} {{ order.shippingInfo.zipCode }}<br>
                        {{ getCountryName(order.shippingInfo.country) }}
                      </p>
                    </div>
                    
                    <div>
                      <h3 class="text-sm font-medium text-gray-500 mb-1">Contact Information</h3>
                      <p class="text-gray-800">
                        {{ order.shippingInfo.email }}<br>
                        {{ order.shippingInfo.phone }}
                      </p>
                    </div>
                    
                    <div v-if="order.shippingInfo.specialInstructions">
                      <h3 class="text-sm font-medium text-gray-500 mb-1">Special Instructions</h3>
                      <p class="text-gray-800">{{ order.shippingInfo.specialInstructions }}</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Order Summary -->
              <div class="mt-8">
                <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
                
                <div class="border border-gray-200 rounded-md">
                  <!-- Order Items -->
                  <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Product
                          </th>
                          <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Price
                          </th>
                          <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Quantity
                          </th>
                          <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                          </th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(item, index) in order.items" :key="index">
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                              <div class="h-10 w-10 flex-shrink-0 rounded overflow-hidden">
                                <img :src="item.image" :alt="item.name" class="h-10 w-10 object-cover" @error="handleImageError($event)">
                              </div>
                              <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ item.name }}</div>
                                <div v-if="item.size || item.color" class="text-sm text-gray-500">
                                  <span v-if="item.size">Size: {{ item.size }}</span>
                                  <span v-if="item.size && item.color"> | </span>
                                  <span v-if="item.color">Color: {{ item.color }}</span>
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                            ${{ item.price.toFixed(2) }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                            {{ item.quantity }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            ${{ (item.price * item.quantity).toFixed(2) }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  
                  <!-- Order Totals -->
                  <div class="border-t border-gray-200 px-6 py-4">
                    <div class="flex justify-between text-sm mb-2">
                      <span class="text-gray-600">Subtotal</span>
                      <span class="font-medium">${{ order.subtotal.toFixed(2) }}</span>
                    </div>
                    
                    <div class="flex justify-between text-sm mb-2">
                      <span class="text-gray-600">Shipping</span>
                      <span class="font-medium">{{ order.shipping > 0 ? '$' + order.shipping.toFixed(2) : 'Free' }}</span>
                    </div>
                    
                    <div class="flex justify-between text-sm mb-2">
                      <span class="text-gray-600">Tax</span>
                      <span class="font-medium">${{ order.tax.toFixed(2) }}</span>
                    </div>
                    
                    <div class="flex justify-between font-bold text-lg border-t border-gray-200 pt-4 mt-2">
                      <span>Total</span>
                      <span>${{ order.total.toFixed(2) }}</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Next Steps -->
              <div class="mt-8 bg-blue-50 p-6 rounded-md">
                <h2 class="text-lg font-semibold mb-2">What's Next?</h2>
                <p class="text-gray-600 mb-4">We've sent a confirmation email to <strong>{{ order.shippingInfo.email }}</strong> with your order details.</p>
                
                <ul class="space-y-2 text-gray-600 mb-4">
                  <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Your order will be processed within 24 hours.</span>
                  </li>
                  <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>You'll receive another email when your order ships.</span>
                  </li>
                  <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>You can track your order in the <router-link to="/account/orders" class="text-blue-600 hover:underline">orders section</router-link> of your account.</span>
                  </li>
                </ul>
              </div>
              
              <!-- Actions -->
              <div class="mt-8 flex justify-center md:justify-end space-x-4">
                <router-link to="/" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                  Continue Shopping
                </router-link>
                
                <button 
                  @click="downloadInvoice" 
                  class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  Download Invoice
                </button>
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
import { useRouter, useRoute } from 'vue-router';
import Footer from '@/components/layout/Footer.vue';
import Header from '@/components/Header.vue';
import OrderService from '@/services/OrderService';
import axios from 'axios';

export default {
  name: 'CheckoutConfirmation',
  components: {
    Footer,
    Header
  },
  setup() {
    const router = useRouter();
    const loading = ref(true);
    const order = ref(null);
    const orderId = ref('');
    const apiError = ref(null);
    
    // Header state
    const mobileMenuOpen = ref(false);
    const categoriesDropdownOpen = ref(false);
    const accountDropdownOpen = ref(false);
    const cartDropdownOpen = ref(false);
    const isLoggedIn = ref(false);
    const user = ref(null);
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
    
    // Get country name from country code
    const getCountryName = (code) => {
      const countries = {
        'US': 'United States',
        'CA': 'Canada',
        'UK': 'United Kingdom',
        'AU': 'Australia',
        'FR': 'France',
        'DE': 'Germany',
        'JP': 'Japan',
        'CN': 'China',
        'IN': 'India',
        'BR': 'Brazil',
        'NP': 'Nepal'
      };
      
      return countries[code] || code;
    };
    
    // Format date
    const formatDate = (dateString) => {
      const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      };
      
      return new Date(dateString).toLocaleDateString(undefined, options);
    };
    
    // Format payment method
    const formatPaymentMethod = (method) => {
      const methods = {
        'cod': 'Cash on Delivery',
        'credit': 'Credit Card',
        'paypal': 'PayPal',
        'esewa': 'eSewa',
        'khalti': 'Khalti',
        'phonepay': 'PhonePay'
      };
      
      return methods[method] || method;
    };
    
    // Handle image loading errors
    const handleImageError = (event) => {
      event.target.src = 'https://via.placeholder.com/150?text=No+Image';
    };
    
    // Submit order to backend
    const submitOrderToBackend = async () => {
      try {
        // Get order data from localStorage
        const orderData = localStorage.getItem('currentOrder');
        if (!orderData) {
          console.error('ERROR: No order data found in localStorage');
          throw new Error('No order data found');
        }
        
        console.log('Retrieved order data from localStorage:', JSON.parse(orderData));
        const parsedOrder = JSON.parse(orderData);
        
        // Check if we have shipping form ID in localStorage
        const shippingFormId = localStorage.getItem('shippingFormId');
        if (!shippingFormId) {
          console.error('ERROR: No shipping form ID found in localStorage');
          throw new Error('Shipping information is required. Please go back to shipping step.');
        }
        
        // Prepare API request data - simplified version
        const apiOrderData = {
          payment_method: parsedOrder.paymentMethod,
          shipping_form_id: shippingFormId,
          total_amount: parsedOrder.total,
          items: parsedOrder.items.map(item => ({
            product_id: item.id,
            quantity: item.quantity,
            size: item.size,
            color: item.color,
            variant_details: item.options || {}
          }))
        };
        
        console.log('Sending simplified API request data:', apiOrderData);
        
        // Submit order data to API
        console.log('Making API call to: /api/checkout');
        const response = await OrderService.submitOrder(apiOrderData);
        
        console.log('API Response:', response.data);
        
        // Return the new order ID from API
        return response.data.data.order.id;
      } catch (error) {
        console.error('Error submitting order to backend:', error);
        console.error('Error details:', {
          message: error.message,
          response: error.response?.data,
          status: error.response?.status
        });
        apiError.value = error.response?.data?.message || 'Failed to store order in database.';
        return null;
      }
    };
    
    // Load order data
    const loadOrderData = async () => {
      loading.value = true;
      
      try {
        let dbOrderId;
        
        // Get order data from localStorage
        const orderData = localStorage.getItem('currentOrder');
        const orderIdData = localStorage.getItem('orderId');
        
        if (orderData && orderIdData) {
          order.value = JSON.parse(orderData);
          orderId.value = orderIdData;
          
          // Try to submit order to backend database if we have a local order
          dbOrderId = await submitOrderToBackend();
          
          if (dbOrderId) {
            console.log('Order stored successfully in database with ID:', dbOrderId);
            
            // Store the database order ID for future reference
            localStorage.setItem('dbOrderId', dbOrderId.toString());
            
            // Update local orderId to match database ID
            orderId.value = dbOrderId.toString();
            
            // Try to fetch order details from API to confirm
            try {
              const apiOrderResponse = await OrderService.getOrderDetails(dbOrderId);
              console.log('API order data:', apiOrderResponse.data);
            } catch (apiError) {
              console.error('Could not fetch order from API:', apiError);
            }
          }
        } else {
          // No order data found, check if we have a database order ID
          const storedDbOrderId = localStorage.getItem('dbOrderId');
          
          if (storedDbOrderId) {
            // Try to fetch order details from API
            try {
              const apiOrderResponse = await OrderService.getOrderDetails(storedDbOrderId);
              
              if (apiOrderResponse.data.success) {
                // Use the order data from API
                const apiOrder = apiOrderResponse.data.data;
                
                // Format order data to match expected structure
                order.value = {
                  orderNumber: apiOrder.order_number,
                  paymentMethod: apiOrder.payment_method,
                  orderDate: apiOrder.created_at,
                  shippingInfo: {
                    firstName: apiOrder.shipping_name.split(' ')[0],
                    lastName: apiOrder.shipping_name.split(' ').slice(1).join(' '),
                    address: apiOrder.shipping_address_line1,
                    addressLine2: apiOrder.shipping_address_line2,
                    city: apiOrder.shipping_city,
                    state: apiOrder.shipping_state,
                    zipCode: apiOrder.shipping_postal_code,
                    country: apiOrder.shipping_country,
                    email: apiOrder.shipping_email,
                    phone: apiOrder.shipping_phone,
                    specialInstructions: apiOrder.customer_notes
                  },
                  items: apiOrder.order_items.map(item => ({
                    id: item.product_id,
                    name: item.product_name,
                    price: parseFloat(item.unit_price),
                    quantity: item.quantity,
                    size: item.size,
                    color: item.color,
                    image: item.product?.image_url || 'https://via.placeholder.com/150'
                  })),
                  subtotal: parseFloat(apiOrder.subtotal_amount),
                  shipping: parseFloat(apiOrder.shipping_amount),
                  tax: parseFloat(apiOrder.tax_amount),
                  total: parseFloat(apiOrder.total_amount)
                };
                
                orderId.value = storedDbOrderId;
              }
            } catch (fetchError) {
              console.error('Error fetching order from API:', fetchError);
              // Redirect to home if we can't get the order
              router.push('/');
              return;
            }
          } else {
            // No data in localStorage or database, redirect to home
            router.push('/');
            return;
          }
        }
      } catch (error) {
        console.error('Error loading order data:', error);
        apiError.value = 'Failed to load order data. Please try again.';
      } finally {
        // Load complete
        loading.value = false;
      }
    };
    
    // Download invoice
    const downloadInvoice = async () => {
      // Check if we have a database order ID
      const dbOrderId = localStorage.getItem('dbOrderId') || orderId.value;
      
      if (!dbOrderId) {
        alert('Cannot generate invoice. Order not found in database.');
        return;
      }
      
      try {
        // Show loading state
        loading.value = true;
        
        // Check if user is logged in
        const token = localStorage.getItem('token');
        if (!token) {
          alert('Please log in to download your invoice.');
          router.push('/login?redirect=' + router.currentRoute.value.fullPath);
          return;
        }
        
        await OrderService.downloadInvoice(dbOrderId);
        
      } catch (error) {
        console.error('Error downloading invoice:', error);
        
        // Show user-friendly error message
        let errorMessage = 'Failed to download invoice. Please try again later.';
        
        if (error.message) {
          errorMessage = error.message;
        }
        
        alert(errorMessage);
        
        // If unauthorized, redirect to login
        if (error.message && (error.message.includes('authorized') || error.message.includes('log in'))) {
          router.push('/login?redirect=' + router.currentRoute.value.fullPath);
        }
      } finally {
        loading.value = false;
      }
    };
    
    // Computed properties for cart
    const subtotal = computed(() => {
      return cartItems.value.reduce((total, item) => total + (item.price * item.quantity), 0);
    });
    
    // Check if user is logged in
    const checkLoginStatus = () => {
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        user.value = JSON.parse(storedUser);
        isLoggedIn.value = true;
      }
    };
    
    // Load cart items
    const loadCartItems = () => {
      const savedCart = localStorage.getItem('cart');
      if (savedCart) {
        cartItems.value = JSON.parse(savedCart);
      }
    };
    
    onMounted(() => {
      loadOrderData();
      checkLoginStatus();
      loadCartItems();
      
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
      order,
      orderId,
      apiError,
      getCountryName,
      formatDate,
      formatPaymentMethod,
      handleImageError,
      downloadInvoice,
      
      // Header state
      mobileMenuOpen,
      categoriesDropdownOpen,
      accountDropdownOpen,
      cartDropdownOpen,
      isLoggedIn,
      user,
      cartItems,
      subtotal,
      toggleAccountDropdown,
      toggleCartDropdown,
      logout
    };
  }
};
</script> 