<!-- Advanced E-commerce Header -->
<template>
  <header :class="['sticky top-0 z-50 modern-header transition-all duration-300', {'shadow-lg': isScrolled}]">
    <!-- Top Bar with Announcement -->
    <div class="bg-gradient-to-r from-blue-800 to-blue-600 text-white text-sm py-2 announcement-bar">
      <div class="container mx-auto px-4 flex justify-center md:justify-between items-center">
        <p class="hidden md:block animate-fadeIn">{{ loadingAnnouncement ? '' : announcementMessage }}</p>
        <div class="flex gap-4">
          <router-link to="/contact-us" class="hover:text-blue-200 transition-all transform hover:scale-105">Help Center</router-link>
          <router-link to="/contact-us#store-map" class="hover:text-blue-200 transition-all transform hover:scale-105">Find a Store</router-link>
        </div>
      </div>
    </div>
    
    <!-- Missing attributes modal -->
    <div v-if="showMissingAttributesModal" class="fixed inset-0 flex items-center justify-center z-[100000]" 
         ref="modalRoot" 
         role="dialog"
         aria-labelledby="missing-attributes-title"
         aria-modal="true"
         @click.self="closeMissingAttributesModal">
      <!-- Full-screen blocking overlay with animation -->
      <div class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm transition-opacity duration-300" 
           @click="closeMissingAttributesModal"></div>
      
      <!-- Modal content with animation -->
      <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-sm mx-4 z-10 transform transition-all duration-300 scale-100 opacity-100"
           tabindex="-1"
           ref="modalContent">
        <!-- Blue header -->
        <div class="bg-blue-600 px-4 py-3 rounded-t-lg flex justify-between items-center">
          <h3 id="missing-attributes-title" class="text-base font-medium text-white">Required Attributes Missing</h3>
          <button @click="closeMissingAttributesModal" 
                  class="text-white hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-white rounded-full"
                  aria-label="Close modal">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <!-- Modal content -->
        <div class="p-5 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
          
          <p class="text-sm text-gray-700 mb-4">
            Please select size and color for items in your cart before checkout.
          </p>
          
          <!-- Action buttons with improved focus styling -->
          <div class="flex justify-center gap-3">
            <button @click="goToProductPage" 
                    type="button" 
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
              View Products
            </button>
            <button @click="closeMissingAttributesModal" 
                    type="button" 
                    class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-200">
              Close
            </button>
                </div>
              </div>
            </div>
          </div>
    
    <!-- Teleport the alert to body to ensure it's outside header context -->
    <teleport to="body">
      <!-- Simple Alert Modal (Browser Alert Style) -->
      <div v-if="showSimpleAlert" class="alert-overlay">
        <!-- Centered alert box -->
        <div class="alert-box simple-alert-animation">
          <div>
            <!-- Alert message -->
            <div class="flex items-start mb-4">
              <!-- Warning icon -->
              <div class="flex-shrink-0 mr-3">
                <svg class="h-6 w-6 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              
              <!-- Text styled professionally -->
              <div>
                <h3 class="text-sm font-medium text-gray-900 mb-1">Selection Required</h3>
                <p class="text-gray-700 text-sm">{{ simpleAlertMessage }}</p>
              </div>
            </div>
            
            <!-- Button aligned right -->
            <div class="flex justify-end">
              <button 
                @click="closeSimpleAlert" 
                class="px-4 py-1.5 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors duration-150"
              >
                OK
            </button>
          </div>
        </div>
      </div>
    </div>
    </teleport>
    
    <!-- Main Header -->
    <div class="bg-white border-b border-gray-100">
      <div class="container mx-auto px-4 py-3 main-header-container">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
          <!-- Logo -->
          <div class="flex justify-between items-center">
            <router-link to="/" class="text-2xl font-bold text-blue-700 logo-container transition-all hover:opacity-90">
              <!-- Only show a logo if we have valid data to show and base64 version is available -->
              <div v-if="logo && logo.base64_image" class="flex items-center">
                <Logo 
                  :src="logo.base64_image" 
                  alt="Aligans Logo" 
                  height="96px"
                  @error="handleLogoError"
                />
              </div>
              
              <!-- If no logo is available, show a skeleton loader during loading -->
              <div v-else-if="logoLoading" class="flex items-center gap-2 animate-pulse">
                <span class="text-4xl flex items-center justify-center h-16 w-16 bg-blue-100 text-blue-500 rounded-md">
                  <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                </span>
              </div>
              
              <!-- Show the basic (not dummy) logo only if we received valid data -->
              <div v-else-if="logo && (logo.image_url || logo.image_path)" class="flex items-center">
                <Logo 
                  :src="logo.image_url || logo.image_path" 
                  alt="Aligans Logo" 
                  height="96px"
                  @error="handleLogoError" 
                  @load="handleLogoLoaded"
                />
              </div>
              
              <!-- Minimal text logo as a pure placeholder during load with no dummy content -->
              <div v-else class="flex items-center gap-2">
                <span class="text-4xl flex items-center justify-center h-16 w-16 bg-gradient-to-br from-blue-700 to-blue-600 text-white rounded-md shadow-md">A</span>
              </div>
            </router-link>
            
            <!-- Mobile Menu Toggle -->
            <button 
              @click="mobileMenuOpen = !mobileMenuOpen" 
              class="md:hidden text-gray-500 hover:text-blue-700 focus:outline-none transform transition-transform duration-300 hover:scale-110"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path v-if="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>
          </div>
          
          <!-- Search Bar -->
          <div class="relative hidden md:block flex-grow max-w-2xl group">
            <input 
              type="text" 
              placeholder="Search for products, brands, categories..." 
              v-model="searchQuery"
              @keyup.enter="performSearch"
              class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 shadow-sm group-hover:shadow-md"
            >
            <button 
              @click="performSearch"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-700 transition-all duration-300 transform hover:scale-110">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </button>
          </div>
          
          <!-- Navigation Icons -->
          <div class="hidden md:flex items-center gap-6">
            <!-- Account Dropdown -->
            <div class="relative account-dropdown-container">
              <button 
                class="flex items-center gap-1 text-gray-700 hover:text-blue-700 transition-all duration-300 hover:scale-105"
                @click="accountDropdownOpen = !accountDropdownOpen"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span v-if="isLoggedIn && user">{{ user.name }}</span>
                <span v-else>Account</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-300" :class="{'rotate-180': accountDropdownOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              
              <!-- Account Dropdown Menu -->
              <div 
                v-show="accountDropdownOpen"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-xl py-1 z-50 account-dropdown transform origin-top transition-all duration-300"
              >
                <template v-if="isLoggedIn">
                  <router-link to="/account" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">My Account</router-link>
                  <router-link to="/account/orders" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">Orders</router-link>
                  <a href="#" @click.prevent="logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">Sign Out</a>
                </template>
                <template v-else>
                  <router-link to="/login" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">Login</router-link>
                  <router-link to="/register" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">Register</router-link>
                </template>
              </div>
            </div>
            
            <!-- Favorites -->
            <div class="relative wishlist-dropdown-container">
              <button 
                class="text-gray-700 hover:text-blue-700 transition-all duration-300 relative hover:scale-105"
                @click.prevent="wishlistDropdownOpen = !wishlistDropdownOpen"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span v-if="wishlistCount > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">{{ wishlistCount }}</span>
              </button>
              
              <!-- Wishlist Dropdown Content -->
              <div 
                v-show="wishlistDropdownOpen"
                class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-xl py-4 z-50 wishlist-dropdown transform origin-top transition-all duration-300"
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
                    <router-link :to="'/products/' + item.id" @click="wishlistDropdownOpen = false" class="flex items-center gap-4 flex-grow">
                      <div class="h-16 w-16 bg-gray-100 rounded">
                        <img 
                          :src="getImageUrl(item.image)" 
                          @error="handleImageError" 
                          class="h-full w-full object-cover rounded"
                        >
                      </div>
                      <div class="flex-grow">
                        <h4 class="font-medium text-sm">{{ item.name }}</h4>
                        <p class="text-gray-500 text-xs">${{ parseFloat(item.price).toFixed(2) }}</p>
                      </div>
                    </router-link>
                    <button 
                      @click.stop.prevent="removeItemFromWishlist(index)" 
                      class="text-gray-400 hover:text-red-500 p-2 wishlist-remove-btn"
                      title="Remove from wishlist"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>
                
                <div class="px-4 py-3 border-t border-gray-100">
                  <div class="mt-4 flex flex-col gap-2">
                    <router-link @click="wishlistDropdownOpen = false" to="/wishlist" class="bg-gray-100 hover:bg-gray-200 text-center py-2 rounded-md text-sm font-medium transition">
                      View Wishlist
                    </router-link>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Cart Dropdown -->
            <div class="relative cart-dropdown-container">
              <button 
                class="flex items-center gap-1 text-gray-700 hover:text-blue-700 transition-all duration-300 relative hover:scale-105"
                @click="cartDropdownOpen = !cartDropdownOpen"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span>Cart</span>
                <span v-if="cartItemCount > 0" class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">{{ cartItemCount }}</span>
              </button>
              
              <!-- Cart Dropdown Content -->
              <div 
                v-show="cartDropdownOpen"
                class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-xl py-4 z-50 cart-dropdown transition-all duration-300"
                @click.stop
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
                    <router-link :to="'/products/' + item.id" class="flex items-center gap-4 flex-grow" @click="cartDropdownOpen = false">
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
                    <button @click.stop="removeCartItem(index)" class="text-gray-400 hover:text-red-500 p-2 remove-cart-item">
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
                    <router-link to="/cart" class="bg-gray-100 hover:bg-gray-200 text-center py-2 rounded-md text-sm font-medium transition" @click="cartDropdownOpen = false">
                      View Cart
                    </router-link>
                    <button @click.stop="handleCheckoutFromDropdown" class="bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-md text-sm font-medium transition checkout-btn">
                      Checkout
                    </button>
                  </div>
                </div>
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
        placeholder="Search for products, brands, categories..." 
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
    
    <!-- Main Navigation -->
    <nav class="border-t border-gray-200 hidden md:block bg-white">
      <div class="container mx-auto px-4">
        <ul class="flex">
          <!-- Hover-Activated Categories Dropdown -->
          <li class="group relative categories-dropdown-container">
            <button 
              class="flex items-center gap-1 text-gray-700 hover:text-blue-700 transition-all duration-300 px-4 py-4 group-hover:scale-105"
              @click="categoriesDropdownOpen = !categoriesDropdownOpen"
            >
              <span>Categories</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-300 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            
            <!-- Categories Dropdown Menu -->
            <div 
              v-show="categoriesDropdownOpen" 
              class="absolute left-0 mt-0 w-64 bg-white shadow-xl rounded-b-md z-50 border-t-2 border-blue-600 categories-dropdown-menu transform origin-top opacity-0 scale-y-0 group-hover:opacity-100 group-hover:scale-y-100 transition-all duration-300"
            >
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
                  class="relative hover:bg-blue-50 category-item"
                >
                  <div class="flex items-center justify-between px-4 py-3 w-full transition-colors duration-200 text-gray-700 hover:text-blue-700 font-medium">
                    <router-link 
                      :to="'/category/' + category.id" 
                      class="flex-grow"
                    >
                      <span>{{ category.name }}</span>
                    </router-link>
                    <button 
                      v-if="getSubcategories(category.id).length > 0"
                      @click="toggleSubcategory(category.id)"
                      class="ml-2 focus:outline-none"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform transition-transform duration-300 hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                      </svg>
                    </button>
                  </div>
                  
                  <!-- Subcategories Menu - Shows on click -->
                  <div 
                    v-if="getSubcategories(category.id).length > 0"
                    v-show="activeCategory === category.id"
                    class="absolute left-full top-0 min-w-[200px] bg-white shadow-xl rounded-md py-2 subcategory-menu transition-opacity duration-300"
                  >
                    <router-link 
                      v-for="subcategory in getSubcategories(category.id)"
                      :key="subcategory.id"
                      :to="'/category/' + subcategory.id"
                      class="block px-4 py-2 hover:text-blue-700 whitespace-nowrap transition-colors duration-200"
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
            <router-link to="/products" class="block px-4 py-4 text-gray-700 hover:text-blue-700 font-medium transition-colors duration-300 hover:bg-blue-50">
              All Products
            </router-link>
          </li>
          <li>
            <router-link to="/new-arrivals" class="block px-4 py-4 text-gray-700 hover:text-blue-700 font-medium transition-colors duration-300 hover:bg-blue-50">
              New Arrivals
            </router-link>
          </li>
          <li>
            <router-link to="/products/sale" class="block px-4 py-4 text-red-600 hover:text-red-700 font-medium transition-colors duration-300 hover:bg-red-50">
              Sale
            </router-link>
          </li>
          <li>
            <router-link to="/brands" class="block px-4 py-4 text-gray-700 hover:text-blue-700 font-medium transition-colors duration-300 hover:bg-blue-50">
              Brands
            </router-link>
          </li>
        </ul>
      </div>
    </nav>
    
    <!-- Mobile Menu -->
    <div 
      v-show="mobileMenuOpen" 
      class="md:hidden px-4 py-2 bg-white border-t border-gray-200 shadow-lg rounded-b-lg"
    >
      <ul class="space-y-2">
        <li>
          <button @click="mobileCategoriesOpen = !mobileCategoriesOpen" class="flex justify-between w-full py-2 px-4 rounded-md text-left font-medium hover:bg-blue-50 transition-colors duration-200">
            <span>Categories</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300" :class="{'rotate-180': mobileCategoriesOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div v-show="mobileCategoriesOpen" class="py-2 pl-4 space-y-2 bg-blue-50 rounded-md mt-1 transform origin-top transition-all duration-300">
            <!-- Dynamic mobile categories - only show if loaded -->
            <template v-if="!categoriesLoading && topCategories.length > 0">
              <div v-for="category in topCategories" :key="category.id" class="mb-3">
                <div class="flex justify-between items-center">
                  <!-- Top category -->
                  <router-link 
                    :to="'/category/' + category.id" 
                    class="py-2 hover:text-blue-700 font-medium flex-grow transition-colors duration-200"
                  >
                    {{ category.name }}
                  </router-link>
                  
                  <!-- Toggle button for subcategories (if any) -->
                  <button 
                    v-if="getSubcategories(category.id).length > 0"
                    @click="toggleMobileSubcategory(category.id)"
                    class="px-2 text-gray-500 flex items-center"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200" :class="{'rotate-180': activeCategory === category.id}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                </div>
                
                <!-- Subcategories (if any) - with transition -->
                <div 
                  v-if="getSubcategories(category.id).length > 0"
                  v-show="activeCategory === category.id"
                  class="pl-4 mt-1 border-l-2 border-blue-300 overflow-hidden transition-all duration-300"
                >
                  <router-link 
                    v-for="subcategory in getSubcategories(category.id)" 
                    :key="subcategory.id"
                    :to="'/category/' + subcategory.id" 
                    class="block py-2 text-sm hover:text-blue-700 transition-colors duration-200"
                  >
                    {{ subcategory.name }}
                  </router-link>
                </div>
              </div>
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
        <li><router-link to="/products" class="block py-2 px-4 hover:bg-blue-50 rounded-md transition-colors duration-200">All Products</router-link></li>
        <li><router-link to="/new-arrivals" class="block py-2 px-4 hover:bg-blue-50 rounded-md transition-colors duration-200">New Arrivals</router-link></li>
        <li><router-link to="/products/sale" class="block py-2 px-4 hover:bg-red-50 rounded-md text-red-600 hover:text-red-700 transition-colors duration-200">Sale</router-link></li>
        <li><router-link to="/brands" class="block py-2 px-4 hover:bg-blue-50 rounded-md transition-colors duration-200">Brands</router-link></li>
        
        <!-- Mobile Account Links -->
        <li v-if="isLoggedIn">
          <router-link to="/account" class="block py-2 px-4 hover:bg-blue-50 rounded-md transition-colors duration-200">My Account</router-link>
        </li>
        <li v-if="isLoggedIn">
          <router-link to="/account/orders" class="block py-2 px-4 hover:bg-blue-50 rounded-md transition-colors duration-200">Orders</router-link>
        </li>
        <li v-if="isLoggedIn">
          <a href="#" @click.prevent="logout" class="block py-2 px-4 hover:bg-blue-50 rounded-md transition-colors duration-200">Sign Out</a>
        </li>
        <li v-if="!isLoggedIn">
          <router-link to="/login" class="block py-2 px-4 hover:bg-blue-50 rounded-md transition-colors duration-200">Login</router-link>
        </li>
        <li v-if="!isLoggedIn">
          <router-link to="/register" class="block py-2 px-4 hover:bg-blue-50 rounded-md transition-colors duration-200">Register</router-link>
        </li>
        
        <li>
          <router-link to="/wishlist" class="block py-2 px-4 hover:bg-blue-50 rounded-md transition-colors duration-200">
            Wishlist <span v-if="wishlistCount > 0" class="ml-1 bg-red-500 text-white text-xs rounded-full px-2 py-1 animate-pulse">{{ wishlistCount }}</span>
          </router-link>
        </li>
        
        <li><router-link to="/cart" class="block py-2 px-4 hover:bg-blue-50 rounded-md transition-colors duration-200">Cart <span v-if="cartItemCount > 0" class="ml-1 bg-blue-600 text-white text-xs rounded-full px-2 py-1 animate-pulse">{{ cartItemCount }}</span></router-link></li>
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
import Logo from './Logo.vue';
import { isAuthenticated, getToken } from '@/utils/auth';

export default {
  name: 'Header',
  components: {
    Logo
  },
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
      activeCategory: null, // Track which category is being hovered
      cartItemsLocal: [], // Local reactive cart items
      wishlistItemsLocal: [], // Local reactive wishlist items
      cartCount: 0, // Direct reactive counter
      wishlistCountLocal: 0, // Direct reactive counter
      categories: [], // All categories
      topCategories: [], // Top level categories
      categoriesLoading: true,
      categoriesError: null,
      logo: cachedLogoData.logo, // Pre-loaded from localStorage
      cachedBase64: cachedLogoData.cachedBase64, // Pre-loaded base64 image
      logoLoading: true, // Always start with loading state
      logoError: null,
      searchQuery: '',
      showMissingAttributesModal: false,
      isScrolled: false,
      cartDropdownOpen: false,
      showSimpleAlert: false,
      simpleAlertMessage: '',
      accountDropdownOpen: false,
      wishlistDropdownOpen: false,
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
    
    // Add event listener to close categories dropdown when clicking outside
    document.addEventListener('click', this.handleOutsideClick);
    
    // Add scroll event listener for header animation
    window.addEventListener('scroll', this.handleScroll);
    
    // Initialize dropdown animations
    this.initializeAnimations();
    
    // Listen for keyboard events globally for modal
    document.addEventListener('keydown', this.globalKeyHandler);
    
    // Listen for the custom event to show the missing attributes modal
    document.addEventListener('show-missing-attributes-modal', this.showMissingAttributesModalMethod);
  },
  beforeUnmount() {
    // Remove event listeners when component is unmounted
    document.removeEventListener('wishlist-updated', this.handleWishlistUpdate);
    document.removeEventListener('cart-updated', this.handleCartUpdate);
    document.removeEventListener('user-login', this.checkAuthState);
    document.removeEventListener('user-logout', this.checkAuthState);
    document.removeEventListener('click', this.handleOutsideClick);
    document.removeEventListener('keydown', this.handleModalKeydown);
    document.removeEventListener('keydown', this.globalKeyHandler);
    document.removeEventListener('show-missing-attributes-modal', this.showMissingAttributesModalMethod);
    document.removeEventListener('keydown', this.handleAlertKeydown);
    
    // Remove scroll event listener
    window.removeEventListener('scroll', this.handleScroll);
    
    // Ensure modal is closed and body style is reset when component is unmounted
    if (this.showMissingAttributesModal) {
      document.body.style.overflow = '';
      document.body.classList.remove('modal-open');
    }
    
    // Ensure alert is closed
    this.showSimpleAlert = false;
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
    activateCategory(categoryId) {
      this.activeCategory = categoryId;
    },
    deactivateCategory() {
      // Use setTimeout to avoid menu flickering when moving between main category and subcategory menu
      setTimeout(() => {
        // Only deactivate if mouse isn't over any menu item
        const isOverCategory = document.querySelector('.category-item:hover');
        const isOverSubcategory = document.querySelector('.subcategory-menu:hover');
        if (!isOverCategory && !isOverSubcategory) {
          this.activeCategory = null;
        }
      }, 100);
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
        // First load data from localStorage for immediate response
        const storedWishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        this.wishlistItemsLocal = storedWishlist;
        this.wishlistCountLocal = storedWishlist.length;
        
        // Apply this immediately for UI responsiveness
        this.$forceUpdate();
        
        // Then get the latest data from the API if authenticated
        if (isAuthenticated()) {
          getWishlist()
            .then(items => {
              if (items && Array.isArray(items)) {
          this.wishlistItemsLocal = items;
          this.wishlistCountLocal = items.length;
                
                // Update localStorage to stay in sync
                localStorage.setItem('wishlist', JSON.stringify(items));
                
                // Force UI update
                this.$forceUpdate();
                console.log('Wishlist successfully updated from API, count:', this.wishlistCountLocal);
              }
            })
            .catch(error => {
          console.error('Error fetching wishlist from API:', error);
        });
        }
        
        console.log('Wishlist count updated:', this.wishlistCountLocal);
      } catch (error) {
        console.error('Error updating local wishlist:', error);
        
        // Fallback to empty wishlist in case of error
        this.wishlistItemsLocal = [];
        this.wishlistCountLocal = 0;
        this.$forceUpdate();
      }
    },
    removeCartItem(index) {
      // Keep dropdown open when removing items
      event.stopPropagation();
      
      console.log('Removing cart item at index:', index);
      
      // First update the local state immediately for better UX
      if (index >= 0 && index < this.cartItemsLocal.length) {
        // Get a copy of the item for logging
        const removedItem = { ...this.cartItemsLocal[index] };
        
        // Remove from local array immediately
        this.cartItemsLocal.splice(index, 1);
        
        // Decrement the cart count by 1 since we're removing one unique product
        this.cartCount -= 1;
        
        console.log('Removed cart item:', removedItem.name);
      
      // Then use the utility function to update localStorage and trigger event
      removeFromCart(index);
        
        // Force update to refresh UI
        this.$forceUpdate();
      
      // Make sure local state is in sync with localStorage
      this.$nextTick(() => {
        this.updateLocalCart();
      });
      }
    },
    removeItemFromWishlist(index) {
      // Make sure we have a valid event and stop propagation
      if (event) {
        event.preventDefault();
        event.stopPropagation();
      }
      
      console.log('Attempting to remove wishlist item at index:', index);
      
      try {
        // Verify we have a valid index and array
        if (!this.wishlistItemsLocal || this.wishlistItemsLocal.length === 0) {
          console.error('Wishlist is empty or undefined');
          return;
        }
        
        if (index < 0 || index >= this.wishlistItemsLocal.length) {
          console.error('Invalid wishlist index:', index);
          return;
        }
        
        // Get the item before removal for API call
        const itemToRemove = this.wishlistItemsLocal[index];
        const itemId = itemToRemove.id;
        const itemName = itemToRemove.name;
        
        console.log('Removing wishlist item:', itemName, 'with ID:', itemId);
        
        // 1. First, update the UI immediately by removing from local array
        this.wishlistItemsLocal.splice(index, 1);
      this.wishlistCountLocal = this.wishlistItemsLocal.length;
        
        // 2. Update localStorage directly for immediate persistence
        const storedWishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        const updatedWishlist = storedWishlist.filter(item => item.id !== itemId);
        localStorage.setItem('wishlist', JSON.stringify(updatedWishlist));
        
        // 3. Make the API call if the user is authenticated
        if (isAuthenticated()) {
          // Use direct axios call with proper error handling
          const apiUrl = `/api/wishlist/${itemId}`;
          const headers = { 'Authorization': `Bearer ${getToken()}` };
          
          axios.delete(apiUrl, { headers })
            .then(response => {
              console.log('Item successfully removed from wishlist via API');
            })
            .catch(error => {
              console.error('API error removing wishlist item:', error);
              
              // On API error, refresh wishlist data
          this.updateLocalWishlist();
        });
        }
        
        // 4. Force UI refresh
        this.$forceUpdate();
        
        // 5. Ensure dropdown stays open
        setTimeout(() => {
          if (this.$el && this.$el.querySelector) {
            const dropdown = this.$el.querySelector('.wishlist-dropdown');
            if (dropdown) {
              dropdown.style.display = 'block';
              this.wishlistDropdownOpen = true;
            }
          }
        }, 0);
        
        // 6. Broadcast event for other components
      document.dispatchEvent(new CustomEvent('wishlist-updated'));
        
        // 7. Show notification if available
        if (typeof this.showToastMessage === 'function') {
          this.showToastMessage(`${itemName} removed from wishlist`, 'info');
        }
        
      } catch (err) {
        console.error('Error in removeItemFromWishlist:', err);
        
        // On any error, refresh wishlist data
        this.updateLocalWishlist();
      }
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
    async fetchLogo() {
      // Always start in loading state to prevent showing dummy data
      this.logoLoading = true;
      
      try {
        // Fetch logo from settings table instead of logos table
        const response = await axios.get('http://localhost:8000/api/settings/logo');
        console.log('Logo data received from settings:', response.data);
        
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
        console.error('Error fetching logo from settings:', error);
        this.logoError = 'Error loading logo image';
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
      
      console.log('Performing comprehensive search with query:', this.searchQuery.trim());
      
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
    },
    toggleMobileSubcategory(categoryId) {
      // Toggle the active category
      if (this.activeCategory === categoryId) {
        this.activeCategory = null;
      } else {
        this.activeCategory = categoryId;
      }
    },
    toggleSubcategory(categoryId) {
      // Toggle the active category
      if (this.activeCategory === categoryId) {
        this.activeCategory = null;
      } else {
        this.activeCategory = categoryId;
      }
    },
    handleOutsideClick(event) {
      // Check if the click was outside the categories dropdown
      const categoriesContainer = this.$el.querySelector('.categories-dropdown-container');
      if (categoriesContainer && !categoriesContainer.contains(event.target)) {
        this.categoriesDropdownOpen = false;
      }
      
      // Check if the click was outside the account dropdown
      const accountContainer = this.$el.querySelector('.account-dropdown-container');
      if (accountContainer && !accountContainer.contains(event.target)) {
        this.accountDropdownOpen = false;
      }
      
      // Check if the click was outside the wishlist dropdown
      const wishlistContainer = this.$el.querySelector('.wishlist-dropdown-container');
      if (wishlistContainer && !wishlistContainer.contains(event.target)) {
        this.wishlistDropdownOpen = false;
      }
      
      // Check if the click was outside the cart dropdown
      const cartContainer = this.$el.querySelector('.cart-dropdown-container');
      if (cartContainer && !cartContainer.contains(event.target)) {
        this.cartDropdownOpen = false;
      }
      
      // Close subcategory menu if click is outside
      const categoryItems = this.$el.querySelectorAll('.category-item');
      const clickedInsideCategory = Array.from(categoryItems).some(item => item.contains(event.target));
      const subcategoryMenu = this.$el.querySelector('.subcategory-menu');
      const clickedInsideSubcategory = subcategoryMenu && subcategoryMenu.contains(event.target);
      
      if (!clickedInsideCategory && !clickedInsideSubcategory) {
        this.activeCategory = null;
      }
    },
    // Navigate to the product page of the first item with missing attributes
    goToProductPage(event) {
      // Stop event propagation
      if (event) {
        event.stopPropagation();
        event.preventDefault();
      }
      
      // Remove keep-open class from dropdown
      const cartDropdown = document.querySelector('.cart-dropdown');
      if (cartDropdown) {
        cartDropdown.classList.remove('keep-open');
      }
      
      // Find items with missing attributes
      const missingAttributesItems = this.getMissingAttributeItems();
      
      console.log('Missing attributes items:', missingAttributesItems);
      
      // First close the modal
      this.closeMissingAttributesModal();
      
      // Wait a moment for modal to finish closing
      setTimeout(() => {
      if (missingAttributesItems.length > 0) {
          // Get the product ID - check various property names for compatibility
          const productId = missingAttributesItems[0].id || 
                         missingAttributesItems[0].product_id || 
                         missingAttributesItems[0].productId;
          
          console.log('Navigating to product page:', productId);
        this.$router.push(`/products/${productId}`);
      } else {
        // Fallback to products page if no specific item found
        this.$router.push('/products');
      }
      }, 300); // Longer delay for smooth transition
    },
    // Handle checkout button click specifically from the dropdown
    handleCheckoutFromDropdown(event) {
      // Stop event propagation and prevent default behavior
      event.stopPropagation();
      event.preventDefault();
      
      // Check if any product is missing size or color selection
      const missingRequiredAttributes = this.cartItemsLocal.filter(item => {
        const needsSize = !item.size || item.size === null || item.size === '' || item.size === undefined;
        const needsColor = !item.color || item.color === null || item.color === '' || item.color === undefined;
        return (needsSize || needsColor);
      });
      
      // If any product is missing attributes, display a simple alert
      if (missingRequiredAttributes.length > 0) {
        // Close dropdown first
        this.cartDropdownOpen = false;
        
        // Show the simple alert
        this.showAlert("Please select size and color for all items in your cart before checkout.");
        return;
      } else {
        // All products have required attributes, proceed to checkout
        this.cartDropdownOpen = false; // Close dropdown before navigating
        
        // Check if user is logged in
        if (!this.isLoggedIn) {
          this.$router.push({ 
            path: '/login', 
            query: { redirect: '/checkout/shipping' } 
          });
        } else {
      // If logged in and all products have size/color, proceed to checkout
      this.$router.push('/checkout/shipping');
        }
      }
    },
    
    // Handle scroll for header animation
    handleScroll() {
      this.isScrolled = window.scrollY > 10;
    },
    // Initialize dropdown animations
    initializeAnimations() {
      // Configure animations for dropdowns
      const dropdowns = document.querySelectorAll('.account-dropdown, .wishlist-dropdown, .cart-dropdown');
      
      // Monitor cart/wishlist count changes for animation
      const cartCount = this.cartCount;
      const wishlistCount = this.wishlistCountLocal;
      
      // Watch for cart count changes
      this.$watch('cartCount', (newCount, oldCount) => {
        if (newCount > oldCount) {
          // New item added, animate the badge
          const badge = document.querySelector('.cart-dropdown-container .absolute');
          if (badge) {
            badge.classList.add('badge-pulse');
            setTimeout(() => {
              badge.classList.remove('badge-pulse');
            }, 500);
          }
        }
      });
      
      // Watch for wishlist count changes
      this.$watch('wishlistCountLocal', (newCount, oldCount) => {
        if (newCount > oldCount) {
          // New item added, animate the badge
          const badge = document.querySelector('.wishlist-dropdown-container .absolute');
          if (badge) {
            badge.classList.add('badge-pulse');
            setTimeout(() => {
              badge.classList.remove('badge-pulse');
            }, 500);
          }
        }
      });
    },
    // Handle checkout button click with validation from any location
    handleCheckout(event) {
      // Stop event propagation if event is provided
      if (event) {
        event.stopPropagation();
        event.preventDefault();
      }
      
      // Check if user is logged in
      const isUserLoggedIn = this.isLoggedIn;
      
      // If not logged in, redirect to login page with return URL
      if (!isUserLoggedIn) {
        this.$router.push({ 
          path: '/login', 
          query: { redirect: '/checkout/shipping' } 
        });
        return;
      }
      
      // Check if cart is empty
      if (this.cartItemsLocal.length === 0) {
        // No items in cart, redirect to products page
        this.$router.push('/products');
        return;
      }
      
      // Check if any product is missing size or color selection
      const missingRequiredAttributes = this.cartItemsLocal.filter(item => {
        // More robust check for missing size/color
        const needsSize = !item.size || item.size === null || item.size === '' || item.size === undefined;
        const needsColor = !item.color || item.color === null || item.color === '' || item.color === undefined;
        
        // For debugging - log details about the missing attributes
        if (needsSize || needsColor) {
          console.log('Missing attributes for item:', item.name);
          console.log('Size:', item.size);
          console.log('Color:', item.color);
        }
        
        return (needsSize || needsColor);
      });
      
      // If any product is missing attributes, show simple alert
      if (missingRequiredAttributes.length > 0) {
        this.showAlert("Please select size and color for all items in your cart before checkout.");
        return;
      }
      
      // If logged in and all products have size/color, proceed to checkout
      this.$router.push('/checkout/shipping');
    },
    
    // Close the missing attributes modal and restore UI
    closeMissingAttributesModal() {
      // Hide modal first
      this.showMissingAttributesModal = false;
      
      // Then restore body scrolling
      document.body.style.overflow = '';
      document.body.classList.remove('modal-open');
      
      // Remove cart dropdown keep-open class
      const cartDropdown = document.querySelector('.cart-dropdown');
      if (cartDropdown) {
        cartDropdown.classList.remove('keep-open');
      }
      
      // Remove event listeners
      document.removeEventListener('keydown', this.handleModalKeydown);
    },
    
    // Handle escape key to close modal
    handleModalKeydown(event) {
      if (event.key === 'Escape' && this.showMissingAttributesModal) {
        this.closeMissingAttributesModal();
      }
    },
    
    // Get items that are missing attributes
    getMissingAttributeItems() {
      return this.cartItemsLocal.filter(item => {
        const needsSize = !item.size || item.size === null || item.size === '' || item.size === undefined;
        const needsColor = !item.color || item.color === null || item.color === '' || item.color === undefined;
        return (needsSize || needsColor);
      });
    },
    globalKeyHandler(event) {
      // Handle escape key press
      if (event.key === 'Escape' && this.showMissingAttributesModal) {
        this.closeMissingAttributesModal();
      }
      
      // Handle tab key to trap focus in modal
      if (event.key === 'Tab' && this.showMissingAttributesModal) {
        const modalContent = this.$refs.modalContent;
        if (modalContent) {
          const focusableElements = modalContent.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
          );
          
          if (focusableElements.length > 0) {
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            
            // If shift+tab on first element, go to last element
            if (event.shiftKey && document.activeElement === firstElement) {
              lastElement.focus();
              event.preventDefault();
            } 
            // If tab on last element, go to first element
            else if (!event.shiftKey && document.activeElement === lastElement) {
              firstElement.focus();
              event.preventDefault();
            }
          }
        }
      }
    },
    // Show modal with improved experience
    showMissingAttributesModalMethod() {
      // Show the modal
      this.showMissingAttributesModal = true;
      
      // Prevent background scrolling
      document.body.style.overflow = 'hidden';
      document.body.classList.add('modal-open');
      
      // Set focus inside modal after a short delay to allow rendering
      this.$nextTick(() => {
        if (this.$refs.modalContent) {
          const focusableElements = this.$refs.modalContent.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
          );
          if (focusableElements.length > 0) {
            setTimeout(() => {
              focusableElements[0].focus();
            }, 100);
          }
        }
      });
    },
    // Show a simple alert modal (mimics browser alert)
    showAlert(message) {
      // Set message and show alert
      this.simpleAlertMessage = message;
      this.showSimpleAlert = true;
      
      // Prevent page scrolling
      document.body.style.overflow = 'hidden';
      document.body.style.position = 'fixed';
      document.body.style.width = '100%';
      document.body.style.top = `-${window.scrollY}px`;
      
      // Capture keyboard events while alert is open
      document.addEventListener('keydown', this.handleAlertKeydown);
      
      // Return focus to body to mimic browser behavior
      document.body.focus();
    },
    // Handle keydown events for the alert
    handleAlertKeydown(event) {
      // Close alert on Enter or Escape key press (like browser alerts)
      if (event.key === 'Enter' || event.key === 'Escape') {
        this.closeSimpleAlert();
        event.preventDefault();
      }
    },
    // Close the simple alert modal
    closeSimpleAlert() {
      this.showSimpleAlert = false;
      
      // Restore page scrolling
      const scrollY = document.body.style.top;
      document.body.style.overflow = '';
      document.body.style.position = '';
      document.body.style.width = '';
      document.body.style.top = '';
      window.scrollTo(0, parseInt(scrollY || '0') * -1);
      
      // Remove the keydown event listener
      document.removeEventListener('keydown', this.handleAlertKeydown);
    },
    getImageUrl(imagePath) {
      if (!imagePath) return 'https://picsum.photos/id/26/100/100';
      
      // If the path is a full URL, return it directly
      if (imagePath.startsWith('http')) {
        return imagePath;
      }
      
      // Otherwise, assume it's a relative path on the server
      return `http://localhost:8000/storage/${imagePath}`;
    },
  }
};
</script> 

