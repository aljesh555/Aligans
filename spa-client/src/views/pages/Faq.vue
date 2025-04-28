<template>
  <div>
    <Header />
    
    <div class="bg-gray-50 py-12">
      <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
          <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Frequently Asked Questions</h1>
          <p class="text-gray-600 mb-8">Find answers to the most common questions about cricket equipment and our services.</p>
          
          <!-- Loading State -->
          <div v-if="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-600 mb-4"></div>
            <p class="text-gray-600">Loading FAQs...</p>
          </div>
          
          <div v-else>
            <!-- Search Bar -->
            <div class="mb-12">
              <div class="relative">
                <input type="text" v-model="searchQuery" placeholder="Search FAQs..." 
                       class="w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                  </svg>
                </button>
              </div>
            </div>
            
            <!-- FAQ Categories -->
            <div class="flex flex-wrap gap-2 mb-8">
              <button @click="filterCategory('all')" 
                      :class="[activeCategory === 'all' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100', 
                              'px-4 py-2 rounded-full text-sm font-medium transition-colors']">
                All Questions
              </button>
              <button 
                v-for="category in categories" 
                :key="category" 
                @click="filterCategory(category)" 
                :class="[activeCategory === category ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100', 
                        'px-4 py-2 rounded-full text-sm font-medium transition-colors']">
                {{ category }}
              </button>
            </div>
            
            <!-- FAQ Accordion -->
            <div class="space-y-4">
              <div v-for="(faq, index) in filteredFaqs" :key="index" class="bg-white rounded-lg shadow-sm overflow-hidden">
                <button @click="toggleFaq(index)" class="w-full px-6 py-4 text-left flex justify-between items-center focus:outline-none">
                  <span class="font-medium text-gray-800">{{ faq.question }}</span>
                  <svg :class="{ 'transform rotate-180': faq.open }" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <div v-show="faq.open" class="px-6 pb-4">
                  <p class="text-gray-600" v-html="faq.answer"></p>
                </div>
              </div>
            </div>
            
            <!-- No Results -->
            <div v-if="filteredFaqs.length === 0" class="mt-8 text-center p-8 bg-white rounded-lg shadow-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 class="text-lg font-medium text-gray-800 mb-2">No matching questions found</h3>
              <p class="text-gray-600">Try adjusting your search or filter to find what you're looking for.</p>
            </div>
            
            <!-- Contact Section -->
            <div class="mt-12 bg-blue-50 rounded-lg p-6 text-center">
              <h2 class="text-xl font-semibold text-gray-800 mb-2">Still have questions?</h2>
              <p class="text-gray-600 mb-4">Can't find the answer you're looking for? Please contact our friendly team.</p>
              <router-link to="/contact-us" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Contact Us
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <Footer />
  </div>
</template>

<script>
import Header from '@/components/Header.vue';
import Footer from '@/components/layout/Footer.vue';
import axios from 'axios';

export default {
  name: 'Faq',
  components: {
    Header,
    Footer
  },
  data() {
    return {
      searchQuery: '',
      activeCategory: 'all',
      loading: true,
      error: null,
      faqs: [],
      categories: []
    }
  },
  metaInfo: {
    title: 'FAQs | Aligans Sports Shop'
  },
  created() {
    this.fetchFaqs();
    this.fetchCategories();
  },
  computed: {
    filteredFaqs() {
      let filtered = this.faqs;
      
      // Filter by category
      if (this.activeCategory !== 'all') {
        filtered = filtered.filter(faq => faq.category === this.activeCategory);
      }
      
      // Filter by search query
      if (this.searchQuery.trim() !== '') {
        const query = this.searchQuery.toLowerCase();
        filtered = filtered.filter(faq => 
          faq.question.toLowerCase().includes(query) || 
          faq.answer.toLowerCase().includes(query)
        );
      }
      
      return filtered;
    }
  },
  methods: {
    async fetchFaqs() {
      try {
        this.loading = true;
        const response = await axios.get('http://127.0.0.1:8000/api/faqs');
        this.faqs = response.data.map(faq => ({
          ...faq,
          open: false
        }));
      } catch (error) {
        console.error('Error fetching FAQs:', error);
        this.error = 'Failed to load FAQs. Please try again later.';
        // Fallback to default FAQs in case of error
        this.faqs = this.getDefaultFaqs();
      } finally {
        this.loading = false;
      }
    },
    async fetchCategories() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/faqs/categories');
        this.categories = response.data;
      } catch (error) {
        console.error('Error fetching categories:', error);
        // Fallback to default categories
        this.categories = ['products', 'shipping', 'returns', 'payments'];
      }
    },
    toggleFaq(index) {
      // Find the actual FAQ object in the filtered list
      const faqToToggle = this.filteredFaqs[index];
      
      // Find the index of this FAQ in the original array
      const originalIndex = this.faqs.findIndex(faq => faq.id === faqToToggle.id);
      
      // Toggle the open state
      if (originalIndex !== -1) {
        this.faqs[originalIndex].open = !this.faqs[originalIndex].open;
      }
    },
    filterCategory(category) {
      this.activeCategory = category;
    },
    // Fallback method in case API fails
    getDefaultFaqs() {
      return [
        {
          id: 1,
          question: 'How do I choose the right cricket bat?',
          answer: 'Choosing the right cricket bat depends on several factors: <br><br><strong>1. Weight</strong> - Cricket bats typically weigh between 2lb 7oz and 3lb. Beginners and younger players should start with lighter bats.<br><strong>2. Size</strong> - Bats come in sizes 0-6 for juniors and Short Handle (SH), Long Blade (LB), and Long Handle (LH) for adults.<br><strong>3. Wood Grade</strong> - English willow bats are graded 1-5, with Grade 1 being the highest quality.<br><strong>4. Playing Style</strong> - Front-foot players often prefer lighter bats, while back-foot players may prefer heavier bats with a higher sweet spot.<br><br>We recommend visiting our store for a professional fitting.',
          category: 'products',
          order: 1,
          is_active: true,
          open: false
        },
        {
          id: 2,
          question: 'What type of cricket shoes should I get?',
          answer: 'Cricket shoes are designed for specific playing surfaces and roles:<br><br><strong>1. Spike Shoes</strong> - Metal spikes provide optimal grip on grass surfaces. Ideal for fast bowlers who need traction during their run-up and delivery.<br><strong>2. Half-Spike Shoes</strong> - Feature metal spikes at the front and rubber studs at the heel. Great for all-rounders.<br><strong>3. Rubber Studs</strong> - Suitable for artificial surfaces and indoor practice. These are versatile and can be used on multiple surfaces.<br><br>Consider your primary role (batsman, bowler, all-rounder) and the surfaces you\'ll be playing on most frequently when selecting cricket shoes.',
          category: 'products',
          order: 2,
          is_active: true,
          open: false
        },
        {
          id: 3,
          question: 'What is your return policy?',
          answer: 'We accept returns within 15 days of purchase under the following conditions:<br><br>- The product must be unused, unworn, and in its original packaging<br>- Original receipt or proof of purchase is required<br>- Custom-made items (like personalized bats or jerseys) are not eligible for return<br>- Sale items may only be eligible for exchange, not refund<br><br>To initiate a return, please contact our customer service team or visit our store with your purchase.',
          category: 'returns',
          order: 1,
          is_active: true,
          open: false
        }
      ];
    }
  }
}
</script> 