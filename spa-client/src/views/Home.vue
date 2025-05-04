<template>
  <div>
    <!-- Advanced E-commerce Header -->
    <Header />
    
    <!-- Hero Section -->
    <section class="relative">
      <!-- Loading State -->
      <div v-if="loading" class="h-[500px] flex items-center justify-center">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="h-[500px] flex items-center justify-center">
        <div class="text-red-500 text-center">
          <p class="text-xl font-semibold">{{ error }}</p>
        </div>
      </div>
      
      <!-- No Banners State -->
      <div v-else-if="!banners || banners.length === 0" class="h-[500px] flex items-center justify-center bg-gradient-to-r from-blue-700 to-indigo-900">
        <div class="text-center text-white">
          <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6">Welcome to Our Store</h1>
          <p class="text-xl mb-8">Quality equipment and apparel for athletes of all levels.</p>
          <div class="flex flex-wrap gap-4 justify-center">
            <router-link to="/products" class="bg-white text-blue-700 hover:bg-blue-100 px-6 py-3 rounded-md font-medium transition-colors duration-300">
              Shop Now
            </router-link>
            <router-link to="/categories" class="border border-white text-white hover:bg-white hover:text-blue-700 px-6 py-3 rounded-md font-medium transition-colors duration-300">
              Browse Categories
            </router-link>
          </div>
          
          <!-- Debug info only visible in development -->
          <div v-if="process.env.NODE_ENV !== 'production'" class="mt-8 p-4 bg-black/50 rounded-lg text-left max-w-2xl mx-auto text-sm overflow-auto max-h-[300px]">
            <p class="mb-2 font-bold">Banner Debug Info:</p>
            <p v-if="error" class="text-red-400 mb-2">{{ error }}</p>
            <p class="text-yellow-300 mb-2">Note: Banners require the 'is_active' field to be true and at least one image field.</p>
            
            <div class="mb-4">
              <p class="font-semibold mb-2">Debug Controls:</p>
              <div class="flex flex-wrap gap-2">
                <button @click="refreshBanners" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">
                  Refresh Banners
              </button>
                <button @click="addTestBanner" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">
                  Add Test Banner
                </button>
                <button @click="showRawBannerData = !showRawBannerData" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-xs">
                  {{ showRawBannerData ? 'Hide' : 'Show' }} Raw Data
                </button>
                <button @click="toggleBannerFilters" class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1 rounded text-xs">
                  {{ bypassBannerFilters ? 'Enable' : 'Disable' }} Filters
                </button>
              </div>
            </div>
            
            <div class="mb-4">
              <p class="font-semibold mb-1">Status:</p>
              <ul class="list-disc list-inside">
                <li>Banner filters: <span class="font-mono">{{ bypassBannerFilters ? 'BYPASSED' : 'ENABLED' }}</span></li>
                <li>Raw data available: <span class="font-mono">{{ rawBannerData ? 'YES' : 'NO' }}</span></li>
                <li>API URL: <span class="font-mono">http://127.0.0.1:8000/api/banners</span></li>
              </ul>
                      </div>
            
            <!-- Raw Banner Data Debug -->
            <div v-if="showRawBannerData && rawBannerData" class="mt-4 p-3 bg-gray-900 rounded text-green-400 font-mono text-xs overflow-auto max-h-[200px]">
              <pre>{{ JSON.stringify(rawBannerData, null, 2) }}</pre>
                    </div>
                    
            <div v-if="window.localStorage.getItem('authToken')" class="mt-4">
              <p class="font-semibold mb-2">Admin Actions:</p>
              <router-link to="/admin/banners" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-xs">
                Go to Banner Management
                        </router-link>
                      </div>
                    </div>
                  </div>
                </div>

      <!-- Banner Display -->
      <div v-else class="relative h-[500px] overflow-hidden">
        <!-- Banner Carousel -->
        <div class="relative h-full">
          <transition-group name="fade" mode="out-in">
            <div v-for="(banner, index) in banners" 
                :key="banner.id"
                v-show="currentBannerIndex === index"
                class="absolute inset-0 w-full h-full">
              <!-- Banner Image -->
              <img 
                :src="getBannerImageUrl(banner)"
                :alt="banner.title || 'Banner image'"
                class="w-full h-full object-cover"
                @error="$event.target.src='https://via.placeholder.com/1920x600'"
              />
              
              <!-- Content Overlay -->
              <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent flex items-center">
                <div class="container mx-auto px-4">
                  <div class="max-w-xl">
                    <h1 v-if="banner.title" class="text-4xl md:text-5xl text-white font-bold mb-4">
                      {{ banner.title }}
                    </h1>
                    <p v-if="banner.description" class="text-xl text-white/90 mb-8">
                      {{ banner.description }}
                    </p>
                    <div v-if="banner.button_text" class="flex gap-4">
                      <button 
                        @click="handleBannerClick(banner)"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-md font-medium transition-colors duration-300">
                        {{ banner.button_text }}
                      </button>
              </div>
                  </div>
                </div>
              </div>
            </div>
          </transition-group>
              
          <!-- Navigation Arrows (only show if multiple banners) -->
          <div v-if="banners.length > 1" class="absolute inset-0 flex items-center justify-between px-4">
              <button 
              @click="prevBanner" 
              class="bg-white/20 hover:bg-white/40 text-white p-2 rounded-full backdrop-blur-sm transition-all transform hover:scale-110 focus:outline-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <button 
              @click="nextBanner"
              class="bg-white/20 hover:bg-white/40 text-white p-2 rounded-full backdrop-blur-sm transition-all transform hover:scale-110 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
          </div>

          <!-- Navigation Dots (only show if multiple banners) -->
          <div v-if="banners.length > 1" class="absolute bottom-4 left-0 right-0">
            <div class="flex justify-center gap-2">
              <button 
                v-for="(_, index) in banners" 
                :key="index"
                @click="goToBanner(index)"
                class="w-3 h-3 rounded-full transition-all duration-300 focus:outline-none"
                :class="currentBannerIndex === index ? 'bg-white scale-110' : 'bg-white/50 hover:bg-white/75'">
              </button>
            </div>
          </div>
            </div>
          </div>
        </section>

    <!-- Main content section -->
    <section class="bg-gradient-to-b from-blue-50 to-white">
      <div v-if="loading" class="container mx-auto p-8 text-center">
        <div class="animate-pulse">
          <div class="h-8 bg-gray-200 rounded w-1/4 mx-auto mb-8"></div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div v-for="i in 3" :key="i" class="rounded-lg bg-gray-100 h-80"></div>
              </div>
            </div>
      </div>
      
      <div v-else>
        <!-- Categories Section -->
        <section class="bg-gray-50 py-16">
          <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Shop By Category</h2>
            
            <!-- Replace the hardcoded slider with our dynamic component -->
            <CategorySlider />
          </div>
        </section>

        <!-- Shop By Brand Section -->
        <ShopByBrandSection />

        <!-- Featured Products -->
        <FeaturedProductsSection />
      </div>
    </section>

    <!-- Advanced Footer -->
    <Footer />
  </div>
