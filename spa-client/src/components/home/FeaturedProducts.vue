<template>
  <section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
      <!-- Section heading -->
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Featured Products</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Discover our most popular gear selected for quality and performance, used by professionals worldwide.</p>
      </div>
      
      <!-- Products grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div 
          v-for="product in products" 
          :key="product.id"
          class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300"
        >
          <!-- Product image -->
          <div class="relative h-64 overflow-hidden group">
            <img 
              :src="product.image" 
              :alt="product.name"
              class="h-full w-full object-cover object-center transform group-hover:scale-110 transition-transform duration-500"
            />
            
            <!-- Sale badge -->
            <div 
              v-if="product.discountPercentage" 
              class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded"
            >
              -{{ product.discountPercentage }}%
            </div>
            
            <!-- Quick action buttons -->
            <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              <button 
                class="bg-white text-gray-800 p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition-colors"
                title="Quick view"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
              </button>
              <button 
                class="bg-white text-gray-800 p-2 rounded-full mx-1 hover:bg-indigo-600 hover:text-white transition-colors"
                title="Add to cart"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
              </button>
            </div>
          </div>
          
          <!-- Product info -->
          <div class="p-4">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="font-semibold text-lg mb-1">{{ product.name }}</h3>
                <p class="text-sm text-gray-500 mb-2">{{ product.category }}</p>
              </div>
              <div class="flex items-center">
                <div v-if="product.discountPercentage" class="flex items-center">
                  <span class="text-lg font-bold text-gray-900">${{ formatPrice(calculateDiscountedPrice(product)) }}</span>
                  <span class="text-sm text-gray-500 line-through ml-2">${{ formatPrice(product.price) }}</span>
                </div>
                <div v-else>
                  <span class="text-lg font-bold text-gray-900">${{ formatPrice(product.price) }}</span>
                </div>
              </div>
            </div>
            
            <!-- Rating stars -->
            <div class="flex items-center mt-2">
              <div class="flex">
                <template v-for="i in 5" :key="i">
                  <svg 
                    :class="[i <= product.rating ? 'text-yellow-400' : 'text-gray-300']"
                    class="w-4 h-4 fill-current" 
                    viewBox="0 0 24 24"
                  >
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                  </svg>
                </template>
              </div>
              <span class="text-xs text-gray-500 ml-1">({{ product.reviewCount }})</span>
            </div>
            
            <!-- Add to cart button -->
            <button class="w-full mt-4 bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition-colors">
              Add to Cart
            </button>
          </div>
        </div>
      </div>
      
      <!-- View all link -->
      <div class="text-center mt-10">
        <router-link 
          to="/products" 
          class="inline-flex items-center text-indigo-600 font-medium hover:text-indigo-800 transition-colors"
        >
          View All Products
          <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
          </svg>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'FeaturedProducts',
  data() {
    return {
      products: [
        {
          id: 1,
          name: 'Pro Tournament Basketball',
          category: 'Basketball',
          price: 89.99,
          discountPercentage: 15,
          rating: 4.8,
          reviewCount: 128,
          image: 'https://images.unsplash.com/photo-1612118745058-8c2b3d1652e5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80'
        },
        {
          id: 2,
          name: 'Elite Soccer Cleats',
          category: 'Soccer',
          price: 149.99,
          discountPercentage: 0,
          rating: 4.6,
          reviewCount: 94,
          image: 'https://images.unsplash.com/photo-1511886929837-354984b71424?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1064&q=80'
        },
        {
          id: 3,
          name: 'Premium Tennis Racket',
          category: 'Tennis',
          price: 199.99,
          discountPercentage: 10,
          rating: 4.9,
          reviewCount: 76,
          image: 'https://images.unsplash.com/photo-1617083934777-707af193c2fa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80'
        },
        {
          id: 4,
          name: 'Pro Running Shoes',
          category: 'Running',
          price: 129.99,
          discountPercentage: 0,
          rating: 4.7,
          reviewCount: 215,
          image: 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1087&q=80'
        }
      ]
    };
  },
  methods: {
    formatPrice(price) {
      return price.toFixed(2);
    },
    calculateDiscountedPrice(product) {
      if (!product.discountPercentage) return product.price;
      return product.price * (1 - product.discountPercentage / 100);
          originalPrice: 149.99,
          discount: 15,
          rating: 4,
          reviewCount: 89,
          image: 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80'
        },
        {
          id: 3,
          name: 'Performance Basketball',
          slug: 'performance-basketball',
          category: 'Basketball',
          price: 59.99,
          originalPrice: 59.99,
          discount: 0,
          rating: 5,
          reviewCount: 67,
          image: 'https://images.unsplash.com/photo-1612118656942-35916945e7c2?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80'
        },
        {
          id: 4,
          name: 'Carbon Fiber Tennis Racket',
          slug: 'carbon-fiber-tennis-racket',
          category: 'Tennis',
          price: 199.99,
          originalPrice: 249.99,
          discount: 20,
          rating: 4,
          reviewCount: 42,
          image: 'https://images.unsplash.com/photo-1617083332323-88f485d61212?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80'
        }
      ]
    };
  }
};
</script>

<style scoped>
/* Optional: Add any component-specific styles here */
</style> 