<style>
/* Premium E-commerce Header - Advanced Professional Design */

/* Base Design System Variables */
:root {
  /* Core Brand Colors - Elegant Blue Scheme */
  --premium-primary: #2563eb;
  --premium-primary-dark: #1e40af;
  --premium-primary-light: #60a5fa;
  --premium-primary-gradient: linear-gradient(135deg, var(--premium-primary-dark), var(--premium-primary), var(--premium-primary-light));
  --premium-primary-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.15);
  
  /* Secondary Colors */
  --premium-secondary: #f97316;
  --premium-secondary-light: #fdba74;
  --premium-secondary-dark: #c2410c;
  
  /* Neutrals */
  --premium-surface-100: #ffffff;
  --premium-surface-200: #f8fafc;
  --premium-surface-300: #e2e8f0;
  --premium-text-primary: #0f172a;
  --premium-text-secondary: #475569;
  --premium-text-light: #f8fafc;
  
  /* Accent Colors */
  --premium-accent-green: #10b981;
  --premium-accent-red: #ef4444;
  --premium-accent-yellow: #eab308;
  
  /* Shadows & Effects */
  --premium-shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
  --premium-shadow-md: 0 4px 15px rgba(0, 0, 0, 0.07);
  --premium-shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1), 0 5px 10px rgba(0, 0, 0, 0.05);
  --premium-shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.1), 0 10px 15px rgba(0, 0, 0, 0.05);
  --premium-glow: 0 0 20px rgba(37, 99, 235, 0.5);
  
  /* Transitions */
  --premium-transition-fast: 0.15s cubic-bezier(0.4, 0, 0.2, 1);
  --premium-transition-normal: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  --premium-transition-bounce: 0.5s cubic-bezier(0.68, -0.6, 0.32, 1.6);
  --premium-transition-smooth: 0.4s cubic-bezier(0.65, 0, 0.35, 1);
  
  /* Border Radius */
  --premium-radius-sm: 0.25rem;
  --premium-radius-md: 0.5rem;
  --premium-radius-lg: 0.75rem;
  --premium-radius-xl: 1rem;
  --premium-radius-full: 9999px;
}

