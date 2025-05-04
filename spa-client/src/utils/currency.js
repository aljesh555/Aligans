/**
 * Currency utility functions for the Aligans Sports e-commerce platform
 * Focused on Nepali Rupees (Rs) formatting
 */

/**
 * Format a number as Nepali Rupees
 * @param {number} amount - The amount to format
 * @param {boolean} showSymbol - Whether to show the Rs symbol (default: true)
 * @returns {string} Formatted price with Rs symbol
 */
export const formatPrice = (amount, showSymbol = true) => {
  if (amount === null || amount === undefined) {
    return showSymbol ? 'Rs 0.00' : '0.00';
  }
  
  const formattedAmount = parseFloat(amount).toFixed(2);
  return showSymbol ? `Rs ${formattedAmount}` : formattedAmount;
};

/**
 * Calculate discount percentage between original and sale price
 * @param {number} originalPrice - The original price
 * @param {number} salePrice - The sale price
 * @returns {number} Discount percentage
 */
export const calculateDiscountPercentage = (originalPrice, salePrice) => {
  if (!originalPrice || !salePrice || originalPrice <= 0) {
    return 0;
  }
  
  const discount = ((originalPrice - salePrice) / originalPrice) * 100;
  return Math.round(discount);
};

export default {
  formatPrice,
  calculateDiscountPercentage
}; 