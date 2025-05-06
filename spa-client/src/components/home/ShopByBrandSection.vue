<template>
  <section class="py-20 bg-gradient-to-b from-white to-gray-50 relative overflow-hidden">
    <!-- Background decorative elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
      <div class="absolute top-10 left-10 w-64 h-64 bg-blue-50 rounded-full opacity-50 blur-3xl"></div>
      <div class="absolute bottom-20 right-20 w-80 h-80 bg-indigo-50 rounded-full opacity-40 blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold text-gray-900 mb-4">Shop By Brand</h2>
      </div>
      
      <BrandSlider ref="brandSlider" />
      
      <div class="text-center mt-12">
        <router-link 
          to="/brands" 
          class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-full hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1"
        >
          View All Brands
          <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
          </svg>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script>
import BrandSlider from './BrandSlider.vue';

export default {
  name: 'ShopByBrandSection',
  components: {
    BrandSlider
  }
};
</script> 