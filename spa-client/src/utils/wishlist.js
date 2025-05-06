/**
 * Wishlist utility functions for managing the wishlist
 */
import axios from 'axios';
import { getToken, isAuthenticated } from './auth';

/**
 * Add an item to the wishlist
 * @param {Object} product - The product to add
 * @returns {Array} - The updated wishlist array
 */
export const addToWishlist = async (product) => {
  try {
    // Ensure we have a consistent image field
    const productWithImage = {
      ...product,
      // Prioritize image field, then image_url, then thumbnail
      image: product.image || product.image_url || product.thumbnail
    };
    
    console.log('Adding to wishlist with image:', productWithImage.image);
    
    if (isAuthenticated()) {
      // Authenticated users: Use the API
      const response = await axios.post('/api/wishlist', {
        product_id: productWithImage.id
      }, {
        headers: {
          'Authorization': `Bearer ${getToken()}`
        }
      });
      
      // Add to localStorage as well for faster access
      const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
      const existingIndex = wishlist.findIndex(item => item.id === productWithImage.id);
      
      if (existingIndex === -1) {
        wishlist.push({
          id: productWithImage.id,
          name: productWithImage.name,
          price: productWithImage.price,
          image: productWithImage.image,
          addedAt: new Date().toISOString()
        });
        
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
      }
      
      // Notify other components that wishlist has been updated
      document.dispatchEvent(new Event('wishlist-updated'));
      
      return response.data.data;
    } else {
      // Non-authenticated users: Use localStorage
      const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
      
      // Check if product is already in wishlist
      const existingIndex = wishlist.findIndex(item => item.id === productWithImage.id);
      
      // Only add if it doesn't exist
      if (existingIndex === -1) {
        wishlist.push({
          id: productWithImage.id,
          name: productWithImage.name,
          price: productWithImage.price,
          image: productWithImage.image,
          addedAt: new Date().toISOString()
        });
        
        // Save updated wishlist to localStorage
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        
        // Notify other components that wishlist has been updated
        document.dispatchEvent(new Event('wishlist-updated'));
      }
      
      return wishlist;
    }
  } catch (error) {
    console.error('Error adding item to wishlist:', error);
    return [];
  }
};

/**
 * Remove an item from the wishlist
 * @param {Number} productId - The ID of the product to remove
 * @returns {Array} - The updated wishlist array
 */
export const removeFromWishlist = async (productId) => {
  try {
    if (isAuthenticated()) {
      // Authenticated users: Use the API
      await axios.delete(`/api/wishlist/${productId}`, {
        headers: {
          'Authorization': `Bearer ${getToken()}`
        }
      });
      
      // Notify other components that wishlist has been updated
      document.dispatchEvent(new Event('wishlist-updated'));
      
      // Get the updated wishlist
      return await getWishlist();
    } else {
      // Non-authenticated users: Use localStorage
      const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
      
      // Filter out the item with the specified ID
      const updatedWishlist = wishlist.filter(item => item.id !== productId);
      
      // Save updated wishlist to localStorage
      localStorage.setItem('wishlist', JSON.stringify(updatedWishlist));
      
      // Notify other components that wishlist has been updated
      document.dispatchEvent(new Event('wishlist-updated'));
      
      return updatedWishlist;
    }
  } catch (error) {
    console.error('Error removing item from wishlist:', error);
    return [];
  }
};

/**
 * Check if a product is in the wishlist
 * @param {Number} productId - The ID of the product to check
 * @returns {Boolean} - True if product is in wishlist, false otherwise
 */
export const isInWishlist = async (productId) => {
  try {
    if (isAuthenticated()) {
      // Authenticated users: Use the API
      const response = await axios.get(`/api/wishlist/check/${productId}`, {
        headers: {
          'Authorization': `Bearer ${getToken()}`
        }
      });
      
      return response.data.in_wishlist;
    } else {
      // Non-authenticated users: Use localStorage
      const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
      
      // Check if product is in wishlist
      return wishlist.some(item => item.id === productId);
    }
  } catch (error) {
    console.error('Error checking if item is in wishlist:', error);
    return false;
  }
};

/**
 * Sync version of isInWishlist for immediate UI updates
 * @param {Number} productId - The ID of the product to check
 * @returns {Boolean} - True if product is in wishlist, false otherwise
 */
export const isInWishlistSync = (productId) => {
  try {
    // For sync operations we always check localStorage as fallback
    const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    return wishlist.some(item => item.id === productId);
  } catch (error) {
    console.error('Error checking if item is in wishlist (sync):', error);
    return false;
  }
};