/* Enhanced Header Backdrop */
.modern-header {
  position: sticky;
  top: 0;
  z-index: 100;
  background-color: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(15px) saturate(180%);
  -webkit-backdrop-filter: blur(15px) saturate(180%);
  transition: var(--premium-transition-normal);
  border-bottom: 1px solid transparent;
}

.modern-header.shadow-lg {
  background-color: rgba(255, 255, 255, 0.95);
  box-shadow: var(--premium-shadow-md);
  border-bottom: 1px solid rgba(0, 0, 0, 0.03);
}

/* Premium Animated Announcement Bar */
.announcement-bar {
  background: var(--premium-primary-gradient);
  position: relative;
  overflow: hidden;
  padding: 0.5rem 0;
}

.announcement-bar::before,
.announcement-bar::after {
  content: '';
  position: absolute;
  width: 200%;
  height: 200%;
  top: -50%;
  left: -50%;
  z-index: 1;
  background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 50%);
}

.announcement-bar::before {
  animation: rippleEffect 8s ease-in-out infinite alternate;
}

.announcement-bar::after {
  animation: rippleEffect 12s ease-in-out infinite alternate-reverse;
}

@keyframes rippleEffect {
  0% { transform: translate(-30%, -30%) scale(1); opacity: 0.3; }
  50% { transform: translate(-10%, -20%) scale(1.2); opacity: 0.5; }
  100% { transform: translate(-20%, -10%) scale(1); opacity: 0.3; }
}

