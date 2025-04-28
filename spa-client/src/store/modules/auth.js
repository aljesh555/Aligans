import axios from 'axios';

export default {
  namespaced: true,
  state: {
    user: null,
    isLoggedIn: false,
    token: null,
    loading: false,
    error: null
  },
  getters: {
    isLoggedIn: state => state.isLoggedIn,
    user: state => state.user,
    isAdmin: state => state.user && (state.user.role === 'admin' || state.user.role === 'manager'),
    isStaff: state => state.user && ['admin', 'manager', 'staff'].includes(state.user.role),
    token: state => state.token,
    loading: state => state.loading,
    error: state => state.error
  },
  mutations: {
    setUser(state, user) {
      state.user = user;
    },
    setLoggedIn(state, value) {
      state.isLoggedIn = value;
    },
    setToken(state, token) {
      state.token = token;
    },
    setLoading(state, loading) {
      state.loading = loading;
    },
    setError(state, error) {
      state.error = error;
    },
    logout(state) {
      state.user = null;
      state.isLoggedIn = false;
      state.token = null;
    }
  },
  actions: {
    // Initialize auth state from localStorage
    initAuth({ commit }) {
      const token = localStorage.getItem('token');
      if (token) {
        commit('setToken', token);
        
        const userStr = localStorage.getItem('user');
        if (userStr) {
          try {
            const user = JSON.parse(userStr);
            commit('setUser', user);
            commit('setLoggedIn', true);
          } catch (error) {
            console.error('Error parsing user from localStorage:', error);
            // Clear invalid user data
            localStorage.removeItem('user');
          }
        }
      }
    },
    
    // Login action
    async login({ commit }, credentials) {
      commit('setLoading', true);
      commit('setError', null);
      
      try {
        // First try admin login
        try {
          const adminResponse = await axios.post('/api/admin/login', credentials);
          
          if (adminResponse.data.valid) {
            const { token, user } = adminResponse.data;
            
            // Save auth data to localStorage
            localStorage.setItem('token', token);
            localStorage.setItem('user', JSON.stringify(user));
            
            // Update state
            commit('setToken', token);
            commit('setUser', user);
            commit('setLoggedIn', true);
            
            return { user, isAdmin: true }; // Return admin flag 
          }
        } catch (adminErr) {
          // If admin login fails, try regular login
          console.log('Admin login failed, trying regular login');
        }
        
        // Regular user login
        const response = await axios.post('/api/login', credentials);
        
        const { token, user } = response.data;
        
        // Save auth data to localStorage
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(user));
        
        // Update state
        commit('setToken', token);
        commit('setUser', user);
        commit('setLoggedIn', true);
        
        return { user, isAdmin: false }; // Return regular user flag
      } catch (error) {
        const errorMessage = error.response?.data?.message || 'Login failed. Please try again.';
        commit('setError', errorMessage);
        throw error;
      } finally {
        commit('setLoading', false);
      }
    },
    
    // Admin Login action (dedicated for admin login view)
    async adminLogin({ commit }, credentials) {
      commit('setLoading', true);
      commit('setError', null);
      
      try {
        const adminResponse = await axios.post('/api/admin/login', credentials);
        
        if (adminResponse.data.valid) {
          const { token, user } = adminResponse.data;
          
          // Save auth data to localStorage
          localStorage.setItem('token', token);
          localStorage.setItem('user', JSON.stringify(user));
          
          // Update state
          commit('setToken', token);
          commit('setUser', user);
          commit('setLoggedIn', true);
          
          return { user, isAdmin: true }; 
        } else {
          throw new Error('Invalid admin credentials');
        }
      } catch (error) {
        const errorMessage = error.response?.data?.message || 'Admin login failed. Please verify your credentials.';
        commit('setError', errorMessage);
        throw error;
      } finally {
        commit('setLoading', false);
      }
    },
    
    // Logout action
    async logout({ commit }) {
      commit('setLoading', true);
      
      try {
        // If user is logged in, try to call logout endpoint
        const token = localStorage.getItem('token');
        if (token) {
          await axios.post('/api/logout', {}, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });
        }
      } catch (error) {
        console.error('Error during logout:', error);
      } finally {
        // Remove token and user from local storage
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        
        // Update state
        commit('logout');
        commit('setLoading', false);
      }
    },
    
    // Verify token action
    async verifyToken({ commit, state }) {
      if (!state.token) return false;
      
      commit('setLoading', true);
      
      try {
        const response = await axios.get('/api/user', {
          headers: {
            'Authorization': `Bearer ${state.token}`
          }
        });
        
        if (response.data && response.data.id) {
          // Update user data with the latest from the server
          localStorage.setItem('user', JSON.stringify(response.data));
          commit('setUser', response.data);
          commit('setLoggedIn', true);
          return true;
        } else {
          throw new Error('Invalid user data');
        }
      } catch (error) {
        console.error('Token verification failed:', error);
        // Clear auth state
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        commit('logout');
        return false;
      } finally {
        commit('setLoading', false);
      }
    }
  }
} 