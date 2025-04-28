<template>
  <div>
    <Header />
    <div class="bg-gray-50 min-h-screen py-8">
      <div class="container mx-auto px-4 max-w-5xl">
        <!-- Page Header with breadcrumbs -->
        <div class="mb-6">
          <div class="flex items-center text-sm text-gray-500 mb-2">
            <router-link to="/" class="hover:text-blue-600">Home</router-link>
            <span class="mx-2">/</span>
            <span class="text-gray-800">My Account</span>
          </div>
          <h1 class="text-2xl md:text-3xl font-bold text-gray-800">My Account</h1>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="bg-white rounded-lg shadow p-12 text-center">
          <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="mt-4 text-gray-600">Loading your account information...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="bg-white rounded-lg shadow p-8">
          <div class="flex flex-col items-center text-center">
            <svg class="h-12 w-12 text-red-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h2 class="text-xl font-medium text-gray-900 mb-2">Unable to load account</h2>
            <p class="text-gray-600 mb-4">{{ error }}</p>
            <router-link to="/login" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
              Go to Login
            </router-link>
          </div>
        </div>

        <!-- Content Section - Two Column Layout -->
        <div v-else-if="user" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Left Column: Navigation -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow overflow-hidden">
              <!-- User Info -->
              <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                  <div class="flex-shrink-0 mr-4">
                    <div class="h-16 w-16 rounded-full bg-blue-600 flex items-center justify-center text-white text-xl font-bold">
                      {{ getUserInitials() }}
                    </div>
                  </div>
                  <div>
                    <h2 class="text-lg font-medium text-gray-900">{{ user.name }}</h2>
                    <p class="text-sm text-gray-500">{{ user.email }}</p>
                  </div>
                </div>
              </div>
              
              <!-- Navigation Links -->
              <nav class="space-y-1 p-4">
                <a @click="activeTab = 'profile'" class="block px-4 py-2 rounded-md cursor-pointer" :class="activeTab === 'profile' ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-700 hover:bg-gray-50'">
                  Profile Information
                </a>
                <router-link to="/account/orders" class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-50">
                  Order History
                </router-link>
                <router-link to="/wishlist" class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-50">
                  Wishlist
                </router-link>
                <a @click="logout" class="block px-4 py-2 rounded-md text-red-600 hover:bg-red-50 cursor-pointer">
                  Logout
                </a>
              </nav>
            </div>
          </div>

          <!-- Right Column: Content -->
          <div class="lg:col-span-2">
            <!-- Profile Section -->
            <div v-if="activeTab === 'profile'" class="bg-white rounded-lg shadow">
              <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex justify-between items-center">
                  <h3 class="text-lg font-medium text-gray-900">Profile Information</h3>
                  <button 
                    v-if="!editing" 
                    @click="startEditing" 
                    class="text-sm text-blue-600 hover:text-blue-800"
                  >
                    Edit
                  </button>
                </div>
              </div>
              <div class="p-6">
                <div v-if="!editing">
                  <dl class="divide-y divide-gray-200">
                    <div class="py-4 grid grid-cols-3 gap-4">
                      <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                      <dd class="text-sm text-gray-900 col-span-2">{{ user.name }}</dd>
                    </div>
                    <div class="py-4 grid grid-cols-3 gap-4">
                      <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                      <dd class="text-sm text-gray-900 col-span-2">{{ user.email }}</dd>
                    </div>
                    <div class="py-4 grid grid-cols-3 gap-4">
                      <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                      <dd class="text-sm text-gray-900 col-span-2">{{ user.phone || 'Not provided' }}</dd>
                    </div>
                    <div class="py-4 grid grid-cols-3 gap-4">
                      <dt class="text-sm font-medium text-gray-500">Account Created</dt>
                      <dd class="text-sm text-gray-900 col-span-2">{{ formatDate(user.created_at) }}</dd>
                    </div>
                  </dl>
                </div>
                
                <!-- Edit Form -->
                <form v-else @submit.prevent="saveChanges" class="space-y-6">
                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input 
                      type="text" 
                      id="name"
                      v-model="editForm.name" 
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                      required
                    >
                  </div>
                  
                  <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input 
                      type="email" 
                      id="email"
                      v-model="editForm.email" 
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                      required
                    >
                  </div>
                  
                  <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input 
                      type="tel" 
                      id="phone"
                      v-model="editForm.phone" 
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                      placeholder="Enter your phone number"
                    >
                  </div>
                  
                  <div class="flex justify-end space-x-3">
                    <button 
                      type="button"
                      @click="cancelEditing" 
                      class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                      Cancel
                    </button>
                    <button 
                      type="submit"
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                      :disabled="updating"
                    >
                      <svg v-if="updating" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      {{ updating ? 'Saving...' : 'Save Changes' }}
                    </button>
                  </div>
                </form>
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
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import Header from '@/components/Header.vue';
import Footer from '@/components/layout/Footer.vue';

