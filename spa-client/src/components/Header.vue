<!-- Advanced E-commerce Header -->
<template>
  <header class="bg-white shadow-md sticky top-0 z-50">
    <!-- Top Bar with Announcement -->
    <div class="bg-blue-700 text-white text-sm py-2">
      <div class="container mx-auto px-4 flex justify-center md:justify-between items-center">
        <p class="hidden md:block">{{ loadingAnnouncement ? '' : announcementMessage }}</p>
        <div class="flex gap-4">
          <router-link to="/contact-us" class="hover:text-blue-200 transition">Help Center</router-link>
          <router-link to="/contact-us#store-map" class="hover:text-blue-200 transition">Find a Store</router-link>
        </div>
      </div>
    </div>
    
    <!-- Main Header -->
    <div class="container mx-auto px-4 py-4">
      <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <!-- Logo -->
        <div class="flex justify-between items-center">
          <router-link to="/" class="text-2xl font-bold text-blue-700">
            <!-- Only show a logo if we have valid data to show -->
            <div v-if="logo && logo.base64_image" class="flex items-center">
              <img 
                :src="logo.base64_image" 
                alt="Aligans Logo" 
                class="h-12" 
                @error="handleLogoError"
              />
            </div>
            
            <!-- If no logo is available, show a skeleton loader during loading -->
            <div v-else-if="logoLoading" class="flex items-center gap-2 animate-pulse">
              <span class="text-3xl flex items-center justify-center h-10 w-10 bg-blue-100 text-blue-500 rounded-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </span>
            </div>
            
            <!-- Show the basic (not dummy) logo only if we received valid data -->
            <div v-else-if="logo && (logo.image_url || logo.image_path)" class="flex items-center">
              <img 
                :src="(logo.image_url || logo.image_path) + '?t=' + new Date().getTime()" 
                alt="Aligans Logo" 
                class="h-12" 
                @error="handleLogoError" 
                @load="handleLogoLoaded"
              />
            </div>
            
            <!-- Minimal text logo as a pure placeholder during load with no dummy content -->
            <div v-else class="flex items-center gap-2">
              <span class="text-3xl flex items-center justify-center h-10 w-10 bg-blue-700 text-white rounded-md">A</span>
            </div>
          </router-link>
          
          <!-- Mobile Menu Toggle -->
          <button 
            @click="mobileMenuOpen = !mobileMenuOpen" 
            class="md:hidden text-gray-500 hover:text-blue-700 focus:outline-none"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path v-if="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
        
        <!-- Search Bar -->
        <div class="relative hidden md:block flex-grow max-w-2xl">
          <input 
            type="text" 
            placeholder="Search for products..." 
            v-model="searchQuery"
            @keyup.enter="performSearch"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
          <button 
            @click="performSearch"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </button>
        </div>
        
        <!-- Navigation Icons -->
        <div class="hidden md:flex items-center gap-4">
          <!-- Account Dropdown -->
          <div class="relative account-dropdown-container">
            <button class="flex items-center gap-1 text-gray-700 hover:text-blue-700 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              <span v-if="isLoggedIn && user">{{ user.name }}</span>
              <span v-else>Account</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            
            <!-- Account Dropdown Menu -->
            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 account-dropdown">
              <template v-if="isLoggedIn">
                <router-link to="/account" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Account</router-link>
                <router-link to="/account/orders" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Orders</router-link>
                <a href="#" @click.prevent="logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign Out</a>
              </template>
              <template v-else>
                <router-link to="/login" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Login</router-link>
                <router-link to="/register" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Register</router-link>
              </template>
            </div>
          </div>
          
          <!-- Favorites -->
          <div class="relative wishlist-dropdown-container">
            <router-link 
              to="/wishlist" 
              class="text-gray-700 hover:text-blue-700 transition relative"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
              <span v-if="wishlistCount > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ wishlistCount }}</span>
            </router-link>
            
            <!-- Wishlist Dropdown Content -->
            <div 
              class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-4 z-50 wishlist-dropdown"
            >
              <div class="px-4 py-2 border-b border-gray-100">
                <h3 class="font-medium">Wishlist ({{ wishlistCount }})</h3>
              </div>
              
              <div v-if="wishlistItemsLocal.length === 0" class="p-4 text-center text-gray-500">
                Your wishlist is empty
              </div>
              
              <div v-else class="max-h-80 overflow-y-auto">
                <!-- Wishlist Items -->
                <div v-for="(item, index) in wishlistItemsLocal" :key="'wishlist-item-'+index" class="flex items-center gap-4 p-4 hover:bg-gray-50">
                  <router-link :to="'/products/' + item.id" class="flex items-center gap-4 flex-grow">
                    <div class="h-16 w-16 bg-gray-100 rounded">
                      <img 
                        :src="item.image || 'https://picsum.photos/id/26/100/100'" 
                        @error="handleImageError" 
                        class="h-full w-full object-cover rounded"
                      >
                    </div>
                    <div class="flex-grow">
                      <h4 class="font-medium text-sm">{{ item.name }}</h4>
                      <p class="text-gray-500 text-xs">${{ parseFloat(item.price).toFixed(2) }}</p>
                    </div>
                  </router-link>
                  <button @click.stop.prevent="removeWishlistItem(index)" class="text-gray-400 hover:text-red-500 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
              
              <div class="px-4 py-3 border-t border-gray-100">
                <div class="mt-4 flex flex-col gap-2">
                  <router-link to="/wishlist" class="bg-gray-100 hover:bg-gray-200 text-center py-2 rounded-md text-sm font-medium transition">
                    View Wishlist
                  </router-link>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Cart Dropdown -->
          <div class="relative cart-dropdown-container">
            <button 
              class="flex items-center gap-1 text-gray-700 hover:text-blue-700 transition relative"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
              <span>Cart</span>
              <span v-if="cartItemCount > 0" class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ cartItemCount }}</span>
            </button>
            
            <!-- Cart Dropdown Content -->
            <div 
              class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-4 z-50 cart-dropdown"
            >
              <div class="px-4 py-2 border-b border-gray-100">
                <h3 class="font-medium">Shopping Cart ({{ cartItemCount }})</h3>
              </div>
              
              <div v-if="cartItemsLocal.length === 0" class="p-4 text-center text-gray-500">
                Your cart is empty
              </div>
              
              <div v-else class="max-h-80 overflow-y-auto">
                <!-- Cart Items -->
                <div v-for="(item, index) in cartItemsLocal" :key="'cart-item-'+index" class="flex items-center gap-4 p-4 hover:bg-gray-50">
                  <router-link :to="'/products/' + item.id" class="flex items-center gap-4 flex-grow">
                    <div class="h-16 w-16 bg-gray-100 rounded">
                      <img 
                        :src="item.image || 'https://picsum.photos/id/26/100/100'" 
                        @error="handleImageError" 
                        class="h-full w-full object-cover rounded"
                      >
                    </div>
                    <div class="flex-grow">
                      <h4 class="font-medium text-sm">{{ item.name }}</h4>
                      <p class="text-gray-500 text-xs">{{ item.quantity }} x ${{ parseFloat(item.price).toFixed(2) }}</p>
                    </div>
                  </router-link>
                  <button @click.stop.prevent="removeCartItem(index)" class="text-gray-400 hover:text-red-500 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
              
              <div class="px-4 py-3 border-t border-gray-100">
                <div class="flex justify-between font-medium">
                  <span>Subtotal:</span>
                  <span>${{ calculateSubtotal().toFixed(2) }}</span>
                </div>
                <div class="mt-4 flex flex-col gap-2">
                  <router-link to="/cart" class="bg-gray-100 hover:bg-gray-200 text-center py-2 rounded-md text-sm font-medium transition">
                    View Cart
                  </router-link>
                  <router-link to="/checkout/shipping" class="bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-md text-sm font-medium transition">
                    Checkout
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Mobile Search - Only visible on mobile -->
      <div class="md:hidden relative mt-4">
        <input 
          type="text" 
          placeholder="Search for products..." 
          v-model="searchQuery"
          @keyup.enter="performSearch"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
        <button 
          @click="performSearch"
          class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-700">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </button>
      </div>
    </div>
    
    <!-- Main Navigation -->
    <nav class="border-t border-gray-200 hidden md:block">
      <div class="container mx-auto px-4">
        <ul class="flex">
          <!-- Mega Menu Dropdown -->
          <li class="group relative categories-dropdown-container">
            <button 
              @click="toggleCategoriesDropdown" 
              class="flex items-center gap-1 text-gray-700 hover:text-blue-700 transition px-4 py-4"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <span>Categories</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            
            <!-- Categories Dropdown Menu -->
            <div v-show="categoriesDropdownOpen" class="absolute left-0 mt-2 w-64 bg-white shadow-lg rounded-md z-50">
              <!-- Only show the loading indicator if we have no categories -->
              <div v-if="categoriesLoading && topCategories.length === 0" class="py-4 px-4 flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="ml-2 text-sm text-gray-600">Loading categories...</span>
              </div>
              
              <!-- Only show categories if they're loaded and not empty -->
              <div v-else-if="topCategories.length > 0" class="py-2">
                <div 
                  v-for="category in topCategories" 
                  :key="category.id"
                  @mouseenter="setHoveredCategory(category.id)"
                  @mouseleave="resetHoveredCategory"
                  class="relative group px-4 py-2 hover:bg-gray-50"
                >
                  <router-link :to="'/category/' + category.id" class="flex items-center justify-between w-full">
                    <span>{{ category.name }}</span>
                    <svg v-if="getSubcategories(category.id).length > 0" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </router-link>
                  
                  <!-- Subcategories Menu -->
                  <div 
                    v-if="hoveredCategory === category.id && getSubcategories(category.id).length > 0"
                    class="absolute left-full top-0 min-w-[200px] bg-white shadow-lg rounded-md py-2"
                  >
                    <router-link 
                      v-for="subcategory in getSubcategories(category.id)"
                      :key="subcategory.id"
                      :to="'/category/' + subcategory.id"
                      class="block px-4 py-2 hover:bg-gray-50 whitespace-nowrap"
                    >
                      {{ subcategory.name }}
                    </router-link>
                  </div>
                </div>
              </div>
              
              <!-- Show a message if no categories are available -->
              <div v-else-if="!categoriesLoading && topCategories.length === 0" class="py-4 px-4 text-center text-gray-500">
                No categories available
              </div>
            </div>
          </li>
          
          <li>
            <router-link to="/products" class="block px-4 py-4 text-gray-700 hover:text-blue-700 font-medium">
              All Products
            </router-link>
          </li>
          <li>
            <router-link to="/new-arrivals" class="block px-4 py-4 text-gray-700 hover:text-blue-700 font-medium">
              New Arrivals
            </router-link>
          </li>
          <li>
            <router-link to="/products/sale" class="block px-4 py-4 text-red-600 hover:text-red-700 font-medium">
              Sale
            </router-link>
          </li>
          <li>
            <router-link to="/teams" class="block px-4 py-4 text-gray-700 hover:text-blue-700 font-medium">
              Teams
            </router-link>
          </li>
          <li>
            <router-link to="/events" class="block px-4 py-4 text-gray-700 hover:text-blue-700 font-medium">
              Events
            </router-link>
          </li>
        </ul>
      </div>
    </nav>
    
    <!-- Mobile Menu -->
    <div 
      v-show="mobileMenuOpen" 
      class="md:hidden px-4 py-2 bg-white border-t border-gray-200"
    >
      <ul class="space-y-2">
        <li>
          <button @click="mobileCategoriesOpen = !mobileCategoriesOpen" class="flex justify-between w-full py-2 px-4 rounded-md text-left font-medium hover:bg-gray-50">
            <span>Categories</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="{'rotate-180': mobileCategoriesOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div v-show="mobileCategoriesOpen" class="py-2 pl-8 space-y-2 bg-gray-50 rounded-md mt-1">
            <!-- Dynamic mobile categories - only show if loaded -->
            <template v-if="!categoriesLoading && topCategories.length > 0">
              <router-link 
                v-for="category in topCategories" 
                :key="category.id" 
                :to="'/category/' + category.id" 
                class="block py-1 hover:text-blue-700"
              >
                {{ category.name }}
              </router-link>
            </template>
            <!-- Loading state -->
            <div v-else-if="categoriesLoading" class="py-1 flex items-center gap-2 text-gray-500">
              <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>Loading...</span>
            </div>
            <!-- No categories state -->
            <div v-else class="py-1 text-gray-500">No categories available</div>
          </div>
        </li>
        <li><router-link to="/products" class="block py-2 px-4 hover:bg-gray-50 rounded-md">All Products</router-link></li>
        <li><router-link to="/new-arrivals" class="block py-2 px-4 hover:bg-gray-50 rounded-md">New Arrivals</router-link></li>
        <li><router-link to="/products/sale" class="block py-2 px-4 hover:bg-gray-50 rounded-md text-red-600 hover:text-red-700">Sale</router-link></li>
        <li><router-link to="/teams" class="block py-2 px-4 hover:bg-gray-50 rounded-md">Teams</router-link></li>
        <li><router-link to="/events" class="block py-2 px-4 hover:bg-gray-50 rounded-md">Events</router-link></li>
        
        <!-- Mobile Account Links -->
        <li v-if="isLoggedIn">
          <router-link to="/account" class="block py-2 px-4 hover:bg-gray-50 rounded-md">My Account</router-link>
        </li>
        <li v-if="isLoggedIn">
          <router-link to="/account/orders" class="block py-2 px-4 hover:bg-gray-50 rounded-md">Orders</router-link>
        </li>
        <li v-if="isLoggedIn">
          <a href="#" @click.prevent="logout" class="block py-2 px-4 hover:bg-gray-50 rounded-md">Sign Out</a>
        </li>
        <li v-if="!isLoggedIn">
          <router-link to="/login" class="block py-2 px-4 hover:bg-gray-50 rounded-md">Login</router-link>
        </li>
        <li v-if="!isLoggedIn">
          <router-link to="/register" class="block py-2 px-4 hover:bg-gray-50 rounded-md">Register</router-link>
        </li>
        
        <li>
          <router-link to="/wishlist" class="block py-2 px-4 hover:bg-gray-50 rounded-md">
            Wishlist <span v-if="wishlistCount > 0" class="ml-1 bg-red-500 text-white text-xs rounded-full px-2 py-1">{{ wishlistCount }}</span>
          </router-link>
        </li>
        
        <li><router-link to="/cart" class="block py-2 px-4 hover:bg-gray-50 rounded-md">Cart <span v-if="cartItemCount > 0" class="ml-1 bg-blue-600 text-white text-xs rounded-full px-2 py-1">{{ cartItemCount }}</span></router-link></li>
      </ul>
    </div>
  </header>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import { getCartItemCount, getCart, removeFromCart, getUniqueCartItemCount } from '@/utils/cart';
