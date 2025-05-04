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
   * Comprehensive search across products, brands, and categories
   * @param {String} query - Search query string
   * @param {Number} page - Page number for pagination
   * @returns {Promise} Promise with comprehensive search results
   */
  async comprehensiveSearch(query, page = 1) {
    try {
      console.log(`Performing comprehensive search with query: "${query}"`);
      
      // Create an object to store all search results
      const searchResults = {
        products: [],
        brands: [],
        categories: []
      };
      
      // Search products
      try {
        const productResponse = await this.searchProducts(query, page);
        if (productResponse) {
          // Extract products based on different possible response formats
          if (productResponse.data) {
            searchResults.products = productResponse.data;
          } else if (productResponse.products) {
            searchResults.products = productResponse.products;
          } else if (Array.isArray(productResponse)) {
            searchResults.products = productResponse;
          }
        }
      } catch (err) {
        console.error('Error searching products:', err);
      }
      
      // Search brands
      try {
        const brandResponse = await axios.get(`${API_URL}/brands`, {
          params: { search: query }
        });
        
        if (brandResponse.data) {
          // Extract brands based on different possible response formats
          if (brandResponse.data.data) {
            searchResults.brands = brandResponse.data.data;
          } else if (brandResponse.data.brands) {
            searchResults.brands = brandResponse.data.brands;
          } else if (Array.isArray(brandResponse.data)) {
            searchResults.brands = brandResponse.data;
          }
        }
      } catch (err) {
        console.error('Error searching brands:', err);
      }
      
      // Search categories
      try {
        const categoryResponse = await axios.get(`${API_URL}/categories`, {
          params: { search: query }
        });
        
        if (categoryResponse.data) {
          // Extract categories based on different possible response formats
          if (categoryResponse.data.data) {
            searchResults.categories = categoryResponse.data.data;
          } else if (categoryResponse.data.categories) {
            searchResults.categories = categoryResponse.data.categories;
          } else if (Array.isArray(categoryResponse.data)) {
            searchResults.categories = categoryResponse.data;
          }
        }
      } catch (err) {
        console.error('Error searching categories:', err);
      }
      
      console.log('Comprehensive search results:', {
        productsFound: searchResults.products.length,
        brandsFound: searchResults.brands.length,
        categoriesFound: searchResults.categories.length
      });
      
      return searchResults;
    } catch (error) {
      console.error('Comprehensive search error:', error);
      throw error;
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