<template>
  <footer class="bg-gradient-to-b from-gray-900 to-slate-950 text-gray-300 relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden opacity-30">
      <div class="absolute top-0 -left-28 w-64 h-64 bg-blue-700 rounded-full filter blur-3xl"></div>
      <div class="absolute right-0 top-64 w-80 h-80 bg-indigo-700 rounded-full filter blur-3xl"></div>
      <div class="absolute bottom-24 left-1/2 transform -translate-x-1/2 w-full h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent opacity-30"></div>
    </div>
    
    <!-- Top Wave SVG -->
    <div class="absolute top-0 left-0 w-full overflow-hidden">
      <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
           viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
        <defs>
          <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
        </defs>
        <g class="parallax">
          <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(96, 165, 250, 0.03)" />
          <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(79, 70, 229, 0.04)" />
          <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(30, 58, 138, 0.02)" />
          <use xlink:href="#gentle-wave" x="48" y="7" fill="rgba(17, 24, 39, 0.02)" />
        </g>
      </svg>
    </div>
    
    <!-- Main Footer Content -->
    <div class="container mx-auto px-4 py-16 relative z-10">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 footer-content-wrapper">
        <!-- Company Info -->
        <div class="lg:col-span-2 company-info-wrapper">
          <div class="flex items-center gap-2 mb-8 transform transition-all hover:translate-x-1 duration-300">
            <!-- Dynamic Logo from API -->
            <div v-if="logoData && logoData.image_url" class="flex items-center">
              <img 
                :src="logoData.image_url" 
                alt="Aligans Logo" 
                class="h-12 w-auto footer-logo-animation" 
                @error="handleLogoError"
              />
            </div>
            <!-- Fallback text logo - only show if loading is complete and no logo found -->
            <div v-else-if="!logoLoading && !logoData" class="logo-fallback">
              <span class="flex items-center justify-center h-12 w-12 bg-gradient-to-br from-blue-600 to-indigo-600 text-white text-xl font-bold rounded-md shadow-blue-glow">A</span>
            </div>
            <span class="text-2xl font-bold text-white">Aligans</span>
          </div>
          
          <!-- Display footer message from database -->
          <div class="mb-8 footer-text-wrapper">
            <!-- Loading state -->
            <div v-if="messageLoading" class="text-gray-400 max-w-md">
              <div class="h-4 bg-gray-700 rounded animate-pulse w-3/4 mb-2"></div>
              <div class="h-4 bg-gray-700 rounded animate-pulse w-1/2"></div>
            </div>
            
            <!-- Message when loaded -->
            <p v-else-if="footerMessage" class="text-gray-400 max-w-md whitespace-pre-line footer-message">
              {{ footerMessage }}
            </p>
          </div>

          <!-- Dynamic Social Media Links -->
          <div class="flex flex-wrap space-x-4 mb-8 social-wrapper">
            <!-- Loading state for social links -->
            <div v-if="socialMediaLoading" class="flex space-x-3">
              <div v-for="i in 3" :key="i" class="h-10 w-10 bg-gray-700 rounded-full animate-pulse"></div>
            </div>
            
            <!-- Dynamic social links -->
            <template v-else>
              <!-- Facebook -->
              <a v-if="socialLinks.facebook" :href="socialLinks.facebook" target="_blank" rel="noopener noreferrer" 
                 class="social-icon-wrapper facebook" aria-label="Facebook">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                </svg>
              </a>
              
              <!-- Instagram -->
              <a v-if="socialLinks.instagram" :href="socialLinks.instagram" target="_blank" rel="noopener noreferrer" 
                 class="social-icon-wrapper instagram" aria-label="Instagram">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 0 1 1.772 1.153 4.902 4.902 0 0 1 1.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 0 1-1.153 1.772 4.902 4.902 0 0 1-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 0 1-1.772-1.153 4.902 4.902 0 0 1-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 0 1 1.153-1.772A4.902 4.902 0 0 1 5.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 0 0-.748-1.15 3.098 3.098 0 0 0-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 1 1 0 10.27 5.135 5.135 0 0 1 0-10.27zm0 1.802a3.333 3.333 0 1 0 0 6.666 3.333 3.333 0 0 0 0-6.666zm5.338-3.205a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4z" clip-rule="evenodd"></path>
                </svg>
              </a>
              
              <!-- Twitter -->
              <a v-if="socialLinks.twitter" :href="socialLinks.twitter" target="_blank" rel="noopener noreferrer" 
                 class="social-icon-wrapper twitter" aria-label="Twitter">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0 0 22 5.92a8.19 8.19 0 0 1-2.357.646 4.118 4.118 0 0 0 1.804-2.27 8.224 8.224 0 0 1-2.605.996 4.107 4.107 0 0 0-6.993 3.743 11.65 11.65 0 0 1-8.457-4.287 4.106 4.106 0 0 0 1.27 5.477A4.072 4.072 0 0 1 2.8 9.713v.052a4.105 4.105 0 0 0 3.292 4.022 4.095 4.095 0 0 1-1.853.07 4.108 4.108 0 0 0 3.834 2.85A8.233 8.233 0 0 1 2 18.407a11.616 11.616 0 0 0 6.29 1.84"></path>
                </svg>
              </a>
              
              <!-- TikTok -->
              <a v-if="socialLinks.tiktok" :href="socialLinks.tiktok" target="_blank" rel="noopener noreferrer" 
                 class="social-icon-wrapper tiktok" aria-label="TikTok">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                </svg>
              </a>
              
              <!-- LinkedIn -->
              <a v-if="socialLinks.linkedin" :href="socialLinks.linkedin" target="_blank" rel="noopener noreferrer" 
                 class="social-icon-wrapper linkedin" aria-label="LinkedIn">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"></path>
                </svg>
              </a>
              
              <!-- YouTube -->
              <a v-if="socialLinks.youtube" :href="socialLinks.youtube" target="_blank" rel="noopener noreferrer" 
                 class="social-icon-wrapper youtube" aria-label="YouTube">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
              </a>
            </template>
          </div>
        </div>
        
        <!-- Quick Links -->
        <div class="footer-column-wrapper">
          <h3 class="text-white font-semibold text-lg mb-6 relative footer-heading">GET TO KNOW US</h3>
          <ul class="space-y-4 footer-links-list">
            <li><router-link to="/about" class="footer-link">About Us</router-link></li>
            <li><router-link to="/contact-us" class="footer-link">Contact Us</router-link></li>
            <li><router-link to="/blog" class="footer-link">Blog</router-link></li>
            <li><router-link to="/faq" class="footer-link">FAQs</router-link></li>
          </ul>
        </div>
        
        <!-- Shop -->
        <div class="footer-column-wrapper">
          <h3 class="text-white font-semibold text-lg mb-6 relative footer-heading">Quick Shop</h3>
          <ul class="space-y-4 footer-links-list">
            <!-- Loading state -->
            <li v-if="categoriesLoading" v-for="i in 4" :key="i" class="h-5 bg-gray-700 rounded animate-pulse w-3/4 mb-3"></li>
            
            <!-- Show All Products link always -->
            <li v-else><router-link to="/products" class="footer-link">All Products</router-link></li>
            
            <!-- Show top categories dynamically -->
            <li v-for="category in topCategories" :key="category.id">
              <router-link :to="'/category/' + category.id" class="footer-link">
                {{ category.name }}
              </router-link>
            </li>
          </ul>
        </div>
        
        <!-- Customer Care Info -->
        <div class="footer-column-wrapper">
          <h3 class="text-white font-semibold text-lg mb-6 relative footer-heading">CUSTOMER CARE</h3>
          <ul class="space-y-4 footer-links-list">
            <!-- Loading state -->
            <li v-if="customerCareLoading" v-for="i in 3" :key="i" class="h-5 bg-gray-700 rounded animate-pulse w-3/4 mb-3"></li>
            
            <!-- Debug info - remove in production -->
            <li v-else-if="!customerCare" class="text-red-400">
              No customer care data available
            </li>
            
            <!-- Data loaded successfully -->
            <template v-else>
              <!-- Working Hours - Day and Time combined -->
              <li v-if="customerCare.timings" class="flex items-center gap-3 footer-contact-item">
                <div class="contact-icon-wrapper">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <span>{{ customerCare.timings }}</span>
              </li>
              
              <!-- Phone -->
              <li v-if="customerCare.phone" class="flex items-center gap-3 footer-contact-item">
                <div class="contact-icon-wrapper">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                </div>
                <a :href="'tel:' + customerCare.phone" class="footer-contact-link">
                  {{ customerCare.phone }}
                </a>
              </li>
              
              <!-- Email -->
              <li v-if="customerCare.email" class="flex items-center gap-3 footer-contact-item">
                <div class="contact-icon-wrapper">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <a :href="'mailto:' + customerCare.email" class="footer-contact-link">
                  {{ customerCare.email }}
                </a>
              </li>
              
              <!-- Address (if available) -->
              <li v-if="customerCare.address" class="flex items-start gap-3 footer-contact-item">
                <div class="contact-icon-wrapper mt-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <span>{{ customerCare.address }}</span>
              </li>
            </template>
          </ul>
        </div>
      </div>
    </div>
    
    <!-- Payment Methods -->
    <div class="border-t border-gray-800 payment-methods-wrapper">
      <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:justify-between items-center gap-6">
          <div class="flex items-center gap-3 payment-methods">
            <span class="text-sm">We Accept:</span>
            <div class="flex gap-3 payment-icons">
              <!-- eSewa -->
              <div class="payment-method-icon bg-[#60BB46]">
                <span class="text-white font-bold text-sm">eSewa</span>
              </div>
              
              <!-- Khalti -->
              <div class="payment-method-icon bg-[#5C2D91]">
                <span class="text-white font-bold text-sm">Khalti</span>
              </div>
              
              <!-- Fonepay -->
              <div class="payment-method-icon bg-[#5F259F] px-2">
                <span class="text-white font-bold text-sm">Fonepay</span>
              </div>
            </div>
          </div>
          
          <div class="flex flex-col md:flex-row items-center gap-4 footer-policy-links">
            <router-link to="/terms" class="footer-policy-link">Terms &amp; Conditions</router-link>
            <span class="hidden md:inline text-sm text-gray-600">|</span>
            <router-link to="/privacy" class="footer-policy-link">Privacy Policy</router-link>
            <span class="hidden md:inline text-sm text-gray-600">|</span>
            <router-link to="/shipping" class="footer-policy-link">Shipping Policy</router-link>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Copyright -->
    <div class="bg-gray-950 py-6 copyright-wrapper relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-r from-blue-900/5 via-indigo-900/5 to-blue-900/5"></div>
      <div class="container mx-auto px-4 text-center relative z-10">
        <p class="text-gray-500 text-sm copyright-text">{{ footerCopyright || copyright }}</p>
      </div>
    </div>
  </footer>
