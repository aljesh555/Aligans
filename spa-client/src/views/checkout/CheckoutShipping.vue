<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Advanced E-commerce Header -->
    <Header />
    
    <div class="container mx-auto px-4 py-8">
      <div class="max-w-4xl mx-auto">
        <!-- Checkout Steps -->
        <div class="flex justify-between mb-8">
          <div class="flex flex-col items-center">
            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center">1</div>
            <span class="mt-2 text-sm font-medium text-blue-600">Shipping</span>
          </div>
          <div class="relative flex items-center flex-1 mx-4">
            <div class="h-1 flex-1 bg-gray-300"></div>
          </div>
          <div class="flex flex-col items-center">
            <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center">2</div>
            <span class="mt-2 text-sm font-medium text-gray-600">Payment</span>
          </div>
          <div class="relative flex items-center flex-1 mx-4">
            <div class="h-1 flex-1 bg-gray-300"></div>
          </div>
          <div class="flex flex-col items-center">
            <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center">3</div>
            <span class="mt-2 text-sm font-medium text-gray-600">Confirmation</span>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm">
          <div class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold">Shipping Information</h1>
            <p class="text-gray-600 mt-1">Please enter your shipping details</p>
          </div>
          
          <div class="p-6">
            <form @submit.prevent="continueToPayment">
              <div v-if="errorMessage" class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                <p>{{ errorMessage }}</p>
                <button 
                  v-if="errorMessage.includes('security reasons')" 
                  @click="resetToCurrentUserInfo"
                  class="mt-2 text-blue-700 hover:underline font-medium"
                >
                  Click here to reset the form with your information
                </button>
              </div>
            
              <!-- Name field -->
              <div class="mb-6">
                <label for="userName" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                <input 
                  id="userName"
                  v-model="userName"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  :class="{ 'border-red-500': userNameError }"
                  required
                >
                <p v-if="userNameError" class="mt-1 text-sm text-red-600">{{ userNameError }}</p>
              </div>
              
              <!-- Email and Phone -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                  <input 
                    id="email"
                    v-model="email"
                    type="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-500': emailError }"
                    required
                  >
                  <p v-if="emailError" class="mt-1 text-sm text-red-600">{{ emailError }}</p>
                </div>
                
                <div>
                  <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                  <input 
                    id="phone"
                    v-model="phone"
                    type="tel"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-500': phoneError }"
                    required
                  >
                  <p v-if="phoneError" class="mt-1 text-sm text-red-600">{{ phoneError }}</p>
                </div>
              </div>
              
              <!-- Province -->
              <div class="mb-6">
                <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province *</label>
                <select 
                  id="province"
                  v-model="province"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  :class="{ 'border-red-500': provinceError }"
                  required
                >
                  <option value="">Select a province</option>
                  <option value="Koshi Province">Koshi Province</option>
                  <option value="Madhesh Province">Madhesh Province</option>
                  <option value="Bagmati Province">Bagmati Province</option>
                  <option value="Gandaki Province">Gandaki Province</option>
                  <option value="Lumbini Province">Lumbini Province</option>
                  <option value="Karnali Province">Karnali Province</option>
                  <option value="Sudurpashchim Province">Sudurpashchim Province</option>
                </select>
                <p v-if="provinceError" class="mt-1 text-sm text-red-600">{{ provinceError }}</p>
              </div>
              
              <!-- City and Area -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                  <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                  <input 
                    id="city"
                    v-model="city"
                    type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-500': cityError }"
                    required
                  >
                  <p v-if="cityError" class="mt-1 text-sm text-red-600">{{ cityError }}</p>
                </div>
                
                <div>
                  <label for="area" class="block text-sm font-medium text-gray-700 mb-1">Area *</label>
                  <input 
                    id="area"
                    v-model="area"
                    type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    :class="{ 'border-red-500': areaError }"
                    required
                  >
                  <p v-if="areaError" class="mt-1 text-sm text-red-600">{{ areaError }}</p>
                </div>
              </div>
              
              <!-- Building details -->
              <div class="mb-6">
                <label for="buildingDetails" class="block text-sm font-medium text-gray-700 mb-1">Building/House no./Floor/Street *</label>
                <input 
                  id="buildingDetails"
                  v-model="buildingDetails"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  :class="{ 'border-red-500': buildingDetailsError }"
                  required
                >
                <p v-if="buildingDetailsError" class="mt-1 text-sm text-red-600">{{ buildingDetailsError }}</p>
              </div>
              
              <!-- Full Address -->
              <div class="mb-6">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Full Address *</label>
                <textarea 
                  id="address"
                  v-model="address"
                  rows="2"
                  placeholder="e.g., house123, street, area, city"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  :class="{ 'border-red-500': addressError }"
                  required
                ></textarea>
                <p v-if="addressError" class="mt-1 text-sm text-red-600">{{ addressError }}</p>
              </div>
              
              <!-- Save Information checkbox -->
              <div class="mb-6">
                <div class="flex items-start">
                  <div class="flex items-center h-5">
                    <input 
                      id="saveInfo"
                      v-model="saveInfo"
                      type="checkbox"
                      class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    >
                  </div>
                  <div class="ml-3 text-sm">
                    <label for="saveInfo" class="font-medium text-gray-700">Save this information for next time</label>
                  </div>
                </div>
              </div>
              
              <!-- Form actions -->
              <div class="flex justify-between">
                <router-link 
                  to="/cart"
                  class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition"
                >
                  Return to Cart
                </router-link>
                
                <button 
                  type="submit"
                  class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
                  :disabled="loading"
                >
                  <span v-if="loading" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                  </span>
                  <span v-else>Continue to Payment</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <Footer />
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import Footer from '@/components/layout/Footer.vue';
import Header from '@/components/Header.vue';
import axios from 'axios';

