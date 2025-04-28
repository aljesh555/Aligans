<template>
  <div class="min-h-screen flex flex-col md:flex-row bg-gray-50">
    <!-- Left Panel - Brand & Info -->
    <div class="hidden md:flex md:w-1/2 bg-blue-700 text-white flex-col justify-center items-center p-8">
      <div class="max-w-md">
        <div class="flex items-center justify-center mb-8">
          <span class="text-4xl font-bold mr-2">Aligans</span>
          <span class="text-sm bg-white text-blue-700 px-2 py-1 rounded">Sports</span>
        </div>
        <h1 class="text-3xl font-bold mb-4">Join the Premier Sports Community</h1>
        <p class="text-lg mb-8">Create an account to discover premium cricket equipment, exclusive events, and connect with other sports enthusiasts.</p>
        
        <div class="space-y-6">
          <div class="flex items-start">
            <div class="flex-shrink-0 mt-1">
              <svg class="h-5 w-5 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-lg font-medium">Premium Quality Equipment</h3>
              <p class="mt-1 text-sm text-blue-200">Access to high-quality cricket equipment from top brands.</p>
            </div>
          </div>
          
          <div class="flex items-start">
            <div class="flex-shrink-0 mt-1">
              <svg class="h-5 w-5 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-lg font-medium">Exclusive Events</h3>
              <p class="mt-1 text-sm text-blue-200">Stay updated on cricket tournaments and training camps.</p>
            </div>
          </div>
          
          <div class="flex items-start">
            <div class="flex-shrink-0 mt-1">
              <svg class="h-5 w-5 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-lg font-medium">Secure Checkout</h3>
              <p class="mt-1 text-sm text-blue-200">Safe and secure payment processing for all purchases.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Right Panel - Registration Form -->
    <div class="w-full md:w-1/2 flex items-center justify-center p-4 md:p-8">
      <div class="w-full max-w-md">
        <!-- Mobile Logo -->
        <div class="md:hidden flex items-center justify-center mb-8">
          <div class="bg-blue-700 text-white rounded-lg p-4 flex items-center">
            <span class="text-3xl font-bold mr-2">Aligans</span>
            <span class="text-xs bg-white text-blue-700 px-2 py-1 rounded">Sports</span>
          </div>
        </div>
        
        <div class="bg-white shadow-xl rounded-lg p-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-2">Create your account</h2>
          <p class="text-gray-600 mb-6">
            Already have an account?
            <router-link to="/login" class="font-medium text-blue-600 hover:text-blue-500">
              Sign in
            </router-link>
          </p>

          <!-- Error Message -->
          <div v-if="error" class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-red-700">{{ error }}</p>
              </div>
            </div>
          </div>

          <!-- Success Message -->
          <div v-if="success" class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-green-700">{{ success }}</p>
              </div>
            </div>
          </div>

          <!-- Registration Form -->
          <form class="space-y-5" @submit.prevent="register">
            <!-- Name Field -->
            <div class="space-y-1">
              <label for="name" class="block text-sm font-medium text-gray-700">Full name</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                  </svg>
                </div>
                <input 
                  v-model="name" 
                  id="name" 
                  name="name" 
                  type="text" 
                  required
                  class="pl-10 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  :class="{'border-red-300 ring-red-300': nameError}"
                  placeholder="John Smith"
                />
              </div>
              <p v-if="nameError" class="mt-1 text-sm text-red-600">{{ nameError }}</p>
            </div>

            <!-- Email Field -->
            <div class="space-y-1">
              <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                  </svg>
                </div>
                <input 
                  v-model="email" 
                  id="email" 
                  name="email" 
                  type="email" 
                  autocomplete="email" 
                  required
                  class="pl-10 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  :class="{'border-red-300 ring-red-300': emailError}"
                  placeholder="you@example.com"
                />
              </div>
              <p v-if="emailError" class="mt-1 text-sm text-red-600">{{ emailError }}</p>
            </div>

            <!-- Phone Field -->
            <div class="space-y-1">
              <label for="phone" class="block text-sm font-medium text-gray-700">Phone number <span class="text-gray-400">(optional)</span></label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                  </svg>
                </div>
                <input 
                  v-model="phone" 
                  id="phone" 
                  name="phone" 
                  type="tel" 
                  class="pl-10 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  placeholder="+1 (555) 123-4567"
                />
              </div>
            </div>

            <!-- Password Field -->
            <div class="space-y-1">
              <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                  </svg>
                </div>
                <input 
                  v-model="password" 
                  id="password" 
                  name="password" 
                  :type="showPassword ? 'text' : 'password'" 
                  required
                  class="pl-10 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  :class="{'border-red-300 ring-red-300': passwordError}"
                  placeholder="••••••••"
                />
                <button 
                  type="button" 
                  @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                >
                  <svg v-if="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                  </svg>
                  <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </div>
              <p v-if="passwordError" class="mt-1 text-sm text-red-600">{{ passwordError }}</p>
              <p v-else class="mt-1 text-xs text-gray-500">Must be 8+ characters with uppercase, lowercase, and number</p>
            </div>

            <!-- Confirm Password Field -->
            <div class="space-y-1">
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                  </svg>
                </div>
                <input 
                  v-model="passwordConfirmation" 
                  id="password_confirmation" 
                  name="password_confirmation" 
                  :type="showPassword ? 'text' : 'password'" 
                  required
                  class="pl-10 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  :class="{'border-red-300 ring-red-300': passwordConfirmationError}"
                  placeholder="••••••••"
                />
              </div>
              <p v-if="passwordConfirmationError" class="mt-1 text-sm text-red-600">{{ passwordConfirmationError }}</p>
            </div>

            <!-- Terms and Conditions -->
            <div class="flex items-start">
              <div class="flex items-center h-5">
                <input 
                  v-model="termsAccepted" 
                  id="terms" 
                  name="terms" 
                  type="checkbox" 
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  :class="{'border-red-300': termsError}"
                />
              </div>
              <div class="ml-3 text-sm">
                <label for="terms" class="font-medium text-gray-700">
                  I agree to the
                  <a href="#" class="font-bold text-blue-600 hover:text-blue-500">Terms of Service</a>
                  and
                  <a href="#" class="font-bold text-blue-600 hover:text-blue-500">Privacy Policy</a>
                </label>
              </div>
            </div>
            <p v-if="termsError" class="mt-1 text-sm text-red-600">{{ termsError }}</p>

            <!-- Submit Button -->
            <div class="mb-6">
              <button 
                type="submit" 
                :disabled="loading"
                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
              >
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                  <svg v-if="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                  </svg>
                </span>
                {{ loading ? 'Creating Account...' : 'Create Account' }}
              </button>
            </div>
            
            <!-- Social Registration -->
            <div class="mt-6">
              <div class="relative">
                <div class="absolute inset-0 flex items-center">
                  <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                  <span class="px-2 bg-white text-gray-500">Or sign up with</span>
                </div>
              </div>

              <div class="mt-6 grid grid-cols-2 gap-3">
                <button type="button" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors duration-200">
                  <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#1877F2" d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96C18.34 21.21 22 17.06 22 12.06C22 6.53 17.5 2.04 12 2.04Z"/>
                  </svg>
                </button>
                <button type="button" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors duration-200">
                  <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#EA4335" d="M5.266 9.765A7.077 7.077 0 012 12c0 1.097.26 2.135.723 3.053a7.022 7.022 0 005.508 4.372 6.93 6.93 0 003.138-.269 7.016 7.016 0 004.841-5.465 7.15 7.15 0 00-1.095-5.002 7.023 7.023 0 00-9.849-2.924z"/>
                    <path fill="#FBBC04" d="M21.49 12c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#4285F4" d="M12 21c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 18.81 7.7 21 12 21z"/>
                    <path fill="#34A853" d="M5.84 12.16c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V5.14H2.18C1.43 6.55 1 8.18 1 9.93s.43 3.38 1.18 4.79l3.66-2.56z"/>
                  </svg>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