</template>

<script>
import axios from 'axios';
import TailwindTest from '@/components/TailwindTest.vue'
import { mapGetters, mapActions } from 'vuex';
import { getCartItemCount, getCart, removeFromCart, getCartSubtotal } from '@/utils/cart';
import { getWishlistCount, getWishlist, removeFromWishlist } from '@/utils/wishlist';
import Header from '@/components/Header.vue';
import Hero from '@/components/Hero.vue';
import CategorySlider from '@/components/home/CategorySlider.vue';
import FeaturedProductsSection from '@/components/home/FeaturedProductsSection.vue'
import Footer from '@/components/layout/Footer.vue';
import ShopByBrandSection from '@/components/home/ShopByBrandSection.vue';

export default {
  name: 'HomeView',
  components: {
    TailwindTest,
    Header,
    Hero,
    CategorySlider,
    FeaturedProductsSection,
    Footer,
    ShopByBrandSection
  },
  data() {
    return {
      loading: true,
      error: null,
      logoData: null,
      banners: [],
      currentBannerIndex: 0,
      autoplayInterval: null,
      apiBaseUrl: 'http://127.0.0.1:8000',
      showTestBanner: false,
      featuredProducts: [],
      categories: [],
      categorySlidePosition: 0,
      productSlidePosition: 0,
      // Header state
      mobileMenuOpen: false,
      mobileCategoriesOpen: false,
      categoriesDropdownOpen: false,
      accountDropdownOpen: false,
      cartDropdownOpen: false,
      wishlistDropdownOpen: false,
      cartItemsLocal: [], // Local reactive cart items
      wishlistItemsLocal: [], // Local reactive wishlist items
      // Add customer care data
      customerCare: {
        timings: '',
        whatsapp: '',
        email: '',
        phone: '',
        address: '',
        viber: ''
      },
      customerCareLoading: true,
      // Footer message from settings
      footerMessage: '',
      footerMessageLoading: true,
      // Debug data
      debugResponse: null,
      mockData: {
        featuredProducts: [
          {
            id: 1,
            name: "Professional Basketball",
            description: "Official size and weight basketball for professional games",
            price: 49.99,
            image_url: null
          },
          {
            id: 2,
            name: "Training Soccer Ball",
            description: "Durable soccer ball perfect for daily training",
            price: 35.95,
            image_url: null
          },
          {
            id: 3,
            name: "Premium Tennis Racket",
            description: "Lightweight tennis racket with perfect balance and control",
            price: 129.99,
            image_url: null
          },
          {
            id: 4,
            name: "Running Shoes",
            description: "Comfortable running shoes with excellent cushioning",
            price: 89.95,
            image_url: null
          }
        ],
        categories: [
          { id: 1, name: "Basketball" },
          { id: 2, name: "Soccer" },
          { id: 3, name: "Tennis" },
          { id: 4, name: "Running" },
          { id: 5, name: "Swimming" },
          { id: 6, name: "Fitness" }
        ]
      },
      showRawBannerData: false,
      rawBannerData: null,
      bypassBannerFilters: true,
      socialLinks: {
        facebook: '',
        instagram: '',
        twitter: '',
        youtube: ''
      },
    }
  },
  async created() {
    try {
      console.log('Home component created, loading data...');
      
      // Try to fetch customer care data FIRST and wait for it
      try {
        console.log('Fetching customer care data...');
        await this.fetchCustomerCare();
        console.log('Customer care data fetched successfully');
      } catch (err) {
        console.error('Failed to fetch customer care data:', err);
        console.log('Using default customer care values');
      }
      
      // Fetch other data in parallel
      console.log('Fetching other data...');
      Promise.all([
        this.fetchLogo().catch(err => console.error('Error fetching logo:', err)),
        this.fetchSocialLinks().catch(err => console.error('Error fetching social links:', err)),
        this.fetchFooterMessage().catch(err => console.error('Error fetching footer message:', err))
      ]);
      
      // Fetch banners
      this.loading = true;
      try {
        await this.fetchDirectBanners();
      } catch (err) {
        console.error('Error during banner initialization:', err);
      }
      
      // Fetch other homepage data
      await this.fetchHomepageData().catch(err => {
        console.error('Error fetching homepage data:', err);
      });
      
      console.log('All data loaded');
    } catch (err) {
      console.error('Error in created hook:', err);
    } finally {
      this.loading = false;
    }
  },
  mounted() {
    // Fetch logo again on mount, as an additional safeguard to ensure fresh logo data
    this.fetchLogo();
  },
  beforeUnmount() {
    if (this.autoplayInterval) {
      clearInterval(this.autoplayInterval);
    }
  },
  computed: {
    ...mapGetters({
      isLoggedIn: 'auth/isLoggedIn',
      user: 'auth/user'
    }),
    wishlistCount() {
      return getWishlistCount();
    },
    cartItemCount() {
      return getCartItemCount();
    },
    cartItems() {
      return getCart();
    },
    wishlistItems() {
      return getWishlist();
    },
    cartSubtotal() {
      return getCartSubtotal();
    },
    currentBanner() {
      return this.banners[this.currentBannerIndex] || null;
    },
    // Computed property to handle non-standard social media links
    additionalSocialLinks() {
      if (!this.socialLinks) return {};
      
      const standardPlatforms = ['facebook', 'instagram', 'twitter', 'youtube', 'tiktok'];
      const additionalLinks = {};
      
      // Extract any non-standard platforms
      Object.entries(this.socialLinks).forEach(([platform, url]) => {
        if (!standardPlatforms.includes(platform) && url) {
          additionalLinks[platform] = url;
        }
      });
      
      return additionalLinks;
    },
    // Improved computed property for the WhatsApp number that handles formatting
    whatsappUrl() {
      if (!this.customerCare.whatsapp) return '#';
      
      // Remove all non-numeric characters including spaces and +
      const cleanNumber = this.customerCare.whatsapp.replace(/[^0-9]/g, '');
      console.log('WhatsApp clean number:', cleanNumber);
      return `https://wa.me/${cleanNumber}`;
    }
  },
  methods: {
    ...mapActions({
      logoutAction: 'auth/logout'
    }),
    logout() {
      this.logoutAction();
      this.accountDropdownOpen = false;
      this.$router.push('/login');
    },
    handleImageError(event) {
      event.target.src = 'https://picsum.photos/id/26/100/100';
    },
    removeCartItem(index) {
      // Remove directly from our local array for immediate UI update
      this.cartItemsLocal.splice(index, 1);
      
      // Update localStorage
      localStorage.setItem('cart', JSON.stringify(this.cartItemsLocal));
      
      // Notify other components
      document.dispatchEvent(new Event('cart-updated'));
    },
    async fetchHomepageData() {
      this.loading = true;
      
      try {
        // First try the storefront endpoint
        try {
          const response = await axios.get('/api/storefront');
          if (response.data) {
            this.featuredProducts = response.data.featuredProducts || [];
            this.categories = response.data.categories || [];
            this.loading = false;
            return;
          }
        } catch (e) {
          console.warn('Storefront endpoint failed, trying individual endpoints');
        }
        
        // If storefront endpoint fails, fall back to individual endpoints
        try {
          const [productsResponse, categoriesResponse] = await Promise.all([
            axios.get('/api/products', {
              params: { featured: 1 }
            }),
            axios.get('/api/storefront/categories')
          ]);
          
          // Handle different possible response structures
          this.featuredProducts = Array.isArray(productsResponse.data.data) 
            ? productsResponse.data.data 
            : (productsResponse.data.products || []);
          
          // Updated to handle new API response structure
          if (categoriesResponse.data.data) {
            this.categories = categoriesResponse.data.data;
          } else if (Array.isArray(categoriesResponse.data)) {
            this.categories = categoriesResponse.data;
          } else {
            this.categories = categoriesResponse.data.categories || [];
          }

        } catch (err) {
          console.warn('All API endpoints failed, using mock data', err);
          // Use mock data
          this.featuredProducts = this.mockData.featuredProducts;
          this.categories = this.mockData.categories;
        }
        
      } catch (error) {
        console.error('Error fetching homepage data:', error);
        // Set mock data for graceful degradation
        this.featuredProducts = this.mockData.featuredProducts;
        this.categories = this.mockData.categories;
      } finally {
        this.loading = false;
      }
    },
    
    // Method to filter out duplicate categories by name
    getUniqueCategories() {
      const uniqueCategories = [];
      const categoryNames = new Set();
      
      for (const category of this.categories) {
        if (!categoryNames.has(category.name)) {
          categoryNames.add(category.name);
          uniqueCategories.push(category);
        }
      }
      
      return uniqueCategories;
    },
    
    // Product slider methods
    slideProductsLeft() {
      if (this.productSlidePosition === 0) return;
      
      // Calculate the width of a single product + gap
      const productElement = this.$refs.productSlider.querySelector('div[class*="flex-shrink-0"]');
      const productWidth = productElement.offsetWidth;
      const gap = 24; // 6 * 4px (gap-6 = 1.5rem = 24px)
      
      // Slide by exactly one product width
      this.productSlidePosition = Math.max(0, this.productSlidePosition - (productWidth + gap));
    },
    
    slideProductsRight() {
      const sliderWidth = this.$refs.productSlider.scrollWidth;
      const containerWidth = this.$refs.productSlider.parentNode.clientWidth;
      const maxPosition = Math.max(0, sliderWidth - containerWidth);
      
      // Calculate the width of a single product + gap
      const productElement = this.$refs.productSlider.querySelector('div[class*="flex-shrink-0"]');
      const productWidth = productElement.offsetWidth;
      const gap = 24; // 6 * 4px (gap-6 = 1.5rem = 24px)
      
      // Don't slide past the end
      if (this.productSlidePosition >= maxPosition) return;
      
      // Slide by exactly one product width
      this.productSlidePosition = Math.min(maxPosition, this.productSlidePosition + (productWidth + gap));
    },
    
    toggleAccountDropdown() {
      this.accountDropdownOpen = !this.accountDropdownOpen;
    },
    toggleCartDropdown() {
      this.cartDropdownOpen = !this.cartDropdownOpen;
    },
    // Add new method to update local cart items
    updateLocalCart() {
      this.cartItemsLocal = getCart();
    },
    updateLocalWishlist() {
      this.wishlistItemsLocal = getWishlist();
    },
    toggleWishlistDropdown() {
      this.wishlistDropdownOpen = !this.wishlistDropdownOpen;
    },
    removeWishlistItem(index) {
      // Remove directly from our local array for immediate UI update
      const productId = this.wishlistItemsLocal[index].id;
      this.wishlistItemsLocal.splice(index, 1);
      
      // Update localStorage
      localStorage.setItem('wishlist', JSON.stringify(this.wishlistItemsLocal));
      
      // Notify other components
      document.dispatchEvent(new Event('wishlist-updated'));
    },
    calculateSubtotal() {
      return this.cartItemsLocal.reduce((total, item) => total + (item.price * item.quantity), 0);
    },
    // Direct API approach - guaranteed to work with any data format
    async fetchDirectBanners() {
      this.loading = true;
      this.error = null;
      
      try {
        // Direct API call with full response logging
        const apiUrl = 'http://127.0.0.1:8000/api/banners';
        console.log(`Fetching banners directly from: ${apiUrl}`);
        
        const response = await fetch(apiUrl, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          }
        });
        
        if (!response.ok) {
          throw new Error(`API responded with status: ${response.status}`);
        }
        
        // Get raw text first for debugging
        const rawText = await response.text();
        console.log('Raw API response text:', rawText);
        
        // Try to parse as JSON
        let data;
        try {
          data = JSON.parse(rawText);
        } catch (err) {
          console.error('Failed to parse JSON:', err);
          this.error = 'Invalid JSON response from API';
          this.loading = false;
          return;
        }
        
        console.log('Parsed banner data:', data);
        
        // Store raw data for debugging
        this.rawBannerData = data;
        
        // Handle any possible data format
        let bannerArray = [];
        
        if (Array.isArray(data)) {
          console.log('Data is an array, using directly');
          bannerArray = data;
        } else if (data && data.data && Array.isArray(data.data)) {
          console.log('Data is wrapped in data property');
          bannerArray = data.data;
        } else if (data && typeof data === 'object') {
          console.log('Data is an object, converting to array');
          bannerArray = Object.values(data);
        } else {
          console.warn('Unknown data format, cannot process');
          this.error = 'Unknown data format from API';
          this.loading = false;
          return;
        }
        
        console.log(`Found ${bannerArray.length} banners in API response`);
        
        // Accept ALL banners with no filtering
        this.banners = bannerArray.map(banner => {
          // Clone banner to avoid reference issues
          const processedBanner = { ...banner };
          
          // Log each banner for debugging
          console.log(`Processing banner:`, processedBanner);
          
          return processedBanner;
        });
        
        console.log(`Processed ${this.banners.length} banners for display`);
        
        if (this.banners.length === 0) {
          this.error = 'No banners found in API response';
        } else {
          // Start carousel
          this.currentBannerIndex = 0;
          if (this.banners.length > 1) {
            this.startAutoplay();
          }
        }
      } catch (err) {
        console.error('Error fetching banners:', err);
        this.error = `Failed to load banners: ${err.message}`;
        this.banners = [];
      } finally {
        this.loading = false;
      }
    },
    
    // Direct approach to refresh banners
    async refreshBanners() {
      this.loading = true;
      await this.fetchDirectBanners();
    },
    
    // Image URL resolution - works with any format from Filament
    getBannerImageUrl(banner) {
      if (!banner) return '';
      
      console.log('Getting image URL for banner:', banner);
      
      // Check each possible image field
      const imageFields = ['image', 'image_url', 'image_link'];
      let imageUrl = '';
      
      // Find first non-empty image field
      for (const field of imageFields) {
        if (banner[field]) {
          imageUrl = banner[field];
          console.log(`Using ${field}:`, imageUrl);
          break;
        }
      }
      
      // If no image found, use placeholder
      if (!imageUrl) {
        console.warn('No image found for banner ID:', banner.id);
        return 'https://via.placeholder.com/1920x600/cccccc/666666?text=No+Image';
      }
      
      // Check if it's already a full URL
      if (imageUrl.startsWith('http://') || imageUrl.startsWith('https://') || imageUrl.startsWith('data:')) {
        return imageUrl;
      }
      
      // Check if it's a storage URL
      if (imageUrl.includes('storage/')) {
        return `http://127.0.0.1:8000/${imageUrl.startsWith('/') ? imageUrl.substring(1) : imageUrl}`;
      }
      
      // Default: assume it's a storage path
      return `http://127.0.0.1:8000/storage/${imageUrl.startsWith('/') ? imageUrl.substring(1) : imageUrl}`;
    },
    startAutoplay() {
      // Clear any existing interval first
      if (this.autoplayInterval) {
        clearInterval(this.autoplayInterval);
      }
      
      // Set new interval
      this.autoplayInterval = setInterval(() => {
        this.nextBanner();
      }, 5000);
      
      console.log('Banner autoplay started');
    },
    nextBanner() {
      if (!this.banners.length) return;
      this.currentBannerIndex = (this.currentBannerIndex + 1) % this.banners.length;
    },
    prevBanner() {
      if (!this.banners.length) return;
      this.currentBannerIndex = (this.currentBannerIndex - 1 + this.banners.length) % this.banners.length;
    },
    goToBanner(index) {
      if (index >= 0 && index < this.banners.length) {
        this.currentBannerIndex = index;
      }
    },
    handleBannerClick(banner) {
      if (!banner) return;
      
      // Determine which link to use (button_link is preferred)
      const link = banner.button_link || banner.link || banner.url || banner.image_link;
      
      if (!link) {
        console.log('Banner has no link to navigate to');
        return;
      }
      
      console.log('Banner click:', link);
      
      // Handle external links
      if (link.startsWith('http://') || link.startsWith('https://') || link.startsWith('//')) {
        window.open(link, '_blank');
        return;
      }
      
      // Handle internal links - make sure they start with /
      const routePath = link.startsWith('/') ? link : `/${link}`;
      
      // Use router to navigate
      this.$router.push(routePath);
    },
    addTestBanner() {
      // Create a test banner with a public image URL
      const testBanner = {
        id: 999,
        title: "Test Banner",
        description: "This test banner verifies that the display logic works correctly.",
        button_text: "Test Button",
        button_link: "/products",
        image_link: "https://via.placeholder.com/1920x500/0066cc/ffffff?text=Test+Banner",
        is_active: true,
        order: 1,
        priority: 10
      };
      
      // Add to banners array
      this.banners = [testBanner];
      this.currentBannerIndex = 0;
      this.showTestBanner = true;
      this.error = null;
      
      console.log('Added test banner for debugging:', testBanner);
      
      // Start autoplay (disabled for test banner)
      if (this.autoplayInterval) {
        clearInterval(this.autoplayInterval);
        this.autoplayInterval = null;
      }
    },
    toggleBannerFilters() {
      this.bypassBannerFilters = !this.bypassBannerFilters;
      console.log(`Banner filters ${this.bypassBannerFilters ? 'bypassed' : 'enabled'}`);
      // Refresh banners with new filter setting
      this.refreshBanners();
    },
    // Add a method to fetch the logo
    async fetchLogo() {
      try {
        // Add a timestamp to prevent caching
        const timestamp = new Date().getTime();
        const response = await axios.get(`http://127.0.0.1:8000/api/logo/active?t=${timestamp}`);
        if (response.data) {
          this.logoData = response.data;
          console.log('Logo data loaded:', this.logoData);
        }
      } catch (error) {
        console.error('Error fetching logo:', error);
        this.logoData = null; // Reset logo data on error
      }
    },
    // Method to get the full URL for a logo image
    getLogoImageUrl(logo) {
      if (!logo || !logo.image_path) return '';
      
      // Create timestamp for cache busting
      const timestamp = new Date().getTime();
      
      // Check if it's already a full URL
      if (logo.image_path.startsWith('http://') || logo.image_path.startsWith('https://')) {
        return `${logo.image_path}?t=${timestamp}`;
      }
      
      // Check if it's a storage URL
      if (logo.image_path.includes('storage/')) {
        return `http://127.0.0.1:8000/${logo.image_path.startsWith('/') ? logo.image_path.substring(1) : logo.image_path}?t=${timestamp}`;
      }
      
      // Default: assume it's a storage path
      return `http://127.0.0.1:8000/storage/${logo.image_path.startsWith('/') ? logo.image_path.substring(1) : logo.image_path}?t=${timestamp}`;
    },
    handleLogoError(event) {
      console.error('Error loading logo image:', event);
      event.target.style.display = 'none';
    },
    // Add a method to fetch social links
    async fetchSocialLinks() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/settings/social-links');
        if (response.data && response.data.success) {
          let socialData = response.data.data;
          
          // Handle case where data might be a JSON string
          if (typeof socialData === 'string') {
            try {
              socialData = JSON.parse(socialData);
            } catch (e) {
              console.error('Error parsing social links JSON string:', e);
            }
          }
          
          this.socialLinks = socialData;
          console.log('Social links loaded:', this.socialLinks);
        }
      } catch (error) {
        console.error('Error fetching social links:', error);
        // Set default links in case of error
        this.socialLinks = {
          facebook: 'https://www.facebook.com/aljesh.raut',
          instagram: 'https://www.instagram.com/aljeshraut/',
          twitter: '',
          youtube: ''
        };
      }
    },
    // Direct fetch for customer care settings
    async fetchCustomerCare() {
      try {
        this.customerCareLoading = true;
        console.log('Fetching customer care from API...');
        const response = await axios.get('http://127.0.0.1:8000/api/settings/customer-care');
        console.log('Raw API response:', response);
        
        if (!response.data || !response.data.success) {
          console.error('Invalid response from API');
          this.customerCareLoading = false;
          return;
        }
        
        // Extract the data from the API response - it's a single JSON setting with key "customer_care"
        const settings = response.data.data;
        
        if (!settings || !settings.customer_care) {
          console.error('No customer_care setting in the response');
          this.customerCareLoading = false;
          return;
        }
        
        console.log('Customer care settings received:', settings);
        
        // Parse the JSON value if it's a string
        let customerCareData = settings.customer_care;
        if (typeof customerCareData === 'string') {
          try {
            customerCareData = JSON.parse(customerCareData);
          } catch (e) {
            console.error('Error parsing customer care JSON:', e);
            this.customerCareLoading = false;
            return;
          }
        }
        
        console.log('Parsed customer care data:', customerCareData);
        
        // Map the JSON data to our customer care object
        this.customerCare = {
          timings: customerCareData.timings || '',
          whatsapp: customerCareData.whatsapp || '',
          email: customerCareData.email || '',
          phone: customerCareData.phone || '',
          address: customerCareData.address || '',
          viber: customerCareData.viber || ''
        };
        
        console.log('Customer care data updated:', this.customerCare);
      } catch (error) {
        console.error('Error fetching customer care:', error);
      } finally {
        this.customerCareLoading = false;
      }
    },
    // Fetch footer message from settings
    async fetchFooterMessage() {
      try {
        console.log('Fetching footer message...');
        const response = await axios.get('http://127.0.0.1:8000/api/settings/message');
        console.log('Footer message response:', response);
        
        if (response.data && response.data.success) {
          let messageData = response.data.data;
          
          // If the data is a string that looks like JSON, try to parse it
          if (typeof messageData === 'string' && 
              (messageData.startsWith('"') || messageData.startsWith('{'))) {
            try {
              const parsed = JSON.parse(messageData);
              messageData = parsed;
            } catch (e) {
              console.log('Message not valid JSON, using as string');
            }
          }
          
          // Update the footer message with the returned data
          this.footerMessage = messageData;
          console.log('Footer message updated:', this.footerMessage);
        } else {
          // Use default message if API fails
          this.footerMessage = 'Your ultimate destination for cricket equipment and apparel. We offer high-quality products for professional and amateur players alike.';
          console.log('Using default footer message');
        }
      } catch (error) {
        console.error('Error fetching footer message:', error);
        // Use default message if error occurs
        this.footerMessage = 'Your ultimate destination for cricket equipment and apparel. We offer high-quality products for professional and amateur players alike.';
      } finally {
        this.footerMessageLoading = false;
      }
    },
  }
}
</script>

<style>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Fade transition for banners */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style> 