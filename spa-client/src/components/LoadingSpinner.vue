<template>
  <div class="flex flex-col items-center justify-center">
    <div 
      class="rounded-full border-t-2 border-b-2"
      :class="[
        sizeClasses.spinner,
        colorClass
      ]"
    ></div>
    <p 
      v-if="message" 
      class="mt-4"
      :class="sizeClasses.text"
    >
      {{ message }}
    </p>
  </div>
</template>

<script>
export default {
  name: 'LoadingSpinner',
  props: {
    message: {
      type: String,
      default: null
    },
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    color: {
      type: String,
      default: 'blue',
      validator: (value) => ['blue', 'red', 'green', 'yellow', 'gray'].includes(value)
    }
  },
  computed: {
    sizeClasses() {
      const sizes = {
        'sm': {
          spinner: 'h-6 w-6',
          text: 'text-sm'
        },
        'md': {
          spinner: 'h-12 w-12',
          text: 'text-base'
        },
        'lg': {
          spinner: 'h-16 w-16',
          text: 'text-lg'
        }
      };
      return sizes[this.size];
    },
    colorClass() {
      const colors = {
        'blue': 'border-blue-500',
        'red': 'border-red-500',
        'green': 'border-green-500',
        'yellow': 'border-yellow-500',
        'gray': 'border-gray-500'
      };
      return colors[this.color];
    }
  }
}
</script>

<style scoped>
div[class*='border'] {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style> 