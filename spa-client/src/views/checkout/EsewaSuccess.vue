<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg text-center">
      <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-green-100 rounded-full">
        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
      </div>
      
      <h1 class="text-2xl font-bold text-gray-800 mb-2">Processing Payment</h1>
      <p class="text-gray-600 mb-6">Your eSewa payment is being verified. Please wait...</p>
      
      <div class="flex justify-center">
        <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
      </div>
    </div>
  </div>
</template>

<script>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';

export default {
  name: 'EsewaSuccess',
  setup() {
    const router = useRouter();
    
    // Simulate verification with the eSewa server
    // In a real implementation, you would verify the payment with the eSewa server API
    const verifyPayment = () => {
      // Get the eSewa payment details from URL query parameters
      const params = new URLSearchParams(window.location.search);
      const oid = params.get('oid'); // Order ID
      const amt = params.get('amt'); // Amount
      const refId = params.get('refId'); // eSewa Reference ID
      
      // Check if we have the required parameters
      if (!oid || !amt || !refId) {
        console.error('Missing required payment parameters');
        router.push('/checkout/payment?error=invalid_payment_data');
        return;
      }
      
      // Get the pending order data from localStorage
      const pendingOrderData = localStorage.getItem('pendingEsewaOrder');
      const txnId = localStorage.getItem('esewaTransactionId');
      
      if (!pendingOrderData || !txnId || txnId !== oid) {
        console.error('Order data mismatch or missing');
        router.push('/checkout/payment?error=missing_order_data');
        return;
      }
      
      // In a real implementation, you would make an API call to verify the payment
      // with eSewa's verification endpoint using the refId
      
      // For demo purposes, we'll simulate a successful verification
      // In production, this should be handled on your backend
      
      // Process the successful payment
      const pendingOrder = JSON.parse(pendingOrderData);
      
      // Add eSewa reference ID to payment details
      pendingOrder.paymentDetails.refId = refId;
      pendingOrder.paymentDetails.verified = true;
      
      // Save as completed order
      localStorage.setItem('currentOrder', JSON.stringify(pendingOrder));
      
      // Generate random order ID
      const orderId = Math.floor(100000 + Math.random() * 900000);
      localStorage.setItem('orderId', orderId.toString());
      
      // Clean up
      localStorage.removeItem('pendingEsewaOrder');
      localStorage.removeItem('esewaTransactionId');
      localStorage.removeItem('cart');
      
      // Redirect to confirmation page
      setTimeout(() => {
        router.push('/checkout/confirmation');
      }, 2000);
    };
    
    onMounted(() => {
      // Verify the payment when component is mounted
      verifyPayment();
    });
    
    return {};
  }
};
</script> 