import { getWishlistCount, getWishlistSync, getWishlist, removeFromWishlist, getWishlistCountSync } from '@/utils/wishlist';
import { ref, onMounted, onUnmounted, computed, watch } from 'vue';
import axios from 'axios';

export default {
  name: 'Header',
  data() {
    // Get cached logo from localStorage immediately, before any component operations
    const cachedLogoData = (() => {
      try {
        const cachedLogo = localStorage.getItem('cached_logo');
        if (cachedLogo) {
          const parsed = JSON.parse(cachedLogo);
          if (parsed && parsed.base64Image) {
            console.log('Pre-initialized logo from localStorage');
            return {
              logo: parsed.data || null,
              cachedBase64: parsed.base64Image || null
            };
          }
        }
        return { logo: null, cachedBase64: null };
      } catch (e) {
        console.error('Error pre-loading cached logo:', e);
        return { logo: null, cachedBase64: null };
      }
    })();

    return {
      announcementMessage: '', // Initialize as empty string instead of default dummy text
      loadingAnnouncement: true, // Track if announcement is loading
      mobileMenuOpen: false,
      mobileCategoriesOpen: false,
      categoriesDropdownOpen: false,
      cartItemsLocal: [], // Local reactive cart items
      wishlistItemsLocal: [], // Local reactive wishlist items
      cartCount: 0, // Direct reactive counter
      wishlistCountLocal: 0, // Direct reactive counter
      categories: [], // All categories
      topCategories: [], // Top level categories
      categoriesLoading: true,
      categoriesError: null,
      hoveredCategory: null, // Track which category is being hovered
      logo: cachedLogoData.logo, // Pre-loaded from localStorage
      cachedBase64: cachedLogoData.cachedBase64, // Pre-loaded base64 image
      logoLoading: true, // Always start with loading state
      logoError: null,
      searchQuery: ''
    };
  },
  computed: {
    ...mapGetters({
      isLoggedIn: 'auth/isLoggedIn',
      user: 'auth/user'
    }),
    wishlistCount() {
      return this.wishlistCountLocal;
    },
    cartItemCount() {
      return this.cartCount;
    }
  },
  mounted() {
    console.log('Header component mounted');
    
    // Initialize reactive local arrays and counters
    this.updateLocalCart();
    this.updateLocalWishlist();
    
    // Initialize auth state from localStorage
    this.checkAuthState();
    
    // Fetch fresh logo data in the background
    this.fetchLogo();
    
    // Fetch header announcement message
    this.fetchHeaderAnnouncement();
    
    // Fetch categories
    this.fetchCategories();
    
    // Listen for wishlist updates
    document.addEventListener('wishlist-updated', this.handleWishlistUpdate);
    
    // Listen for cart updates
    document.addEventListener('cart-updated', this.handleCartUpdate);
    
    // Listen for auth updates
    document.addEventListener('user-login', this.checkAuthState);
    document.addEventListener('user-logout', this.checkAuthState);
    
    // Listen for clicks outside the categories dropdown to close it
    document.addEventListener('click', this.closeDropdownsOnClickOutside);
  },
  beforeUnmount() {
    // Remove event listeners when component is unmounted
    document.removeEventListener('wishlist-updated', this.handleWishlistUpdate);
    document.removeEventListener('cart-updated', this.handleCartUpdate);
    document.removeEventListener('user-login', this.checkAuthState);
    document.removeEventListener('user-logout', this.checkAuthState);
    document.removeEventListener('click', this.closeDropdownsOnClickOutside);
  },
  methods: {
    ...mapActions({
      logoutAction: 'auth/logout'
    }),
    async fetchHeaderAnnouncement() {
      try {
        const response = await axios.get('http://localhost:8000/api/settings/header-announcement');
        if (response.data && response.data.success) {
          this.announcementMessage = response.data.data;
          this.loadingAnnouncement = false;
        }
      } catch (error) {
        console.error('Error fetching header announcement:', error);
        // Keep using the default message if there's an error
      }
    },
    async fetchCategories() {
      try {
        this.categoriesLoading = true;
        this.categoriesError = null;
        
        // Reset categories to prevent showing stale data during loading
        this.categories = [];
        this.topCategories = [];
        
        const response = await axios.get('/api/categories');
        
        if (response.data && response.data.data) {
          this.categories = response.data.data;
          
          // Filter top-level categories (parent_id is null or 0)
          this.topCategories = this.categories.filter(cat => !cat.parent_id);
          
          console.log('Categories loaded:', this.categories.length);
          console.log('Top categories:', this.topCategories.length);
        }
      } catch (error) {
        console.error('Error fetching categories:', error);
        this.categoriesError = 'Failed to load categories';
      } finally {
        this.categoriesLoading = false;
      }
    },
    getSubcategories(parentId) {
      return this.categories.filter(cat => cat.parent_id === parentId);
    },
    setHoveredCategory(categoryId) {
      this.hoveredCategory = categoryId;
    },
    resetHoveredCategory() {
      this.hoveredCategory = null;
    },
    checkAuthState() {
      // This method checks localStorage directly in case store isn't updated
      const token = localStorage.getItem('token');
      const userStr = localStorage.getItem('user');
      
      if (token && userStr) {
        try {
          // Manually check and initialize user state
          const userData = JSON.parse(userStr);
          if (!this.isLoggedIn) {
            this.$store.commit('auth/setUser', userData);
            this.$store.commit('auth/setLoggedIn', true);
            this.$store.commit('auth/setToken', token);
            console.log('Auth state updated from localStorage in Header');
          }
        } catch (e) {
          console.error('Error parsing user data in checkAuthState:', e);
        }
      } else if (this.isLoggedIn) {
        // If user is logged out, but store says logged in, update store
        this.$store.commit('auth/logout');
      }
    },
    updateLocalCart() {
      try {
        this.cartItemsLocal = getCart();
        // Update the direct reactive counter
        this.cartCount = getUniqueCartItemCount(); // Use unique product count instead of total quantity
        console.log('Cart count updated (unique products): ', this.cartCount);
        this.$forceUpdate(); // Force update to refresh UI
      } catch (error) {
        console.error('Error updating local cart:', error);
      }
    },
    updateLocalWishlist() {
      try {
        // Use sync version first for immediate UI update
        this.wishlistItemsLocal = getWishlistSync();
        this.wishlistCountLocal = this.wishlistItemsLocal.length;
        
        // Then update with API data
        getWishlist().then(items => {
          this.wishlistItemsLocal = items;
          this.wishlistCountLocal = items.length;
          this.$forceUpdate(); // Force update to refresh UI
        }).catch(error => {
          console.error('Error fetching wishlist from API:', error);
        });
        
        console.log('Wishlist count updated: ', this.wishlistCountLocal);
        this.$forceUpdate(); // Force update to refresh UI
      } catch (error) {
        console.error('Error updating local wishlist:', error);
      }
    },
    removeCartItem(index) {
      // First update the local state immediately for better UX
      if (index >= 0 && index < this.cartItemsLocal.length) {
        // Decrement the cart count by 1 since we're removing one unique product
        this.cartCount -= 1;
        this.cartItemsLocal.splice(index, 1);
      }
      
      // Then use the utility function to update localStorage and trigger event
      removeFromCart(index);
      
      // Make sure local state is in sync with localStorage
      this.$nextTick(() => {
        this.updateLocalCart();
      });
    },
    removeWishlistItem(index) {
        const itemId = this.wishlistItemsLocal[index].id;
      // Remove from array immediately for UI responsiveness
        this.wishlistItemsLocal.splice(index, 1);
      this.wishlistCountLocal = this.wishlistItemsLocal.length;
        
      // Then perform the actual removal in the background
      removeFromWishlist(itemId).catch(error => {
        console.error('Error removing wishlist item:', error);
        // If there's an error, refresh the wishlist to ensure it's in sync
          this.updateLocalWishlist();
        });
      
      // Dispatch event to update other components
      document.dispatchEvent(new CustomEvent('wishlist-updated'));
    },
    handleCartUpdate() {
      console.log('Cart updated event received in Header');
      
      // First update the count immediately using localStorage value
      this.cartCount = getUniqueCartItemCount(); // Use unique product count
      
      // Then do full update
      this.updateLocalCart();
    },
    handleWishlistUpdate() {
      console.log('Wishlist updated event received in Header');
      
      // First update the count immediately using localStorage value
      const currentWishlist = getWishlist();
      this.wishlistCountLocal = currentWishlist.length;
      
      // Then do full update
      this.updateLocalWishlist();
    },
    handleImageError(event) {
      event.target.src = 'https://picsum.photos/id/26/100/100';
    },
    calculateSubtotal() {
      return this.cartItemsLocal.reduce((total, item) => total + (item.price * item.quantity), 0);
    },
    logout() {
      this.logoutAction();
      
      // Dispatch a custom event to notify components of logout
      document.dispatchEvent(new CustomEvent('user-logout'));
      
      // Close the account dropdown
      const accountDropdown = this.$el.querySelector('.account-dropdown');
      if (accountDropdown) {
        this.accountDropdownOpen = false;
      }
      
      this.$router.push('/login');
    },
    closeDropdownsOnClickOutside(event) {
      // Close categories dropdown if click is outside of it
      if (this.categoriesDropdownOpen) {
        const categoriesDropdown = this.$el.querySelector('.categories-dropdown-container');
        if (categoriesDropdown && !categoriesDropdown.contains(event.target)) {
          this.categoriesDropdownOpen = false;
        }
      }
    },
    toggleCategoriesDropdown(event) {
      // Stop event propagation to prevent immediate closing
      event.stopPropagation();
      this.categoriesDropdownOpen = !this.categoriesDropdownOpen;
    },
    async fetchLogo() {
      // Always start in loading state to prevent showing dummy data
      this.logoLoading = true;
      
      try {
        // Fetch updated logo data
        const response = await axios.get('http://localhost:8000/api/logo/active');
        console.log('Logo data received:', response.data);
        
        if (response.data) {
          const freshLogo = response.data;
          
          // Ensure URL is correct
          if (freshLogo && !freshLogo.image_url && freshLogo.image_path) {
            const pathOnly = freshLogo.image_path.replace(/^http:\/\/localhost:8000\//, '');
            freshLogo.image_path = `http://localhost:8000/${pathOnly}`;
          }
          
          // Update logo object
          this.logo = freshLogo;
          
          // Check if we need to update the cached base64
          const needsNewBase64 = this.shouldUpdateCache(freshLogo);
          
          if (needsNewBase64) {
            // Get and cache the base64 version
            this.fetchAndCacheBase64(freshLogo.image_url || freshLogo.image_path);
          }
        } 
      } catch (error) {
        console.error('Error fetching logo:', error);
        this.logoError = 'Error loading logo';
      } finally {
        // Set loading to false regardless of outcome
        this.logoLoading = false;
      }
    },
    
    shouldUpdateCache(newLogo) {
      // Check if we need to update the cached base64
      try {
        const cachedLogoStr = localStorage.getItem('cached_logo');
        if (!cachedLogoStr) return true;
        
        const cachedLogo = JSON.parse(cachedLogoStr);
        if (!cachedLogo || !cachedLogo.data) return true;
        
        // Compare important logo data to see if it changed
        const oldPath = cachedLogo.data.image_path;
        const newPath = newLogo.image_path;
        const oldUrl = cachedLogo.data.image_url;
        const newUrl = newLogo.image_url;
        
        // If any of these changed, we need a new base64
        if (oldPath !== newPath || oldUrl !== newUrl) {
          console.log('Logo has changed, updating cache');
          return true;
        }
        
        // Check if the cache is older than 24 hours
        const cacheTime = cachedLogo.timestamp || 0;
        const now = new Date().getTime();
        if (now - cacheTime > 86400000) { // 24 hours
          console.log('Cache is older than 24 hours, updating');
          return true;
        }
        
        console.log('Using existing cache, no update needed');
        return false;
      } catch (e) {
        console.error('Error checking cache status:', e);
        return true;
      }
    },
    
    async fetchAndCacheBase64(src) {
      if (!src) return;
      
      try {
        // Add cache-busting parameter
        const imgSrc = src + '?t=' + new Date().getTime();
        
        // Fetch the image
        console.log('Fetching image for base64 conversion:', imgSrc);
        const response = await fetch(imgSrc);
        const blob = await response.blob();
        
        // Convert to base64
        const reader = new FileReader();
        reader.onloadend = () => {
          const base64data = reader.result;
          
          // Update the display immediately
          this.cachedBase64 = base64data;
          
          // Also update localStorage for next visit
          localStorage.setItem('cached_logo', JSON.stringify({
            data: this.logo,
            base64Image: base64data,
            timestamp: new Date().getTime()
          }));
          
          console.log('Logo cached as base64');
        };
        
        reader.readAsDataURL(blob);
      } catch (e) {
        console.error('Error converting logo to base64:', e);
      }
    },
    handleLogoError(event) {
      console.error('Error loading logo image:', event);
      // Set fallback image or just hide the image
      event.target.style.display = 'none';
      // Show the default logo instead
      this.logoError = 'Failed to load logo image';
    },
    handleLogoLoaded() {
      console.log('Logo loaded successfully');
    },
    performSearch() {
      // Don't perform an empty search
      if (!this.searchQuery.trim()) {
        return;
      }
      
      console.log('Performing search with query:', this.searchQuery.trim());
      
      // Navigate to search results page with query parameter
      this.$router.push({
        path: '/search',
        query: { q: this.searchQuery.trim() }
      }).catch(err => {
        // Ignore navigation errors to same route
        if (err.name !== 'NavigationDuplicated') {
          console.error('Navigation error:', err);
        }
      });
      
      // Close mobile menu if open
      this.mobileMenuOpen = false;
      
      // Clear search query after search
      // Uncomment if you want to clear after search: this.searchQuery = '';
    }
  }
};
</script> 

<style scoped>
/* Add a smooth transition for hover effect */
.group:hover .group-hover\:block {
  display: block;
}

/* Add a slight delay for dropdown to prevent accidental closing */
.group-hover\:block {
  transition-delay: 0.1s;
}

/* Add subtle animations for category items */
.category-column ul li a {
  transform: translateX(0);
  transition: transform 0.2s ease;
}

.category-column ul li a:hover {
  transform: translateX(3px);
}

/* Cart dropdown hover behavior */
.cart-dropdown {
  display: none;
  top: 100%;
  opacity: 0;
  transition: opacity 0.2s;
}

.cart-dropdown-container:hover .cart-dropdown {
  display: block;
  opacity: 1;
}

/* Wishlist dropdown hover behavior */
.wishlist-dropdown {
  display: none;
  top: 100%;
  opacity: 0;
  transition: opacity 0.2s;
}

.wishlist-dropdown-container:hover .wishlist-dropdown {
  display: block;
  opacity: 1;
}

/* Account dropdown hover behavior */
.account-dropdown {
  display: none;
  top: 100%;
  opacity: 0;
  transition: opacity 0.2s;
}

.account-dropdown-container:hover .account-dropdown {
  display: block;
  opacity: 1;
}

/* Add padding to ensure no hover gaps */
.cart-dropdown-container::after,
.wishlist-dropdown-container::after,
.account-dropdown-container::after {
  content: '';
  position: absolute;
  height: 20px;
  left: 0;
  right: 0;
  bottom: -20px;
  z-index: 40;
}

@media (max-width: 768px) {
  .mobile-menu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-in-out;
  }
  
  .mobile-menu.open {
    max-height: 1000px;
  }
}
</style> 