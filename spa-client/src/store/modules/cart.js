import axios from 'axios';

const state = {
  items: JSON.parse(localStorage.getItem('cartItems')) || [],
  subtotal: 0,
  loading: false,
  error: null
};

const getters = {
  cartItems: state => state.items,
  cartItemCount: state => state.items.reduce((count, item) => count + item.quantity, 0),
  cartSubtotal: state => state.items.reduce((total, item) => total + (item.price * item.quantity), 0),
  isCartEmpty: state => state.items.length === 0,
  isLoading: state => state.loading,
  error: state => state.error
};

const mutations = {
  SET_CART_ITEMS(state, items) {
    state.items = items;
    localStorage.setItem('cartItems', JSON.stringify(items));
  },
  ADD_TO_CART(state, payload) {
    const { product, quantity } = payload;
    const existingItem = state.items.find(item => item.id === product.id);
    
    if (existingItem) {
      existingItem.quantity += quantity;
    } else {
      state.items.push({
        id: product.id,
        name: product.name,
        price: product.on_sale ? product.discount_price : product.price,
        image_url: product.image_url,
        quantity
      });
    }
    
    localStorage.setItem('cartItems', JSON.stringify(state.items));
  },
  UPDATE_CART_ITEM(state, { id, quantity }) {
    const item = state.items.find(item => item.id === id);
    if (item) {
      item.quantity = quantity;
    }
    localStorage.setItem('cartItems', JSON.stringify(state.items));
  },
  REMOVE_FROM_CART(state, productId) {
    state.items = state.items.filter(item => item.id !== productId);
    localStorage.setItem('cartItems', JSON.stringify(state.items));
  },
  CLEAR_CART(state) {
    state.items = [];
    localStorage.removeItem('cartItems');
  },
  SET_LOADING(state, isLoading) {
    state.loading = isLoading;
  },
  SET_ERROR(state, error) {
    state.error = error;
  }
};

const actions = {
  // Add to cart (works for both guest and authenticated users)
  addToCart({ commit, rootState }, { product, quantity = 1 }) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    
    try {
      // For authenticated users, sync with the backend
      if (rootState.auth.isLoggedIn) {
        // We'll handle server sync in a separate action
        // But still update local cart immediately for responsiveness
        commit('ADD_TO_CART', { product, quantity });
        dispatch('syncCartWithServer');
      } else {
        // For guests, just update the local cart
        commit('ADD_TO_CART', { product, quantity });
      }
      
      commit('SET_LOADING', false);
    } catch (error) {
      commit('SET_ERROR', error.message || 'Failed to add item to cart');
      commit('SET_LOADING', false);
      throw error;
    }
  },
  
  // Update cart item quantity
  updateCartItem({ commit, dispatch, rootState }, { id, quantity }) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    
    try {
      // Update local cart
      commit('UPDATE_CART_ITEM', { id, quantity });
      
      // For authenticated users, sync with the backend
      if (rootState.auth.isLoggedIn) {
        dispatch('syncCartWithServer');
      }
      
      commit('SET_LOADING', false);
    } catch (error) {
      commit('SET_ERROR', error.message || 'Failed to update cart item');
      commit('SET_LOADING', false);
      throw error;
    }
  },
  
  // Remove item from cart
  removeFromCart({ commit, dispatch, rootState }, productId) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    
    try {
      // Remove from local cart
      commit('REMOVE_FROM_CART', productId);
      
      // For authenticated users, sync with the backend
      if (rootState.auth.isLoggedIn) {
        dispatch('syncCartWithServer');
      }
      
      commit('SET_LOADING', false);
    } catch (error) {
      commit('SET_ERROR', error.message || 'Failed to remove item from cart');
      commit('SET_LOADING', false);
      throw error;
    }
  },
  
  // Clear cart
  clearCart({ commit }) {
    commit('CLEAR_CART');
  },
  
  // For authenticated users - sync local cart with the server
  async syncCartWithServer({ state, commit, rootState }) {
    if (!rootState.auth.isLoggedIn) return;
    
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    
    try {
      // Get the server cart first
      const response = await axios.get('/api/carts');
      
      // For each item in local cart, add or update in server cart
      for (const item of state.items) {
        await axios.post('/api/carts/add-item', {
          product_id: item.id,
          quantity: item.quantity
        });
      }
      
      commit('SET_LOADING', false);
    } catch (error) {
      commit('SET_ERROR', error.message || 'Failed to sync cart with server');
      commit('SET_LOADING', false);
      console.error('Error syncing cart with server:', error);
    }
  },
  
  // Get cart from server after login
  async fetchCart({ commit }) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);
    
    try {
      const response = await axios.get('/api/carts');
      
      if (response.data.data && response.data.data.cartItems) {
        // Transform server cart items to match our local format
        const serverItems = response.data.data.cartItems.map(item => ({
          id: item.product_id,
          name: item.product.name,
          price: item.product.on_sale ? item.product.discount_price : item.product.price,
          image_url: item.product.image_url,
          quantity: item.quantity
        }));
        
        commit('SET_CART_ITEMS', serverItems);
      }
      
      commit('SET_LOADING', false);
    } catch (error) {
      commit('SET_ERROR', error.message || 'Failed to fetch cart');
      commit('SET_LOADING', false);
      console.error('Error fetching cart:', error);
    }
  },
  
  // Merge guest cart with user cart after login
  async mergeGuestCart({ state, dispatch }) {
    if (state.items.length === 0) {
      // If guest cart is empty, just fetch the server cart
      await dispatch('fetchCart');
    } else {
      // If guest has items, sync them to the server and then fetch the updated cart
      await dispatch('syncCartWithServer');
    }
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}; 