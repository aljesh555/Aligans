<template>
  <header class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-3">
      <div class="flex justify-between items-center">
        <!-- Logo -->
        <router-link to="/" class="font-bold text-xl text-indigo-600">
          <img v-if="logo" :src="logo.logo_url" alt="Site Logo" class="h-10 w-auto">
          <span v-else>ShopApp</span>
        </router-link>
        
        <!-- Navigation -->
        <nav class="hidden md:flex space-x-6">
          <router-link to="/" class="text-gray-700 hover:text-indigo-600 transition-colors">
            Home
          </router-link>
          <router-link to="/products" class="text-gray-700 hover:text-indigo-600 transition-colors">
            Products
          </router-link>
          <router-link to="/categories" class="text-gray-700 hover:text-indigo-600 transition-colors">
            Categories
          </router-link>
          <router-link to="/about" class="text-gray-700 hover:text-indigo-600 transition-colors">
            About
          </router-link>
        </nav>
        
        <!-- User menu & Cart -->
        <div class="flex items-center space-x-4">
          <button class="text-gray-700 hover:text-indigo-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </button>
          
          <router-link to="/login" class="text-gray-700 hover:text-indigo-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </router-link>
          
          <!-- Mobile menu button -->
          <button class="md:hidden text-gray-700 hover:text-indigo-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import { getCartItemCount } from '@/utils/cart';
import { getWishlistCount } from '@/utils/wishlist';
import axios from 'axios';

export default {
  name: 'Header',
  setup() {
    const router = useRouter();
    
    // State
    const mobileMenuOpen = ref(false);
    const mobileCategoriesOpen = ref(false);
    const categoriesDropdownOpen = ref(false);
    const accountDropdownOpen = ref(false);
    const cartDropdownOpen = ref(false);
    const isLoggedIn = ref(false);
    const user = ref(null);
    const cartItemsCount = ref(0);
    const wishlistItemsCount = ref(0);
    const categories = ref([]);
    const logo = ref(null);
    
    // Toggle mobile categories
    const toggleMobileCategories = () => {
      mobileCategoriesOpen.value = !mobileCategoriesOpen.value;
    };
    
    // Toggle categories dropdown on desktop
    const toggleCategoriesDropdown = () => {
      categoriesDropdownOpen.value = !categoriesDropdownOpen.value;
      if (categoriesDropdownOpen.value) {
        accountDropdownOpen.value = false;
        cartDropdownOpen.value = false;
      }
    };
    
    // Toggle account dropdown
    const toggleAccountDropdown = () => {
      accountDropdownOpen.value = !accountDropdownOpen.value;
      if (accountDropdownOpen.value) {
        categoriesDropdownOpen.value = false;
        cartDropdownOpen.value = false;
      }
    };
    
    // Toggle cart dropdown
    const toggleCartDropdown = () => {
      cartDropdownOpen.value = !cartDropdownOpen.value;
      if (cartDropdownOpen.value) {
        categoriesDropdownOpen.value = false;
        accountDropdownOpen.value = false;
      }
    };
    
    // Logout function
    const logout = () => {
      localStorage.removeItem('user');
      localStorage.removeItem('token');
      isLoggedIn.value = false;
      user.value = null;
      accountDropdownOpen.value = false;
      router.push('/login');
    };
    
    // Fetch categories
    const fetchCategories = async () => {
      try {
        const response = await axios.get('/api/categories');
        if (response.data && response.data.data) {
          categories.value = response.data.data;
        }
      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    };

    // Update cart count
    const updateCartCount = () => {
      cartItemsCount.value = getCartItemCount();
    };

    // Update wishlist count
    const updateWishlistCount = () => {
      wishlistItemsCount.value = getWishlistCount();
    };
    
    // Check if user is logged in
    const checkLoginStatus = () => {
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        try {
          user.value = JSON.parse(storedUser);
          isLoggedIn.value = true;
        } catch (e) {
          console.error('Error parsing user data:', e);
        }
      }
    };
    
    // Fetch active logo
    const fetchLogo = async () => {
      try {
        const response = await axios.get('http://localhost:8000/api/logo/active');
        if (response.data) {
          logo.value = response.data;
        }
      } catch (error) {
        console.error('Error fetching logo:', error);
      }
    };
    
    onMounted(() => {
      checkLoginStatus();
      fetchCategories();
      updateCartCount();
      updateWishlistCount();
      fetchLogo();
      
      // Listen for storage events
      window.addEventListener('storage', (e) => {
        if (e.key === 'user' || e.key === 'token') {
          checkLoginStatus();
        }
      });
      
      // Listen for cart update events
      document.addEventListener('cart-updated', updateCartCount);

      // Listen for wishlist update events
      document.addEventListener('wishlist-updated', updateWishlistCount);
      
      // Close dropdowns when clicking outside
      document.addEventListener('click', (e) => {
        const accountDropdown = document.querySelector('.account-dropdown');
        const cartDropdown = document.querySelector('.cart-dropdown');
        const categoriesDropdown = document.querySelector('.categories-dropdown');
        
        if (accountDropdown && !accountDropdown.contains(e.target)) {
          accountDropdownOpen.value = false;
        }
        
        if (cartDropdown && !cartDropdown.contains(e.target)) {
          cartDropdownOpen.value = false;
        }
        
        if (categoriesDropdown && !categoriesDropdown.contains(e.target)) {
          categoriesDropdownOpen.value = false;
        }
      });
    });
    
    return {
      mobileMenuOpen,
      mobileCategoriesOpen,
      categoriesDropdownOpen,
      accountDropdownOpen,
      cartDropdownOpen,
      isLoggedIn,
      user,
      cartItemsCount,
      wishlistItemsCount,
      categories,
      logo,
      toggleMobileCategories,
      toggleCategoriesDropdown,
      toggleAccountDropdown,
      toggleCartDropdown,
      logout
    };
  }
}
</script> 