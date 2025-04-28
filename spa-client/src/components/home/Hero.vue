<template>
  <section class="relative bg-gray-900 text-white overflow-hidden">
    <!-- Background image with overlay -->
    <div class="absolute inset-0 z-0">
      <img 
        src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1740&q=80" 
        alt="Hero background"
        class="w-full h-full object-cover opacity-40"
      />
      <div class="absolute inset-0 bg-gradient-to-r from-blue-900/80 to-indigo-900/80"></div>
    </div>
    
    <!-- Content -->
    <div class="container mx-auto px-4 py-20 md:py-32 relative z-10">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
        <div class="max-w-xl animate-slide-in-left">
          <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
            Elevate Your Game with Premier Gear
          </h1>
          <p class="text-lg md:text-xl mb-8 text-gray-200">
            Discover our exclusive collection of high-performance products designed for champions. Limited time offers available now.
          </p>
          <div class="flex flex-wrap gap-4">
            <router-link 
              to="/products" 
              class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors duration-300 text-center"
            >
              Shop Now
            </router-link>
            <router-link 
              to="/categories" 
              class="px-8 py-3 bg-transparent border border-white hover:bg-white/10 text-white font-medium rounded-md transition-colors duration-300 text-center"
            >
              Explore Categories
            </router-link>
          </div>
          
          <!-- Features -->
          <div class="mt-12 grid grid-cols-3 gap-4">
            <div class="text-center">
              <div class="flex justify-center mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <p class="text-sm">Fast Delivery</p>
            </div>
            <div class="text-center">
              <div class="flex justify-center mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <p class="text-sm">Quality Guarantee</p>
            </div>
            <div class="text-center">
              <div class="flex justify-center mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
              </div>
              <p class="text-sm">Secure Payment</p>
            </div>
          </div>
        </div>
        
        <div class="hidden md:block animate-slide-in-right">
          <div class="relative">
            <!-- Featured product image -->
            <img 
              src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8c2hvZXN8ZW58MHx8MHx8&auto=format&fit=crop&w=800&q=60" 
              alt="Featured product"
              class="w-full h-auto rounded-lg shadow-2xl animate-float"
            />
            
            <!-- Discount badge -->
            <div class="absolute -top-4 -right-4 bg-red-500 text-white text-sm font-bold rounded-full w-20 h-20 flex items-center justify-center animate-pulse">
              <div class="text-center">
                <div class="text-2xl font-bold">40%</div>
                <div class="text-xs uppercase">Off</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'Hero'
};
</script>

<style scoped>
@keyframes float {
  0% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-10px);
  }
  100% {
    transform: translateY(0px);
  }
}

@keyframes slide-in-left {
  0% {
    opacity: 0;
    transform: translateX(-50px);
  }
  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slide-in-right {
  0% {
    opacity: 0;
    transform: translateX(50px);
  }
  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

.animate-slide-in-left {
  animation: slide-in-left 0.8s ease-out forwards;
}

.animate-slide-in-right {
  animation: slide-in-right 0.8s ease-out forwards;
}
</style> 