/**
 * Toggle a product in the wishlist (add if not present, remove if present)
 * @param {Object} product - The product to toggle
 * @returns {Boolean} - True if product was added, false if removed
 */
export const toggleWishlistItem = async (product) => {
  try {
    const inWishlist = await isInWishlist(product.id);
    
    if (inWishlist) {
      await removeFromWishlist(product.id);
      return false; // Item was removed
    } else {
      await addToWishlist(product);
      return true; // Item was added
    }
  } catch (error) {
    console.error('Error toggling wishlist item:', error);
    return false;
  }
};

/**
 * Get the current wishlist contents
 * @returns {Array} - The current wishlist array
 */
export const getWishlist = async () => {
  try {
    if (isAuthenticated()) {
      // Authenticated users: Use the API
      const response = await axios.get('/api/wishlist', {
        headers: {
          'Authorization': `Bearer ${getToken()}`
        }
      });
      
      // Store in localStorage as well for faster access
      const wishlistItems = response.data.data.map(item => {
        // Make sure we have a valid image URL
        let imageUrl = item.product.image_url || item.product.thumbnail;
        
        // Format image URL if needed
        if (imageUrl && !imageUrl.startsWith('http')) {
          imageUrl = `http://localhost:8000/storage/${imageUrl}`;
        }
        
        return {
          id: item.product_id,
          name: item.product.name,
          price: item.product.price,
          image: imageUrl,
          addedAt: item.created_at
        };
      });
      
      localStorage.setItem('wishlist', JSON.stringify(wishlistItems));
      
      return wishlistItems;
    } else {
      // Non-authenticated users: Use localStorage
      const items = JSON.parse(localStorage.getItem('wishlist')) || [];
      
      // Ensure all items have proper image URLs
      return items.map(item => {
        // Make sure we have a valid image URL
        let imageUrl = item.image;
        
        // Format image URL if needed
        if (imageUrl && !imageUrl.startsWith('http')) {
          imageUrl = `http://localhost:8000/storage/${imageUrl}`;
        }
        
        return {
          ...item,
          image: imageUrl
        };
      });
    }
  } catch (error) {
    console.error('Error getting wishlist:', error);
    // Fall back to localStorage if API fails
    const items = JSON.parse(localStorage.getItem('wishlist')) || [];
    
    // Ensure all items have proper image URLs
    return items.map(item => {
      // Make sure we have a valid image URL
      let imageUrl = item.image;
      
      // Format image URL if needed
      if (imageUrl && !imageUrl.startsWith('http')) {
        imageUrl = `http://localhost:8000/storage/${imageUrl}`;
      }
      
      return {
        ...item,
        image: imageUrl
      };
    });
  }
};

/**
 * Get wishlist synchronously (for immediate UI updates)
 * @returns {Array} - The current wishlist array from localStorage
 */
export const getWishlistSync = () => {
  try {
    return JSON.parse(localStorage.getItem('wishlist')) || [];
  } catch (error) {
    console.error('Error getting wishlist synchronously:', error);
    return [];
  }
};

/**
 * Clear the entire wishlist
 * @returns {Array} - Empty array
 */
export const clearWishlist = async () => {
  try {
    if (isAuthenticated()) {
      // Authenticated users: Remove each item individually
      const wishlist = await getWishlist();
      
      for (const item of wishlist) {
        await removeFromWishlist(item.id);
      }
    }
    
    // Always clear localStorage
    localStorage.setItem('wishlist', JSON.stringify([]));
    
    // Notify other components that wishlist has been updated
    document.dispatchEvent(new Event('wishlist-updated'));
    
    return [];
  } catch (error) {
    console.error('Error clearing wishlist:', error);
    return [];
  }
};

/**
 * Get the wishlist count
 * @returns {Number} - The number of items in the wishlist
 */
export const getWishlistCount = async () => {
  try {
    const wishlist = await getWishlist();
    return wishlist.length;
  } catch (error) {
    console.error('Error getting wishlist count:', error);
    return 0;
  }
};

/**
 * Get wishlist count synchronously (for immediate UI updates)
 * @returns {Number} - The number of items in the wishlist from localStorage
 */
export const getWishlistCountSync = () => {
  try {
    const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    return wishlist.length;
  } catch (error) {
    console.error('Error getting wishlist count synchronously:', error);
    return 0;
  }
}; 