.announcement-bar .container {
  position: relative;
  z-index: 5;
}

.announcement-bar p {
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  letter-spacing: 0.02em;
  font-weight: 500;
}

/* Advanced Logo Animation */
.logo-container {
  position: relative;
  overflow: visible;
  padding: 0.5rem 0;
  transition: transform var(--premium-transition-bounce);
  isolation: isolate;
}

.logo-container::after {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at center, rgba(96, 165, 250, 0.15), transparent 70%);
  opacity: 0;
  z-index: -1;
  transition: opacity var(--premium-transition-normal);
  border-radius: var(--premium-radius-md);
}

.logo-container:hover {
  transform: translateY(-2px);
}

.logo-container:hover::after {
  opacity: 1;
}

.logo-wrapper {
  transform-style: preserve-3d;
  transition: var(--premium-transition-smooth);
}

.logo-container:hover .logo-wrapper {
  transform: perspective(500px) rotateY(2deg);
  filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
}

.logo-image {
  transform-style: preserve-3d;
  backface-visibility: hidden;
  transition: var(--premium-transition-smooth);
}

/* Enhanced Search Bar */
input[type="text"] {
  transition: all var(--premium-transition-normal);
  border-color: var(--premium-surface-300);
  background-color: var(--premium-surface-200);
  border-radius: var(--premium-radius-lg);
  box-shadow: var(--premium-shadow-sm);
}

