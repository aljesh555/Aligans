<template>
  <div>
    <!-- Notification Container -->
    <div class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start z-50">
      <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
        <Notification
          :show="notificationVisible"
          :type="notificationType"
          :title="notificationTitle"
          :message="notificationMessage"
          @close="notificationVisible = false"
        />
      </div>
    </div>

    <!-- Router View -->
    <router-view />

    <!-- Footer could go here -->
  </div>
</template>

<script>
import axios from 'axios';
import { ErrorMessage, LoadingSpinner, EmptyState } from './components/common';
import { Button, Card, Notification } from './components/ui';
import { useStore } from 'vuex';
import { onMounted } from 'vue';

export default {
  name: 'App',
  components: {
    ErrorMessage,
    LoadingSpinner,
    EmptyState,
    Button,
    Card,
    Notification
  },
  setup() {
    const store = useStore();

    // Function to check token validity
    const verifyToken = async (token) => {
      try {
        // Make a request to validate the token with the backend
        // This would typically be a dedicated endpoint that returns the user data if token is valid
        const response = await axios.get('/api/user', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        
        // If we get a successful response, the token is valid
        if (response.data && response.data.id) {
          // Update user data with the latest from the server
          localStorage.setItem('user', JSON.stringify(response.data));
          // Initialize auth state in store
          store.commit('auth/setUser', response.data);
          store.commit('auth/setLoggedIn', true);
          return true;
        } else {
          // If the response doesn't contain user data, clear auth state
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          store.commit('auth/logout');
          return false;
        }
      } catch (error) {
        console.error('Error verifying token:', error);
        // If there's an error (e.g., 401 Unauthorized), clear auth state
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        store.commit('auth/logout');
        return false;
      }
    };

    onMounted(async () => {
      // Initialize auth state from localStorage
      store.dispatch('auth/initAuth');
      
      // Check if token exists and verify with the backend
      const token = localStorage.getItem('token');
      if (token) {
        await store.dispatch('auth/verifyToken');
      }
    });

    return {
      verifyToken
    };
  },
  data() {
    return {
      notificationVisible: false,
      notificationType: '',
      notificationTitle: '',
      notificationMessage: ''
    }
  },
  methods: {
    showNotification(type, title, message) {
      this.notificationType = type;
      this.notificationTitle = title;
      this.notificationMessage = message;
      this.notificationVisible = true;
      
      // Auto-hide after 3 seconds
      setTimeout(() => {
        this.notificationVisible = false;
      }, 3000);
    }
  }
}
</script>

<style>
body {
  font-family: 'Arial', sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f9fafb;
  color: #111827;
}

.container {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
}

.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 