export default {
  name: 'Account',
  components: {
    Header,
    Footer
  },
  setup() {
    const router = useRouter();
    const user = ref(null);
    const loading = ref(true);
    const error = ref(null);
    const editing = ref(false);
    const updating = ref(false);
    const activeTab = ref('profile');
    const editForm = ref({
      name: '',
      email: '',
      phone: ''
    });

    // Fetch user data
    const fetchUserData = async () => {
      loading.value = true;
      error.value = null;
      
      try {
        const response = await axios.get('http://localhost:8000/api/user/profile');
        
        if (response.data && response.data.success) {
          user.value = response.data.data;
        } else if (response.data && response.data.data) {
          user.value = response.data.data;
        } else {
          error.value = 'Failed to load user data';
        }
      } catch (err) {
        console.error('Error fetching user data:', err);
        if (err.response && err.response.status === 401) {
          error.value = 'Please log in to view your account';
        } else {
          error.value = 'Could not fetch your account data. Please try again later.';
        }
      } finally {
        loading.value = false;
      }
    };

    // Start editing user data
    const startEditing = () => {
      editForm.value = {
        name: user.value.name,
        email: user.value.email,
        phone: user.value.phone || ''
      };
      editing.value = true;
    };

    // Cancel editing
    const cancelEditing = () => {
      editing.value = false;
    };

    // Save changes
    const saveChanges = async () => {
      if (!editing.value) return;
      
      updating.value = true;
      
      try {
        const response = await axios.post('http://localhost:8000/api/user/update-profile', editForm.value);
        
        if (response.data && (response.data.success || response.data.data)) {
          user.value = {
            ...user.value,
            ...editForm.value
          };
          editing.value = false;
          
          // Show success message in a more elegant way later
          alert('Profile updated successfully!');
        } else {
          alert('Failed to update profile. Please try again.');
        }
      } catch (err) {
        console.error('Error updating profile:', err);
        let errorMessage = 'Failed to update profile. Please try again.';
        
        if (err.response && err.response.data && err.response.data.message) {
          errorMessage = err.response.data.message;
        }
        
        alert(errorMessage);
      } finally {
        updating.value = false;
      }
    };

    // Logout function
    const logout = async () => {
      try {
        await axios.post('http://localhost:8000/api/logout');
        router.push('/login');
      } catch (error) {
        console.error('Error during logout:', error);
        router.push('/login');
      }
    };

    // Get user initials for avatar
    const getUserInitials = () => {
      if (!user.value || !user.value.name) return 'U';
      
      const nameParts = user.value.name.split(' ');
      if (nameParts.length === 1) {
        return nameParts[0].charAt(0).toUpperCase();
      }
      
      return (nameParts[0].charAt(0) + nameParts[nameParts.length - 1].charAt(0)).toUpperCase();
    };

    // Format date
    const formatDate = (dateString) => {
      if (!dateString) return 'N/A';
      
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    };

    onMounted(() => {
      fetchUserData();
    });

    return {
      user,
      loading,
      error,
      editing,
      updating,
      activeTab,
      editForm,
      fetchUserData,
      startEditing,
      cancelEditing,
      saveChanges,
      logout,
      getUserInitials,
      formatDate
    };
  }
}
</script> 