</template>

<script>
import axios from 'axios';
import { API_URL } from '@/services/config';

export default {
  name: 'Footer',
  data() {
    return {
      socialMediaLinks: [],
      socialLinks: {
        facebook: '',
        instagram: '',
        twitter: '',
        linkedin: '',
        youtube: '',
        tiktok: ''
      },
      socialMediaLoading: true,
      logoData: null,
      logoLoading: true,
      footerMessage: '',
      messageLoading: true,
      footerCopyright: '',
      copyright: `© ${new Date().getFullYear()} Aligans. All rights reserved.`,
      customerCare: null,
      customerCareLoading: true,
      categoriesLoading: true,
      topCategories: []
    };
  },
  async created() {
    await Promise.all([
      this.fetchSocialMediaLinks(),
      this.fetchLogo(),
      this.fetchFooterMessage(),
      this.fetchCustomerCare(),
      this.fetchTopCategories()
    ]);
  },
  methods: {
    async fetchSocialMediaLinks() {
      this.socialMediaLoading = true;
      
      try {
        // Use the social-links API endpoint to fetch data
        const response = await axios.get(`${API_URL}/settings/social-links`);
        console.log('Social media response:', response.data);
        
        if (response.data && response.data.success && response.data.data) {
          // The API returns data in format { data: { social_links: [...] } }
          if (response.data.data.social_links) {
            let socialLinksData = response.data.data.social_links;
            
            // Handle repeater format as array of key-value pairs
            // [{"key":"Facebook","value":"facebook.com"}, {"key":"Instagram","value":"https://www.instagram.com/"}, ...]
            if (Array.isArray(socialLinksData)) {
              // Map the repeater format to our expected object structure
              const mappedData = {};
              
              socialLinksData.forEach(item => {
                if (item.key.toLowerCase() === 'facebook') mappedData.facebook = this.ensureHttpPrefix(item.value);
                if (item.key.toLowerCase() === 'instagram') mappedData.instagram = this.ensureHttpPrefix(item.value);
                if (item.key.toLowerCase() === 'twitter') mappedData.twitter = this.ensureHttpPrefix(item.value);
                if (item.key.toLowerCase() === 'tiktok') mappedData.tiktok = this.ensureHttpPrefix(item.value);
                if (item.key.toLowerCase() === 'yutube' || item.key.toLowerCase() === 'youtube') mappedData.youtube = this.ensureHttpPrefix(item.value);
                if (item.key.toLowerCase() === 'linkedin') mappedData.linkedin = this.ensureHttpPrefix(item.value);
              });
              
              this.socialLinks = mappedData;
              console.log('Social links set from repeater format:', this.socialLinks);
            } 
            // If it's already in the expected object format, just use it
            else if (typeof socialLinksData === 'object' && !Array.isArray(socialLinksData)) {
              // Ensure all URLs have http/https prefix
              const mappedData = {};
              Object.keys(socialLinksData).forEach(key => {
                mappedData[key] = this.ensureHttpPrefix(socialLinksData[key]);
              });
              this.socialLinks = mappedData;
              console.log('Social links set from object format:', this.socialLinks);
            }
            // If it's a JSON string, parse it
            else if (typeof socialLinksData === 'string') {
              try {
                const parsed = JSON.parse(socialLinksData);
                
                // Check if parsed result is in repeater format
                if (Array.isArray(parsed)) {
                  const mappedData = {};
                  
                  parsed.forEach(item => {
                    if (item.key.toLowerCase() === 'facebook') mappedData.facebook = this.ensureHttpPrefix(item.value);
                    if (item.key.toLowerCase() === 'instagram') mappedData.instagram = this.ensureHttpPrefix(item.value);
                    if (item.key.toLowerCase() === 'twitter') mappedData.twitter = this.ensureHttpPrefix(item.value);
                    if (item.key.toLowerCase() === 'tiktok') mappedData.tiktok = this.ensureHttpPrefix(item.value);
                    if (item.key.toLowerCase() === 'yutube' || item.key.toLowerCase() === 'youtube') mappedData.youtube = this.ensureHttpPrefix(item.value);
                    if (item.key.toLowerCase() === 'linkedin') mappedData.linkedin = this.ensureHttpPrefix(item.value);
                  });
                  
                  this.socialLinks = mappedData;
                } else {
                  // Ensure all URLs have http/https prefix
                  const mappedData = {};
                  Object.keys(parsed).forEach(key => {
                    mappedData[key] = this.ensureHttpPrefix(parsed[key]);
                  });
                  this.socialLinks = mappedData;
                }
              } catch (e) {
                console.error('Error parsing social links data:', e);
                this.setDefaultSocialLinks();
              }
            }
          } else {
            console.log('No social_links object in the API response');
            this.setDefaultSocialLinks();
          }
        } else {
          // Try the old format for backward compatibility
          if (response.data && response.data.value) {
            try {
              // Parse the JSON value
              let socialData;
              
              // Handle the case where the data is already an object
              if (typeof response.data.value === 'object') {
                socialData = response.data.value;
              } 
              // Handle the case where it's a JSON string
              else if (typeof response.data.value === 'string') {
                socialData = JSON.parse(response.data.value);
              }
              
              // Update social links with fetched data
              if (socialData) {
                const mappedData = {};
                Object.keys(socialData).forEach(key => {
                  mappedData[key] = this.ensureHttpPrefix(socialData[key]);
                });
                this.socialLinks = mappedData;
              }
              
              console.log('Social links set from old format:', this.socialLinks);
            } catch (e) {
              console.error('Error parsing social links:', e);
              this.setDefaultSocialLinks();
            }
          } else {
            console.log('Invalid API response or missing data');
            this.setDefaultSocialLinks();
          }
        }
      } catch (error) {
        console.error('Error fetching social media links:', error);
        this.setDefaultSocialLinks();
      } finally {
        this.socialMediaLoading = false;
      }
    },
    
    // Helper method to ensure URLs have http/https prefix
    ensureHttpPrefix(url) {
      if (!url) return '';
      
      if (url.startsWith('http://') || url.startsWith('https://')) {
        return url;
      }
      
      return 'https://' + url;
    },
    
    // Set default social links
    setDefaultSocialLinks() {
      this.socialLinks = {
        facebook: 'https://www.facebook.com/profile.php?id=61559404987181',
        instagram: 'https://www.instagram.com/aliganscricket/',
        twitter: 'https://x.com',
        youtube: 'https://www.youtube.com/',
        tiktok: 'https://www.tiktok.com/explore',
        linkedin: ''
      };
    },
    
    // Method to fetch the logo
    async fetchLogo() {
      this.logoLoading = true;
      try {
        // Use the settings/logo endpoint instead of logo/active
        const response = await axios.get(`${API_URL}/settings/logo`);
        if (response.data) {
          this.logoData = response.data;
          console.log('Logo data loaded from settings:', this.logoData);
        } else {
          this.logoData = null;
        }
      } catch (error) {
        console.error('Error fetching logo from settings:', error);
        this.logoData = null; // Reset logo data on error
      } finally {
        this.logoLoading = false;
      }
    },
    
    // Method to fetch the footer message from settings
    async fetchFooterMessage() {
      this.messageLoading = true;
      
      try {
        // First try our direct endpoint that always returns the correct value
        const directResponse = await axios.get(`${API_URL}/footer-about-text`);
        console.log('Direct API response:', directResponse.data);
        
        if (directResponse.data && directResponse.data.success) {
          this.footerMessage = directResponse.data.data;
          console.log('Footer message set from direct endpoint:', this.footerMessage);
          this.messageLoading = false;
          return;
        }
      } catch (directError) {
        console.error('Error with direct endpoint:', directError);
        // Continue to next approach if this fails
      }
      
      try {
        // Try getting by key
        const response = await axios.get(`${API_URL}/settings/by-key/footer_about_text`);
        console.log('Footer text API response:', response.data);
        
        if (response.data && response.data.success) {
          // Get the data field
          let value = response.data.data;
          
          // If it's a string that looks like another JSON string, parse it again
          if (typeof value === 'string' && value.startsWith('"') && value.endsWith('"')) {
            try {
              value = JSON.parse(value);
            } catch (e) {
              // If parsing fails, strip the quotes manually
              value = value.substring(1, value.length - 1);
            }
          }
          
          this.footerMessage = value;
          console.log('Footer message set from by-key endpoint:', this.footerMessage);
        }
      } catch (error) {
        console.error('Error fetching by key:', error);
        
        // If all else fails, use the hardcoded value
        this.footerMessage = "This is Aljesh Raut";
        console.log('Using hardcoded fallback value:', this.footerMessage);
      } finally {
        this.messageLoading = false;
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
      
      // Use the API_URL for constructing storage URLs
      const baseUrl = API_URL.split('/api')[0]; // Get the base URL without '/api'
      
      // Check if it's a storage URL
      if (logo.image_path.includes('storage/')) {
        return `${baseUrl}/${logo.image_path.startsWith('/') ? logo.image_path.substring(1) : logo.image_path}?t=${timestamp}`;
      }
      
      // Default: assume it's a storage path
      return `${baseUrl}/storage/${logo.image_path.startsWith('/') ? logo.image_path.substring(1) : logo.image_path}?t=${timestamp}`;
    },
    
    // Handle logo loading errors
    handleLogoError(event) {
      console.error('Error loading logo image:', event);
      event.target.style.display = 'none';
    },
    
    // Method to fetch customer care data from settings
    async fetchCustomerCare() {
      console.log('Fetching customer care data from API');
      this.customerCareLoading = true;
      
      try {
        // Use the customer-care API endpoint to fetch data
        const response = await axios.get(`${API_URL}/settings/customer-care`);
        console.log('Customer care API response:', response.data);
        
        // Debug the exact structure received
        if (response.data && response.data.data && response.data.data.customer_care) {
          console.log('Raw customer_care data:', JSON.stringify(response.data.data.customer_care));
        }
        
        if (response.data && response.data.success && response.data.data) {
          // The API returns data in format { data: { customer_care: [...] } }
          if (response.data.data.customer_care) {
            let customerCareData = response.data.data.customer_care;
            
            // Initialize an empty customer care object
            const mappedData = {
              timings: '',
              phone: '',
              email: '',
              address: ''
            };
            
            // Variables to store day and time separately
            let day = '';
            let time = '';
            
            // Parse if string (important step)
            if (typeof customerCareData === 'string') {
              try {
                console.log('Parsing customer_care string data');
                customerCareData = JSON.parse(customerCareData);
                console.log('Parsed successfully:', customerCareData);
              } catch (e) {
                console.error('Failed to parse customer_care JSON string:', e);
              }
            }
            
            // Process the data based on its format
            if (Array.isArray(customerCareData)) {
              console.log('Processing array format with', customerCareData.length, 'items');
              
              // Process repeater format (array of key-value pairs)
              for (const item of customerCareData) {
                console.log('Processing item:', item);
                
                // Extract key and value using appropriate properties
                // Support both uppercase and lowercase property names
                const key = (item.Key || item.key || '').toLowerCase();
                const value = item.Value || item.value || '';
                
                console.log(`Found key: "${key}", value: "${value}"`);
                
                // Map keys based on lowercase name
                switch (key) {
                  case 'day':
                    day = value;
                    console.log('Set day to', day);
                    break;
                  case 'time':
                    time = value;
                    console.log('Set time to', time);
                    break;
                  case 'contact':
                    mappedData.phone = value;
                    console.log('Set phone to', value);
                    break;
                  case 'email':
                    mappedData.email = value;
                    console.log('Set email to', value);
                    break;
                  case 'address':
                    mappedData.address = value;
                    console.log('Set address to', value);
                    break;
                }
              }
              
              // Combine day and time if both are available
              if (day && time) {
                mappedData.timings = `${day}: ${time}`;
              } else if (day) {
                mappedData.timings = day;
              } else if (time) {
                mappedData.timings = time;
              }
              
              console.log('Final mapped data:', mappedData);
                  this.customerCare = mappedData;
            } 
            // Handle object format directly
            else if (typeof customerCareData === 'object') {
              this.customerCare = {
                timings: customerCareData.timings || '',
                phone: customerCareData.phone || customerCareData.contact || '',
                email: customerCareData.email || '',
                address: customerCareData.address || ''
              };
            }
            
            console.log('Final customer care data:', this.customerCare);
          } else {
            console.log('No customer_care object in the API response');
            this.setDefaultCustomerCare();
          }
        } else {
          console.log('Invalid API response or missing data');
          this.setDefaultCustomerCare();
        }
      } catch (error) {
        console.error('Error fetching customer care data:', error);
        this.setDefaultCustomerCare();
      } finally {
        this.customerCareLoading = false;
      }
    },
    
    // Set default customer care data as fallback
    setDefaultCustomerCare() {
      this.customerCare = {
        timings: '10AM to 10PM',
        email: 'care@yourstore.com',
        address: 'santingar,Baneshwor',
        phone: '+977-1-4444444'
      };
    },

    async fetchTopCategories() {
      this.categoriesLoading = true;
      
      try {
        // Use the categories API endpoint to fetch data
        const response = await axios.get(`${API_URL}/categories`);
        console.log('Categories response:', response.data);
        
        if (response.data && response.data.data) {
          // Filter top-level categories (parent_id is null or 0)
          this.topCategories = response.data.data
            .filter(cat => !cat.parent_id)
            .slice(0, 5); // Limit to 5 categories for the footer
          
          console.log('Top categories loaded:', this.topCategories);
        } else {
          console.log('No categories data found');
          this.topCategories = [];
        }
      } catch (error) {
        console.error('Error fetching categories:', error);
        this.topCategories = [];
      } finally {
        this.categoriesLoading = false;
      }
    }
  }
}
</script>

<style scoped>
/* Footer Logo Animation */
.footer-logo-animation {
  filter: drop-shadow(0 0 8px rgba(96, 165, 250, 0.3));
  transition: all 0.3s ease;
}

.logo-fallback {
  transition: all 0.3s ease;
}

/* Shadow Blue Glow */
.shadow-blue-glow {
  box-shadow: 0 0 15px rgba(96, 165, 250, 0.4);
}

/* Footer Content Layout Animation */
.footer-content-wrapper {
  opacity: 0;
  animation: fadeInUp 0.8s ease forwards 0.2s;
}

.footer-message {
  position: relative;
  line-height: 1.7;
}

/* Footer Column Styles Animation */
.footer-column-wrapper {
  opacity: 0;
  animation: fadeInRight 0.6s ease forwards;
  animation-delay: calc(var(--index, 0) * 0.1s + 0.4s);
}

.footer-column-wrapper:nth-child(3) {
  --index: 1;
}

.footer-column-wrapper:nth-child(4) {
  --index: 2;
}

.footer-column-wrapper:nth-child(5) {
  --index: 3;
}

/* Heading Animation */
.footer-heading {
  position: relative;
  display: inline-block;
}

.footer-heading::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 0;
  width: 40px;
  height: 2px;
  background: linear-gradient(90deg, #60a5fa, rgba(96, 165, 250, 0.3));
  transition: width 0.3s ease;
}

.footer-heading:hover::after {
  width: 60px;
}

/* Footer Links */
.footer-link {
  display: inline-block;
  color: #9ca3af;
  position: relative;
  transition: all 0.3s ease;
  padding-left: 0;
}

.footer-link::before {
  content: '';
  position: absolute;
  left: 0;
  top: 50%;
  width: 0;
  height: 1px;
  background-color: #60a5fa;
  transform: translateY(-50%);
  opacity: 0;
  transition: all 0.3s ease;
}

.footer-link:hover {
  color: white;
  padding-left: 16px;
}

.footer-link:hover::before {
  width: 10px;
  opacity: 1;
}

/* Social Icons */
.social-wrapper {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
}

.social-icon-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.08);
  color: #9ca3af;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  z-index: 1;
}