export default {
  name: 'RegisterView',
  setup() {
    const store = useStore();
    const router = useRouter();
    
    const name = ref('');
    const email = ref('');
    const phone = ref('');
    const password = ref('');
    const passwordConfirmation = ref('');
    const termsAccepted = ref(false);
    const showPassword = ref(false);
    const loading = ref(false);
    const error = ref('');
    const success = ref('');
    const nameError = ref('');
    const emailError = ref('');
    const passwordError = ref('');
    const passwordConfirmationError = ref('');
    const termsError = ref('');

    const validateForm = () => {
      let isValid = true;
      nameError.value = '';
      emailError.value = '';
      passwordError.value = '';
      passwordConfirmationError.value = '';
      termsError.value = '';

      // Name validation
      if (!name.value) {
        nameError.value = 'Name is required';
        isValid = false;
      }

      // Email validation
      if (!email.value) {
        emailError.value = 'Email is required';
        isValid = false;
      } else if (!/\S+@\S+\.\S+/.test(email.value)) {
        emailError.value = 'Please enter a valid email address';
        isValid = false;
      }

      // Password validation
      if (!password.value) {
        passwordError.value = 'Password is required';
        isValid = false;
      } else if (password.value.length < 8) {
        passwordError.value = 'Password must be at least 8 characters';
        isValid = false;
      } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(password.value)) {
        passwordError.value = 'Password must contain at least one uppercase letter, one lowercase letter, and one number';
        isValid = false;
      }

      // Password confirmation validation
      if (password.value !== passwordConfirmation.value) {
        passwordConfirmationError.value = 'Passwords do not match';
        isValid = false;
      }

      // Terms validation
      if (!termsAccepted.value) {
        termsError.value = 'You must accept the terms and conditions';
        isValid = false;
      }

      return isValid;
    };

    const register = async () => {
      if (!validateForm()) return;

      try {
        loading.value = true;
        error.value = '';
        success.value = '';

        console.log('Sending registration data:', {
          name: name.value,
          email: email.value,
          phone: phone.value,
          password: '********', // Don't log actual password
          password_confirmation: '********'
        });

        const response = await axios.post('/api/register', {
          name: name.value,
          email: email.value,
          phone: phone.value,
          password: password.value,
          password_confirmation: passwordConfirmation.value
        });
        
        console.log('Registration successful:', response.data);
        
        // Do not store token or user info - require explicit login
        // localStorage.setItem('token', response.data.token);
        // localStorage.setItem('user', JSON.stringify(response.data.user));
        // store.commit('auth/setUser', response.data.user);
        // store.commit('auth/setLoggedIn', true);
        
        // Show success message
        success.value = 'Account created successfully! Please login with your credentials.';
        
        // Redirect to login page
        setTimeout(() => {
          router.push({
            path: '/login',
            query: { newRegistration: 'true' }
          });
        }, 1500);
      } catch (err) {
        console.error('Registration error:', err);
        
        if (err.response) {
          console.error('Error response data:', err.response.data);
          console.error('Error response status:', err.response.status);
          
          if (err.response.status === 422) {
            // Validation errors
            const errors = err.response.data.errors;
            console.log('Validation errors:', errors);
            
            if (errors.name) nameError.value = errors.name[0];
            if (errors.email) emailError.value = errors.email[0];
            if (errors.password) passwordError.value = errors.password[0];
            if (errors.password_confirmation) passwordConfirmationError.value = errors.password_confirmation[0];
            if (errors.terms_accepted) termsError.value = errors.terms_accepted[0];
          } else {
            error.value = err.response.data.message || 'Registration failed';
          }
        } else if (err.request) {
          // The request was made but no response was received
          console.error('No response received:', err.request);
          error.value = 'No response from server. Please check your connection.';
        } else {
          // Something happened in setting up the request
          console.error('Error setting up request:', err.message);
          error.value = 'Something went wrong. Please try again.';
        }
      } finally {
        loading.value = false;
      }
    };

    return {
      name,
      email,
      phone,
      password,
      passwordConfirmation,
      termsAccepted,
      showPassword,
      loading,
      error,
      success,
      nameError,
      emailError,
      passwordError,
      passwordConfirmationError,
      termsError,
      register
    };
  }
};
</script> 