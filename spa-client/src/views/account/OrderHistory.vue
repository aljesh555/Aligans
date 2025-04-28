<template>
  <div>
    <Header />
    <div class="container mx-auto px-4 py-8">
      <div class="max-w-5xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
          <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Order History</h1>
          <p class="text-gray-600 mt-2">View and track all your previous orders</p>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center py-12">
          <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-8">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm text-red-700">{{ error }}</p>
              <button @click="fetchOrders" class="mt-2 text-sm font-medium text-red-700 hover:text-red-600">
                Try again
              </button>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="orders.length === 0" class="bg-white rounded-lg shadow-sm p-8 text-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <h2 class="mt-4 text-lg font-medium text-gray-900">No orders yet</h2>
          <p class="mt-2 text-gray-500">You haven't placed any orders yet. Start shopping to see your orders here.</p>
          <router-link to="/products" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
            Browse Products
          </router-link>
        </div>

        <!-- Orders List -->
        <div v-else>
          <div class="bg-white shadow-sm rounded-lg overflow-hidden divide-y divide-gray-200">
            <div v-for="order in orders" :key="order.id" class="p-6">
              <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                <div>
                  <h3 class="text-lg font-medium text-gray-900">
                    Order #{{ order.id }}
                  </h3>
                  <p class="mt-1 text-sm text-gray-500">
                    Placed on {{ formatDate(order.created_at) }}
                  </p>
                </div>
                <div class="mt-4 md:mt-0">
                  <span 
                    class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium"
                    :class="{
                      'bg-green-100 text-green-800': order.status === 'completed',
                      'bg-yellow-100 text-yellow-800': order.status === 'processing',
                      'bg-blue-100 text-blue-800': order.status === 'pending',
                      'bg-red-100 text-red-800': order.status === 'cancelled',
                      'bg-purple-100 text-purple-800': order.status === 'refunded'
                    }"
                  >
                    {{ capitalizeFirstLetter(order.status) }}
                  </span>
                </div>
              </div>

              <div class="mt-6 border-t border-gray-100 pt-4">
                <div class="flex justify-between text-sm">
                  <span class="font-medium text-gray-900">Total</span>
                  <span class="font-medium text-gray-900">Rs. {{ Number(order.total_amount).toFixed(2) }}</span>
                </div>
              </div>

              <div class="mt-6 flex items-center justify-between">
                <button 
                  @click="viewOrderDetails(order)" 
                  class="text-sm font-medium text-blue-600 hover:text-blue-500"
                >
                  View Order Details
                </button>
              </div>

              <!-- Order Details Section (expandable) -->
              <div v-if="expandedOrderId === order.id" class="mt-6 bg-gray-50 p-4 rounded-md">
                <div v-if="orderDetails[order.id]">
                  <h4 class="text-sm font-medium text-gray-900 mb-4">Order Items</h4>
                  
                  <div v-for="item in orderDetails[order.id].orderItems || []" :key="item.id" class="flex py-2 border-b border-gray-200 last:border-0">
                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                      <img 
                        :src="item.product && item.product.image_url ? item.product.image_url : (item.image_url || '/images/product-placeholder.jpg')" 
                        :alt="item.product ? item.product.name : (item.product_name || 'Product')" 
                        class="h-full w-full object-cover object-center"
                      >
                    </div>
                    <div class="ml-4 flex flex-1 flex-col">
                      <div>
                        <div class="flex justify-between text-sm font-medium text-gray-900">
                          <h5>{{ item.product ? item.product.name : (item.product_name || 'Product') }}</h5>
                          <p class="ml-4">Rs. {{ Number(item.price || item.discounted_price || 0).toFixed(2) }}</p>
                        </div>
                      </div>
                      <div class="flex flex-1 items-end justify-between text-sm">
                        <p class="text-gray-500">Qty {{ item.quantity }}</p>
                        <p class="text-gray-500">Subtotal: Rs. {{ Number((item.price || item.discounted_price || 0) * item.quantity).toFixed(2) }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="mt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-600">Subtotal</span>
                      <span>Rs. {{ Number(order.total_amount).toFixed(2) }}</span>
                    </div>
                    
                    <!-- Payment Information -->
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-600">Payment Method</span>
                      <span class="capitalize">{{ order.payment_method || 'Not specified' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-600">Payment Status</span>
                      <span class="capitalize" :class="{
                        'text-green-600': order.payment_status === 'paid',
                        'text-yellow-600': order.payment_status === 'pending',
                        'text-red-600': order.payment_status === 'failed'
                      }">{{ order.payment_status || 'Not specified' }}</span>
                    </div>
                    
                    <div class="flex justify-between text-sm font-medium">
                      <span class="text-gray-900">Total</span>
                      <span class="text-gray-900">Rs. {{ Number(order.total_amount).toFixed(2) }}</span>
                    </div>
                  </div>
                </div>
                <div v-else-if="loadingDetails" class="py-4 text-center">
                  <svg class="animate-spin h-5 w-5 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <p class="mt-2 text-sm text-gray-500">Loading order details...</p>
                </div>
                <div v-else class="py-4 text-center">
                  <p class="text-sm text-gray-500">Could not load order details</p>
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
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Header from '@/components/Header.vue';
import Footer from '@/components/layout/Footer.vue';

export default {
  name: 'OrderHistory',
  components: {
    Header,
    Footer
  },
  setup() {
    const orders = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const expandedOrderId = ref(null);
    const orderDetails = ref({});
    const loadingDetails = ref(false);

    const fetchOrders = async () => {
      loading.value = true;
      error.value = null;
      
      try {
        // First try the user/orders endpoint
        let response = await axios.get('/api/user/orders');
        
        if (response.data && response.data.success) {
          orders.value = response.data.data;
        } else if (response.data && response.data.data) {
          // Handle direct data response format
          orders.value = response.data.data;
        } else {
          // Fallback to alternative endpoint if first one fails
          response = await axios.get('/api/orders');
          
          if (response.data && (response.data.success || response.data.data)) {
            orders.value = response.data.data || response.data;
          } else {
            error.value = 'Failed to load orders';
          }
        }
        
        console.log('Orders loaded:', orders.value.length);
      } catch (err) {
        console.error('Error fetching orders:', err);
        if (err.response && err.response.status === 401) {
          error.value = 'Please log in to view your orders';
        } else {
          error.value = 'Could not fetch your orders. Please try again later.';
        }
      } finally {
        loading.value = false;
      }
    };

    const viewOrderDetails = async (order) => {
      // Toggle expanded state
      if (expandedOrderId.value === order.id) {
        expandedOrderId.value = null;
        return;
      }
      
      expandedOrderId.value = order.id;
      
      // Check if we already have the details
      if (orderDetails.value[order.id]) {
        return;
      }
      
      loadingDetails.value = true;
      
      try {
        const response = await axios.get(`/api/orders/${order.id}`);
        if (response.data && (response.data.data || response.data.order)) {
          orderDetails.value[order.id] = response.data.data || response.data.order;
          
          // If the response doesn't have orderItems property, try to extract it
          if (!orderDetails.value[order.id].orderItems && response.data.order_items) {
            orderDetails.value[order.id].orderItems = response.data.order_items;
          }
        }
      } catch (err) {
        console.error('Error fetching order details:', err);
      } finally {
        loadingDetails.value = false;
      }
    };

    const formatDate = (dateString) => {
      if (!dateString) return '';
      
      const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      };
      
      return new Date(dateString).toLocaleDateString(undefined, options);
    };

    const capitalizeFirstLetter = (string) => {
      if (!string) return '';
      return string.charAt(0).toUpperCase() + string.slice(1);
    };

    onMounted(() => {
      fetchOrders();
    });

    return {
      orders,
      loading,
      error,
      expandedOrderId,
      orderDetails,
      loadingDetails,
      fetchOrders,
      viewOrderDetails,
      formatDate,
      capitalizeFirstLetter
    };
  }
};
</script>

<style scoped>
.bg-white {
  background-color: #ffffff;
}

.shadow-sm {
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.rounded-lg {
  border-radius: 0.5rem;
}

.rounded-md {
  border-radius: 0.375rem;
}

.rounded-full {
  border-radius: 9999px;
}

/* Animation for expanded content */
.mt-6 {
  transition: all 0.3s ease-in-out;
}

/* Improve buttons */
button.text-blue-600 {
  transition: all 0.2s ease;
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
}

button.text-blue-600:hover {
  background-color: #eef2ff;
}

/* Status color adjustments */
.bg-green-100 {
  background-color: #d1fae5;
}

.bg-yellow-100 {
  background-color: #fef3c7;
}

.bg-blue-100 {
  background-color: #dbeafe;
}

.bg-red-100 {
  background-color: #fee2e2;
}

.bg-purple-100 {
  background-color: #ede9fe;
}

/* Product images */
.h-16.w-16 img {
  transition: transform 0.3s ease;
}

.h-16.w-16:hover img {
  transform: scale(1.05);
}
</style> 