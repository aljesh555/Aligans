<template>
  <div class="min-h-screen flex flex-col">
    <!-- eSewa Header -->
    <header class="bg-green-600 text-white py-4 px-6 shadow-md">
      <div class="container mx-auto flex justify-between items-center">
        <div class="flex items-center">
          <span class="text-2xl font-bold">eSewa</span>
          <span class="ml-2 text-xs bg-white text-green-600 px-2 py-1 rounded">SIMULATED</span>
        </div>
        <div>
          <span class="text-sm">Logged in as: {{ esewaId }}</span>
        </div>
      </div>
    </header>
    
    <!-- eSewa Payment Content -->
    <div class="flex-grow bg-gray-100 py-8">
      <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
          <div class="bg-green-50 p-4 border-b border-green-100">
            <h1 class="text-xl font-bold text-green-800">Payment Confirmation</h1>
            <p class="text-sm text-green-600">Transaction ID: {{ transactionId }}</p>
          </div>
          
          <div class="p-6">
            <div class="mb-6">
              <h2 class="text-lg font-medium text-gray-700 mb-2">Payment Details</h2>
              
              <div class="bg-gray-50 p-4 rounded border border-gray-200">
                <div class="flex justify-between py-2 border-b border-gray-200">
                  <span class="text-gray-600">Merchant</span>
                  <span class="font-medium">Aligans Sports Store</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                  <span class="text-gray-600">Order ID</span>
                  <span class="font-medium">{{ transactionId }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                  <span class="text-gray-600">Amount</span>
                  <span class="font-medium text-green-700">Rs. {{ amount.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between py-2">
                  <span class="text-gray-600">Date</span>
                  <span class="font-medium">{{ formatDate(new Date()) }}</span>
                </div>
              </div>
            </div>
            
            <div class="mb-6">
              <h2 class="text-lg font-medium text-gray-700 mb-2">Order Summary</h2>
              
              <div class="space-y-3 max-h-40 overflow-y-auto mb-4">
                <div v-for="(item, index) in orderItems" :key="index" class="flex items-center gap-3 p-2 bg-gray-50 rounded">
                  <div class="w-10 h-10 bg-gray-200 rounded flex-shrink-0">
                    <img 
                      :src="item.image" 
                      :alt="item.name" 
                      class="w-full h-full object-cover"
                      @error="handleImageError"
                    />
                  </div>
                  <div class="flex-grow overflow-hidden">
                    <p class="text-sm font-medium truncate">{{ item.name }}</p>
                    <p class="text-xs text-gray-500">{{ item.quantity }} × Rs. {{ item.price.toFixed(2) }}</p>
                  </div>
                </div>
              </div>
              
              <div class="border-t border-gray-200 pt-3">
                <div class="flex justify-between text-sm py-1">
                  <span class="text-gray-600">Subtotal</span>
                  <span>Rs. {{ subtotal.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between text-sm py-1">
                  <span class="text-gray-600">Shipping</span>
                  <span>{{ shipping > 0 ? 'Rs. ' + shipping.toFixed(2) : 'Free' }}</span>
                </div>
                <div class="flex justify-between text-sm py-1">
                  <span class="text-gray-600">Tax</span>
                  <span>Rs. {{ tax.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-base pt-2 border-t border-gray-200 mt-2">
                  <span>Total</span>
                  <span>Rs. {{ amount.toFixed(2) }}</span>
                </div>
              </div>
            </div>
            
            <div class="mb-6">
              <h2 class="text-lg font-medium text-gray-700 mb-2">Payment Method</h2>
              <div class="bg-gray-50 p-4 rounded border border-gray-200 flex items-center">
                <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center mr-3">
                  <span class="text-xl font-bold">e</span>
                </div>
                <div>
                  <p class="font-medium">eSewa Wallet</p>
                  <p class="text-xs text-gray-500">Balance: Rs. 50,000.00</p>
                </div>
              </div>
            </div>
            
            <div class="space-y-3">
              <button 
                @click="completePayment(true)" 
                class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition-colors"
              >
                Complete Payment
              </button>
              
              <button 
                @click="completePayment(false)" 
                class="w-full py-3 border border-red-500 text-red-500 hover:bg-red-50 font-medium rounded-md transition-colors"
              >
                Cancel Payment
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Loading Overlay -->
    <div v-if="processing" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full text-center">
        <div class="mx-auto w-16 h-16 border-4 border-green-600 border-t-transparent rounded-full animate-spin mb-4"></div>
        <h3 class="text-lg font-bold mb-2">Processing Payment</h3>
        <p class="text-gray-600">Please wait while we process your payment...</p>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

export default {
  name: 'MockEsewa',
  setup() {
    const router = useRouter();
    const pendingOrder = ref(null);
    const esewaId = ref('');
    const transactionId = ref('');
    const amount = ref(0);
    const orderItems = ref([]);
    const subtotal = ref(0);
    const shipping = ref(0);
    const tax = ref(0);
    const processing = ref(false);
    
    const loadOrderData = () => {
      const pendingOrderData = localStorage.getItem('pendingEsewaOrder');
      
      if (pendingOrderData) {
        pendingOrder.value = JSON.parse(pendingOrderData);
        
        // Extract relevant data from order
        esewaId.value = pendingOrder.value.paymentDetails.esewaId;
        transactionId.value = pendingOrder.value.paymentDetails.txnId;
        amount.value = pendingOrder.value.total;
        orderItems.value = pendingOrder.value.items;
        subtotal.value = pendingOrder.value.subtotal;
        shipping.value = pendingOrder.value.shipping;
        tax.value = pendingOrder.value.tax;
      } else {
        // No pending order, redirect back to checkout
        router.push('/checkout/payment');
      }
    };
    
    const formatDate = (date) => {
      return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      }).format(date);
    };
    
    const handleImageError = (event) => {
      event.target.src = 'https://via.placeholder.com/150?text=No+Image';
    };
    
    const completePayment = (success) => {
      processing.value = true;
      
      setTimeout(() => {
        if (success) {
          // Save as completed order
          localStorage.setItem('currentOrder', JSON.stringify(pendingOrder.value));
          
          // Generate random order ID
          const orderId = Math.floor(100000 + Math.random() * 900000);
          localStorage.setItem('orderId', orderId.toString());
          
          // Clean up
          localStorage.removeItem('pendingEsewaOrder');
          localStorage.removeItem('cart');
          
          // Redirect to confirmation page
          router.push('/checkout/confirmation');
        } else {
          // Payment canceled
          localStorage.removeItem('pendingEsewaOrder');
          
          // Redirect back to payment page with error
          router.push('/checkout/payment?error=payment_canceled');
        }
      }, 2000);
    };
    
    onMounted(() => {
      loadOrderData();
    });
    
    return {
      esewaId,
      transactionId,
      amount,
      orderItems,
      subtotal,
      shipping,
      tax,
      processing,
      formatDate,
      handleImageError,
      completePayment
    };
  }
};
</script> 