.social-icon-wrapper::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border-radius: 50%;
  transform: scale(0);
  z-index: -1;
  transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.social-icon-wrapper:hover {
  color: white;
  transform: translateY(-3px);
  box-shadow: 0 7px 20px rgba(0, 0, 0, 0.3);
}

.social-icon-wrapper:hover::before {
  transform: scale(1);
}

/* Social Icons Colors */
.facebook::before {
  background-color: #1877f2;
}

.instagram::before {
  background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
}

.twitter::before {
  background-color: #1da1f2;
}

.linkedin::before {
  background-color: #0077b5;
}

.youtube::before {
  background-color: #ff0000;
}

.tiktok::before {
  background-color: #000000;
}

/* Contact Items */
.footer-contact-item {
  transition: all 0.3s ease;
}

.footer-contact-item:hover {
  transform: translateX(5px);
}

.contact-icon-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  background-color: rgba(96, 165, 250, 0.1);
  color: #60a5fa;
  transition: all 0.3s ease;
}

.footer-contact-item:hover .contact-icon-wrapper {
  background-color: rgba(96, 165, 250, 0.2);
  color: #93c5fd;
}

.footer-contact-link {
  color: #9ca3af;
  transition: all 0.3s ease;
}

.footer-contact-link:hover {
  color: #ffffff;
}

