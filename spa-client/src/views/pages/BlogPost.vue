<template>
  <div>
    <Header />
    
    <div class="bg-gray-50 py-12">
      <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
          <!-- Loading State -->
          <div v-if="loading" class="animate-pulse space-y-8">
            <div class="h-8 bg-gray-200 rounded w-3/4 mb-6"></div>
            <div class="h-4 bg-gray-200 rounded w-1/4 mb-10"></div>
            <div v-if="!error" class="bg-white rounded-lg shadow-md p-8">
              <div class="h-64 bg-gray-200 rounded mb-8"></div>
              <div class="space-y-4">
                <div class="h-4 bg-gray-200 rounded w-full"></div>
                <div class="h-4 bg-gray-200 rounded w-full"></div>
                <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                <div class="h-4 bg-gray-200 rounded w-full"></div>
                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
              </div>
            </div>
          </div>
          
          <!-- Error State -->
          <div v-else-if="error" class="bg-red-50 border-l-4 border-red-500 p-6 mb-8">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-lg font-medium text-red-700">{{ error }}</h3>
                <div class="mt-4">
                  <router-link to="/blog" class="inline-flex items-center text-red-700 hover:text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to blog
                  </router-link>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Blog Post Content -->
          <div v-else>
            <!-- Back to Blog Link -->
            <div class="mb-6">
              <router-link to="/blog" class="inline-flex items-center text-blue-600 hover:text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to blog
              </router-link>
            </div>
            
            <!-- Post Header -->
            <div class="mb-8">
              <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">{{ post.title }}</h1>
              <div class="flex items-center text-gray-500 text-sm">
                <span>{{ formatDate(post.published_at) }}</span>
              </div>
            </div>
            
            <!-- Featured Image -->
            <div v-if="post.featured_image" class="mb-8 overflow-hidden rounded-lg shadow-md">
              <img 
                :src="getImageUrl(post.featured_image)" 
                :alt="post.title" 
                class="w-full object-cover max-h-96"
                @error="handleImageError" 
              />
            </div>
            
            <!-- Post Content -->
            <div class="bg-white rounded-lg shadow-md p-8 mb-8">
              <div v-if="post.excerpt" class="text-lg text-gray-600 mb-6 italic">
                {{ post.excerpt }}
              </div>
              
              <div class="prose prose-blue max-w-none" v-html="post.content"></div>
            </div>
            
            <!-- Sharing Buttons (Optional) -->
            <div class="flex items-center justify-between mt-8 border-t pt-6">
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Share this post</h3>
                <div class="flex space-x-4">
                  <a href="#" class="text-gray-400 hover:text-blue-500">
                    <span class="sr-only">Facebook</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                    </svg>
                  </a>
                  <a href="#" class="text-gray-400 hover:text-blue-400">
                    <span class="sr-only">Twitter</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                    </svg>
                  </a>
                  <a href="#" class="text-gray-400 hover:text-green-500">
                    <span class="sr-only">WhatsApp</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path fill-rule="evenodd" d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.43c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.87.159.058.994.468 1.163.549.17.08.283.116.32.178.38.058.038.335-.086.66l-.907-.227z" clip-rule="evenodd" />
                    </svg>
                  </a>
                </div>
              </div>
              
              <div class="flex items-center">
                <a href="#" class="inline-flex items-center text-gray-500 hover:text-gray-700">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                  </svg>
                  <span>Like</span>
                </a>
              </div>
            </div>
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

export default {
  name: 'BlogPost',
  components: {
    Header
  },
  data() {
    return {
      post: {},
      loading: true,
      error: null,
      baseApiUrl: 'http://127.0.0.1:8000/api'
    };
  },
  created() {
    this.fetchBlogPost();
  },
  methods: {
    async fetchBlogPost() {
      this.loading = true;
      this.error = null;
      
      try {
        const slug = this.$route.params.slug;
        if (!slug) {
          this.error = 'Blog post not found';
          this.loading = false;
          return;
        }
        
        const response = await axios.get(`${this.baseApiUrl}/blog/posts/${slug}`);
        
        if (response.data && response.data.success) {
          this.post = response.data.data;
        } else {
          this.error = 'Failed to load blog post';
        }
      } catch (error) {
        console.error('Error fetching blog post:', error);
        this.error = 'Blog post not found. It may have been removed or is no longer available.';
      } finally {
        this.loading = false;
      }
    },
    formatDate(dateString) {
      if (!dateString) return '';
      
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
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
    }
  },
  watch: {
    // Watch for route changes to update the post when navigating between posts
    '$route.params.slug': {
      handler() {
        this.fetchBlogPost();
      }
    }
  },
  metaInfo() {
    return {
      title: this.post.title ? `${this.post.title} | Blog | Aligans` : 'Blog Post | Aligans'
    };
  }
}
</script>

<style>
/* Add styles for rich content rendering */
.prose img {
  border-radius: 0.375rem;
  margin: 2rem auto;
}

.prose h2 {
  margin-top: 2rem;
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
}

.prose h3 {
  margin-top: 1.5rem;
  font-size: 1.25rem;
  font-weight: 600;
  color: #1f2937;
}

.prose p {
  margin-top: 1rem;
  margin-bottom: 1rem;
}

.prose a {
  color: #2563eb;
  text-decoration: underline;
}

.prose ul {
  list-style-type: disc;
  padding-left: 1.5rem;
  margin-top: 1rem;
  margin-bottom: 1rem;
}

.prose ol {
  list-style-type: decimal;
  padding-left: 1.5rem;
  margin-top: 1rem;
  margin-bottom: 1rem;
}

.prose blockquote {
  border-left: 4px solid #d1d5db;
  padding-left: 1rem;
  font-style: italic;
  color: #4b5563;
  margin: 1.5rem 0;
}
</style> 