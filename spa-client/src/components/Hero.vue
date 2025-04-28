<template>
  <section class="relative">
    <!-- Loading State -->
    <div v-if="loading" class="h-[400px] flex items-center justify-center">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="h-[400px] flex items-center justify-center">
      <div class="text-red-500 text-center">
        <p class="text-xl font-semibold">Failed to load banners</p>
        <p class="text-sm mt-2">{{ error }}</p>
      </div>
    </div>

    <!-- No Banners State -->
    <div v-else-if="!banners || banners.length === 0" class="h-[400px] flex items-center justify-center">
      <div class="text-gray-500 text-center">
        <p class="text-xl font-semibold">No active banners</p>
        <p class="text-sm mt-2">Please add banners through the admin panel</p>
      </div>
    </div>

    <!-- Banners Carousel -->
    <div v-else class="relative h-[400px] overflow-hidden group" ref="heroContainer">
      <!-- Previous Button -->
      <button 
        v-if="banners.length > 1"
        @click="prevSlide" 
        class="absolute left-4 top-1/2 -translate-y-1/2 z-10 bg-white/20 hover:bg-white/40 text-white p-2 rounded-full shadow-lg transition-all duration-300 opacity-0 group-hover:opacity-100 backdrop-blur-sm hover:scale-110"
        aria-label="Previous slide"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>

      <!-- Next Button -->
      <button 
        v-if="banners.length > 1"
        @click="nextSlide" 
        class="absolute right-4 top-1/2 -translate-y-1/2 z-10 bg-white/20 hover:bg-white/40 text-white p-2 rounded-full shadow-lg transition-all duration-300 opacity-0 group-hover:opacity-100 backdrop-blur-sm hover:scale-110"
        aria-label="Next slide"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>

      <div v-for="(banner, index) in banners" 
           :key="banner.id"
           class="absolute inset-0 transition-all duration-700 transform cursor-pointer"
           :class="{ 
             'opacity-100 translate-x-0': currentIndex === index,
             'opacity-0 translate-x-full': currentIndex < index,
             'opacity-0 -translate-x-full': currentIndex > index
           }"
           @click="handleBannerClick(banner)">
        <div class="relative h-full w-full overflow-hidden">
          <!-- Banner image with parallax effect -->
          <div class="absolute inset-0 transform transition-transform duration-700 group-hover:scale-110"
               :style="{ transform: `scale(${1 + (parallaxOffset * 0.1)})` }">
            <img 
              :src="getImageUrl(banner)" 
              :alt="banner.title || 'Banner image ' + (index + 1)" 
              class="w-full h-full object-cover"
            />
          </div>
          
          <!-- Banner content overlay -->
          <div class="absolute inset-0 bg-gradient-to-r from-black/50 to-transparent flex items-center">
            <div class="container mx-auto px-6 md:px-12 py-6">
              <div class="max-w-md">
                <!-- Title -->
                <h2 v-if="banner.title" class="text-white text-3xl font-bold mb-4">{{ banner.title }}</h2>
                
                <!-- Description -->
                <p v-if="banner.description" class="text-white text-lg mb-6">{{ banner.description }}</p>
                
                <!-- Action button -->
                <button 
                  v-if="banner.button_text" 
                  @click.stop="navigateToLink(banner.button_link)"
                  class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-full transition-colors duration-300 shadow-lg hover:shadow-xl"
                >
                  {{ banner.button_text }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Navigation Dots -->
      <div v-if="banners.length > 1" class="absolute bottom-6 left-0 right-0 flex justify-center gap-2">
        <button 
          v-for="(_, index) in banners" 
          :key="index"
          @click="goToSlide(index)"
          class="w-2 h-2 rounded-full transition-all duration-300"
          :class="currentIndex === index ? 'bg-white scale-125' : 'bg-white/50 hover:bg-white/75'"
          :aria-label="'Go to slide ' + (index + 1)"
        ></button>
      </div>

      <!-- Progress Bar -->
      <div v-if="banners.length > 1" class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
        <div class="h-full bg-white transition-all duration-5000 ease-linear"
             :style="{ width: `${(currentIndex + 1) * (100 / banners.length)}%` }"
             :class="{ 'transition-none': isTransitioning }">
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Hero',
  data() {
    return {
      banners: [],
      loading: true,
      error: null,
      currentIndex: 0,
      interval: null,
      isTransitioning: false,
      isAutoplayActive: true,
      parallaxOffset: 0,
      mouseX: 0,
      mouseY: 0,
      apiBaseUrl: 'http://127.0.0.1:8000'
    }
  },
  async created() {
    try {
      const response = await axios.get(`${this.apiBaseUrl}/api/banners`);
      if (response.data && Array.isArray(response.data)) {
        // Filter active banners and sort by order/priority
        this.banners = response.data
          .filter(banner => banner.is_active)
          .sort((a, b) => {
            // First sort by order
            if (a.order !== b.order) {
              return a.order - b.order;
            }
            // Then by priority if order is the same
            return (b.priority || 0) - (a.priority || 0);
          });

        if (this.banners.length > 0) {
          this.startAutoSlide();
        } else {
          console.warn('No active banners found');
        }
      } else {
        console.error('Invalid response format:', response.data);
        this.error = 'Invalid response format from server';
      }
    } catch (err) {
      console.error('Error fetching banners:', err);
      this.error = err.response?.data?.message || err.message || 'Failed to fetch banners';
    } finally {
      this.loading = false;
    }
  },
  mounted() {
    this.setupParallax();
  },
  methods: {
    getImageUrl(banner) {
      // First try image_url if it exists
      if (banner.image_url) {
        return this.getFullUrl(banner.image_url);
      }
      
      // Then try image field
      if (banner.image) {
        return this.getFullUrl(banner.image);
      }
      
      // Finally try image_link
      if (banner.image_link) {
        return this.getFullUrl(banner.image_link);
      }
      
      // Return a default image if none of the above exist
      return 'https://via.placeholder.com/1920x600';
    },
    getFullUrl(path) {
      if (!path) return '';
      
      // If it's already a full URL, return it as is
      if (path.startsWith('http')) {
        return path;
      }
      
      // If it starts with a slash, append to base URL
      if (path.startsWith('/')) {
        return `${this.apiBaseUrl}${path}`;
      }
      
      // Otherwise, assume it's a relative path and append to storage URL
      return `${this.apiBaseUrl}/storage/${path}`;
    },
    setupParallax() {
      const container = this.$refs.heroContainer;
      if (!container) return;

      container.addEventListener('mousemove', (e) => {
        const rect = container.getBoundingClientRect();
        this.mouseX = e.clientX - rect.left;
        this.mouseY = e.clientY - rect.top;
        
        // Calculate parallax offset based on mouse position
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        this.parallaxOffset = {
          x: (this.mouseX - centerX) / centerX,
          y: (this.mouseY - centerY) / centerY
        };
      });

      container.addEventListener('mouseleave', () => {
        this.parallaxOffset = { x: 0, y: 0 };
      });
    },
    startAutoSlide() {
      if (this.banners.length <= 1) return;
      
      this.isAutoplayActive = true;
      this.interval = setInterval(() => {
        if (!this.isTransitioning) {
          this.nextSlide();
        }
      }, 5000);
    },
    pauseAutoplay() {
      this.isAutoplayActive = !this.isAutoplayActive;
      if (this.isAutoplayActive) {
        this.startAutoSlide();
      } else {
        clearInterval(this.interval);
      }
    },
    nextSlide() {
      if (this.isTransitioning || this.banners.length <= 1) return;
      this.isTransitioning = true;
      this.currentIndex = (this.currentIndex + 1) % this.banners.length;
      setTimeout(() => {
        this.isTransitioning = false;
      }, 700);
    },
    prevSlide() {
      if (this.isTransitioning || this.banners.length <= 1) return;
      this.isTransitioning = true;
      this.currentIndex = (this.currentIndex - 1 + this.banners.length) % this.banners.length;
      setTimeout(() => {
        this.isTransitioning = false;
      }, 700);
    },
    goToSlide(index) {
      if (this.isTransitioning || this.banners.length <= 1) return;
      this.isTransitioning = true;
      this.currentIndex = index;
      setTimeout(() => {
        this.isTransitioning = false;
      }, 700);
    },
    navigateToLink(link) {
      if (!link) return;
      
      // Check if it's an internal or external link
      if (link.startsWith('http') || link.startsWith('//')) {
        // For external links, open in a new tab
        window.open(link, '_blank');
      } else {
        // For internal links, use router navigation
        this.$router.push(link);
      }
    },
    handleBannerClick(banner) {
      // First try button_link, then fallback to image_link
      const link = banner.button_link || banner.image_link;
      if (link) {
        this.navigateToLink(link);
      }
    }
  },
  beforeUnmount() {
    if (this.interval) {
      clearInterval(this.interval);
    }
    const container = this.$refs.heroContainer;
    if (container) {
      container.removeEventListener('mousemove', this.handleMouseMove);
      container.removeEventListener('mouseleave', this.handleMouseLeave);
    }
  }
}
</script> 

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}
.slide-enter-from,
.slide-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

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

.animate-float {
  animation: float 3s ease-in-out infinite;
}
</style> 