input[type="text"]:focus {
  background-color: var(--premium-surface-100);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2), var(--premium-shadow-md);
  border-color: var(--premium-primary-light);
  transform: translateY(-1px);
}

.search-button {
  transition: all var(--premium-transition-normal);
  transform-origin: center;
}

.search-button:hover {
  color: var(--premium-primary);
  transform: scale(1.1) rotate(5deg);
}

/* Advanced Navigation Effects */
.block.px-4.py-4 {
  position: relative;
  transition: color var(--premium-transition-normal);
  overflow: hidden;
}

.block.px-4.py-4::before {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--premium-primary-light), var(--premium-primary), var(--premium-primary-light));
  transform: translateX(-50%);
  transition: width var(--premium-transition-normal);
  border-radius: 3px 3px 0 0;
  z-index: 1;
}

.block.px-4.py-4::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to bottom, transparent, rgba(96, 165, 250, 0.08));
  opacity: 0;
  transition: opacity var(--premium-transition-normal);
  z-index: -1;
}

.block.px-4.py-4:hover::before {
  width: 80%;
}

.block.px-4.py-4:hover::after {
  opacity: 1;
}

/* Premium Dropdown Styling */
.account-dropdown,
.wishlist-dropdown,
.cart-dropdown,
.categories-dropdown-menu {
  background-color: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px) saturate(180%);
  -webkit-backdrop-filter: blur(10px) saturate(180%);
  border-radius: var(--premium-radius-lg);
  border: 1px solid rgba(229, 231, 235, 0.5);
  box-shadow: var(--premium-shadow-xl), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
  transform-origin: top;
  transition-property: transform, opacity, visibility;
  transition-duration: 0.35s;
  transition-timing-function: cubic-bezier(0.25, 1, 0.5, 1);
  overflow: hidden;
}

