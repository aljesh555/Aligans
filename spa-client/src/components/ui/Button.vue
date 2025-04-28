<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :class="[
      'inline-flex items-center justify-center rounded-md font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors',
      sizeClass,
      variantClass,
      { 'opacity-50 cursor-not-allowed': disabled },
      { 'cursor-wait': loading }
    ]"
    @click="$emit('click', $event)"
  >
    <!-- Loading spinner -->
    <svg 
      v-if="loading" 
      class="animate-spin -ml-1 mr-2 h-4 w-4" 
      xmlns="http://www.w3.org/2000/svg" 
      fill="none" 
      viewBox="0 0 24 24"
    >
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    
    <!-- Left icon -->
    <span v-if="$slots.icon" class="mr-2">
      <slot name="icon"></slot>
    </span>
    
    <!-- Button text -->
    <slot></slot>
    
    <!-- Right icon -->
    <span v-if="$slots.trailingIcon" class="ml-2">
      <slot name="trailingIcon"></slot>
    </span>
  </button>
</template>

<script>
export default {
  name: 'Button',
  props: {
    type: {
      type: String,
      default: 'button'
    },
    variant: {
      type: String,
      default: 'primary',
      validator: (value) => ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'outline', 'link'].includes(value)
    },
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['xs', 'sm', 'md', 'lg', 'xl'].includes(value)
    },
    disabled: {
      type: Boolean,
      default: false
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    sizeClass() {
      const sizes = {
        'xs': 'px-2.5 py-1.5 text-xs',
        'sm': 'px-3 py-2 text-sm',
        'md': 'px-4 py-2 text-sm',
        'lg': 'px-4 py-2 text-base',
        'xl': 'px-6 py-3 text-base'
      };
      return sizes[this.size];
    },
    variantClass() {
      const variants = {
        'primary': 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
        'secondary': 'bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500',
        'success': 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
        'danger': 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        'warning': 'bg-yellow-500 hover:bg-yellow-600 text-white focus:ring-yellow-500',
        'info': 'bg-blue-400 hover:bg-blue-500 text-white focus:ring-blue-400',
        'light': 'bg-gray-100 hover:bg-gray-200 text-gray-800 focus:ring-gray-200',
        'dark': 'bg-gray-800 hover:bg-gray-900 text-white focus:ring-gray-800',
        'outline': 'bg-transparent hover:bg-gray-100 text-gray-700 border border-gray-300 focus:ring-blue-500',
        'link': 'bg-transparent text-blue-600 hover:text-blue-800 hover:underline focus:ring-blue-500 p-0'
      };
      return variants[this.variant];
    }
  }
}
</script> 