export default {
  name: 'CheckoutShipping',
  components: {
    Footer,
    Header
  },
  setup() {
    const router = useRouter();
    const loading = ref(false);
    const errorMessage = ref('');
    
    // Header state
    const mobileMenuOpen = ref(false);
    const categoriesDropdownOpen = ref(false);
    const accountDropdownOpen = ref(false);
    const cartDropdownOpen = ref(false);
    const isLoggedIn = ref(false);
    const user = ref(null);
    const cartItems = ref([]);
    
    // Toggle dropdowns
    const toggleAccountDropdown = () => {
      accountDropdownOpen.value = !accountDropdownOpen.value;
      if (accountDropdownOpen.value) cartDropdownOpen.value = false;
    };
    
    const toggleCartDropdown = () => {
      cartDropdownOpen.value = !cartDropdownOpen.value;
      if (cartDropdownOpen.value) accountDropdownOpen.value = false;
    };
    
    // Computed properties for cart
    const cartItemsCount = computed(() => cartItems.value.length);
    const cartSubtotal = computed(() => {
      return cartItems.value.reduce((total, item) => {
        return total + (item.price * item.quantity);
      }, 0);
    });
    
    // Check if user is logged in
    const checkLoginStatus = () => {
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        user.value = JSON.parse(storedUser);
        isLoggedIn.value = true;
      }
    };
    
    // Load cart items
    const loadCartItems = () => {
      try {
        const savedCart = localStorage.getItem('cart');
        if (savedCart) {
          cartItems.value = JSON.parse(savedCart);
        }
      } catch (error) {
        console.error('Error loading cart:', error);
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
    
    // Handle image errors
    const handleImageError = (e) => {
      e.target.src = 'https://via.placeholder.com/150';
    };
    
    // Form fields
    const userName = ref('');
    const email = ref('');
    const phone = ref('');
    const province = ref('');
    const city = ref('');
    const area = ref('');
    const buildingDetails = ref('');
    const address = ref('');
    const saveInfo = ref(false);
    
    // Form errors
    const userNameError = ref('');
    const emailError = ref('');
    const phoneError = ref('');
    const provinceError = ref('');
    const cityError = ref('');
    const areaError = ref('');
    const buildingDetailsError = ref('');
    const addressError = ref('');
    
    // Validate form
    const validateForm = () => {
      let isValid = true;
      
      // Reset errors
      userNameError.value = '';
      emailError.value = '';
      phoneError.value = '';
      provinceError.value = '';
      cityError.value = '';
      areaError.value = '';
      buildingDetailsError.value = '';
      addressError.value = '';
      errorMessage.value = '';
      
      // Validate user name
      if (!userName.value.trim()) {
        userNameError.value = 'Full name is required';
        isValid = false;
      }
      
      // Validate email
      if (!email.value.trim()) {
        emailError.value = 'Email is required';
        isValid = false;
      } else if (!isValidEmail(email.value)) {
        emailError.value = 'Please enter a valid email address';
        isValid = false;
      }
      
      // Validate phone
      if (!phone.value.trim()) {
        phoneError.value = 'Phone number is required';
        isValid = false;
      }

      // Validate province
      if (!province.value) {
        provinceError.value = 'Please select a province';
        isValid = false;
      }
      
      // Validate city
      if (!city.value.trim()) {
        cityError.value = 'City is required';
        isValid = false;
      }
      
      // Validate area
      if (!area.value.trim()) {
        areaError.value = 'Area is required';
        isValid = false;
      }
      
      // Validate building details
      if (!buildingDetails.value.trim()) {
        buildingDetailsError.value = 'Building details are required';
        isValid = false;
      }
      
      // Validate address
      if (!address.value.trim()) {
        addressError.value = 'Full address is required';
        isValid = false;
      }
      
      return isValid;
    };
    
    // Check if email is valid
    const isValidEmail = (email) => {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    };
    
    // Continue to payment
    const continueToPayment = () => {
      if (!validateForm()) {
        errorMessage.value = 'Please fix the errors in the form';
        window.scrollTo(0, 0);
        return;
      }
      
      loading.value = true;
      
      // Check if current user info matches form personal information
      // This is a security measure to prevent submitting another user's information
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        try {
          const userData = JSON.parse(storedUser);
          
          // Security check: Ensure that the form is being submitted with the current user's data
          if (userData.email && userData.email !== email.value) {
            console.error('Security warning: Form email does not match logged-in user');
            errorMessage.value = 'For security reasons, you can only submit shipping information with your own email address.';
            loading.value = false;
            window.scrollTo(0, 0);
            
            // Reset the form with correct user information
            loadUserInfo();
            return;
          }
        } catch (e) {
          console.error('Error parsing user data in continueToPayment:', e);
        }
      }
      
      // Prepare shipping information
      const shippingInfo = {
        user_id: user.value?.id,
        user_name: userName.value,
        email: email.value,
        phone: phone.value,
        province_name: province.value,
        city: city.value,
        area: area.value,
        building_details: buildingDetails.value,
        address: address.value
      };
      
      // Save to localStorage for the current checkout process
      localStorage.setItem('shippingInfo', JSON.stringify(shippingInfo));
      
      // Get token from localStorage
      const token = localStorage.getItem('token');
      if (!token) {
        errorMessage.value = 'You must be logged in to checkout';
        loading.value = false;
        router.push('/login?redirect=/checkout/shipping');
        return;
      }
      
      // Always save shipping form to API, regardless of saveInfo checkbox
      axios.post('/api/shipping-forms', shippingInfo)
        .then(response => {
          console.log('Shipping address saved to account:', response.data);
          
          // Save shipping form ID to localStorage
          if (response.data.success && response.data.data && response.data.data.id) {
            localStorage.setItem('shippingFormId', response.data.data.id);
            console.log('Shipping form ID saved to localStorage:', response.data.data.id);
            
            // Now proceed to payment page
            loading.value = false;
            router.push('/checkout/payment');
          } else {
            throw new Error('Failed to get shipping form ID from API');
          }
        })
        .catch(error => {
          console.error('Error saving shipping address:', error);
          errorMessage.value = 'Failed to save shipping information. Please try again.';
          loading.value = false;
          window.scrollTo(0, 0);
        });
    };
    
    // Load saved shipping info if available
    const loadSavedShippingInfo = () => {
      console.log('Loading saved shipping info...');
      
      // First - ALWAYS load user info from localStorage to ensure personal data is correct
      loadUserInfo();
      
      // Check for shipping info from current checkout process first
      const currentShippingInfo = localStorage.getItem('shippingInfo');
      if (currentShippingInfo) {
        try {
          const info = JSON.parse(currentShippingInfo);
          console.log('Found shipping info in localStorage:', info.user_name);
          
          // Only use address information from the saved form
          // Keep the personal information (name, email, phone) from the current user
          province.value = info.province_name || '';
          city.value = info.city || '';
          area.value = info.area || '';
          buildingDetails.value = info.building_details || '';
          address.value = info.address || '';
          return;
        } catch (e) {
          console.error('Error parsing shipping info:', e);
        }
      } else {
        console.log('No shipping info found in localStorage');
      }
      
      // If user is logged in, try to get saved shipping info from API, but with security checks
      if (isLoggedIn.value && user.value && user.value.id) {
        console.log('Trying to load shipping info from API for user ID:', user.value.id);
        
        // Get auth token
        const token = localStorage.getItem('token');
        if (!token) {
          console.warn('No token found, cannot make authenticated API call');
          return;
        }
        
        // Make API call with proper authorization and explicit user_id parameter
        axios.get(`/api/shipping-forms/default?user_id=${user.value.id}`, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        })
          .then(response => {
            if (response.data.success && response.data.data) {
              const info = response.data.data;
              
              // SECURITY CHECK: Only use the shipping form if it belongs to the current user
              if (info.user_id && info.user_id == user.value.id) {
                console.log('Found matching shipping form in API for current user:', user.value.name);
                
                // Only use address information from the saved form
                // Keep the personal information (name, email, phone) from the current user
                province.value = info.province_name || '';
                city.value = info.city || '';
                area.value = info.area || '';
                buildingDetails.value = info.building_details || '';
                address.value = info.address || '';
                
                // Save shipping form ID to localStorage
                if (info.id) {
                  localStorage.setItem('shippingFormId', info.id);
                  console.log('Existing shipping form ID saved to localStorage:', info.id);
                }
              } else {
                console.warn('Security warning: API returned shipping form that does not belong to current user!');
                console.log('Current user ID:', user.value.id);
                console.log('Shipping form user ID:', info.user_id);
              }
            } else {
              console.log('No shipping forms found in API for current user');
            }
          })
          .catch(error => {
            console.error('Error loading saved shipping address:', error);
          });
      } else {
        console.log('User not logged in or missing ID, cannot load shipping info from API');
      }
    };
    
    // Load user info (name, email) from the user object
    const loadUserInfo = () => {
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        try {
          const userData = JSON.parse(storedUser);
          // Update the user value
          user.value = userData;
          isLoggedIn.value = true;
          
          // Set form fields from user data
          email.value = userData.email || '';
          userName.value = userData.name || '';
          phone.value = userData.phone || '';
          
          console.log('loadUserInfo loaded data for user:', userData.name);
        } catch (e) {
          console.error('Error parsing user data in loadUserInfo:', e);
        }
      } else {
        console.warn('No user data found in localStorage');
      }
    };
    
    // Check if cart is empty
    const checkCart = () => {
      const cart = localStorage.getItem('cart');
      if (!cart || JSON.parse(cart).length === 0) {
        router.push('/cart');
      }
    };
    
    // Check if user is logged in
    const checkAuth = () => {
      const user = localStorage.getItem('user');
      if (!user) {
        router.push({
          path: '/login',
          query: { redirect: '/checkout/shipping' }
        });
      }
    };
    
    // Reset the form with the current logged-in user's information
    const resetToCurrentUserInfo = () => {
      console.log('Resetting form to current user info');
      
      // Clear any previous shipping form data
      localStorage.removeItem('shippingInfo');
      localStorage.removeItem('shippingFormId');
      
      // Load the current user info
      loadUserInfo();
      
      // Clear errors
      errorMessage.value = '';
      
      // Clear address fields too to avoid confusion
      province.value = '';
      city.value = '';
      area.value = '';
      buildingDetails.value = '';
      address.value = '';
    };
    
    onMounted(() => {
      checkAuth();
      checkCart();
      
      // First check login status and load user info
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        try {
          const userData = JSON.parse(storedUser);
          user.value = userData;
          isLoggedIn.value = true;
          
          console.log('Loaded user data for:', userData.name);
          
          // First load shipping info (which will first load user info and then address details)
          loadSavedShippingInfo();
        } catch (e) {
          console.error('Error parsing stored user data:', e);
        }
      }
      
      // Close dropdowns when clicking outside
      document.addEventListener('click', (e) => {
        const accountDropdown = document.querySelector('.account-dropdown');
        const cartDropdown = document.querySelector('.cart-dropdown');
        
        if (accountDropdown && !accountDropdown.contains(e.target)) {
          accountDropdownOpen.value = false;
        }
        
        if (cartDropdown && !cartDropdown.contains(e.target)) {
          cartDropdownOpen.value = false;
        }
      });
    });
    
    return {
      // Header state
      mobileMenuOpen,
      categoriesDropdownOpen,
      accountDropdownOpen,
      cartDropdownOpen,
      isLoggedIn,
      user,
      cartItems,
      cartItemsCount,
      cartSubtotal,
      toggleAccountDropdown,
      toggleCartDropdown,
      logout,
      handleImageError,
      
      // Form state
      loading,
      errorMessage,
      userName,
      email,
      phone,
      province,
      city,
      area,
      buildingDetails,
      address,
      saveInfo,
      userNameError,
      emailError,
      phoneError,
      provinceError,
      cityError,
      areaError,
      buildingDetailsError,
      addressError,
      continueToPayment,
      resetToCurrentUserInfo
    };
  }
};
</script> 