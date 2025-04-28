<template>
  <div>
    <Header />
    
    <div class="bg-gray-50 py-12">
      <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
          <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Cricket Blog</h1>
          <p class="text-gray-600 mb-10">Stay updated with the latest cricket equipment tips and reviews</p>
          
          <!-- Loading State -->
          <div v-if="loading" class="animate-pulse space-y-8">
            <div v-for="i in 5" :key="i" class="bg-white rounded-lg shadow-sm overflow-hidden">
              <div class="p-6">
                <div class="h-4 bg-gray-200 rounded w-1/4 mb-3"></div>
                <div class="h-6 bg-gray-200 rounded w-3/4 mb-3"></div>
                <div class="h-4 bg-gray-200 rounded w-full mb-4"></div>
                <div class="h-4 bg-gray-200 rounded w-2/3 mb-4"></div>
                <div class="h-4 bg-gray-200 rounded w-1/4"></div>
              </div>
            </div>
          </div>
          
          <!-- Error State -->
          <div v-else-if="error" class="bg-red-50 border-l-4 border-red-500 p-4 mb-8">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-red-700">
                  {{ error }}
                </p>
                <button @click="fetchBlogPosts" class="mt-2 text-sm font-medium text-red-700 hover:text-red-600">
                  Try again
                </button>
              </div>
            </div>
          </div>
          
          <!-- No Posts State -->
          <div v-else-if="!blogPosts.length" class="bg-white rounded-lg shadow-md p-8 text-center mx-auto max-w-md">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No blog posts found</h3>
            <p class="mt-1 text-gray-500">Check back later for new content.</p>
          </div>
          
          <!-- Blog Posts -->
          <div v-else>
            <!-- Blog Items Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
              <!-- Blog Item Card -->
              <div 
                v-for="post in blogPosts" 
                :key="post.id" 
                class="bg-white rounded-lg shadow-sm overflow-hidden flex flex-col h-full"
              >
                <!-- Image Section -->
                <div v-if="post.featured_image" class="aspect-w-16 aspect-h-9 bg-gray-100">
                  <img 
                    :src="getImageUrl(post.featured_image)" 
                    :alt="post.title"
                    class="object-cover w-full h-48" 
                    @error="handleImageError"
                  />
                </div>
                
                <!-- Content Section -->
                <div class="p-4 flex-grow flex flex-col">
                  <div class="text-gray-500 text-xs mb-2">
                    {{ formatDate(post.published_at) }}
                  </div>
                  <h2 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">{{ post.title }}</h2>
                  <p v-if="post.excerpt" class="text-gray-600 text-sm mb-4 flex-grow line-clamp-3">{{ post.excerpt }}</p>
                  <router-link 
                    :to="'/blog/' + post.slug" 
                    class="inline-flex items-center text-blue-600 text-sm font-medium hover:text-blue-700 mt-auto"
                  >
                    Read more
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </router-link>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Pagination -->
          <div v-if="pagination && pagination.last_page > 1" class="mt-10 flex justify-center">
            <nav class="flex items-center space-x-1">
              <!-- Previous Page -->
              <button 
                type="button" 
                class="px-3 py-2 rounded-md text-gray-500 hover:bg-gray-100"
                :disabled="pagination.current_page === 1"
                :class="{'opacity-50 cursor-not-allowed': pagination.current_page === 1}"
                @click="changePage(pagination.current_page - 1)"
              >
                <span class="sr-only">Previous</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
              </button>
              
              <!-- Page Numbers -->
              <button 
                v-for="page in getPageNumbers()" 
                :key="page"
                type="button" 
                class="px-4 py-2 rounded-md"
                :class="{
                  'bg-blue-600 text-white font-medium': page === pagination.current_page,
                  'text-gray-500 hover:bg-gray-100 hover:text-gray-800': page !== pagination.current_page
                }"
                @click="changePage(page)"
              >
                {{ page }}
              </button>
              
              <!-- Next Page -->
              <button 
                type="button" 
                class="px-3 py-2 rounded-md text-gray-500 hover:bg-gray-100"
                :disabled="pagination.current_page === pagination.last_page"
                :class="{'opacity-50 cursor-not-allowed': pagination.current_page === pagination.last_page}"
                @click="changePage(pagination.current_page + 1)"
              >
                <span class="sr-only">Next</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>
    
    <Footer />
  </div>
