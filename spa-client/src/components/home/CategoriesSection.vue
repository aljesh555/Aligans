<template>
  <section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Shop By Category</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Browse our wide selection of equipment and apparel across all major sports categories.</p>
      </div>
      
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
        <!-- Category Card: Soccer -->
        <div class="group relative overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
          <img 
            src="https://images.unsplash.com/photo-1553778263-73a83bab9b0c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
            alt="Soccer equipment" 
            class="w-full h-60 object-cover group-hover:scale-105 transition-transform duration-300"
          >
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-80"></div>
          <div class="absolute bottom-0 left-0 p-4 text-white">
            <h3 class="text-xl font-bold mb-1">Soccer</h3>
            <p class="text-sm text-gray-200 mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">Professional quality balls, cleats, and gear</p>
            <a href="#" class="text-sm font-medium text-white hover:underline">Shop Now →</a>
          </div>
        </div>

        <!-- Category Card: Basketball -->
        <div class="group relative overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
          <img 
            src="https://images.unsplash.com/photo-1518063319789-7217e6706b04?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
            alt="Basketball equipment" 
            class="w-full h-60 object-cover group-hover:scale-105 transition-transform duration-300"
          >
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-80"></div>
          <div class="absolute bottom-0 left-0 p-4 text-white">
            <h3 class="text-xl font-bold mb-1">Basketball</h3>
            <p class="text-sm text-gray-200 mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">Indoor and outdoor equipment for all ages</p>
            <a href="#" class="text-sm font-medium text-white hover:underline">Shop Now →</a>
          </div>
        </div>

        <!-- Category Card: Tennis -->
        <div class="group relative overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
          <img 
            src="https://images.unsplash.com/photo-1595435934249-5df7ed86e1c0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
            alt="Tennis equipment" 
            class="w-full h-60 object-cover group-hover:scale-105 transition-transform duration-300"
          >
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-80"></div>
          <div class="absolute bottom-0 left-0 p-4 text-white">
            <h3 class="text-xl font-bold mb-1">Tennis</h3>
            <p class="text-sm text-gray-200 mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">Premium rackets, balls, and court apparel</p>
            <a href="#" class="text-sm font-medium text-white hover:underline">Shop Now →</a>
          </div>
        </div>

        <!-- Category Card: Swimming -->
        <div class="group relative overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
          <img 
            src="https://images.unsplash.com/photo-1600077063357-c86544c0b2c9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
            alt="Swimming equipment" 
            class="w-full h-60 object-cover group-hover:scale-105 transition-transform duration-300"
          >
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-80"></div>
          <div class="absolute bottom-0 left-0 p-4 text-white">
            <h3 class="text-xl font-bold mb-1">Swimming</h3>
            <p class="text-sm text-gray-200 mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">Competition and recreational swimming gear</p>
            <a href="#" class="text-sm font-medium text-white hover:underline">Shop Now →</a>
          </div>
        </div>

        <!-- Category Card: Running -->
        <div class="group relative overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
          <img 
            src="https://images.unsplash.com/photo-1511886786222-8a600a03b731?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
            alt="Running equipment" 
            class="w-full h-60 object-cover group-hover:scale-105 transition-transform duration-300"
          >
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-80"></div>
          <div class="absolute bottom-0 left-0 p-4 text-white">
            <h3 class="text-xl font-bold mb-1">Running</h3>
            <p class="text-sm text-gray-200 mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">Performance shoes, clothing, and accessories</p>
            <a href="#" class="text-sm font-medium text-white hover:underline">Shop Now →</a>
          </div>
        </div>

        <!-- Category Card: Fitness -->
        <div class="group relative overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
          <img 
            src="https://images.unsplash.com/photo-1598733963971-ee1869dea4e4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
            alt="Fitness equipment" 
            class="w-full h-60 object-cover group-hover:scale-105 transition-transform duration-300"
          >
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-80"></div>
          <div class="absolute bottom-0 left-0 p-4 text-white">
            <h3 class="text-xl font-bold mb-1">Fitness</h3>
            <p class="text-sm text-gray-200 mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">Home and gym workout equipment</p>
            <a href="#" class="text-sm font-medium text-white hover:underline">Shop Now →</a>
          </div>
        </div>

        <!-- Category Card: Cycling -->
        <div class="group relative overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
          <img 
            src="https://images.unsplash.com/photo-1541625602330-2277a4c46182?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" 
            alt="Cycling equipment" 
            class="w-full h-60 object-cover group-hover:scale-105 transition-transform duration-300"
          >
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-80"></div>
          <div class="absolute bottom-0 left-0 p-4 text-white">
            <h3 class="text-xl font-bold mb-1">Cycling</h3>
            <p class="text-sm text-gray-200 mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">Road, mountain and urban bikes and accessories</p>
            <a href="#" class="text-sm font-medium text-white hover:underline">Shop Now →</a>
          </div>
        </div>

        <!-- View All Card -->
        <div class="group relative overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 bg-blue-600 hover:bg-blue-700">
          <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center text-white p-4">
              <h3 class="text-xl font-bold mb-2">View All Categories</h3>
              <p class="text-sm text-blue-100 mb-4">Explore our complete selection</p>
              <span class="inline-block border-2 border-white rounded-full p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'CategoriesSection'
}
</script>

<style scoped>
/* Any additional scoped styles */
</style> 