.group:hover .account-dropdown,
.group:hover .wishlist-dropdown,
.group:hover .cart-dropdown,
.group:hover .categories-dropdown-menu {
  animation: luxuryDropdown 0.35s cubic-bezier(0.25, 1, 0.5, 1) forwards;
}

@keyframes luxuryDropdown {
  0% { 
    opacity: 0; 
    transform: translateY(-10px) scale(0.98);
    box-shadow: var(--premium-shadow-lg);
  }
  70% { 
    transform: translateY(2px) scale(1.01);
  }
  100% { 
    opacity: 1; 
    transform: translateY(0) scale(1);
    box-shadow: var(--premium-shadow-xl), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
}
}

/* Enhanced Cart & Wishlist Items */
.cart-dropdown .p-4,
.wishlist-dropdown .p-4 {
  position: relative;
  transition: all var(--premium-transition-normal);
  border-radius: var(--premium-radius-md);
  margin: 0.25rem 0.5rem;
  overflow: hidden;
  border: 1px solid transparent;
}

.cart-dropdown .p-4::before,
.wishlist-dropdown .p-4::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(to right, transparent, rgba(96, 165, 250, 0.05), transparent);
    opacity: 0;
  transition: opacity var(--premium-transition-normal);
}

.cart-dropdown .p-4:hover,
.wishlist-dropdown .p-4:hover {
  transform: translateX(5px);
  border-color: rgba(96, 165, 250, 0.2);
  box-shadow: var(--premium-shadow-sm);
}