/* Payment Methods */
.payment-methods-wrapper {
  opacity: 0;
  animation: fadeIn 0.8s ease forwards 0.8s;
}

.payment-icons {
  display: flex;
  gap: 12px;
}

.payment-method-icon {
  height: 32px;
  width: auto;
  min-width: 48px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.payment-method-icon:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
}

/* Policy Links */
.footer-policy-link {
  font-size: 0.875rem;
  color: #9ca3af;
  transition: all 0.3s ease;
}

.footer-policy-link:hover {
  color: #60a5fa;
}

/* Copyright */
.copyright-wrapper {
  opacity: 0;
  animation: fadeIn 0.8s ease forwards 1s;
}

.copyright-text {
  position: relative;
  display: inline-block;
}

/* Waves Animation */
.waves {
  position: absolute;
  width: 100%;
  height: 15vh;
  min-height: 100px;
  max-height: 150px;
  transform: rotate(180deg);
}

.parallax > use {
  animation: moveForever 25s cubic-bezier(.55, .5, .45, .5) infinite;
}

.parallax > use:nth-child(1) {
  animation-delay: -2s;
  animation-duration: 7s;
}

.parallax > use:nth-child(2) {
  animation-delay: -3s;
  animation-duration: 10s;
}

.parallax > use:nth-child(3) {
  animation-delay: -4s;
  animation-duration: 13s;
}

.parallax > use:nth-child(4) {
  animation-delay: -5s;
  animation-duration: 20s;
}

@keyframes moveForever {
  0% {
    transform: translate3d(-90px, 0, 0);
  }
  100% {
    transform: translate3d(85px, 0, 0);
  }
}

/* Fade In Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Fade Up Animation */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Fade Right Animation */
@keyframes fadeInRight {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  .footer-column-wrapper {
    animation-delay: calc(var(--index, 0) * 0.1s + 0.2s);
  }
  
  .footer-link:hover {
    padding-left: 12px;
  }
  
  .social-icon-wrapper {
    width: 36px;
    height: 36px;
  }
  
  .waves {
    height: 40px;
    min-height: 40px;
  }
}
</style> 