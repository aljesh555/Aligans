<template>
  <div 
    v-if="error" 
    class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md my-4"
    :class="[hasFontSize ? '' : 'text-base']"
  >
    <!-- Error Icon -->
    <div class="flex items-start">
      <div class="flex-shrink-0">
        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
      </div>
      <div class="ml-3">
        <div :class="[titleClass]">{{ title || 'Error' }}</div>
        <div class="mt-1" :class="[messageClass]">
          {{ message || error }}
        </div>
        
        <!-- Retry button if action provided -->
        <div v-if="retryAction" class="mt-3">
          <button 
            @click="retryAction" 
            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
          >
            <svg class="mr-1.5 h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
            </svg>
            {{ retryText || 'Try Again' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ErrorMessage',
  props: {
    error: {
      type: [String, Object],
      default: null
    },
    message: {
      type: String,
      default: null
    },
    title: {
      type: String,
      default: 'Error'
    },
    retryAction: {
      type: Function,
      default: null
    },
    retryText: {
      type: String,
      default: 'Try Again'
    },
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['sm', 'md', 'lg'].includes(value)
    }
  },
  computed: {
    titleClass() {
      return {
        'sm': 'text-sm font-medium text-red-800',
        'md': 'text-base font-medium text-red-800',
        'lg': 'text-lg font-medium text-red-800'
      }[this.size];
    },
    messageClass() {
      return {
        'sm': 'text-xs text-red-700',
        'md': 'text-sm text-red-700',
        'lg': 'text-base text-red-700'
      }[this.size];
    },
    hasFontSize() {
      return this.size !== 'md';
    }
  }
}
</script> 