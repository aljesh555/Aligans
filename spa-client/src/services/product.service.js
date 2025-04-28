import axios from 'axios';
import { API_URL } from './config';

class ProductService {
  /**
   * Get all products with optional category filtering
   * @param {Number} categoryId - Optional category ID to filter products
   * @param {String} search - Optional search term to filter products
   * @param {Number} page - Page number for pagination
   * @returns {Promise} Promise with products data
   */
  async getProducts(categoryId = null, search = null, page = 1) {
    // Build query parameters in a more reliable way
    const params = { page };
    
    if (categoryId) {
      params.category_id = categoryId;
    }
    
    if (search) {
      params.search = search;
    }
    
    console.log(`Calling API with params:`, params);
    console.log(`Full URL: ${API_URL}/storefront/products`);
    
    try {
      const response = await axios.get(`${API_URL}/storefront/products`, { params });
      console.log('API responded with:', response.status);
      return response.data;
    } catch (error) {
      console.error('ProductService.getProducts error:', error);
      throw error;
    }
  }

  /**
   * Direct search for products
   * @param {String} query - Search query string
   * @param {Number} page - Page number for pagination
   * @returns {Promise} Promise with search results data
   */
  async searchProducts(query, page = 1) {
    try {
      console.log(`Performing direct search with query: "${query}"`);
      // Try the dedicated search endpoint if it exists
      const response = await axios.get(`${API_URL}/products/search`, {
        params: { query, page }
      });
      console.log('Search API responded with:', response.status);
      return response.data;
    } catch (error) {
      console.error('Direct search error:', error);
      // If direct search fails, fall back to regular products endpoint
      return this.getProducts(null, query, page);
    }
  }

  /**
   * Get a single product by ID
   * @param {Number} id - Product ID
   * @returns {Promise} Promise with product data
   */
  async getProduct(id) {
    const response = await axios.get(`${API_URL}/products/${id}`);
    return response.data.product || response.data;
  }

  /**
   * Get all product categories
   * @returns {Promise} Promise with categories data
   */
  async getCategories() {
    const response = await axios.get(`${API_URL}/storefront/categories`);
    return response.data.data || response.data.categories || [];
  }
}

export default new ProductService(); 