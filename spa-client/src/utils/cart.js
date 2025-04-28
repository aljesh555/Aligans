/**
 * Cart utility functions for managing the shopping cart
 */

/**
 * Add an item to the cart
 * @param {Object} product - The product to add
 * @param {Number} quantity - The quantity to add
 * @param {String} size - Optional size parameter
 * @param {String} color - Optional color parameter
 * @returns {Object} - The result of adding to cart: { success, cart, message }
 */
export const addToCart = (product, quantity = 1, size = null, color = null) => {
  try {
    // Get current cart from localStorage
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Get product stock
    const productStock = product.stock || 0;
    
    // Find if the product is already in cart with the same options
    const existingItemIndex = cart.findIndex(item => 
      item.id === product.id && 
      (size === null || item.size === size) &&
      (color === null || item.color === color)
    );
    
    // Calculate total quantity after addition
    let totalQuantity = quantity;
    if (existingItemIndex !== -1) {
      totalQuantity += cart[existingItemIndex].quantity;
    }
    
    // Validate against available stock
    if (totalQuantity > productStock) {
      // Return error if trying to add more than stock
      return {
        success: false,
        cart: cart,
        message: `Cannot add ${quantity} items to cart. Only ${productStock} available in stock.${existingItemIndex !== -1 ? ` You already have ${cart[existingItemIndex].quantity} in your cart.` : ''}`
      };
    }
    
    if (existingItemIndex !== -1) {
      // Product exists, update quantity
      cart[existingItemIndex].quantity += quantity;
    } else {
      // Add new item to cart
      cart.push({
        id: product.id,
        name: product.name,
        price: product.price,
        image: product.image || product.thumbnail,
        quantity: quantity,
        size: size,
        color: color,
        stock: productStock, // Use actual stock value
        addedAt: new Date().toISOString()
      });
    }
    
    // Save updated cart to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Notify other components that cart has been updated
    document.dispatchEvent(new Event('cart-updated'));
    
    return {
      success: true,
      cart: cart,
      message: 'Product added to cart successfully'
    };
  } catch (error) {
    console.error('Error adding item to cart:', error);
    return {
      success: false,
      cart: [],
      message: 'An error occurred while adding to cart'
    };
  }
};

/**
 * Remove an item from the cart
 * @param {Number} index - The index of the item to remove
 * @returns {Array} - The updated cart array
 */
export const removeFromCart = (index) => {
  try {
    // Get current cart from localStorage
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Remove the item at the specified index
    if (index >= 0 && index < cart.length) {
      cart.splice(index, 1);
      
      // Save updated cart to localStorage
      localStorage.setItem('cart', JSON.stringify(cart));
      
      // Notify other components that cart has been updated
      document.dispatchEvent(new Event('cart-updated'));
    }
    
    return cart;
  } catch (error) {
    console.error('Error removing item from cart:', error);
    return [];
  }
};

/**
 * Update an item's quantity in the cart
 * @param {Number} index - The index of the item to update
 * @param {Number} quantity - The new quantity
 * @returns {Array} - The updated cart array
 */
export const updateCartItemQuantity = (index, quantity) => {
  try {
    // Get current cart from localStorage
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Update the quantity of the item at the specified index
    if (index >= 0 && index < cart.length) {
      if (quantity <= 0) {
        // Remove item if quantity is zero or negative
        return removeFromCart(index);
      }
      
      cart[index].quantity = quantity;
      
      // Save updated cart to localStorage
      localStorage.setItem('cart', JSON.stringify(cart));
      
      // Notify other components that cart has been updated
      document.dispatchEvent(new Event('cart-updated'));
    }
    
    return cart;
  } catch (error) {
    console.error('Error updating cart item quantity:', error);
    return [];
  }
};

/**
 * Clear the entire cart
 * @returns {Array} - Empty array
 */
export const clearCart = () => {
  try {
    // Save empty cart to localStorage
    localStorage.setItem('cart', JSON.stringify([]));
    
    // Notify other components that cart has been updated
    document.dispatchEvent(new Event('cart-updated'));
    
    return [];
  } catch (error) {
    console.error('Error clearing cart:', error);
    return [];
  }
};

/**
 * Get the current cart contents
 * @returns {Array} - The current cart array
 */
export const getCart = () => {
  try {
    return JSON.parse(localStorage.getItem('cart')) || [];
  } catch (error) {
    console.error('Error getting cart:', error);
    return [];
  }
};

/**
 * Calculate the total number of items in the cart
 * @returns {Number} - The total number of items
 */
export const getCartItemCount = () => {
  try {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    return cart.reduce((total, item) => total + item.quantity, 0);
  } catch (error) {
    console.error('Error calculating cart item count:', error);
    return 0;
  }
};

/**
 * Calculate the number of unique products in the cart (ignoring quantity)
 * @returns {Number} - The number of unique products
 */
export const getUniqueCartItemCount = () => {
  try {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    return cart.length; // Length of the cart array = number of unique products
  } catch (error) {
    console.error('Error calculating unique cart item count:', error);
    return 0;
  }
};

/**
 * Buy Now functionality - Clear cart and add a single product for immediate checkout
 * @param {Object} product - The product to buy
 * @param {Number} quantity - The quantity to buy
 * @param {String} size - Optional size parameter
 * @param {String} color - Optional color parameter
 * @returns {Object} - The result of buying now: { success, cart, message }
 */
export const buyNow = (product, quantity = 1, size = null, color = null) => {
  try {
    // Get product stock
    const productStock = product.stock || 0;
    
    // Validate against available stock
    if (quantity > productStock) {
      return {
        success: false,
        cart: [],
        message: `Cannot buy ${quantity} items. Only ${productStock} available in stock.`
      };
    }
    
    // Clear the cart first
    localStorage.setItem('cart', JSON.stringify([]));
    
    // Create new cart with only this product
    const cart = [{
      id: product.id,
      name: product.name,
      price: product.price,
      image: product.image || product.thumbnail,
      quantity: quantity,
      size: size,
      color: color,
      stock: productStock, // Use actual stock
      addedAt: new Date().toISOString()
    }];
    
    // Save cart to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Notify other components that cart has been updated
    document.dispatchEvent(new Event('cart-updated'));
    
    return {
      success: true,
      cart: cart,
      message: 'Ready to checkout'
    };
  } catch (error) {
    console.error('Error processing buy now:', error);
    return {
      success: false,
      cart: [],
      message: 'An error occurred while processing your order'
    };
  }
};

/**
 * Calculate the subtotal of the cart
 * @returns {Number} - The cart subtotal
 */
export const getCartSubtotal = () => {
  try {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
  } catch (error) {
    console.error('Error calculating cart subtotal:', error);
    return 0;
  }
}; 