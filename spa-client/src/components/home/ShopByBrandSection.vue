<template>
  <section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Shop By Brand</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Discover products from your favorite brands that match your style and performance needs.</p>
      </div>
      
      <BrandSlider ref="brandSlider" />
      
      <div class="text-center mt-10">
        <router-link 
          to="/brands" 
          class="inline-flex items-center text-blue-600 font-medium hover:text-blue-800 transition-colors"
        >
          View All Brands
          <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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