<template>
  <div class="product-tabs mt-10">
    <!-- Updated tab header to match the specified design -->
    <div class="flex border-b border-gray-200">
      <button 
        v-for="tab in tabs" 
        :key="tab.id"
        class="px-6 py-3 text-lg font-medium"
        :class="activeTab === tab.id ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
        @click="setActiveTab(tab.id)"
      >
        {{ tab.label }}
      </button>
    </div>
    
    <div class="tabs-content mt-6">
      <!-- Details Tab -->
      <div v-if="activeTab === 'details'" class="tab-pane">
        <div v-if="loading.details" class="flex justify-center py-10">
          <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-primary-600"></div>
        </div>
        
        <div v-else-if="error.details" class="bg-red-50 p-4 rounded-md">
          <p class="text-red-600">{{ error.details }}</p>
        </div>
        
        <div v-else-if="!productDetails" class="bg-gray-50 p-4 rounded-md">
          <p class="text-gray-600">No product details available.</p>
        </div>
        
        <div v-else class="prose max-w-none" v-html="productDetails"></div>
      </div>
      
      <!-- Specifications Tab -->
      <div v-if="activeTab === 'specifications'" class="tab-pane">
        <div v-if="loading.specifications" class="flex justify-center py-10">
          <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-primary-600"></div>
        </div>
        
        <div v-else-if="error.specifications" class="bg-red-50 p-4 rounded-md">
          <p class="text-red-600">{{ error.specifications }}</p>
        </div>
        
        <div v-else-if="!productSpecifications" class="bg-gray-50 p-4 rounded-md">
          <p class="text-gray-600">No specifications available for this product.</p>
        </div>
        
        <div v-else class="prose max-w-none" v-html="productSpecifications"></div>
      </div>
      
      <!-- Reviews Tab -->
      <div v-if="activeTab === 'reviews'" class="tab-pane">
        <div v-if="loading.reviews" class="flex justify-center py-10">
          <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-primary-600"></div>
        </div>
        
        <div v-else-if="error.reviews" class="bg-red-50 p-4 rounded-md">
          <p class="text-red-600">{{ error.reviews }}</p>
        </div>
        
        <div v-else>
          <!-- Reviews Summary -->
          <div class="review-summary bg-gray-50 p-6 rounded-lg mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="review-average">
                <div class="text-4xl font-bold mb-2">
                  {{ ratingSummary.average.toFixed(1) }}
                  <span class="text-base text-gray-500 font-normal">/5</span>
                </div>
                <div class="flex mb-2">
                  <span v-for="i in 5" :key="i" class="text-xl">
                    <span v-if="Math.round(ratingSummary.average) >= i" class="text-yellow-400">★</span>
                    <span v-else class="text-gray-300">★</span>
                  </span>
                </div>
                <div class="text-sm text-gray-500">
                  Based on {{ ratingSummary.total }} reviews
                </div>
              </div>
              
              <div class="rating-distribution">
                <div v-for="i in 5" :key="i" class="flex items-center mb-2">
                  <div class="w-16 text-sm flex items-center">
                    {{ 6 - i }} <span class="text-yellow-400 ml-1">★</span>
                  </div>
                  <div class="w-full bg-gray-200 rounded-full h-2.5 mx-2">
                    <div class="bg-blue-600 h-2.5 rounded-full" :style="{
                      width: ratingSummary.total ? (ratingSummary.distribution[6 - i] / ratingSummary.total * 100) + '%' : '0%'
                    }"></div>
                  </div>
                  <div class="w-12 text-sm text-gray-500">
                    {{ ratingSummary.distribution[6 - i] }}
                  </div>
                </div>
              </div>
            </div>
            
            <div class="mt-6">
              <button 
                @click="showReviewForm = !showReviewForm" 
                class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition"
              >
                Write a Review
              </button>
            </div>
          </div>
          
          <!-- Review Form -->
          <div v-if="showReviewForm" class="review-form bg-gray-50 p-6 rounded-lg mb-8">
            <h3 class="text-xl font-semibold mb-4">Write Your Review</h3>
            
            <div v-if="reviewSubmitSuccess" class="bg-green-50 p-4 mb-4 rounded-md">
              <p class="text-green-600">{{ reviewSubmitMessage }}</p>
            </div>
            
            <form @submit.prevent="submitReview">
              <!-- Rating Selection -->
              <div class="mb-4">
                <label class="block mb-2 font-medium">Your Rating <span class="text-red-500">*</span></label>
                <div class="flex">
                  <template v-for="i in 5" :key="i">
                    <span 
                      @click="newReview.rating = i" 
                      class="text-2xl cursor-pointer"
                      :class="i <= newReview.rating ? 'text-yellow-400' : 'text-gray-300'"
                    >
                      ★
                    </span>
                  </template>
                </div>
                <div v-if="reviewErrors.rating" class="text-red-500 text-sm mt-1">
                  {{ reviewErrors.rating[0] }}
                </div>
              </div>
              
              <!-- Title -->
              <div class="mb-4">
                <label for="review-title" class="block mb-2 font-medium">Review Title</label>
                <input 
                  id="review-title"
                  v-model="newReview.title"
                  type="text"
                  class="w-full rounded-md border-gray-300 shadow-sm"
                  placeholder="Summarize your experience"
                >
                <div v-if="reviewErrors.title" class="text-red-500 text-sm mt-1">
                  {{ reviewErrors.title[0] }}
                </div>
              </div>
              
              <!-- Comment -->
              <div class="mb-4">
                <label for="review-comment" class="block mb-2 font-medium">Your Review <span class="text-red-500">*</span></label>
                <textarea 
                  id="review-comment"
                  v-model="newReview.comment"
                  rows="5"
                  class="w-full rounded-md border-gray-300 shadow-sm"
                  placeholder="Share your experience with this product"
                ></textarea>
                <div v-if="reviewErrors.comment" class="text-red-500 text-sm mt-1">
                  {{ reviewErrors.comment[0] }}
                </div>
              </div>
              
              <!-- Name & Email (For Guest Reviews) -->
              <template v-if="!isLoggedIn">
                <div class="mb-4">
                  <label for="reviewer-name" class="block mb-2 font-medium">Your Name <span class="text-red-500">*</span></label>
                  <input 
                    id="reviewer-name"
                    v-model="newReview.reviewer_name"
                    type="text"
                    class="w-full rounded-md border-gray-300 shadow-sm"
                  >
                  <div v-if="reviewErrors.reviewer_name" class="text-red-500 text-sm mt-1">
                    {{ reviewErrors.reviewer_name[0] }}
                  </div>
                </div>
                
                <div class="mb-4">
                  <label for="reviewer-email" class="block mb-2 font-medium">Your Email <span class="text-red-500">*</span></label>
                  <input 
                    id="reviewer-email"
                    v-model="newReview.reviewer_email"
                    type="email"
                    class="w-full rounded-md border-gray-300 shadow-sm"
                  >
                  <div v-if="reviewErrors.reviewer_email" class="text-red-500 text-sm mt-1">
                    {{ reviewErrors.reviewer_email[0] }}
                  </div>
                </div>
              </template>
              
              <div class="mt-6">
                <button 
                  type="submit" 
                  class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition"
                  :disabled="reviewSubmitting"
                >
                  <span v-if="reviewSubmitting">Submitting...</span>
                  <span v-else>Submit Review</span>
                </button>
                <button 
                  type="button" 
                  @click="showReviewForm = false" 
                  class="ml-4 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-md transition"
                >
                  Cancel
                </button>
              </div>
            </form>
          </div>
          
          <!-- Review List -->
          <div v-if="reviews.length === 0" class="bg-gray-50 p-4 rounded-md mb-4">
            <p class="text-gray-600">There are no reviews yet. Be the first to review this product!</p>
          </div>
          
          <div v-else class="reviews-list">
            <div v-for="(review, index) in reviews" :key="index" class="review mb-6 pb-6 border-b last:border-0">
              <div class="flex items-start">
                <div class="review-content flex-1">
                  <div class="flex items-center mb-1">
                    <div class="flex text-yellow-400">
                      <span v-for="i in 5" :key="i" class="text-lg">
                        <span v-if="review.rating >= i">★</span>
                        <span v-else class="text-gray-300">★</span>
                      </span>
                    </div>
                    <span v-if="review.is_verified_purchase" class="ml-4 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                      Verified Purchase
                    </span>
                  </div>
                  
                  <h4 class="font-semibold text-lg mb-1">{{ review.title || 'Review' }}</h4>
                  
                  <div class="text-sm text-gray-500 mb-2">
                    By {{ review.reviewer_name }} on {{ formatDate(review.created_at) }}
                  </div>
                  
                  <div class="review-text mt-2" v-html="review.comment"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    productId: {
      type: [Number, String],
      required: true
    }
  },
  
  data() {
    return {
      tabs: [
        { id: 'details', label: 'Details' },
        { id: 'specifications', label: 'Specifications' },
        { id: 'reviews', label: 'Reviews' }
      ],
      activeTab: 'details',
      productDetails: null,
      productSpecifications: null,
      reviews: [],
      ratingSummary: {
        average: 0,
        total: 0,
        distribution: {
          5: 0,
          4: 0,
          3: 0,
          2: 0,
          1: 0
        }
      },
      loading: {
        details: false,
        specifications: false,
        reviews: false
      },
      error: {
        details: null,
        specifications: null,
        reviews: null
      },
      showReviewForm: false,
      newReview: {
        rating: 5,
        title: '',
        comment: '',
        reviewer_name: '',
        reviewer_email: ''
      },
      reviewErrors: {},
      reviewSubmitting: false,
      reviewSubmitSuccess: false,
      reviewSubmitMessage: ''
    };
  },
  
  computed: {
    isLoggedIn() {
      // Replace with your authentication logic
      return false; // For now, we assume user is not logged in
    }
  },
  
  watch: {
    productId: {
      immediate: true,
      handler() {
        this.fetchData();
      }
    },
    
    activeTab: {
      handler(newTab) {
        this.fetchDataForTab(newTab);
      }
    }
  },
  
  methods: {
    setActiveTab(tabId) {
      this.activeTab = tabId;
    },
    
    fetchData() {
      this.fetchDataForTab(this.activeTab);
    },
    
    fetchDataForTab(tab) {
      switch (tab) {
        case 'details':
          this.fetchProductDetails();
          break;
        case 'specifications':
          this.fetchProductSpecifications();
          break;
        case 'reviews':
          this.fetchProductReviews();
          break;
      }
    },
    
    async fetchProductDetails() {
      if (this.productDetails !== null) return;
      
      this.loading.details = true;
      this.error.details = null;
      
      try {
        const response = await axios.get(`/api/products/${this.productId}/details`);
        if (response.data.success) {
          this.productDetails = response.data.data.details;
        } else {
          this.error.details = 'Unable to load product details.';
        }
      } catch (error) {
        console.error('Error fetching product details:', error);
        this.error.details = 'An error occurred while loading product details.';
      } finally {
        this.loading.details = false;
      }
    },
    
    async fetchProductSpecifications() {
      if (this.productSpecifications !== null) return;
      
      this.loading.specifications = true;
      this.error.specifications = null;
      
      try {
        const response = await axios.get(`/api/products/${this.productId}/specifications`);
        if (response.data.success) {
          this.productSpecifications = response.data.data.specifications;
        } else {
          this.error.specifications = 'Unable to load product specifications.';
        }
      } catch (error) {
        console.error('Error fetching product specifications:', error);
        this.error.specifications = 'An error occurred while loading product specifications.';
      } finally {
        this.loading.specifications = false;
      }
    },
    
    async fetchProductReviews() {
      this.loading.reviews = true;
      this.error.reviews = null;
      
      try {
        const response = await axios.get(`/api/products/${this.productId}/reviews`);
        if (response.data.success) {
          this.reviews = response.data.data.reviews;
          this.ratingSummary = response.data.data.rating_summary;
        } else {
          this.error.reviews = 'Unable to load product reviews.';
        }
      } catch (error) {
        console.error('Error fetching product reviews:', error);
        this.error.reviews = 'An error occurred while loading product reviews.';
      } finally {
        this.loading.reviews = false;
      }
    },
    
    async submitReview() {
      this.reviewSubmitting = true;
      this.reviewErrors = {};
      this.reviewSubmitSuccess = false;
      
      try {
        const response = await axios.post(`/api/products/${this.productId}/reviews`, this.newReview);
        
        if (response.data.success) {
          this.reviewSubmitSuccess = true;
          this.reviewSubmitMessage = response.data.message;
          
          // Reset the form
          this.newReview = {
            rating: 5,
            title: '',
            comment: '',
            reviewer_name: '',
            reviewer_email: ''
          };
          
          // Close the form after a delay
          setTimeout(() => {
            this.showReviewForm = false;
            this.reviewSubmitSuccess = false;
          }, 3000);
        }
      } catch (error) {
        console.error('Error submitting review:', error);
        
        if (error.response && error.response.data && error.response.data.errors) {
          this.reviewErrors = error.response.data.errors;
        } else {
          this.reviewSubmitSuccess = true;
          this.reviewSubmitMessage = 'An error occurred while submitting your review.';
        }
      } finally {
        this.reviewSubmitting = false;
      }
    },
    
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    }
  }
};
</script>

<style scoped>
.product-tabs {
  /* Add any additional styling here */
}
</style> 