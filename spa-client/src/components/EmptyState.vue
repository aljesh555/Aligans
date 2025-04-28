<template>
  <div class="text-center py-8">
    <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-gray-100">
      <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
      </svg>
    </div>
    <h3 class="mt-4 text-lg font-medium text-gray-900">{{ title }}</h3>
    <p class="mt-2 text-sm text-gray-500">{{ message }}</p>
    
    <div v-if="actionText && actionHandler" class="mt-6">
      <button
        @click="actionHandler"
        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
      >
        {{ actionText }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EmptyState',
  props: {
    title: {
      type: String,
      default: 'No data found'
    },
    message: {
      type: String,
      default: 'There are no items to display at this time.'
    },
    actionText: {
      type: String,
      default: null
    },
    actionHandler: {
      type: Function,
      default: null
    },
    icon: {
      type: String,
      default: 'default',
      validator: (value) => ['default', 'search', 'data', 'filter'].includes(value)
    }
  }
}
</script> 