<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Advanced E-commerce Header -->
    <Header />

    <!-- Product Content -->
    <div class="container mx-auto px-4 py-8">
      <!-- Loading state -->
      <div v-if="loading" class="flex justify-center py-16">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
      
      <!-- Product detail content -->
      <div v-else-if="!product" class="text-center py-16">
        <div class="mb-6">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-700 mb-2">Product Not Found</h2>
        <p class="text-gray-500 mb-6">The product you are looking for does not exist or has been removed.</p>
        <router-link to="/products" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
          </svg>
          Browse Products
        </router-link>
      </div>
      
      <!-- Product Details -->
      <div v-else class="mb-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
          <!-- Product Image Gallery -->
          <div>
            <!-- Main Product Image -->
            <div class="bg-gray-100 rounded-lg overflow-hidden h-96 flex items-center justify-center mb-4">
              <img 
                :src="getImageUrl(currentImage || product.image_url)" 
                :alt="product.name" 
                class="object-contain h-full w-full transition-opacity duration-300"
              >
            </div>
            
            <!-- Thumbnail Gallery -->
            <div class="flex space-x-2 overflow-x-auto pb-2">
              <!-- Main image thumbnail -->
              <div 
                class="w-20 h-20 flex-shrink-0 border-2 rounded-md overflow-hidden cursor-pointer transition-all duration-200"
                :class="{'border-blue-500': currentImage === product.image_url || !currentImage, 'border-gray-200 hover:border-gray-300': currentImage && currentImage !== product.image_url}"
                @mouseover="currentImage = product.image_url"
                @click="currentImage = product.image_url"
              >
            <img 
              :src="getImageUrl(product.image_url)" 
              :alt="product.name" 
                  class="w-full h-full object-cover"
                >
              </div>
              
              <!-- Additional image thumbnails -->
              <div 
                v-for="(image, index) in productAdditionalImages" 
                :key="index"
                class="w-20 h-20 flex-shrink-0 border-2 rounded-md overflow-hidden cursor-pointer transition-all duration-200"
                :class="{'border-blue-500': currentImage === image, 'border-gray-200 hover:border-gray-300': currentImage !== image}"
                @mouseover="currentImage = image"
                @click="currentImage = image"
              >
                <img 
                  :src="getImageUrl(image)" 
                  :alt="`${product.name} - View ${index + 1}`" 
                  class="w-full h-full object-cover"
                  @error="handleImageError($event, index)"
            >
              </div>
            </div>
          </div>
          
          <!-- Product Info -->
          <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ product.name }}</h1>
            
            <div class="flex items-center mb-4">
              <div class="flex text-yellow-400">
                <template v-for="i in 5" :key="i">
                  <svg v-if="i <= Math.round(averageRating)" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                </template>
              </div>
              <span class="text-sm text-gray-500 ml-2">({{ averageRating.toFixed(1) }})</span>
              <span class="mx-2 text-gray-300">|</span>
              <span class="text-sm text-indigo-600">{{ reviews.length }} {{ reviews.length === 1 ? 'Review' : 'Reviews' }}</span>
            </div>
            
            <!-- Price Display -->
            <div class="mb-6">
              <!-- Display both original and sale price if product is on sale -->
              <div v-if="product.on_sale && product.sale_price" class="flex flex-col">
                <span class="text-lg text-gray-500 line-through">Rs. {{ parseFloat(product.price).toFixed(2) }}</span>
                <span class="text-2xl font-bold text-red-600">Rs. {{ parseFloat(product.sale_price).toFixed(2) }}</span>
              </div>
              <!-- Display only regular price if not on sale -->
              <div v-else class="text-2xl font-bold text-gray-900">
                Rs. {{ parseFloat(product.price).toFixed(2) }}
              </div>
            </div>
            
            <p class="text-gray-600 mb-6" v-if="product.short_description" v-html="product.short_description"></p>
            
            <!-- Dynamic Size selector for cricket products -->
            <div v-if="getSizeVariants.length > 0" class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Size <span class="text-blue-600">*</span></label>
              <div class="text-sm text-gray-500 mb-2" v-if="!selectedSize">Please select a size option</div>
              <div class="flex flex-wrap gap-2">
                <button 
                  v-for="size in getSizeVariants" 
                  :key="size"
                  class="px-4 py-2 border rounded-md text-sm"
                  :class="selectedSize === size ? 'border-blue-600 bg-blue-50 text-blue-600' : 'border-gray-300 text-gray-700 hover:border-gray-400'"
                  @click="selectedSize = size"
                >
                  {{ size }}
                </button>
              </div>
            </div>
            
            <!-- Color selector if available -->
            <div v-if="getColorVariants.length > 0" class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Color <span class="text-blue-600">*</span></label>
              <div class="text-sm text-gray-500 mb-2" v-if="!selectedColor">Please select a color option</div>
              <div class="flex flex-wrap gap-2">
                <button 
                  v-for="color in getColorVariants" 
                  :key="color"
                  class="px-4 py-2 border rounded-md text-sm"
                  :class="selectedColor === color ? 'border-blue-600 bg-blue-50 text-blue-600' : 'border-gray-300 text-gray-700 hover:border-gray-400'"
                  @click="selectedColor = color"
                >
                  {{ color }}
                </button>
              </div>
            </div>
            
            <!-- Quantity selector -->
            <div class="mb-8">
              <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
              <div class="flex w-32">
                <button 
                  @click="quantity > 1 ? quantity-- : null"
                  class="px-3 py-1 border border-gray-300 rounded-l-md bg-gray-50 hover:bg-gray-100"
                  :class="{ 'opacity-50 cursor-not-allowed': quantity <= 1 || product.stock <= 0 }"
                  :disabled="quantity <= 1 || product.stock <= 0"
                >
                  -
                </button>
                <input 
                  type="number" 
                  v-model="quantity" 
                  min="1"
                  max="product.stock"
                  class="w-full px-3 py-1 border-t border-b border-gray-300 text-center"
                  :class="{ 'bg-gray-100': product.stock <= 0 }"
                  :disabled="product.stock <= 0"
                  @change="validateQuantity"
                >
                <button 
                  @click="incrementQuantity"
                  class="px-3 py-1 border border-gray-300 rounded-r-md bg-gray-50 hover:bg-gray-100"
                  :class="{ 'opacity-50 cursor-not-allowed': product.stock <= 0 || quantity >= product.stock }"
                  :disabled="product.stock <= 0 || quantity >= product.stock"
                >
                  +
                </button>
              </div>
              <div v-if="product.stock > 0 && product.stock <= 5" class="mt-1 text-sm text-orange-600">
                Only {{ product.stock }} items left in stock!
              </div>
              <div v-else-if="product.stock <= 0" class="mt-1 text-sm text-red-600">
                This product is currently out of stock
              </div>
            </div>
            
            <!-- Add to cart and wishlist buttons -->
            <div class="flex flex-wrap gap-4">
              <button 
                @click="addToCartLocal"
                data-add-to-cart
                class="flex-1 sm:flex-none sm:w-auto px-6 py-3 rounded-md flex items-center justify-center transition"
                :class="product.stock > 0 ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                :disabled="product.stock <= 0"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                {{ product.stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
              </button>
              
              <button 
                @click="buyNowLocal"
                class="flex-1 sm:flex-none sm:w-auto px-6 py-3 rounded-md flex items-center justify-center transition"
                :class="product.stock > 0 ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                :disabled="product.stock <= 0"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                {{ product.stock > 0 ? 'Buy Now' : 'Out of Stock' }}
              </button>
              
              <button 
                @click="toggleWishlist" 
                data-wishlist-toggle
                class="px-4 py-3 border rounded-md transition"
                :class="inWishlist ? 'bg-red-50 border-red-300 text-red-600 hover:bg-red-100' : 'border-gray-300 text-gray-600 hover:bg-gray-50'"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :fill="inWishlist ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
              </button>
            </div>
            
            <!-- Additional product info -->
            <div class="mt-6 flex items-center text-sm text-gray-500">
              <svg v-if="product.stock > 0" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span v-if="product.stock > 0">In stock and ready to ship</span>
              <span v-else class="text-red-600 font-medium">Out of Stock</span>
            </div>
            
            <div v-if="product.stock > 0" class="mt-2 flex items-center text-sm text-gray-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Ships within 24 hours
            </div>
            
            <div class="mt-2 flex items-center text-sm text-gray-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
              Secured payment processing
            </div>
          </div>
        </div>
      
        <!-- Product details tabs -->
        <div class="mt-16 border-t border-gray-200 pt-12">
          <div class="flex border-b border-gray-200">
            <button 
              @click="activeTab = 'details'"
              class="px-6 py-3 text-lg font-medium" 
              :class="activeTab === 'details' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
            >
              Details
            </button>
            <button 
              @click="activeTab = 'specifications'"
              class="px-6 py-3 text-lg font-medium"
              :class="activeTab === 'specifications' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
            >
              Specifications
            </button>
            <button 
              @click="activeTab = 'reviews'"
              class="px-6 py-3 text-lg font-medium"
              :class="activeTab === 'reviews' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
            >
              Reviews
            </button>
          </div>
          
          <!-- Details Tab -->
          <div v-if="activeTab === 'details'" class="py-8">
            <div class="prose prose-lg max-w-none" v-html="product.description">
            </div>
          </div>
          
          <!-- Specifications Tab -->
          <div v-if="activeTab === 'specifications'" class="py-8">
            <div v-if="product.variants && product.variants.length > 0" class="bg-white rounded-lg border border-gray-200">
              <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Product Specifications</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Detailed information about this product's features and attributes.</p>
              </div>
              <div class="border-t border-gray-200">
                <dl>
                  <div v-for="(variant, index) in product.variants" :key="index" 
                       class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                       :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                    <dt class="text-sm font-medium text-gray-500">{{ variant.attribute }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ variant.value }}</dd>
                  </div>
                </dl>
              </div>
            </div>
            <div v-else class="py-8 text-center">
              <p class="text-gray-500">No specifications available for this product.</p>
            </div>
          </div>
          
          <!-- Reviews Tab -->
          <div v-if="activeTab === 'reviews'" class="py-8">
            <!-- Loading state -->
            <div v-if="isLoadingReviews" class="flex justify-center py-12">
              <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
            </div>
            
            <!-- Reviews list -->
            <div v-else-if="reviews.length > 0" class="mb-12">
              <h3 class="text-xl font-bold mb-6">Customer Reviews ({{ reviews.length }})</h3>
              
              <!-- Average rating display -->
              <div class="flex items-center mb-8">
                <div class="mr-4">
                  <span class="text-3xl font-bold">{{ averageRating.toFixed(1) }}</span>
                  <span class="text-gray-500">/5</span>
                </div>
                <div class="flex text-yellow-400 text-2xl">
                  <template v-for="i in 5" :key="i">
                    <svg v-if="i <= Math.round(averageRating)" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                  </template>
                </div>
                <span class="ml-2 text-gray-500">({{ reviews.length }} {{ reviews.length === 1 ? 'review' : 'reviews' }})</span>
              </div>
              
              <!-- Individual reviews -->
              <div class="space-y-8">
                <div v-for="(review, index) in reviews" :key="index" class="border-b border-gray-200 pb-8 mb-8 last:border-b-0 last:mb-0 last:pb-0">
                  <div class="flex items-center mb-3">
                    <div class="flex text-yellow-400">
                      <template v-for="i in 5" :key="i">
                        <svg v-if="i <= review.rating" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                      </template>
                    </div>
                    <span class="ml-2 font-medium text-gray-900">Review by {{ review.user ? review.user.name : 'Anonymous' }}</span>
                  </div>
                  
                  <div class="text-sm text-gray-500 mb-2">
                    Posted on {{ new Date(review.created_at).toLocaleDateString() }}
                  </div>
                  
                  <div class="text-gray-700 mb-4">
                    {{ review.comment }}
                  </div>
                  
                  <!-- Review image if available -->
                  <div v-if="review.image_path" class="mt-4">
                    <img 
                      :src="getReviewImageUrl(review.image_path)" 
                      :alt="`Review by ${review.user ? review.user.name : 'Anonymous'}`"
                      class="max-w-[120px] max-h-[90px] rounded-lg shadow-md hover:shadow-lg transition cursor-pointer object-cover"
                      @click="openImageModal(review.image_path)"
                    >
                  </div>
                </div>
              </div>
            </div>
            
            <div v-else class="bg-gray-50 p-6 rounded-lg mb-8 text-center">
              <p class="text-gray-500">No reviews yet. Be the first to review this product!</p>
            </div>
            
            <!-- Add a review form -->
            <div v-if="isLoggedIn" class="mt-12 border-t border-gray-200 pt-8">
              <h3 class="text-xl font-bold mb-6">Write a Review</h3>
              
              <form @submit.prevent="submitReview" class="space-y-6">
                <!-- Rating selection -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Rating <span class="text-red-500">*</span></label>
                  <div class="flex text-gray-400">
                    <button 
                      v-for="star in 5" 
                      :key="star" 
                      type="button"
                      @click="newReview.rating = star" 
                      class="focus:outline-none p-1"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" :class="star <= newReview.rating ? 'text-yellow-400' : 'text-gray-300'" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </button>
                  </div>
                  <div v-if="newReview.errors.rating" class="text-red-500 text-sm mt-1">{{ newReview.errors.rating }}</div>
                </div>
                
                <!-- Review comment -->
                <div>
                  <label for="review-comment" class="block text-sm font-medium text-gray-700 mb-1">Your Review <span class="text-red-500">*</span></label>
                  <textarea 
                    id="review-comment" 
                    v-model="newReview.comment" 
                    rows="4" 
                    placeholder="What did you like or dislike about this product?" 
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  ></textarea>
                  <div v-if="newReview.errors.comment" class="text-red-500 text-sm mt-1">{{ newReview.errors.comment }}</div>
                </div>
                
                <!-- Image upload -->
                <div>
                  <label for="review-image" class="block text-sm font-medium text-gray-700 mb-1">Add a Photo (Optional)</label>
                  <div class="mt-1 flex items-center">
                    <span v-if="reviewImagePreview" class="relative">
                      <img :src="reviewImagePreview" alt="Review image preview" class="h-24 w-32 object-cover rounded-md mr-4">
                      <button 
                        @click="removeImage" 
                        type="button"
                        class="absolute -top-2 -right-2 bg-red-100 text-red-500 rounded-full p-1 hover:bg-red-200"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </span>
                    <label 
                      for="file-upload" 
                      class="cursor-pointer bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                    >
                      <span>Upload a file</span>
                      <input 
                        id="file-upload" 
                        name="file-upload" 
                        type="file" 
                        class="sr-only" 
                        accept="image/*"
                        @change="onFileChange"
                      >
                    </label>
                  </div>
                  <div v-if="newReview.errors.image" class="text-red-500 text-sm mt-1">{{ newReview.errors.image }}</div>
                  <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
                </div>
                
                <!-- Submit button -->
                <div>
                  <button 
                    type="submit" 
                    class="w-full md:w-auto px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center justify-center"
                    :disabled="newReview.submitting"
                  >
                    <svg v-if="newReview.submitting" xmlns="http://www.w3.org/2000/svg" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    {{ newReview.submitting ? 'Submitting...' : 'Submit Review' }}
                  </button>
                </div>
              </form>
            </div>
            
            <div v-else class="mt-12 border-t border-gray-200 pt-8 text-center">
              <p class="text-gray-600 mb-4">You need to be logged in to write a review.</p>
              <router-link to="/login" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Log in to Write a Review
              </router-link>
            </div>
            
            <!-- Image Modal -->
            <div v-if="imageModal.visible" class="fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center p-4" @click="closeImageModal">
              <div class="max-w-4xl max-h-full relative" @click.stop>
                <img 
                  :src="getReviewImageUrl(imageModal.src)" 
                  alt="Review image" 
                  class="max-w-full max-h-[90vh] object-contain rounded-lg"
                >
                <button 
                  @click="closeImageModal" 
                  class="absolute top-4 right-4 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-75"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Related products section -->
        <div class="mt-16 border-t border-gray-200 pt-12">
          <h2 class="text-2xl font-bold mb-8">You might also like</h2>
          
          <!-- Loading state -->
          <div v-if="loadingRelatedProducts" class="flex justify-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
          </div>
          
          <!-- Error state -->
          <div v-else-if="relatedProductsError" class="text-center py-8 text-gray-500">
            {{ relatedProductsError }}
          </div>
          
          <!-- No related products found -->
          <div v-else-if="relatedProducts.length === 0" class="text-center py-8 text-gray-500">
            No related products found
          </div>
          
          <!-- Related products carousel -->
          <div v-else class="relative">
            <!-- Previous button -->
            <button 
              @click="slideRelatedProducts('prev')" 
              class="absolute left-0 top-1/2 -translate-y-1/2 -ml-4 z-10 bg-white rounded-full p-2 shadow-md hover:bg-gray-100 focus:outline-none border border-gray-200"
              :class="{'opacity-50 cursor-not-allowed': relatedSlideIndex === 0}"
              :disabled="relatedSlideIndex === 0"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            
            <!-- Products container with overflow -->
            <div 
              class="overflow-hidden" 
              @touchstart="handleTouchStart" 
              @touchmove="handleTouchMove" 
              @touchend="handleTouchEnd"
            >
              <div 
                class="flex transition-transform duration-300 ease-in-out"
                :style="{ transform: `translateX(-${relatedSlideIndex * getSlidePercentage()}%)` }"
              >
                <div 
                  v-for="relatedProduct in relatedProducts" 
                  :key="relatedProduct.id" 
                  class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 flex-shrink-0 px-3"
                >
                  <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition h-full flex flex-col">
                    <div class="h-48 bg-gray-200 relative">
                      <img 
                        :src="getImageUrl(relatedProduct.image_url || relatedProduct.image)" 
                        :alt="relatedProduct.name"
                        class="w-full h-full object-cover"
                        @error="handleImageError"
                      >
                      <!-- Sale badge if product is on sale -->
                      <div v-if="relatedProduct.on_sale" class="absolute top-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded-md">
                        Sale
                      </div>
                      <!-- New badge if product is new arrival -->
                      <div v-if="relatedProduct.is_new_arrival" class="absolute top-2 right-2 bg-green-600 text-white text-xs px-2 py-1 rounded-md">
                        New
                      </div>
                    </div>
                    
                    <div class="p-4 flex-grow flex flex-col">
                      <h3 class="text-lg font-medium mb-2">{{ relatedProduct.name }}</h3>
                      <p v-if="relatedProduct.short_description" class="text-gray-600 mb-4 text-sm line-clamp-2 flex-grow" v-html="relatedProduct.short_description"></p>
                      <p v-else class="text-gray-600 mb-4 text-sm line-clamp-2 flex-grow">{{ getShortDescription(relatedProduct.description) }}</p>
                      
                      <div class="flex justify-between items-center mt-auto">
                        <!-- Sale price display -->
                        <div class="flex flex-col">
                          <span v-if="relatedProduct.on_sale && relatedProduct.sale_price" class="text-gray-500 line-through text-sm">
                            Rs{{ parseFloat(relatedProduct.price).toFixed(2) }}
                          </span>
                          <span v-if="relatedProduct.on_sale && relatedProduct.sale_price" class="text-red-600 font-bold">
                            Rs{{ parseFloat(relatedProduct.sale_price).toFixed(2) }}
                          </span>
                          <span v-else class="text-blue-700 font-bold">
                            Rs{{ parseFloat(relatedProduct.price).toFixed(2) }}
                          </span>
                        </div>
                        
                        <router-link :to="'/products/' + relatedProduct.id" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm transition">
                          View
                        </router-link>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Next button -->
            <button 
              @click="slideRelatedProducts('next')" 
              class="absolute right-0 top-1/2 -translate-y-1/2 -mr-4 z-10 bg-white rounded-full p-2 shadow-md hover:bg-gray-100 focus:outline-none border border-gray-200"
              :class="{'opacity-50 cursor-not-allowed': relatedSlideIndex >= maxRelatedSlideIndex}"
              :disabled="relatedSlideIndex >= maxRelatedSlideIndex"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
            
            <!-- Slide indicators and auto-play control -->
            <div class="flex justify-center items-center mt-6 gap-2">
              <button 
                v-for="(_, index) in Math.ceil(relatedProducts.length / 5)" 
                :key="index"
                @click="relatedSlideIndex = index"
                class="w-2 h-2 mx-1 rounded-full focus:outline-none"
                :class="relatedSlideIndex === index ? 'bg-blue-600' : 'bg-gray-300'"
              ></button>
              
              <!-- Auto-play toggle -->
              <button 
                @click="toggleAutoPlay" 
                class="ml-4 text-sm text-gray-600 flex items-center gap-1 py-1 px-2 rounded hover:bg-gray-100"
              >
                <svg v-if="autoPlayActive" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                </svg>
                <span>{{ autoPlayActive ? 'Pause' : 'Auto-play' }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Footer -->
    <Footer />

    <!-- Custom Notification Popup -->
    <transition name="fade">
      <div v-if="notification.show" class="fixed inset-0 flex items-center justify-center z-50 p-4">
        <div class="absolute inset-0 bg-black bg-opacity-50" @click="closeNotification"></div>
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full relative overflow-hidden">
          <!-- Colored header based on notification type -->
          <div :class="`h-1.5 w-full ${notification.type === 'error' ? 'bg-red-500' : notification.type === 'success' ? 'bg-green-500' : 'bg-blue-500'}`"></div>
          
          <div class="p-6">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4" 
                :class="notification.type === 'error' ? 'bg-red-100 text-red-500' : notification.type === 'success' ? 'bg-green-100 text-green-500' : 'bg-blue-100 text-blue-500'">
              <svg v-if="notification.type === 'error'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <svg v-else-if="notification.type === 'success'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            
            <!-- Title -->
            <h3 class="text-lg font-medium text-center mb-2" 
                :class="notification.type === 'error' ? 'text-red-800' : notification.type === 'success' ? 'text-green-800' : 'text-blue-800'">
              {{ notification.title }}
            </h3>
            
            <!-- Message -->
            <p class="text-sm text-gray-600 text-center mb-6">{{ notification.message }}</p>
            
            <!-- Buttons -->
            <div class="flex justify-center gap-3">
              <button v-if="notification.showCancel" 
                     @click="closeNotification" 
                     class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                {{ notification.cancelText }}
              </button>
              <button @click="handleNotificationAction" 
                     :class="`px-4 py-2 rounded-md shadow-sm text-sm font-medium text-white focus:outline-none ${notification.type === 'error' ? 'bg-red-600 hover:bg-red-700' : notification.type === 'success' ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700'}`">
                {{ notification.actionText }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import productService from '@/services/product.service';
import axios from 'axios';
import { addToCart, buyNow, getCartItemCount, getUniqueCartItemCount } from '@/utils/cart';
import { addToWishlist, isInWishlist, isInWishlistSync, removeFromWishlist, getWishlistCount, getWishlistCountSync } from '@/utils/wishlist';
import { mapGetters, mapActions } from 'vuex';
import { ref, computed, onMounted, watch, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Header from '@/components/Header.vue';
import Footer from '@/components/layout/Footer.vue';

export default {
  name: 'ProductDetail',
  components: {
    Header,
    Footer
  },
  props: {
    id: {
      type: [String, Number],
      required: true
    }
  },
  setup() {
    const route = useRoute();
    const router = useRouter();
    const loading = ref(true);
    const product = ref(null);
    const quantity = ref(1);
    const selectedSize = ref(null);
    const selectedColor = ref(null);
    const inWishlist = ref(false);
    const activeTab = ref('details');
    const mobileMenuOpen = ref(false);
    const mobileCategoriesOpen = ref(false);
    const categoriesDropdownOpen = ref(false);
    const accountDropdownOpen = ref(false);
    const cartDropdownOpen = ref(false);
    // Related products data
    const relatedProducts = ref([]);
    const loadingRelatedProducts = ref(false);
    const relatedProductsError = ref(null);
    
    const mockProducts = ref({
      1: {
        id: 1,
        name: "Professional Basketball",
        description: "Official size and weight basketball for professional games. Made with premium materials for excellent grip and durability. Suitable for indoor and outdoor courts.",
        price: 49.99,
        image_url: null,
        category_id: 1
      },
      2: {
        id: 2,
        name: "Training Soccer Ball",
        description: "Durable soccer ball perfect for daily training. Features reinforced stitching and water-resistant exterior. Designed for consistent performance on all field types.",
        price: 35.95,
        image_url: null,
        category_id: 2
      },
      3: {
        id: 3,
        name: "Premium Tennis Racket",
        description: "Lightweight tennis racket with perfect balance and control. Engineered with carbon fiber frame and optimal string tension. Includes vibration dampening technology for reduced arm strain.",
        price: 129.99,
        image_url: null,
        category_id: 3
      },
      4: {
        id: 4,
        name: "Running Shoes",
        description: "Comfortable running shoes with excellent cushioning. Features breathable mesh upper and responsive foam midsole. Designed for neutral pronation and daily training runs.",
        price: 89.95,
        image_url: null,
        category_id: 4
      }
    });

    const cartItemCount = ref(getUniqueCartItemCount());
    const wishlistCount = ref(getWishlistCount());

    // Reviews data
    const reviews = ref([]);
    const isLoadingReviews = ref(false);
    const isLoggedIn = ref(false);
    const reviewImagePreview = ref(null);
    const imageModal = ref({
      visible: false,
      src: ''
    });
    const newReview = ref({
      rating: 0,
      comment: '',
      image: null,
      submitting: false,
      errors: {}
    });

    // Computed property for average rating
    const averageRating = computed(() => {
      if (!reviews.value.length) return 0;
      const sum = reviews.value.reduce((total, review) => total + review.rating, 0);
      return sum / reviews.value.length;
    });

    // Computed properties to extract variants
    const getSizeVariants = computed(() => {
      if (!product.value || !product.value.variants) return [];
      
      // Look for size-related variants (Size, SH, LH, etc)
      const sizeVariants = product.value.variants.filter(v => 
        v.attribute.toLowerCase().includes('size') || 
        ['sh', 'lh', 'handle'].some(term => v.attribute.toLowerCase().includes(term))
      );
      
      // Extract just the values
      return sizeVariants.map(v => v.value);
    });
    
    const getColorVariants = computed(() => {
      if (!product.value || !product.value.variants) return [];
      
      // Look for color-related variants
      const colorVariants = product.value.variants.filter(v => 
        v.attribute.toLowerCase().includes('color') || 
        v.attribute.toLowerCase().includes('colour')
      );
      
      // Extract just the values
      return colorVariants.map(v => v.value);
    });

    // Fetch reviews for the product
    const fetchReviews = async () => {
      try {
        isLoadingReviews.value = true;
        const response = await axios.get(`/api/products/${route.params.id}/reviews`);
        
        if (response.data.data) {
          reviews.value = response.data.data;
        } else if (response.data) {
          reviews.value = response.data;
        }
      } catch (error) {
        console.error('Error fetching reviews:', error);
        // Mock reviews for development
        reviews.value = [
          {
            id: 1,
            product_id: route.params.id,
            rating: 5,
            comment: 'This is exactly what I was looking for. Great quality and performance.',
            image_path: null,
            user: {
              id: 1,
              name: 'John Smith'
            },
            created_at: new Date().toISOString()
          },
          {
            id: 2,
            product_id: route.params.id,
            rating: 4,
            comment: 'Solid construction and works as advertised. Would recommend to others.',
            image_path: null,
            user: {
              id: 2,
              name: 'Sarah Johnson'
            },
            created_at: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString()
          }
        ];
      } finally {
        isLoadingReviews.value = false;
      }
    };

    // Get review image URL
    const getReviewImageUrl = (path) => {
      if (!path) return '';
      
      if (path.startsWith('http')) {
        return path;
      }
      
      return `http://localhost:8000/storage/${path}`;
    };

    // Handle file change for image upload
    const onFileChange = (e) => {
      const file = e.target.files[0];
      if (!file) return;
      
      // Validate file type and size
      const isImage = file.type.match('image.*');
      const isSizeValid = file.size <= 2 * 1024 * 1024; // 2MB limit
      
      if (!isImage) {
        newReview.value.errors.image = 'Please select an image file (PNG, JPG, GIF)';
        return;
      }
      
      if (!isSizeValid) {
        newReview.value.errors.image = 'Image size should not exceed 2MB';
        return;
      }
      
      // Clear previous errors
      newReview.value.errors.image = null;
      
      // Save file for form submission
      newReview.value.image = file;
      
      // Create preview
      const reader = new FileReader();
      reader.onload = (e) => {
        reviewImagePreview.value = e.target.result;
      };
      reader.readAsDataURL(file);
    };

    // Remove selected image
    const removeImage = () => {
      newReview.value.image = null;
      reviewImagePreview.value = null;
    };

    // Open image modal
    const openImageModal = (imagePath) => {
      imageModal.value.src = imagePath;
      imageModal.value.visible = true;
      
      // Prevent scrolling when modal is open
      document.body.style.overflow = 'hidden';
    };

    // Close image modal
    const closeImageModal = () => {
      imageModal.value.visible = false;
      
      // Restore scrolling
      document.body.style.overflow = '';
    };

    // Submit a new review
    const submitReview = async () => {
      // Reset errors
      newReview.value.errors = {};
      
      // Validate form
      let isValid = true;
      
      if (!newReview.value.rating) {
        newReview.value.errors.rating = 'Please select a rating';
        isValid = false;
      }
      
      if (!newReview.value.comment.trim()) {
        newReview.value.errors.comment = 'Please enter your review';
        isValid = false;
      } else if (newReview.value.comment.trim().length < 10) {
        newReview.value.errors.comment = 'Review must be at least 10 characters';
        isValid = false;
      }
      
      if (!isValid) return;
      
      // Submit the review
      try {
        newReview.value.submitting = true;
        
        // Create form data for file upload
        const formData = new FormData();
        formData.append('product_id', product.value.id);
        formData.append('rating', newReview.value.rating);
        formData.append('comment', newReview.value.comment);
        
        // Add image if present
        if (newReview.value.image) {
          formData.append('image', newReview.value.image);
        }
        
        const response = await axios.post(`/api/reviews`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        
        // Add the new review to the list
        if (response.data) {
          // For development, we'll add it to the beginning of the list
          const review = response.data;
          
          // Add user information if it's missing (for mock data)
          if (!review.user) {
            review.user = {
              id: 99,
              name: 'Current User'
            };
          }
          
          reviews.value.unshift(review);
          
          // Recalculate average rating after adding the new review
          // This will update the header display automatically through the computed property
        }
        
        // Reset the form
        newReview.value = {
          rating: 0,
          comment: '',
          image: null,
          submitting: false,
          errors: {}
        };
        
        // Clear image preview
        reviewImagePreview.value = null;
        
        // Show success message
        alert('Thank you for your review!');
        
      } catch (error) {
        console.error('Error submitting review:', error);
        
        // Handle validation errors from the server
        if (error.response && error.response.data && error.response.data.errors) {
          newReview.value.errors = error.response.data.errors;
        } else if (error.response && error.response.data && error.response.data.error) {
          alert(error.response.data.error);
        } else {
          alert('An error occurred while submitting your review. Please try again.');
        }
      } finally {
        newReview.value.submitting = false;
      }
    };

    // Check if user is logged in
    const checkLoginStatus = async () => {
      try {
        // In a real app, this would check the authentication status
        // For now, we'll use localStorage or token state from store if available
        // Placeholder implementation
        const token = localStorage.getItem('token');
        isLoggedIn.value = !!token;
        
        // Alternatively, you can check with the server
        // const response = await axios.get('/api/user/profile');
        // isLoggedIn.value = !!response.data;
      } catch (error) {
        console.error('Error checking login status:', error);
        isLoggedIn.value = false;
      }
    };

    const fetchProductDetails = async () => {
      try {
        const response = await axios.get(`/api/products/${route.params.id}`);
        
        // Check possible response structures
        if (response.data.data) {
          product.value = response.data.data;
        } else if (response.data) {
          product.value = response.data;
        } else {
          product.value = null;
        }
        
        loading.value = false;
        
        // Check if product is in wishlist
        if (product.value) {
          // Set quantity to 0 if product is out of stock, otherwise set to 1
          if (product.value.stock <= 0) {
            quantity.value = 0;
          } else {
            quantity.value = 1; // Set default quantity to 1 if stock is available
          }
          
          inWishlist.value = isInWishlistSync(product.value.id);
          
          // Fetch reviews after product is loaded
          fetchReviews();
          
          // Fetch related products after product is loaded
          fetchRelatedProducts();
        }

        // Set the initial main image
        currentImage.value = product.value.image_url;

        // Debug the raw API response
        console.log('Raw API response:', response.data);

        // Load additional images with more robust checks for different API response structures
        if (product.value.images && Array.isArray(product.value.images.additional)) {
          // If product.images.additional array exists, use it directly
          productAdditionalImages.value = product.value.images.additional;
          console.log('Loaded additional images from images.additional:', productAdditionalImages.value);
        } else if (product.value.productImages && Array.isArray(product.value.productImages)) {
          // If product.productImages array exists (direct API response format)
          productAdditionalImages.value = product.value.productImages.map(img => img.image_url);
          console.log('Loaded additional images from productImages array:', productAdditionalImages.value);
        } else {
          // Try to fetch product images separately if not included in initial response
          try {
            const imagesResponse = await axios.get(`/api/products/${route.params.id}/images`);
            if (imagesResponse.data && (imagesResponse.data.data || Array.isArray(imagesResponse.data))) {
              const imagesData = imagesResponse.data.data || imagesResponse.data;
              if (Array.isArray(imagesData)) {
                productAdditionalImages.value = imagesData.map(img => img.image_url || img);
                console.log('Loaded additional images from separate API call:', productAdditionalImages.value);
              }
            }
          } catch (imageError) {
            console.error('Error fetching additional product images:', imageError);
            productAdditionalImages.value = [];
          }
        }

        // Debug the response data structure
        console.log('Product data from API:', product.value);
        console.log('Images data structure:', product.value?.images || 'No images property');
        console.log('productImages data structure:', product.value?.productImages || 'No productImages property');
        console.log('Final productAdditionalImages array:', productAdditionalImages.value);
      } catch (error) {
        console.error('Error fetching product details, using mock data:', error);
        
        // Use mock data if available
        if (mockProducts.value[route.params.id]) {
          product.value = mockProducts.value[route.params.id];
        } else {
          // If ID not in mock data, create a fallback product with the ID
          product.value = {
            id: route.params.id,
            name: `Product ${route.params.id}`,
            description: "Detailed product description will be available soon.",
            price: 99.99,
            image_url: null
          };
        }
        loading.value = false;
        
        // Try to fetch reviews even for mock products
        fetchReviews();
      }
    };

    // Add this new function to handle image loading errors
    const handleImageError = (event, index) => {
      console.error(`Error loading image at index ${index}:`, event);
      // Optionally replace with a placeholder image
      event.target.src = "https://via.placeholder.com/150?text=Image+Error";
    };

    // Update the getImageUrl function to better handle different image path formats
    const getImageUrl = (imagePath) => {
      if (!imagePath) {
        return "https://via.placeholder.com/150?text=No+Image";
      }
      
      // If the path is a full URL, return it directly
      if (imagePath.startsWith('http')) {
        return imagePath;
      }
      
      // Handle paths that might already have "storage/" in them
      if (imagePath.startsWith('storage/')) {
        return `http://localhost:8000/${imagePath}`;
      }
      
      // Otherwise, assume it's a relative path on the server
      return `http://localhost:8000/storage/${imagePath}`;
    };

    const addToCartLocal = () => {
      if (!product.value) return;
      
      // Check if product is in stock
      if (product.value.stock <= 0) {
        showNotification({
          type: 'error',
          title: 'Out of Stock',
          message: 'Sorry, this product is out of stock'
        });
        return;
      }
      
      // Check if quantity is valid (must be at least 1)
      if (quantity.value < 1) {
        showNotification({
          type: 'error',
          title: 'Invalid Quantity',
          message: 'Quantity must be at least 1 to add to cart',
          action: () => { quantity.value = 1; } // Automatically adjust to minimum
        });
        return;
      }
      
      // Check if requested quantity exceeds stock
      if (quantity.value > product.value.stock) {
        showNotification({
          type: 'error',
          title: 'Limited Stock',
          message: `Sorry, only ${product.value.stock} items are available in stock. Please reduce your quantity.`,
          action: () => { quantity.value = product.value.stock; } // Automatically adjust to max available
        });
        return;
      }
      
      // Validate selections if variants exist
      if (getSizeVariants.value.length > 0 && !selectedSize.value) {
        showNotification({
          type: 'info',
          title: 'Size Selection Required',
          message: 'Please select a size option by clicking on one of the size buttons',
          actionText: 'Select Size'
        });
        return;
      }
      
      if (getColorVariants.value.length > 0 && !selectedColor.value) {
        showNotification({
          type: 'info',
          title: 'Color Selection Required',
          message: 'Please select a color option by clicking on one of the color buttons',
          actionText: 'Select Color'
        });
        return;
      }
      
      // Prepare the product data for cart
      const productData = {
        id: product.value.id,
        name: product.value.name,
        price: product.value.on_sale && product.value.sale_price ? 
               parseFloat(product.value.sale_price) : 
               parseFloat(product.value.price),
        original_price: parseFloat(product.value.price),
        on_sale: product.value.on_sale || false,
        image: getImageUrl(product.value.image_url),
        stock: product.value.stock,
        options: {
          size: selectedSize.value,
          color: selectedColor.value
        }
      };
      
      // Add to cart using the utility function
      const result = addToCart(productData, quantity.value, selectedSize.value, selectedColor.value);
      
      if (!result.success) {
        // Show error message if adding to cart failed
        showNotification({
          type: 'error',
          title: 'Error',
          message: result.message
        });
        return;
      }
      
      // Log that we're dispatching the event
      console.log('Dispatching cart-updated event');
      
      // Force update cart count in header
      document.dispatchEvent(new CustomEvent('cart-updated'));
      
      // Force update the cartItemCount ref
      cartItemCount.value = getUniqueCartItemCount();
      
      // Show a success notification
      showNotification({
        type: 'success',
        title: 'Added to Cart',
        message: `${product.value.name} has been added to your cart.`,
        actionText: 'View Cart',
        showCancel: true,
        cancelText: 'Continue Shopping',
        action: () => { router.push('/cart'); }
      });
      
      // Show a subtle visual feedback instead of alert
      const addToCartButton = document.querySelector('[data-add-to-cart]');
      if (addToCartButton) {
        const originalText = addToCartButton.innerHTML;
        
        // Remove size and color from the confirmation message
        addToCartButton.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          Added to Cart
        `;
        
        setTimeout(() => {
          addToCartButton.innerHTML = originalText;
        }, 2000);
      }
    };

    const buyNowLocal = () => {
      if (!product.value) return;
      
      // Check if product is in stock
      if (product.value.stock <= 0) {
        showNotification({
          type: 'error',
          title: 'Out of Stock',
          message: 'Sorry, this product is out of stock'
        });
        return;
      }
      
      // Check if quantity is valid (must be at least 1)
      if (quantity.value < 1) {
        showNotification({
          type: 'error',
          title: 'Invalid Quantity',
          message: 'Quantity must be at least 1 to proceed with purchase',
          action: () => { quantity.value = 1; } // Automatically adjust to minimum
        });
        return;
      }
      
      // Check if requested quantity exceeds stock
      if (quantity.value > product.value.stock) {
        showNotification({
          type: 'error',
          title: 'Limited Stock',
          message: `Sorry, only ${product.value.stock} items are available in stock. Please reduce your quantity.`,
          action: () => { quantity.value = product.value.stock; } // Automatically adjust to max available
        });
        return;
      }
      
      // Validate selections if variants exist
      if (getSizeVariants.value.length > 0 && !selectedSize.value) {
        showNotification({
          type: 'info',
          title: 'Size Selection Required',
          message: 'Please select a size option by clicking on one of the size buttons',
          actionText: 'Select Size'
        });
        return;
      }
      
      if (getColorVariants.value.length > 0 && !selectedColor.value) {
        showNotification({
          type: 'info',
          title: 'Color Selection Required',
          message: 'Please select a color option by clicking on one of the color buttons',
          actionText: 'Select Color'
        });
        return;
      }
      
      // Prepare the product data for cart
      const productData = {
        id: product.value.id,
        name: product.value.name,
        price: product.value.on_sale && product.value.sale_price ? 
               parseFloat(product.value.sale_price) : 
               parseFloat(product.value.price),
        original_price: parseFloat(product.value.price),
        on_sale: product.value.on_sale || false,
        image: getImageUrl(product.value.image_url),
        stock: product.value.stock,
        options: {
          size: selectedSize.value,
          color: selectedColor.value
        }
      };
      
      // Use buyNow utility function and proceed to checkout
      const buyResult = buyNow(productData, quantity.value, selectedSize.value, selectedColor.value);
      
      if (!buyResult.success) {
        // Show error message if buying now failed
        showNotification({
          type: 'error',
          title: 'Error',
          message: buyResult.message
        });
        return;
      }
      
      // Update cart count immediately
      cartItemCount.value = getUniqueCartItemCount();
      
      // Update cart count
      console.log('Dispatching cart-updated event from buyNow');
      document.dispatchEvent(new CustomEvent('cart-updated'));
      
      // Navigate directly to checkout
      router.push('/checkout/shipping');
    };

    // Validate and adjust quantity when manually entered
    const validateQuantity = () => {
      // Convert to number and ensure it's an integer
      quantity.value = parseInt(quantity.value) || 0;
      
      // Set quantity to 0 if product is out of stock
      if (product.value && product.value.stock <= 0) {
        quantity.value = 0;
        return;
      }
      
      // Enforce minimum of 1 when stock is available
      if (product.value && product.value.stock > 0 && quantity.value < 1) {
        quantity.value = 1;
      }
      
      // Enforce maximum stock limit
      if (product.value && product.value.stock > 0 && quantity.value > product.value.stock) {
        quantity.value = product.value.stock;
      }
    };
    
    // Increment quantity with stock validation
    const incrementQuantity = () => {
      if (product.value && quantity.value < product.value.stock) {
        quantity.value++;
      }
    };

    const toggleWishlist = async () => {
      if (!product.value) return;
      
      // Create a copy of product data for the wishlist
      const productData = {
        id: product.value.id,
        name: product.value.name,
        price: product.value.price,
        image: product.value.image_url || product.value.thumbnail
      };
      
      // Toggle product in wishlist: remove if exists, add if doesn't
      if (inWishlist.value) {
        await removeFromWishlist(product.value.id);
        inWishlist.value = false;
      } else {
        await addToWishlist(productData);
        inWishlist.value = true;
      }
      
      // Update the wishlist count immediately
      wishlistCount.value = getWishlistCountSync();
      
      // Dispatch event to update wishlist count in header
      console.log('Dispatching wishlist-updated event');
      document.dispatchEvent(new CustomEvent('wishlist-updated'));
      
      // Show visual feedback on the wishlist button
      const wishlistButton = document.querySelector('[data-wishlist-toggle]');
      if (wishlistButton) {
        if (inWishlist.value) {
          wishlistButton.classList.add('pulse-animation');
          setTimeout(() => {
            wishlistButton.classList.remove('pulse-animation');
          }, 1000);
        }
      }
    };

    const logout = () => {
      logoutAction();
      accountDropdownOpen.value = false;
      router.push('/login');
    };

    const checkWishlistStatus = async () => {
      if (product.value) {
        // First set the value synchronously from localStorage for immediate UI feedback
        inWishlist.value = isInWishlistSync(product.value.id);
        
        // Then update with the true value from the API if the user is logged in
        const apiStatus = await isInWishlist(product.value.id);
        inWishlist.value = apiStatus;
      }
    };

    const forceUpdate = () => {
      // Force component update to recalculate cartItemCount
      product.value = { ...product.value };
    };

    const toggleAccountDropdown = () => {
      accountDropdownOpen.value = !accountDropdownOpen.value;
    };

    const toggleCartDropdown = () => {
      cartDropdownOpen.value = !cartDropdownOpen.value;
    };

    // Calculate discount percentage
    const calculateDiscountPercentage = (originalPrice, salePrice) => {
      const original = parseFloat(originalPrice);
      const sale = parseFloat(salePrice);
      if (original <= 0 || sale <= 0 || sale >= original) return 0;
      
      const discountPercent = Math.round(((original - sale) / original) * 100);
      return discountPercent;
    };

    // Get a short description from a longer text
    const getShortDescription = (text) => {
      if (!text) return '';
      
      // Strip HTML tags
      const strippedText = text.replace(/<[^>]*>?/gm, '');
      
      // Return first 100 characters with ellipsis if needed
      return strippedText.length > 100 ? strippedText.substring(0, 100) + '...' : strippedText;
    };
    
    // Fetch related products
    const fetchRelatedProducts = async () => {
      if (!product.value || !product.value.id) return;
      
      loadingRelatedProducts.value = true;
      relatedProductsError.value = null;
      
      try {
        const response = await axios.get(`/api/products/${product.value.id}/related`);
        
        if (response.data && response.data.success && response.data.data) {
          relatedProducts.value = response.data.data;
        } else if (response.data) {
          relatedProducts.value = response.data;
        } else {
          relatedProductsError.value = 'Failed to load related products';
        }
      } catch (error) {
        console.error('Error fetching related products:', error);
        relatedProductsError.value = 'Error loading related products';
        
        // For development, use placeholder data if API fails
        relatedProducts.value = [];
      } finally {
        loadingRelatedProducts.value = false;
      }
    };

    // Add onMounted hook to fetch product details when component loads
    onMounted(() => {
      console.log('ProductDetail component mounted, fetching product with ID:', route.params.id);
      fetchProductDetails();
      checkLoginStatus();
      
      // Update initial counts
      cartItemCount.value = getUniqueCartItemCount();
      wishlistCount.value = getWishlistCountSync(); // Use sync version for initial load
      
      // Function to handle cart updates
      const handleCartUpdate = () => {
        console.log('Cart updated event received in ProductDetail');
        cartItemCount.value = getUniqueCartItemCount();
      };
      
      // Function to handle wishlist updates
      const handleWishlistUpdate = async () => {
        console.log('Wishlist updated event received in ProductDetail');
        wishlistCount.value = getWishlistCountSync(); // Use sync version for immediate UI update
        checkWishlistStatus();
      };
      
      // Function to handle window resize for carousel
      const handleResize = () => {
        // Ensure relatedSlideIndex is valid after resize
        relatedSlideIndex.value = Math.min(relatedSlideIndex.value, maxRelatedSlideIndex.value);
      };
      
      // Listen for wishlist updates
      document.addEventListener('wishlist-updated', handleWishlistUpdate);
      
      // Listen for cart updates
      document.addEventListener('cart-updated', handleCartUpdate);
      
      // Listen for window resize
      window.addEventListener('resize', handleResize);
      
      // Start auto-play if enabled
      if (autoPlayActive.value) {
        startAutoPlay();
      }
      
      // Cleanup function
      onUnmounted(() => {
        document.removeEventListener('wishlist-updated', handleWishlistUpdate);
        document.removeEventListener('cart-updated', handleCartUpdate);
        window.removeEventListener('resize', handleResize);
        
        // Clean up auto-play interval
        stopAutoPlay();
      });
    });
    
    // Watch for route param changes
    watch(() => route.params.id, (newId, oldId) => {
      if (newId !== oldId) {
        console.log('Route param changed, fetching new product:', newId);
        fetchProductDetails();
      }
    });

    const currentImage = ref(null);
    const productAdditionalImages = ref([]);
    const hasAdditionalImages = computed(() => {
      return product.value && 
        product.value.images && 
        product.value.images.additional && 
        product.value.images.additional.length > 0;
    });

    const relatedSlideIndex = ref(0);
    const maxRelatedSlideIndex = computed(() => {
      const visibleItems = window.innerWidth < 640 ? 1 : 
                          window.innerWidth < 768 ? 2 :
                          window.innerWidth < 1024 ? 3 : 5;
      return Math.max(0, Math.ceil(relatedProducts.value.length / visibleItems) - 1);
    });

    const slideRelatedProducts = (direction) => {
      if (direction === 'prev') {
        relatedSlideIndex.value = Math.max(0, relatedSlideIndex.value - 1);
      } else if (direction === 'next') {
        relatedSlideIndex.value = Math.min(maxRelatedSlideIndex.value, relatedSlideIndex.value + 1);
      }
    };

    const getSlidePercentage = () => {
      const visibleItems = window.innerWidth < 640 ? 1 : 
                          window.innerWidth < 768 ? 2 :
                          window.innerWidth < 1024 ? 3 : 5;
      return 100 / visibleItems;
    };

    // Touch handling variables
    const touchStartX = ref(0);
    const touchEndX = ref(0);
    
    // Auto-play functionality
    const autoPlayActive = ref(false);
    const autoPlayInterval = ref(null);
    
    const startAutoPlay = () => {
      if (autoPlayInterval.value) {
        clearInterval(autoPlayInterval.value);
      }
      
      autoPlayInterval.value = setInterval(() => {
        if (relatedSlideIndex.value < maxRelatedSlideIndex.value) {
          relatedSlideIndex.value++;
        } else {
          relatedSlideIndex.value = 0; // Loop back to start
        }
      }, 3000); // Change slide every 3 seconds
    };
    
    const stopAutoPlay = () => {
      if (autoPlayInterval.value) {
        clearInterval(autoPlayInterval.value);
        autoPlayInterval.value = null;
      }
    };
    
    const toggleAutoPlay = () => {
      autoPlayActive.value = !autoPlayActive.value;
      
      if (autoPlayActive.value) {
        startAutoPlay();
      } else {
        stopAutoPlay();
      }
    };
    
    // Handle touch events for mobile swipe
    const handleTouchStart = (e) => {
      touchStartX.value = e.touches[0].clientX;
    };
    
    const handleTouchMove = (e) => {
      touchEndX.value = e.touches[0].clientX;
    };
    
    const handleTouchEnd = () => {
      const swipeDistance = touchEndX.value - touchStartX.value;
      
      // Check if it's a significant swipe (more than 50px)
      if (Math.abs(swipeDistance) > 50) {
        if (swipeDistance > 0) {
          // Swipe right - go to previous slide
          slideRelatedProducts('prev');
        } else {
          // Swipe left - go to next slide
          slideRelatedProducts('next');
        }
      }
      
      // Reset touch values
      touchStartX.value = 0;
      touchEndX.value = 0;
    };

    // Notification functionality
    const notification = ref({
      show: false,
      type: 'info', // 'info', 'success', 'error'
      title: '',
      message: '',
      actionText: 'OK',
      cancelText: 'Cancel',
      showCancel: false,
      action: null // Callback function for the action button
    });
    
    const showNotification = (options) => {
      notification.value = {
        show: true,
        type: 'info',
        title: '',
        message: '',
        actionText: 'OK',
        cancelText: 'Cancel',
        showCancel: false,
        action: null,
        ...options
      };
    };
    
    const closeNotification = () => {
      notification.value.show = false;
    };
    
    const handleNotificationAction = () => {
      if (notification.value.action && typeof notification.value.action === 'function') {
        notification.value.action();
      }
      closeNotification();
    };

    return {
      loading,
      product,
      quantity,
      selectedSize,
      selectedColor,
      inWishlist,
      activeTab,
      getSizeVariants,
      getColorVariants,
      mobileMenuOpen,
      mobileCategoriesOpen,
      categoriesDropdownOpen,
      accountDropdownOpen,
      cartDropdownOpen,
      mockProducts,
      cartItemCount,
      wishlistCount,
      reviews,
      isLoadingReviews,
      isLoggedIn,
      reviewImagePreview,
      imageModal,
      newReview,
      averageRating,
      fetchProductDetails,
      getImageUrl,
      addToCartLocal,
      buyNowLocal,
      toggleWishlist,
      logout,
      checkWishlistStatus,
      forceUpdate,
      toggleAccountDropdown,
      toggleCartDropdown,
      onFileChange,
      removeImage,
      openImageModal,
      closeImageModal,
      submitReview,
      checkLoginStatus,
      getReviewImageUrl,
      validateQuantity,
      incrementQuantity,
      calculateDiscountPercentage,
      currentImage,
      productAdditionalImages,
      hasAdditionalImages,
      relatedProducts,
      loadingRelatedProducts,
      relatedProductsError,
      getShortDescription,
      fetchRelatedProducts,
      relatedSlideIndex,
      maxRelatedSlideIndex,
      slideRelatedProducts,
      getSlidePercentage,
      handleTouchStart,
      handleTouchMove,
      handleTouchEnd,
      autoPlayActive,
      autoPlayInterval,
      startAutoPlay,
      stopAutoPlay,
      toggleAutoPlay,
      notification,
      showNotification,
      closeNotification,
      handleNotificationAction
    };
  }
}
</script>

<style>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.pulse-animation {
  animation: pulse 1s;
}

@keyframes pulse {
  0% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7);
  }
  
  50% {
    transform: scale(1.1);
    box-shadow: 0 0 0 10px rgba(220, 38, 38, 0);
  }
  
  100% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
  }
}

/* Modal Transition Animations */
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
</style> 