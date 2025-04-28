<template>
  <div>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-700 to-indigo-900 text-white py-20">
      <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center">
          <div class="md:w-1/2 mb-10 md:mb-0">
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6">Your Ultimate Sports Shop</h1>
            <p class="text-xl mb-8">Quality equipment and apparel for athletes of all levels.</p>
            <div class="flex flex-wrap gap-4">
              <a href="#" class="bg-white text-blue-700 hover:bg-blue-100 px-6 py-3 rounded-md font-medium transition-colors duration-300">Shop Now</a>
              <a href="#" class="border border-white text-white hover:bg-white hover:text-blue-700 px-6 py-3 rounded-md font-medium transition-colors duration-300">Learn More</a>
            </div>
          </div>
          <div class="md:w-1/2">
            <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                alt="Sports equipment" 
                class="rounded-lg shadow-xl">
          </div>
        </div>
      </div>
    </section>

    <!-- Categories Section -->
    <CategoriesSection />

    <!-- Featured Products Section -->
    <FeaturedProductsSection />

    <!-- Testimonials Section -->
    <TestimonialsSection />

    <!-- Newsletter Section -->
    <NewsletterSection />
  </div>
</template>

<script>
import FeaturedProductsSection from '@/components/home/FeaturedProductsSection.vue'
import CategoriesSection from '@/components/home/CategoriesSection.vue'
import TestimonialsSection from '@/components/home/TestimonialsSection.vue'
import NewsletterSection from '@/components/home/NewsletterSection.vue'

export default {
  name: 'HomePage',
  components: {
    FeaturedProductsSection,
    CategoriesSection,
    TestimonialsSection,
    NewsletterSection
  }
}
</script> 