.cart-dropdown .p-4:hover::before,
.wishlist-dropdown .p-4:hover::before {
    opacity: 1;
}

/* Advanced Notification Badge Effects */
.absolute.-top-2.-right-2 {
  box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.8);
  transition: all var(--premium-transition-bounce);
  }

.badge-pulse {
  animation: advancedPulse 1s cubic-bezier(0.68, -0.6, 0.32, 1.6);
}

@keyframes advancedPulse {
  0% { transform: scale(0.8); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
  50% { transform: scale(1.2); box-shadow: 0 0 0 4px rgba(239, 68, 68, 0); }
  100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}

/* Enhanced Navigation Icons */
.account-dropdown-container button svg,
.wishlist-dropdown-container a svg,
.cart-dropdown-container button svg {
  transition: transform var(--premium-transition-bounce);
}

.account-dropdown-container button:hover svg,
.wishlist-dropdown-container a:hover svg,
.cart-dropdown-container button:hover svg {
  transform: scale(1.15);
}

/* Premium Mobile Menu */
.md\\:hidden.px-4.py-2 {
  background-color: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(10px) saturate(180%);
  -webkit-backdrop-filter: blur(10px) saturate(180%);
  border-radius: 0 0 var(--premium-radius-lg) var(--premium-radius-lg);
  box-shadow: var(--premium-shadow-lg);
  border: 1px solid rgba(229, 231, 235, 0.5);
  border-top: none;
}

.md\\:hidden button,
.md\\:hidden a {
  position: relative;
  transition: all var(--premium-transition-normal);
  border-radius: var(--premium-radius-md);
  overflow: hidden;
}

.md\\:hidden button::after,
.md\\:hidden a::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(to right, transparent, rgba(96, 165, 250, 0.1), transparent);
  transform: translateX(-100%);
  transition: transform 0.5s ease;
}

.md\\:hidden button:hover::after,
.md\\:hidden a:hover::after {
  transform: translateX(100%);
}

/* Premium Button Effects */
button.bg-blue-600 {
  background: var(--premium-primary-gradient);
  border-radius: var(--premium-radius-md);
  box-shadow: var(--premium-shadow-md);
  transition: all var(--premium-transition-normal);
  border: 1px solid rgba(96, 165, 250, 0.3);
  position: relative;
  overflow: hidden;
}

button.bg-blue-600:hover {
  box-shadow: var(--premium-shadow-lg), 0 0 15px rgba(37, 99, 235, 0.3);
  transform: translateY(-1px);
}

button.bg-blue-600:active {
  transform: translateY(1px);
  box-shadow: var(--premium-shadow-sm);
  }

button.bg-blue-600::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transform: translateX(-100%);
  transition: transform 0.75s ease;
  }

button.bg-blue-600:hover::before {
  transform: translateX(100%);
}

/* Enhanced Category Styles */
.category-item {
  transition: all var(--premium-transition-normal);
  border-radius: var(--premium-radius-md);
  margin: 0.1rem 0.25rem;
  border: 1px solid transparent;
}

.category-item:hover {
  background-color: rgba(96, 165, 250, 0.05);
  border-color: rgba(96, 165, 250, 0.1);
  transform: translateX(2px);
  box-shadow: var(--premium-shadow-sm);
}

.subcategory-menu {
  background-color: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: var(--premium-radius-lg);
  border: 1px solid rgba(229, 231, 235, 0.5);
  box-shadow: var(--premium-shadow-lg);
  animation: subcategorySlide 0.3s cubic-bezier(0.25, 1, 0.5, 1) forwards;
}

@keyframes subcategorySlide {
  0% { opacity: 0; transform: translateX(-10px); }
  100% { opacity: 1; transform: translateX(0); }
}

/* Advanced Logo Transparency */
.logo-wrapper,
.logo-image {
  background-color: transparent !important;
  background-image: none !important;
  mix-blend-mode: normal;
}

/* Enhance Responsiveness */
@media (max-width: 768px) {
  .logo-image {
    max-height: 60px;
    transition: max-height var(--premium-transition-normal);
}

.announcement-bar {
    padding: 0.4rem 0;
  }
  
  .md\\:hidden.px-4.py-2 {
    max-height: 70vh;
    overflow-y: auto;
    /* iOS momentum scroll */
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
}

  /* Custom scrollbar */
  .md\\:hidden.px-4.py-2::-webkit-scrollbar {
    width: 5px;
  }
  
  .md\\:hidden.px-4.py-2::-webkit-scrollbar-track {
    background: transparent;
  }
  
  .md\\:hidden.px-4.py-2::-webkit-scrollbar-thumb {
    background: rgba(96, 165, 250, 0.3);
    border-radius: var(--premium-radius-full);
  }
}

/* Enhanced Checkout Button */
.bg-blue-600.hover\\:bg-blue-700.checkout-btn {
  animation: checkoutPulse 2.5s infinite;
  position: relative;
}

@keyframes checkoutPulse {
  0% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.3); }
  70% { box-shadow: 0 0 0 8px rgba(37, 99, 235, 0); }
  100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
}

/* Advanced Menu Animation */
.block.px-4.py-4 {
  transition: transform var(--premium-transition-smooth), color var(--premium-transition-normal);
}

.block.px-4.py-4:hover {
  transform: translateY(-1px);
}

.block.px-4.py-4:active {
  transform: translateY(1px);
}

/* Advanced Cart & Wishlist Count Updates */
@keyframes countUpdate {
  0% { transform: scale(0.8); color: var(--premium-surface-100); }
  50% { transform: scale(1.4); color: white; }
  100% { transform: scale(1); color: var(--premium-surface-100); }
}

.cart-item-count-update,
.wishlist-item-count-update {
  animation: countUpdate 0.5s cubic-bezier(0.68, -0.6, 0.32, 1.6);
}

/* Added Interactivity to Search Button */
button[type="button"] {
  transition: all var(--premium-transition-bounce);
}

button[type="button"]:hover {
  transform: scale(1.1);
}

button[type="button"]:active {
  transform: scale(0.95);
}

/* Advanced Header Scroll Animation */
.shadow-lg.modern-header {
  transform: translateZ(0);
}

