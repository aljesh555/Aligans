<template>
  <section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Shop by Category</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Browse our wide selection of products across our most popular categories</p>
      </div>
      
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div v-for="category in categories" :key="category.id" class="group">
          <router-link :to="`/category/${category.id}`" class="block">
            <div class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl aspect-square">
              <img 
                :src="category.image" 
                :alt="category.name" 
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/0"></div>
              <div class="absolute bottom-0 left-0 right-0 p-4 md:p-6">
                <h3 class="text-white text-lg md:text-xl font-semibold">{{ category.name }}</h3>
                <p class="text-gray-200 text-sm mt-1 opacity-0 transform translate-y-4 transition-all duration-300 group-hover:opacity-100 group-hover:translate-y-0">
                  {{ category.count }} Products
                </p>
              </div>
            </div>
          </router-link>
        </div>
      </div>
      
      <div class="text-center mt-12">
        <router-link 
          to="/categories" 
          class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium"
        >
          View all categories
          <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
          </svg>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'FeaturedCategories',
  data() {
    return {
      categories: [
        {
          id: 1,
          name: 'Electronics',
          count: 24,
          image: 'https://images.unsplash.com/photo-1498049794561-7780e7231661?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80'
        },
        {
          id: 2,
          name: 'Clothing',
          count: 36,
          image: 'https://images.unsplash.com/photo-1445205170230-053b83016050?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1171&q=80'
        },
        {
          id: 3,
          name: 'Home & Garden',
          count: 18,
          image: 'https://images.unsplash.com/photo-1556020685-ae41abfc9365?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80'
        },
        {
          id: 4,
          name: 'Sports',
          count: 12,
          image: 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80'
        },
        {
          id: 5,
          name: 'Beauty',
          count: 15,
          image: 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=880&q=80'
        },
        {
          id: 6,
          name: 'Toys & Games',
          count: 14,
          image: 'https://images.unsplash.com/photo-1516627145497-ae6968895b40?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1140&q=80'
        },
        {
          id: 7,
          name: 'Books',
          count: 22,
          image: 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80'
        },
        {
          id: 8,
          name: 'Jewelry',
          count: 9,
          image: 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80'
        }
      ]
    }
  }
}
</script> 