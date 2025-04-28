<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg text-center">
      <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-red-100 rounded-full">
        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </div>
      
      <h1 class="text-2xl font-bold text-gray-800 mb-2">Payment Failed</h1>
      <p class="text-gray-600 mb-6">Your eSewa payment was not completed successfully.</p>
      
      <div class="mb-4">
        <p class="text-sm text-gray-500">You will be redirected to the payment page in <span class="font-bold">{{ countdown }}</span> seconds.</p>
      </div>
      
      <button 
        @click="redirectToPayment"
        class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors"
      >
        Return to Payment Page
      </button>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';

export default {
  name: 'EsewaFailure',
  setup() {
    const router = useRouter();
    const countdown = ref(5);
    let countdownInterval = null;
    
    // Clear any pending payment data
    const clearPendingPayment = () => {
      localStorage.removeItem('pendingEsewaOrder');
      localStorage.removeItem('esewaTransactionId');
    };
    
    // Redirect to payment page
    const redirectToPayment = () => {
      clearPendingPayment();
      router.push('/checkout/payment?error=payment_failed');
    };
    
    // Start countdown timer
    const startCountdown = () => {
      countdownInterval = setInterval(() => {
        countdown.value--;
        
        if (countdown.value <= 0) {
          clearInterval(countdownInterval);
          redirectToPayment();
        }
      }, 1000);
    };
    
    onMounted(() => {
      // Log error details (if available)
      const params = new URLSearchParams(window.location.search);
      const errorCode = params.get('q');
      
      if (errorCode) {
        console.error('eSewa payment failed with error code:', errorCode);
      }
      
      clearPendingPayment();
      startCountdown();
    });
    
    onBeforeUnmount(() => {
      if (countdownInterval) {
        clearInterval(countdownInterval);
      }
    });
    
    return {
      countdown,
      redirectToPayment
    };
  }
};
</script> 