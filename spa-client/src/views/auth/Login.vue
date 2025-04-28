<template>
  <div class="min-h-screen flex bg-gray-50">
    <!-- Left Panel - Brand & Info -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-800 to-blue-600 text-white flex-col justify-center items-center p-8">
      <div class="max-w-md">
        <div class="flex items-center justify-center mb-8">
          <span class="text-5xl font-bold mr-2">Aligans</span>
          <span class="text-sm bg-white text-blue-700 px-2 py-1 rounded">Sports</span>
        </div>
        <h1 class="text-3xl font-bold mb-6">Welcome Back</h1>
        <p class="text-xl mb-8 text-blue-100">Sign in to access your account and continue your sports journey with us.</p>
        
        <div class="space-y-6">
          <div class="flex items-start">
            <div class="flex-shrink-0 mt-1">
              <svg class="h-5 w-5 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-lg font-medium">Premium Features</h3>
              <p class="mt-1 text-sm text-blue-200">Access exclusive content and features.</p>
            </div>
          </div>
          
          <div class="flex items-start">
            <div class="flex-shrink-0 mt-1">
              <svg class="h-5 w-5 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-lg font-medium">Secure Shopping</h3>
              <p class="mt-1 text-sm text-blue-200">Enjoy secure and convenient online shopping.</p>
            </div>
          </div>
          
          <div class="flex items-start">
            <div class="flex-shrink-0 mt-1">
              <svg class="h-5 w-5 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-lg font-medium">Track Orders</h3>
              <p class="mt-1 text-sm text-blue-200">Monitor your orders and delivery status.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Right Panel - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-4 md:p-12">
      <div class="w-full max-w-md">
        <!-- Mobile Logo -->
        <div class="lg:hidden flex items-center justify-center mb-10">
          <div class="bg-blue-700 text-white rounded-lg p-4 flex items-center">
            <span class="text-3xl font-bold mr-2">Aligans</span>
            <span class="text-xs bg-white text-blue-700 px-2 py-1 rounded">Sports</span>
          </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-xl p-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-2">Sign in to your account</h2>
          <p class="text-gray-500 mb-8">
            Don't have an account yet?
            <router-link to="/register" class="font-medium text-blue-600 hover:text-blue-500">
              Create an account
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

          <!-- Login Form -->
          <form class="space-y-6" @submit.prevent="login">
            <!-- Email Field -->
            <div class="space-y-2">
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
                  class="pl-10 appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                  :class="{'border-red-300 ring-red-300': emailError}"
                  placeholder="you@example.com"
                />
              </div>
              <p v-if="emailError" class="mt-1 text-sm text-red-600">{{ emailError }}</p>
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
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
                  autocomplete="current-password" 
                  required
                  class="pl-10 appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
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
            </div>

            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <input 
                  v-model="rememberMe" 
                  id="remember-me" 
                  name="remember-me" 
                  type="checkbox" 
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                />
                <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                  Remember me
                </label>
              </div>

              <div class="text-sm">
                <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                  Forgot your password?
                </a>
              </div>
            </div>

            <div>
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
                  <svg v-else class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                  </svg>
                </span>
                {{ loading ? 'Signing in...' : 'Sign in' }}
              </button>
            </div>

            <!-- Social Login -->
            <div class="mt-6">
              <div class="relative">
                <div class="absolute inset-0 flex items-center">
                  <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                  <span class="px-2 bg-white text-gray-500">Or sign in with</span>
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
import { ref, onMounted } from 'vue';
import { useStore } from 'vuex';
import { useRouter, useRoute } from 'vue-router';

export default {
  name: 'LoginView',
  setup() {
    const store = useStore();
    const router = useRouter();
    const route = useRoute();
    
    const email = ref('');
    const password = ref('');
    const rememberMe = ref(false);
    const showPassword = ref(false);
    const loading = ref(false);
    const error = ref('');
    const success = ref('');
    const emailError = ref('');
    const passwordError = ref('');

    // Check if coming from registration
    onMounted(() => {
      if (route.query.newRegistration) {
        success.value = 'Registration successful! Please log in with your new account.';
      }
    });

    const validateForm = () => {
      let isValid = true;
      emailError.value = '';
      passwordError.value = '';

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
      } else if (password.value.length < 6) {
        passwordError.value = 'Password must be at least 6 characters';
        isValid = false;
      }

      return isValid;
    };

    const login = async () => {
      if (!validateForm()) return;

      try {
        loading.value = true;
        error.value = '';
        success.value = '';

        const result = await store.dispatch('auth/login', {
          email: email.value,
          password: password.value,
          remember_me: rememberMe.value
        });
        
        // Show success message
        success.value = 'Successfully logged in!';
        
        // Determine where to redirect based on admin status
        let redirectPath = '/';
        
        // If user is admin or result indicates they're an admin, redirect to admin dashboard
        if ((result.user && (result.user.role === 'admin' || result.user.role === 'manager')) || result.isAdmin) {
          redirectPath = '/admin/dashboard';
        } else {
          // Otherwise use the redirect query param or default to home
          redirectPath = router.currentRoute.value.query.redirect || '/';
        }
        
        // Redirect after a short delay to show the success message
        setTimeout(() => {
          router.push(redirectPath);
        }, 1000);
      } catch (err) {
        console.error('Login error:', err);
        
        if (err.response) {
          if (err.response.status === 422) {
            // Validation errors
            const errors = err.response.data.errors;
            if (errors.email) emailError.value = errors.email[0];
            if (errors.password) passwordError.value = errors.password[0];
          } else if (err.response.status === 401) {
            error.value = 'Invalid email or password. Please try again.';
          } else {
            error.value = err.response.data.message || 'Login failed. Please try again.';
          }
        } else if (err.request) {
          error.value = 'No response from server. Please check your connection.';
        } else {
          error.value = err.message || 'Something went wrong. Please try again.';
        }
      } finally {
        loading.value = false;
      }
    };

    return {
      email,
      password,
      rememberMe,
      showPassword,
      loading,
      error,
      success,
      emailError,
      passwordError,
      login
    };
  }
};
</script> 