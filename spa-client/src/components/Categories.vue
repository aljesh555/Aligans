<template>
  <section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-center mb-8">Shop by Category</h2>
      
      <div v-if="loading" class="flex justify-center">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
      </div>
      
      <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ error }}
      </div>
      
      <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div v-for="category in categories" :key="category.id" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
          <div class="h-40 bg-indigo-100 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
          <div class="p-4">
            <h3 class="font-bold text-lg mb-1">{{ category.name }}</h3>
            <p class="text-gray-600 text-sm">{{ category.products_count }} products</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'Categories',
  data() {
    return {
      categories: [],
      loading: true,
      error: null
    }
  },
  mounted() {
    this.fetchCategories()
  },
  methods: {
    async fetchCategories() {
      try {
        this.loading = true
        const response = await fetch('http://localhost:8000/api/storefront/categories')
        if (!response.ok) {
          throw new Error('Failed to fetch categories')
        }
        const data = await response.json()
        this.categories = data.data || []
      } catch (err) {
        this.error = err.message
      } finally {
        this.loading = false
      }
    }
  }
}
</script> 