</template>

<script>
import axios from 'axios';
import Header from '@/components/Header.vue';
import Footer from '@/components/layout/Footer.vue';

export default {
  name: 'Blog',
  components: {
    Header,
    Footer
  },
  data() {
    return {
      blogPosts: [],
      loading: true,
      error: null,
      pagination: null,
      baseApiUrl: 'http://127.0.0.1:8000/api'
    };
  },
  created() {
    this.fetchBlogPosts();
  },
  methods: {
    async fetchBlogPosts(page = 1) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await axios.get(`${this.baseApiUrl}/blog/posts`, {
          params: {
            page,
            per_page: 12
          }
        });
        
        if (response.data && response.data.success) {
          const data = response.data.data;
          this.blogPosts = data.data; // Laravel pagination data is nested in a 'data' property
          
          // Set pagination data
          this.pagination = {
            current_page: data.current_page,
            from: data.from,
            last_page: data.last_page,
            per_page: data.per_page,
            to: data.to,
            total: data.total
          };
        } else {
          this.error = 'Failed to load blog posts';
        }
      } catch (error) {
        console.error('Error fetching blog posts:', error);
        this.error = 'An error occurred while loading blog posts. Please try again.';
      } finally {
        this.loading = false;
      }
    },
    formatDate(dateString) {
      if (!dateString) return '';
      
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      });
    },
    getImageUrl(path) {
      if (!path) return '/images/fallback-image.jpg';
      
      // Check if it's a full URL
      if (path.startsWith('http://') || path.startsWith('https://')) {
        return path;
      }
      
      // Simplest direct approach - directly point to Laravel storage URL
      return `http://127.0.0.1:8000/storage/${path}`;
    },
    handleImageError(event) {
      // Set a fallback image if the image fails to load
      console.log('Image failed to load, setting fallback');
      event.target.src = '/images/fallback-image.jpg';
      event.target.classList.add('opacity-60');
    },
    changePage(page) {
      if (page < 1 || page > this.pagination.last_page) return;
      
      // Update URL with page parameter
      this.$router.push({ 
        query: { 
          ...this.$route.query, 
          page 
        }
      });
      
      // Fetch posts for the selected page
      this.fetchBlogPosts(page);
      
      // Scroll to top of posts
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    getPageNumbers() {
      if (!this.pagination) return [];
      
      const current = this.pagination.current_page;
      const last = this.pagination.last_page;
      const delta = 2; // Number of pages to show on each side of current page
      
      const range = [];
      
      // Always include first, last, and pages around current
      for (let i = Math.max(1, current - delta); i <= Math.min(last, current + delta); i++) {
        range.push(i);
      }
      
      // Handle edge cases for smaller page counts
      if (range.length < 5) {
        if (range[0] > 1) {
          while (range.length < 5 && range[0] > 1) {
            range.unshift(range[0] - 1);
          }
        } else if (range[range.length - 1] < last) {
          while (range.length < 5 && range[range.length - 1] < last) {
            range.push(range[range.length - 1] + 1);
          }
        }
      }
      
      return range;
    }
  },
  watch: {
    // Watch for URL changes and update page accordingly
    '$route.query.page': {
      immediate: true,
      handler(page) {
        if (page && !isNaN(page)) {
          this.fetchBlogPosts(Number(page));
        }
      }
    }
  },
  metaInfo() {
    return {
      title: 'Cricket Blog | Aligans'
    };
  }
}
</script>

<style>
/* Line clamp utilities for truncating text */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Card hover effect */
.bg-white {
  transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.bg-white:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
</style> 