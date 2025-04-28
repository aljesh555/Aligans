import axios from 'axios';
import { API_URL } from './config';

class AdminProductService {
  /**
   * Get all products for admin
   * @returns {Promise} Promise with products data
   */
  async getProducts() {
    const response = await axios.get(`${API_URL}/admin/products`);
    return response.data;
  }

  /**
   * Get a single product by ID for admin
   * @param {Number} id - Product ID
   * @returns {Promise} Promise with product data
   */
  async getProduct(id) {
    const response = await axios.get(`${API_URL}/admin/products/${id}`);
    return response.data;
  }

  /**
   * Create a new product
   * @param {Object} productData - Product data to create
   * @returns {Promise} Promise with created product data
   */
  async createProduct(productData) {
    const formData = this.prepareFormData(productData);
    const response = await axios.post(`${API_URL}/admin/products`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    return response.data;
  }

  /**
   * Update an existing product
   * @param {Number} id - Product ID to update
   * @param {Object} productData - Updated product data
   * @returns {Promise} Promise with updated product data
   */
  async updateProduct(id, productData) {
    const formData = this.prepareFormData(productData);
    formData.append('_method', 'PUT'); // Laravel method spoofing
    
    const response = await axios.post(`${API_URL}/admin/products/${id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    return response.data;
  }

  /**
   * Delete a product
   * @param {Number} id - Product ID to delete
   * @returns {Promise} Promise with deletion status
   */
  async deleteProduct(id) {
    const response = await axios.delete(`${API_URL}/admin/products/${id}`);
    return response.data;
  }

  /**
   * Prepare form data from product object
   * @param {Object} product - Product data
   * @returns {FormData} FormData object with product data
   */
  prepareFormData(product) {
    const formData = new FormData();
    
    // Add all the regular form fields (except images and specifications)
    Object.keys(product).forEach(key => {
      if (key !== 'images' && key !== 'specifications') {
        if (product[key] !== null && product[key] !== undefined) {
          if (typeof product[key] === 'boolean') {
            formData.append(key, product[key] ? '1' : '0');
          } else {
            formData.append(key, product[key]);
          }
        }
      }
    });
    
    // Handle images - check if they are files or existing images
    if (product.images && product.images.length > 0) {
      product.images.forEach((image, index) => {
        // If it's a File object
        if (image instanceof File) {
          formData.append(`images[${index}]`, image);
        }
        // If it's an object with a file property (from the form)
        else if (image.file && image.file instanceof File) {
          formData.append(`images[${index}]`, image.file);
          if (image.isPrimary) {
            formData.append('primary_image_index', index);
          }
        }
        // If it's an existing image with an ID
        else if (image.id) {
          formData.append(`existing_images[${index}]`, image.id);
          if (image.isPrimary) {
            formData.append('primary_existing_image', image.id);
          }
        }
      });
    }
    
    // Add specifications
    if (product.specifications && product.specifications.length > 0) {
      product.specifications.forEach((spec, index) => {
        if (spec.name && spec.value) {
          formData.append(`specifications[${index}][name]`, spec.name);
          formData.append(`specifications[${index}][value]`, spec.value);
        }
      });
    }
    
    return formData;
  }
}

export default new AdminProductService(); 