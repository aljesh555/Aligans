import axios from 'axios';

class OrderService {
  /**
   * Submit the order to the API
   * @param {Object} orderData - The order data to submit
   * @returns {Promise} - The API response
   */
  async submitOrder(orderData) {
    console.log('OrderService.submitOrder called with data:', orderData);
    
    // First check if shipping_form_id was provided in the order data
    let shippingFormId = orderData.shipping_form_id;
    
    // If not provided in the order data, try to get it from localStorage
    if (!shippingFormId) {
      shippingFormId = localStorage.getItem('shippingFormId');
    }
    
    // If still not available, try to get it from the API
    if (!shippingFormId) {
      try {
        const token = localStorage.getItem('token');
        if (token) {
          // Check for latest shipping form
          const shippingResponse = await axios.get('/api/shipping-forms/default');
          if (shippingResponse.data.success && shippingResponse.data.data) {
            shippingFormId = shippingResponse.data.data.id;
            console.log('Found shipping form ID from API:', shippingFormId);
            
            // Save it to localStorage for future use
            localStorage.setItem('shippingFormId', shippingFormId);
          }
        }
      } catch (err) {
        console.warn('Could not retrieve shipping form ID from API:', err.message);
      }
    }
    
    // If we still don't have a shipping form ID, throw an error
    if (!shippingFormId) {
      throw new Error('Shipping information is required to complete checkout');
    }
    
    // Simplify the data for our new API structure
    const simplifiedData = {
      payment_method: orderData.payment_method,
      shipping_form_id: shippingFormId,
      total_amount: orderData.total_amount,
      items: orderData.items.map(item => ({
        product_id: item.product_id || item.id,
        quantity: item.quantity,
        size: item.size,
        color: item.color,
        variant_details: item.options || {}
      }))
    };
    
    console.log('Simplified data for API:', simplifiedData);
    
    try {
      const response = await axios.post('/api/checkout', simplifiedData);
      console.log('OrderService.submitOrder successful response:', response.data);
      return response;
    } catch (error) {
      console.error('OrderService.submitOrder error:', error);
      console.error('Error response:', error.response?.data);
      throw error;
    }
  }

  /**
   * Verify eSewa payment
   * @param {Object} paymentData - The payment data to verify
   * @returns {Promise} - The API response
   */
  async verifyEsewaPayment(paymentData) {
    return axios.post('/api/payments/esewa/verify', paymentData);
  }

  /**
   * Get a list of user's orders
   * @returns {Promise} - The API response
   */
  async getUserOrders() {
    return axios.get('/api/orders');
  }

  /**
   * Get details of a specific order
   * @param {number|string} orderId - The order ID
   * @returns {Promise} - The API response
   */
  async getOrderDetails(orderId) {
    return axios.get(`/api/orders/${orderId}`);
  }

  /**
   * Generate and download an invoice for an order
   * @param {number|string} orderId - The order ID
   * @returns {Promise} - The API response
   */
  async downloadInvoice(orderId) {
    try {
      // Get the token from localStorage
      const token = localStorage.getItem('token');
      if (!token) {
        throw new Error('Authentication required. Please log in to download your invoice.');
      }
      
      // Get the order details first to ensure they exist
      await this.getOrderDetails(orderId);
      
      // Make a request to download the invoice
      const response = await axios.get(`/api/orders/${orderId}/invoice`, {
        responseType: 'blob', // Important for file downloads
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });
      
      // Check if the response is valid
      if (!response.data || response.data.size === 0) {
        throw new Error('Received empty PDF from server.');
      }
      
      // Create a URL for the blob data
      const url = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
      
      // Create a link element and trigger the download
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', `invoice-${orderId}.pdf`);
      document.body.appendChild(link);
      link.click();
      
      // Clean up
      window.URL.revokeObjectURL(url);
      document.body.removeChild(link);
      
      return true;
    } catch (error) {
      console.error('Error downloading invoice:', error);
      // Provide more specific error messages based on the type of error
      if (error.response) {
        // The request was made and the server responded with a status code
        // that falls out of the range of 2xx
        if (error.response.status === 401 || error.response.status === 403) {
          throw new Error('You are not authorized to download this invoice. Please log in again.');
        } else if (error.response.status === 404) {
          throw new Error('Invoice not found. The order might not exist or has been deleted.');
        } else {
          throw new Error(`Server error (${error.response.status}). Please try again later.`);
        }
      } else if (error.request) {
        // The request was made but no response was received
        throw new Error('No response from server. Please check your internet connection.');
      }
      throw error;
    }
  }
}

export default new OrderService(); 