.shadow-lg.modern-header::after {
  content: '';
  position: absolute;
  bottom: -1px;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(to right, transparent, var(--premium-primary-light), transparent);
  opacity: 0.5;
}

/* Ultra-Premium Animation Effects */
@media (prefers-reduced-motion: no-preference) {
.logo-image {
    transition: filter 0.5s ease;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
}

.logo-container:hover .logo-image {
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.15));
}

  /* Card hover effect for dropdowns */
  .cart-dropdown,
.wishlist-dropdown, 
  .account-dropdown {
    transform-style: preserve-3d;
    perspective: 1000px;
  }
  
  /* Advanced hover effect for category items */
  .category-item {
  position: relative;
    overflow: hidden;
}

  .category-item::after {
  content: '';
  position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, transparent, rgba(96, 165, 250, 0.05), transparent);
    transform: translateX(-100%);
    transition: transform 0.6s ease;
}

  .category-item:hover::after {
    transform: translateX(100%);
  }
}

.modal-fixed {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  pointer-events: auto !important;
  }
  
/* Advanced Missing Attributes Modal */
.attributes-modal-backdrop {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
  z-index: 9998;
  pointer-events: auto !important;
}

.attributes-modal-container {
  animation: modalAppear 0.3s var(--premium-transition-bounce);
  position: relative;
  z-index: 9999;
  pointer-events: auto !important;
}

@keyframes modalAppear {
  0% { transform: scale(0.9); opacity: 0; }
  70% { transform: scale(1.03); }
  100% { transform: scale(1); opacity: 1; }
}

/* Keep dropdown open when showing modal */
.cart-dropdown.keep-open {
  opacity: 1 !important;
  transform: scale(1) !important;
  visibility: visible !important;
  display: block !important;
  pointer-events: auto !important;
  z-index: 99 !important;
}

/* Enhanced Cart Dropdown */
.cart-dropdown {
  opacity: 1;
  transform: translateY(0);
  box-shadow: var(--premium-shadow-xl), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
  transform-origin: top right;
  pointer-events: auto;
}

.cart-dropdown .remove-cart-item {
  z-index: 10;
  position: relative;
  transition: all var(--premium-transition-normal);
  border-radius: 50%;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.cart-dropdown .remove-cart-item:hover {
  background-color: rgba(239, 68, 68, 0.1);
  transform: scale(1.1);
}

/* Cart item highlight on hover */
.cart-dropdown .p-4:hover {
  background-color: rgba(96, 165, 250, 0.05);
}

/* Make checkout button stand out */
.cart-dropdown .checkout-btn {
  position: relative;
  overflow: hidden;
  font-weight: 500;
  box-shadow: var(--premium-shadow-sm);
  background: var(--premium-primary-gradient);
}

.cart-dropdown .checkout-btn:after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.2), transparent);
  transform: translateX(-100%);
}

.cart-dropdown .checkout-btn:hover:after {
  animation: shine 1s ease forwards;
}

@keyframes shine {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

/* Add this to your style section */
.cart-dropdown.keep-open {
  display: block !important;
  opacity: 1 !important;
  visibility: visible !important;
  z-index: 9000;
}

.modal-fixed {
  z-index: 9999;
  display: flex !important;
}

/* Notification/Modal fade transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s, transform 0.3s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: scale(0.95);
}

.fade-enter-to,
.fade-leave-from {
  opacity: 1;
  transform: scale(1);
}

/* Modal specific styles to ensure it displays correctly */
body.modal-open {
  overflow: hidden !important;
  height: 100vh !important;
  width: 100vw !important;
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  touch-action: none !important;
  -webkit-overflow-scrolling: none !important;
}

/* Modal animation */
@keyframes modalFadeIn {
  from {
    opacity: 0;
    transform: scale(0.95) translateY(10px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

@keyframes overlayFadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Modal entry animations */
[v-if="showMissingAttributesModal"] {
  animation: modalFadeIn 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
}

[v-if="showMissingAttributesModal"] > div:first-child {
  animation: overlayFadeIn 0.2s ease-out forwards;
}

[v-if="showMissingAttributesModal"] > div:nth-child(2) {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
  transform: translateY(0) scale(1);
  transition: transform 0.3s ease-out, box-shadow 0.3s ease-out;
  will-change: transform, opacity;
}

/* Enhanced focus rings for better accessibility */
[v-if="showMissingAttributesModal"] button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
}

/* Make sure cart dropdown stays open when showing modal */
.cart-dropdown.keep-open {
  display: block !important;
  opacity: 1 !important;
  transform: scale(1) !important;
  visibility: visible !important;
  pointer-events: auto !important;
  z-index: 99 !important;
}

/* Add this to your CSS */
.simple-alert-animation {
  animation: simpleAlertIn 0.15s ease-out forwards;
}

@keyframes simpleAlertIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Browser-like alert animation - quick and subtle */
.simple-alert-animation {
  animation: simpleAlertIn 0.15s ease-out forwards;
}

@keyframes simpleAlertIn {
  from { 
    opacity: 0; 
    transform: translate(-50%, -60%);  /* Maintains horizontal center */
  }
  to { 
    opacity: 1; 
    transform: translate(-50%, -50%);  /* Maintains horizontal center */
  }
}

/* Alert specific styles - ensure they apply at body level */
.alert-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999999;
}

.alert-box {
  position: fixed;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  background-color: white;
  border-radius: 0.375rem;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  border: 1px solid #e5e7eb;
  width: 24rem;
  max-width: 90%;
  padding: 1rem;
  z-index: 1000000;
}

/* Account dropdown styles */
.account-dropdown {
  transform-origin: top right;
  animation: accountDropdownAnimation 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
  box-shadow: var(--premium-shadow-xl), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
}

@keyframes accountDropdownAnimation {
  from {
    opacity: 0;
    transform: translateY(-10px) scale(0.98);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.account-dropdown-container button .rotate-180 {
  transform: rotate(180deg);
}

/* Wishlist dropdown styles */
.wishlist-dropdown {
  transform-origin: top right;
  animation: wishlistDropdownAnimation 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
  box-shadow: var(--premium-shadow-xl), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
}

@keyframes wishlistDropdownAnimation {
  from {
    opacity: 0;
    transform: translateY(-10px) scale(0.98);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Cart dropdown styles */
.cart-dropdown {
  transform-origin: top right;
  animation: cartDropdownAnimation 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
  box-shadow: var(--premium-shadow-xl), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
}

@keyframes cartDropdownAnimation {
  from {
    opacity: 0;
    transform: translateY(-10px) scale(0.98);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Enhanced wishlist remove button */
.wishlist-remove-btn {
  position: relative;
  z-index: 60;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
  background-color: rgba(239, 68, 68, 0.05);
  opacity: 0.7;
}

.wishlist-remove-btn:hover {
  background-color: rgba(239, 68, 68, 0.15);
  color: #ef4444 !important;
  transform: scale(1.15);
  opacity: 1;
  box-shadow: 0 2px 8px rgba(239, 68, 68, 0.15);
}

.wishlist-remove-btn:active {
  transform: scale(0.95);
}

/* Fix dropdown open state */
.wishlist-dropdown-container,
.wishlist-dropdown {
  cursor: default;
}

/* Keep wishlist dropdown shown when removing items */
.wishlist-dropdown.keep-open {
  display: block !important;
  opacity: 1 !important;
  visibility: visible !important;
  transform: scale(1) !important;
  z-index: 9000 !important;
}
</style> 