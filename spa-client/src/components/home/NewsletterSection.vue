<template>
  <section class="py-16 bg-blue-700 text-white">
    <div class="container mx-auto px-4">
      <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-3xl font-bold mb-4">Join Our Newsletter</h2>
        <p class="text-lg mb-8">Sign up to receive updates on new products, special offers, and training tips.</p>
        <form @submit.prevent="submitForm" class="flex flex-col sm:flex-row gap-2">
          <input 
            v-model="email" 
            type="email" 
            placeholder="Your email address" 
            class="flex-grow px-4 py-3 rounded-md text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          >
          <button 
            type="submit" 
            class="bg-white text-blue-700 px-6 py-3 font-medium rounded-md hover:bg-blue-50 transition-colors duration-300 flex items-center justify-center"
            :disabled="isLoading"
          >
            <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Subscribe
          </button>
        </form>
        <div v-if="message" :class="['mt-4 text-sm', isSuccess ? 'text-green-200' : 'text-red-200']">
          {{ message }}
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'NewsletterSection',
  data() {
    return {
      email: '',
      isLoading: false,
      message: '',
      isSuccess: false
    }
  },
  methods: {
    async submitForm() {
      this.isLoading = true;
      this.message = '';
      
      try {
        // Simulate API call with timeout
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // Success response
        this.isSuccess = true;
        this.message = 'Thank you for subscribing! Check your email to confirm your subscription.';
        this.email = '';
      } catch (error) {
        // Error response
        this.isSuccess = false;
        this.message = 'There was an error processing your subscription. Please try again.';
      } finally {
        this.isLoading = false;
      }
    }
  }
}
</script> 