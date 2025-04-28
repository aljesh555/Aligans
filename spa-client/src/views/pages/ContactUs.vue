<template>
  <div>
    <Header />
    
    <div class="bg-gray-50 py-16">
      <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
          <h1 class="text-3xl font-bold text-center mb-12">Contact Us</h1>
          
          <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Success message -->
            <div v-if="formSubmitted" class="mb-8 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative">
              <h2 class="font-bold text-lg mb-2">Thank You!</h2>
              <p>Your message has been sent successfully. We'll get back to you shortly.</p>
              <div class="mt-4">
                <button 
                  @click="resetForm" 
                  class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors"
                >
                  Send Another Message
                </button>
                  </div>
                </div>
                
            <!-- Form -->
            <form v-else @submit.prevent="submitForm" class="space-y-6">
              <!-- Name input -->
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input 
                  id="name"
                  v-model="form.name"
                  type="text" 
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{'border-red-500': errors.name}"
                  required
                >
                <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
                </div>
                
              <!-- Email input -->
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input 
                  id="email"
                  v-model="form.email"
                  type="email" 
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{'border-red-500': errors.email}"
                  required
                >
                <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
                </div>
                
              <!-- Phone input -->
              <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number (Optional)</label>
                <input 
                  id="phone"
                  v-model="form.phone"
                  type="tel" 
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{'border-red-500': errors.phone}"
                >
                <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone[0] }}</p>
                </div>
                
              <!-- Subject input -->
              <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                <input 
                  id="subject"
                  v-model="form.subject"
                  type="text" 
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{'border-red-500': errors.subject}"
                  required
                >
                <p v-if="errors.subject" class="mt-1 text-sm text-red-600">{{ errors.subject[0] }}</p>
                </div>
                
              <!-- Message textarea -->
              <div>
                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                <textarea 
                  id="message"
                  v-model="form.message"
                  rows="5" 
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{'border-red-500': errors.message}"
                  required
                ></textarea>
                <p v-if="errors.message" class="mt-1 text-sm text-red-600">{{ errors.message[0] }}</p>
                </div>
                
              <!-- Submit button -->
              <div class="flex justify-end">
                <button 
                  type="submit" 
                  class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  :disabled="loading"
                >
                  <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ loading ? 'Sending...' : 'Send Message' }}
                  </button>
                </div>
              
              <!-- General error message -->
              <div v-if="generalError" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                {{ generalError }}
                </div>
              </form>
            </div>
            
            <!-- Contact Information -->
          <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Office Info - Dynamic -->
            <div class="bg-white p-6 rounded-lg shadow-md">
              <h2 class="text-xl font-semibold mb-4">Our Office</h2>
              
              <!-- Loading state -->
              <div v-if="customerCareLoading" class="animate-pulse space-y-3">
                <div class="bg-gray-200 h-4 w-3/4 rounded"></div>
                <div class="bg-gray-200 h-4 w-2/3 rounded"></div>
                <div class="bg-gray-200 h-4 w-4/5 rounded"></div>
              </div>
              
              <!-- Content when loaded -->
              <div v-else class="space-y-3">
                <div v-if="customerCare.address" class="flex items-start">
                  <div class="flex-shrink-0 mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm text-gray-700" v-html="formatAddress(customerCare.address)"></p>
                  </div>
                </div>
                
                <div v-if="customerCare.email" class="flex items-center">
                  <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <a :href="'mailto:' + customerCare.email" class="text-sm text-blue-600 hover:underline">{{ customerCare.email }}</a>
                  </div>
                </div>
                
                <div v-if="customerCare.phone" class="flex items-center">
                  <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <a :href="'tel:' + customerCare.phone.replace(/[^0-9+]/g, '')" class="text-sm text-blue-600 hover:underline">{{ customerCare.phone }}</a>
                  </div>
                </div>
                
                <div v-if="customerCare.whatsapp" class="flex items-center">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M19.3379 4.7018C17.3082 2.6652 14.5737 1.5 11.6853 1.5C5.7606 1.5 0.954541 6.3204 0.954541 12.2593C0.954541 14.1734 1.4443 16.0426 2.37311 17.6873L0.871094 23.25L6.50825 21.7723C8.08051 22.6262 9.8626 23.0736 11.6814 23.0736H11.6853C17.6061 23.0736 22.5 18.2531 22.5 12.3142C22.5 9.42293 21.3676 6.73839 19.3379 4.7018ZM11.6853 21.1979C10.0681 21.1979 8.48393 20.7691 7.11246 19.9701L6.78443 19.7804L3.47344 20.7056L4.41042 17.4515L4.20496 17.1111C3.33173 15.6881 2.87094 14.0022 2.87094 12.2593C2.87094 7.36301 6.80519 3.37962 11.6892 3.37962C14.0571 3.37962 16.2812 4.34546 17.9667 6.04262C19.6521 7.73979 20.5873 9.97178 20.5834 12.3142C20.5834 17.2143 16.5653 21.1979 11.6853 21.1979ZM16.4789 14.5473C16.2187 14.4175 14.9111 13.7733 14.6744 13.6873C14.4378 13.6013 14.2647 13.5582 14.0915 13.8212C13.9184 14.0842 13.4148 14.6835 13.2613 14.8593C13.1078 15.0352 12.9583 15.0566 12.6982 14.9268C12.438 14.797 11.5725 14.515 10.5535 13.6051C9.75699 12.8962 9.22 12.0219 9.0705 11.7551C8.921 11.4882 9.0588 11.3467 9.19231 11.2129C9.31236 11.0928 9.45919 10.9005 9.59276 10.7458C9.72633 10.5912 9.76928 10.4838 9.85517 10.3079C9.94106 10.132 9.89812 9.97737 9.83323 9.84759C9.76833 9.71782 9.23133 8.40277 9.01471 7.8759C8.80726 7.35772 8.59981 7.42332 8.43869 7.41627C8.2852 7.40922 8.11211 7.40922 7.93902 7.40922C7.76593 7.40922 7.48651 7.47481 7.24989 7.74168C7.01326 8.00854 6.33662 8.65273 6.33662 9.96779C6.33662 11.2828 7.26807 12.5548 7.40164 12.7307C7.53521 12.9066 9.22 15.4978 11.7807 16.6694C12.3528 16.9148 12.7991 17.0661 13.1508 17.1835C13.7229 17.3778 14.2453 17.3543 14.6587 17.2877C15.1184 17.2143 16.1777 16.6423 16.3943 16.0155C16.6108 15.3887 16.6108 14.8618 16.5459 14.7593C16.481 14.6568 16.3079 14.5964 16.0478 14.4666L16.4789 14.5473Z" fill="currentColor"/>
                    </svg>
                  </div>
                  <div class="ml-3">
                    <a :href="whatsappUrl" target="_blank" class="text-sm text-blue-600 hover:underline">{{ customerCare.whatsapp }}</a>
                  </div>
                </div>
                
                <div v-if="customerCare.viber" class="flex items-center">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M19.0508 12.0743C19.0508 11.4931 18.6821 11.1244 18.1009 11.1244C17.5196 11.1244 17.151 11.4931 17.151 12.0743C17.151 15.6358 14.2617 18.5252 10.7001 18.5252C9.1804 18.5252 7.6816 17.9857 6.51953 17.0149C5.8042 16.4337 5.06885 15.7602 4.85693 14.7475C4.71729 14.1453 4.63965 13.0908 5.35498 12.3754C5.83008 11.9003 6.55469 11.8436 7.21729 11.8649C7.54883 12.8146 8.08301 13.7536 8.73633 14.4481C8.29883 14.2999 7.82373 14.1526 7.39551 14.0989C6.87061 14.0452 6.34717 14.1853 6.0791 14.4534C5.83301 14.6977 5.81152 15.0664 6.03516 15.3555C6.52783 15.9995 7.7793 16.8774 10.7001 16.8774C13.3555 16.8774 15.5303 14.7026 15.5303 12.0473C15.5303 10.6636 14.2285 9.52881 12.683 9.52881C11.1375 9.52881 9.83569 10.6636 9.83569 12.0473C9.83569 12.9966 10.5728 13.8313 11.522 13.9467C10.8262 13.9467 10.3975 13.4317 10.3975 12.8076C10.3975 12.0957 10.9253 11.5143 11.5889 11.5143C12.2417 11.5143 12.7588 12.0318 12.7588 12.6846C12.7588 13.3374 12.2417 13.8545 11.5889 13.8545C11.2041 13.8545 10.8584 13.6499 10.6548 13.3413C10.209 13.3147 9.81934 12.9028 9.81934 12.0473C9.81934 10.8701 11.0713 9.61798 12.683 9.61798C14.2842 9.61798 15.5201 10.8701 15.5201 12.0473C15.5201 14.6491 13.3018 16.8774 10.6899 16.8774C7.82373 16.8774 6.5957 15.9878 6.13184 15.3965C5.84229 15.025 5.84229 14.5557 6.13184 14.2559C6.46338 13.9248 7.09033 13.7305 7.73438 13.8047C8.14063 13.8476 8.53687 13.9785 8.91748 14.1243C8.94653 14.1233 8.97412 14.1141 9.00195 14.1038C8.38574 13.4619 7.84595 12.5205 7.55103 11.6147C7.08985 11.5757 5.75879 11.6201 4.96191 12.4165C4.06689 13.3115 4.16602 14.5962 4.33398 15.3136C4.58008 16.4805 5.4126 17.261 6.18262 17.8872C7.41699 18.9046 9.0205 19.4766 10.6104 19.4766C14.7891 19.4766 18.2227 16.0435 18.2227 11.9654C18.2231 10.8828 17.7866 9.84503 17.0122 9.07105C17.7334 9.9502 19.0508 10.9731 19.0508 12.0743Z" fill="currentColor"/>
                      <path d="M12.4922 6.54175C14.334 6.38677 16.1143 7.00319 17.4658 8.23754C18.8174 9.4719 19.5801 11.156 19.5527 12.998C19.5527 13.5791 19.9214 13.9479 20.5026 13.9479C21.0837 13.9479 21.4524 13.5791 21.4524 12.998C21.4799 10.6467 20.5181 8.39331 18.8198 6.80974C17.1215 5.22616 14.8086 4.41113 12.4565 4.59667C11.8752 4.6396 11.5059 5.00876 11.5537 5.57886C11.5791 6.14897 11.9482 6.58472 12.4922 6.54175Z" fill="currentColor"/>
                      <path d="M11.7188 8.55237C10.7998 8.55237 9.92749 8.91225 9.26489 9.57485C8.60229 10.2374 8.24243 11.1098 8.24243 12.0288C8.24243 12.6099 8.61115 12.9787 9.19243 12.9787C9.77367 12.9787 10.1338 12.6099 10.1338 12.0288C10.1338 10.8599 10.5401 10.4536 11.7089 10.4536C12.29 10.4536 12.6587 10.0848 12.6587 9.50357C12.6587 8.92233 12.29 8.55237 11.7188 8.55237Z" fill="currentColor"/>
                    </svg>
                  </div>
                  <div class="ml-3">
                    <a :href="viberUrl" target="_blank" class="text-sm text-blue-600 hover:underline">{{ customerCare.viber }}</a>
                  </div>
                </div>
              </div>
            </div>
              
            <!-- Business Hours - Dynamic -->
            <div class="bg-white p-6 rounded-lg shadow-md">
              <h2 class="text-xl font-semibold mb-4">Business Hours</h2>
              
              <!-- Loading state -->
              <div v-if="customerCareLoading" class="animate-pulse space-y-3">
                <div class="bg-gray-200 h-4 w-3/4 rounded"></div>
                <div class="bg-gray-200 h-4 w-2/3 rounded"></div>
                <div class="bg-gray-200 h-4 w-4/5 rounded"></div>
              </div>
              
              <!-- Content when loaded -->
              <div v-else class="space-y-2">
                <div v-if="customerCare.timings" class="space-y-2">
                  <div v-for="(timing, index) in formattedTimings" :key="index" class="flex justify-between">
                    <span class="text-sm text-gray-600">{{ timing.days }}:</span>
                    <span class="text-sm text-gray-900 font-medium">{{ timing.hours }}</span>
                  </div>
                </div>
                <div v-else>
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Monday - Friday:</span>
                    <span class="text-sm text-gray-900 font-medium">9:00 AM - 6:00 PM</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Saturday:</span>
                    <span class="text-sm text-gray-900 font-medium">10:00 AM - 4:00 PM</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Sunday:</span>
                    <span class="text-sm text-gray-900 font-medium">Closed</span>
                  </div>
                </div>
                
                <hr class="my-3 border-gray-200" />
                <p class="text-sm text-gray-600">
                  For customer support outside these hours, please send us an email or leave a message using the form above, and we'll get back to you as soon as possible.
                </p>
              </div>
            </div>
          </div>
          
          <!-- Map Section - Just a placeholder for now -->
          <div id="store-map" class="mt-12 relative h-96 rounded-lg overflow-hidden">
            <div v-if="customerCareLoading" class="absolute inset-0 bg-gray-300 flex items-center justify-center">
              <p class="text-gray-600 font-medium">Loading map...</p>
            </div>
            <iframe
              v-else
              width="100%"
              height="100%"
              style="border:0"
              loading="lazy"
              allowfullscreen
              referrerpolicy="no-referrer-when-downgrade"
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.514774372905!2d85.33966321744384!3d27.686926699999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb19002f77d05f%3A0x66c5c2b55f5234c1!2sAligans%20Cricket%20Store!5e0!3m2!1sen!2s!4v1714486069991!5m2!1sen!2s">
            </iframe>
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
  name: 'ContactUs',
  components: {
    Header,
    Footer
  },
  data() {
    return {
      form: {
        name: '',
        email: '',
        phone: '',
        subject: '',
        message: ''
      },
      loading: false,
      errors: {},
      formSubmitted: false,
      generalError: null,
      // Customer care data
      customerCare: {
        timings: '',
        whatsapp: '',
        email: '',
        phone: '',
        address: '',
        viber: ''
      },
      customerCareLoading: true
    };
  },
  computed: {
    // Compute formatted timings from the timings string
    formattedTimings() {
      if (!this.customerCare.timings) return [];
      
      // Try to parse business hours into a structured format
      // This assumes the timings are in format like "Monday-Friday: 9AM-6PM, Saturday: 10AM-4PM, Sunday: Closed"
      try {
        const timingParts = this.customerCare.timings.split(',').map(t => t.trim());
        return timingParts.map(part => {
          const [days, hours] = part.split(':').map(p => p.trim());
          return { days, hours };
        });
      } catch (e) {
        console.error('Error parsing timings:', e);
        return [{ days: 'Business Hours', hours: this.customerCare.timings }];
    }
  },
    // Formatted WhatsApp URL
    whatsappUrl() {
      if (!this.customerCare.whatsapp) return '#';
      
      // Remove all non-numeric characters including spaces and +
      const cleanNumber = this.customerCare.whatsapp.replace(/[^0-9]/g, '');
      return `https://wa.me/${cleanNumber}`;
    },
    // Formatted Viber URL
    viberUrl() {
      if (!this.customerCare.viber) return '#';
      
      // Remove all non-numeric characters
      const cleanNumber = this.customerCare.viber.replace(/[^0-9]/g, '');
      return `viber://chat?number=${cleanNumber}`;
    }
  },
  async created() {
    await this.fetchCustomerCare();
  },
  methods: {
    async submitForm() {
      this.loading = true;
      this.errors = {};
      this.generalError = null;
      
      try {
        const response = await axios.post('http://127.0.0.1:8000/api/contact', this.form);
        
        if (response.data.success) {
          this.formSubmitted = true;
          // You could also show a success toast/notification here
        } else {
          this.generalError = response.data.message || 'An error occurred. Please try again.';
        }
      } catch (error) {
        console.error('Error submitting form:', error);
        
        if (error.response && error.response.data && error.response.data.errors) {
          // Validation errors
          this.errors = error.response.data.errors;
        } else {
          // General error
          this.generalError = error.response?.data?.message || 'An error occurred. Please try again later.';
        }
      } finally {
        this.loading = false;
      }
    },
    resetForm() {
      this.form = {
        name: '',
        email: '',
        phone: '',
        subject: '',
        message: ''
      };
      this.errors = {};
      this.generalError = null;
      this.formSubmitted = false;
    },
    // Format the address to display line breaks properly
    formatAddress(address) {
      if (!address) return '';
      return address.replace(/\n/g, '<br>');
    },
    // Fetch customer care data from API
    async fetchCustomerCare() {
      try {
        this.customerCareLoading = true;
        console.log('Fetching customer care from API...');
        const response = await axios.get('http://127.0.0.1:8000/api/settings/customer-care');
        
        if (!response.data || !response.data.success) {
          console.error('Invalid response from API');
          this.customerCareLoading = false;
          return;
        }
        
        // Extract the data from the API response
        const settings = response.data.data;
        
        if (!settings || !settings.customer_care) {
          console.error('No customer_care setting in the response');
          this.customerCareLoading = false;
          return;
        }
        
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
  }
}
};
</script> 