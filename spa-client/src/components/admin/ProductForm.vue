<template>
  <form @submit.prevent="submitForm" class="bg-white p-6">
    <div v-if="errors.length" class="mb-4 bg-red-50 p-4 rounded-md">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
          <div class="mt-2 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
              <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Basic Info Section -->
      <div class="md:col-span-2 bg-gray-50 p-4 rounded-md">
        <h4 class="text-lg font-medium text-gray-700 mb-4">Basic Information</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Product Name -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Product Name*</label>
            <input
              id="name"
              v-model="formData.name"
              type="text"
              required
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>

          <!-- SKU -->
          <div>
            <label for="sku" class="block text-sm font-medium text-gray-700">SKU*</label>
            <input
              id="sku"
              v-model="formData.sku"
              type="text"
              required
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>

          <!-- Slug -->
          <div>
            <label for="slug" class="block text-sm font-medium text-gray-700">Slug*</label>
            <div class="mt-1 flex rounded-md shadow-sm">
              <input
                id="slug"
                v-model="formData.slug"
                type="text"
                required
                class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              />
              <button
                type="button"
                @click="generateSlug"
                class="ml-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none"
              >
                Generate
              </button>
            </div>
          </div>

          <!-- Category -->
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Category*</label>
            <select
              id="category"
              v-model="formData.category_id"
              required
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            >
              <option :value="null" disabled>Select a category</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>

          <!-- Status -->
          <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status*</label>
            <select
              id="status"
              v-model="formData.status"
              required
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            >
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
              <option value="out_of_stock">Out of Stock</option>
            </select>
          </div>

          <!-- Featured -->
          <div class="flex items-center h-full pt-6">
            <input
              id="featured"
              v-model="formData.featured"
              type="checkbox"
              class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
            />
            <label for="featured" class="ml-2 block text-sm text-gray-700">
              Featured Product
            </label>
          </div>
        </div>
      </div>

      <!-- Description Section -->
      <div class="md:col-span-2 bg-gray-50 p-4 rounded-md">
        <h4 class="text-lg font-medium text-gray-700 mb-4">Description</h4>
        
        <!-- Short Description -->
        <div class="mb-4">
          <label for="short_description" class="block text-sm font-medium text-gray-700">Short Description*</label>
          <textarea
            id="short_description"
            v-model="formData.short_description"
            rows="2"
            required
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          ></textarea>
        </div>

        <!-- Full Description -->
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700">Full Description*</label>
          <textarea
            id="description"
            v-model="formData.description"
            rows="6"
            required
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          ></textarea>
        </div>
      </div>

      <!-- Pricing Section -->
      <div class="bg-gray-50 p-4 rounded-md">
        <h4 class="text-lg font-medium text-gray-700 mb-4">Pricing</h4>

        <!-- Regular Price -->
        <div class="mb-4">
          <label for="regular_price" class="block text-sm font-medium text-gray-700">Regular Price (Rs.)*</label>
          <input
            id="regular_price"
            v-model.number="formData.regular_price"
            type="number"
            step="0.01"
            min="0"
            required
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          />
        </div>

        <!-- Sale Price -->
        <div class="mb-4">
          <label for="sale_price" class="block text-sm font-medium text-gray-700">Sale Price (Rs.)</label>
          <input
            id="sale_price"
            v-model.number="formData.sale_price"
            type="number"
            step="0.01"
            min="0"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          />
        </div>

        <!-- Cost Price -->
        <div>
          <label for="cost_price" class="block text-sm font-medium text-gray-700">Cost Price (Rs.)</label>
          <input
            id="cost_price"
            v-model.number="formData.cost_price"
            type="number"
            step="0.01"
            min="0"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          />
        </div>
      </div>

      <!-- Inventory Section -->
      <div class="bg-gray-50 p-4 rounded-md">
        <h4 class="text-lg font-medium text-gray-700 mb-4">Inventory</h4>

        <!-- Stock Quantity -->
        <div class="mb-4">
          <label for="stock" class="block text-sm font-medium text-gray-700">Stock Quantity*</label>
          <input
            id="stock"
            v-model.number="formData.stock"
            type="number"
            min="0"
            required
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          />
        </div>

        <!-- New Arrival -->
        <div class="mb-4 flex items-center">
          <input
            id="is_new_arrival"
            v-model="formData.is_new_arrival"
            type="checkbox"
            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
          />
          <label for="is_new_arrival" class="ml-2 block text-sm text-gray-700">
            Mark as New Arrival
          </label>
        </div>
      </div>

      <!-- Sale Settings -->
      <div class="bg-gray-50 p-4 rounded-md">
        <h4 class="text-lg font-medium text-gray-700 mb-4">Sale Settings</h4>

        <!-- On Sale -->
        <div class="mb-4 flex items-center">
          <input
            id="on_sale"
            v-model="formData.on_sale"
            type="checkbox"
            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
          />
          <label for="on_sale" class="ml-2 block text-sm text-gray-700">
            On Sale
          </label>
        </div>

        <!-- Discount Price -->
        <div class="mb-4" v-if="formData.on_sale">
          <label for="discount_price" class="block text-sm font-medium text-gray-700">Discount Price (Rs.)</label>
          <input
            id="discount_price"
            v-model.number="formData.discount_price"
            type="number"
            step="0.01"
            min="0"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          />
        </div>

        <!-- Discount Percentage -->
        <div class="mb-4" v-if="formData.on_sale">
          <label for="discount_percent" class="block text-sm font-medium text-gray-700">Discount Percentage (%)</label>
          <input
            id="discount_percent"
            v-model.number="formData.discount_percent"
            type="number"
            min="0"
            max="100"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          />
        </div>

        <!-- Sale End Date -->
        <div class="mb-4" v-if="formData.on_sale">
          <label for="sale_ends_at" class="block text-sm font-medium text-gray-700">Sale End Date</label>
          <input
            id="sale_ends_at"
            v-model="formData.sale_ends_at"
            type="datetime-local"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          />
        </div>
      </div>

      <!-- Images Section -->
      <div class="md:col-span-2 bg-gray-50 p-4 rounded-md">
        <h4 class="text-lg font-medium text-gray-700 mb-4">Product Images</h4>

        <!-- Image Upload -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">Upload Images</label>
          <div class="flex items-center justify-center border-2 border-dashed border-gray-300 rounded-md p-6">
            <div class="space-y-1 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <div class="flex text-sm text-gray-600">
                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                  <span>Upload a file</span>
                  <input id="file-upload" @change="handleFileUpload" multiple type="file" class="sr-only" accept="image/*" />
                </label>
                <p class="pl-1">or drag and drop</p>
              </div>
              <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
            </div>
          </div>
        </div>

        <!-- Preview Images -->
        <div v-if="previewImages.length > 0" class="mt-4">
          <h5 class="text-sm font-medium text-gray-700 mb-2">Preview</h5>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div v-for="(image, index) in previewImages" :key="index" class="relative">
              <img :src="image.url" alt="Product preview" class="w-full h-32 object-cover rounded-md" />
              <button 
                @click="removeImage(index)" 
                type="button" 
                class="absolute top-1 right-1 bg-red-500 rounded-full p-1 text-white shadow hover:bg-red-600"
              >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
              <div v-if="image.isPrimary" class="absolute bottom-1 left-1 bg-blue-500 text-white text-xs px-2 py-1 rounded-md">
                Primary
              </div>
              <button 
                v-if="!image.isPrimary" 
                @click="setPrimaryImage(index)" 
                type="button" 
                class="absolute bottom-1 left-1 bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-md hover:bg-gray-300"
              >
                Set as Primary
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Specifications Section -->
      <div class="md:col-span-2 bg-gray-50 p-4 rounded-md">
        <div class="flex justify-between items-center mb-4">
          <h4 class="text-lg font-medium text-gray-700">Specifications</h4>
          <button 
            type="button" 
            @click="addSpecification" 
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none"
          >
            Add Specification
          </button>
        </div>
        
        <div v-if="formData.specifications.length === 0" class="text-gray-500 text-sm p-4 text-center border border-gray-200 rounded-md">
          No specifications added yet. Click the "Add Specification" button to add one.
        </div>
        
        <div v-for="(spec, index) in formData.specifications" :key="index" class="mb-4 p-4 border border-gray-200 rounded-md bg-white">
          <div class="flex justify-between items-center mb-3">
            <h5 class="font-medium text-gray-700">Specification #{{ index + 1 }}</h5>
            <button 
              type="button" 
              @click="removeSpecification(index)" 
              class="text-red-600 hover:text-red-800"
            >
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label :for="`spec-name-${index}`" class="block text-sm font-medium text-gray-700">Name</label>
              <input
                :id="`spec-name-${index}`"
                v-model="spec.name"
                type="text"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              />
            </div>
            <div>
              <label :for="`spec-value-${index}`" class="block text-sm font-medium text-gray-700">Value</label>
              <input
                :id="`spec-value-${index}`"
                v-model="spec.value"
                type="text"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-6 flex justify-end">
      <button
        type="button"
        @click="$emit('cancel')"
        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
      >
        Cancel
      </button>
      <button
        type="submit"
        :disabled="submitting"
        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
      >
        {{ submitting ? 'Saving...' : (product ? 'Update Product' : 'Create Product') }}
      </button>
    </div>
  </form>
</template>

<script>
import { ref, reactive, computed, onMounted, watch } from 'vue';

export default {
  name: 'ProductForm',
  props: {
    product: {
      type: Object,
      default: null
    },
    categories: {
      type: Array,
      default: () => []
    }
  },
  emits: ['save', 'cancel'],
  setup(props, { emit }) {
    const submitting = ref(false);
    const errors = ref([]);
    const previewImages = ref([]);
    
    // Initialize form data
    const defaultFormData = {
      name: '',
      sku: '',
      slug: '',
      category_id: null,
      status: 'active',
      featured: false,
      short_description: '',
      description: '',
      regular_price: 0,
      sale_price: null,
      cost_price: null,
      stock: 0,
      is_new_arrival: false,
      on_sale: false,
      discount_price: null,
      discount_percent: null,
      sale_ends_at: null,
      images: [],
      specifications: []
    };
    
    const formData = reactive({ ...defaultFormData });
    
    // Load product data if editing
    onMounted(() => {
      if (props.product) {
        Object.keys(formData).forEach(key => {
          if (props.product[key] !== undefined) {
            formData[key] = props.product[key];
          }
        });
        
        // Handle images separately
        if (props.product.images && props.product.images.length > 0) {
          previewImages.value = props.product.images.map((image, index) => ({
            url: image.url,
            file: null,
            id: image.id,
            isPrimary: image.is_primary || index === 0
          }));
          
          formData.images = [...props.product.images]; // Clone array
        }
        
        // Handle specifications
        if (!formData.specifications || !Array.isArray(formData.specifications)) {
          formData.specifications = [];
        }
      }
    });
    
    // Generate slug from name
    const generateSlug = () => {
      if (!formData.name) {
        return;
      }
      
      formData.slug = formData.name
        .toLowerCase()
        .replace(/[^\w\s-]/g, '') // Remove special characters
        .replace(/\s+/g, '-') // Replace spaces with hyphens
        .replace(/-+/g, '-') // Replace multiple hyphens with a single one
        .trim();
    };
    
    // Watch for name changes to update SKU suggestion
    watch(() => formData.name, (newValue) => {
      if (!newValue || formData.sku) {
        return;
      }
      
      // Generate a simple SKU based on the first 3 characters of the name + random numbers
      const namePrefix = newValue.substring(0, 3).toUpperCase();
      const randomNum = Math.floor(1000 + Math.random() * 9000); // 4-digit random number
      formData.sku = `${namePrefix}-${randomNum}`;
    });
    
    // Handle file uploads
    const handleFileUpload = (event) => {
      const files = event.target.files;
      if (!files || files.length === 0) return;
      
      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (!file.type.startsWith('image/')) {
          errors.value.push(`File ${file.name} is not an image.`);
          continue;
        }
        
        const reader = new FileReader();
        reader.onload = (e) => {
          previewImages.value.push({
            url: e.target.result,
            file: file,
            isPrimary: previewImages.value.length === 0 && formData.images.length === 0
          });
        };
        reader.readAsDataURL(file);
      }
      
      event.target.value = ""; // Reset file input
    };
    
    // Remove image from preview
    const removeImage = (index) => {
      const removedImage = previewImages.value[index];
      previewImages.value.splice(index, 1);
      
      // If we removed the primary image, select a new one if available
      if (removedImage.isPrimary && previewImages.value.length > 0) {
        previewImages.value[0].isPrimary = true;
      }
      
      // If an existing image (with ID) was removed, also remove from form data
      if (removedImage.id) {
        formData.images = formData.images.filter(image => image.id !== removedImage.id);
      }
    };
    
    // Set primary image
    const setPrimaryImage = (index) => {
      previewImages.value.forEach((image, i) => {
        image.isPrimary = i === index;
      });
    };
    
    // Add a new specification
    const addSpecification = () => {
      formData.specifications.push({ name: '', value: '' });
    };
    
    // Remove specification
    const removeSpecification = (index) => {
      formData.specifications.splice(index, 1);
    };
    
    // Form validation
    const validateForm = () => {
      errors.value = [];
      
      if (!formData.name) errors.value.push("Product name is required");
      if (!formData.sku) errors.value.push("SKU is required");
      if (!formData.slug) errors.value.push("Slug is required");
      if (!formData.category_id) errors.value.push("Category is required");
      if (!formData.short_description) errors.value.push("Short description is required");
      if (!formData.description) errors.value.push("Full description is required");
      if (formData.regular_price <= 0) errors.value.push("Regular price must be greater than 0");
      if (formData.sale_price !== null && formData.sale_price <= 0) errors.value.push("Sale price must be greater than 0");
      if (formData.sale_price !== null && formData.sale_price >= formData.regular_price) errors.value.push("Sale price must be less than regular price");
      if (formData.stock < 0) errors.value.push("Stock quantity cannot be negative");
      
      return errors.value.length === 0;
    };
    
    // Form submission
    const submitForm = () => {
      if (!validateForm()) {
        return;
      }
      
      submitting.value = true;
      
      try {
        // Prepare form data
        const productData = { ...formData };
        
        // Map regular_price to price for the API
        productData.price = productData.regular_price;
        
        // Ensure discount_price is always included, even if null
        if (productData.discount_price === undefined) {
          productData.discount_price = null;
        }
        
        // Ensure discount_percent is always included, even if null
        if (productData.discount_percent === undefined) {
          productData.discount_percent = null;
        }
        
        // Prepare images for submission
        productData.images = previewImages.value.map(image => {
          if (image.file) {
            // For new images, send the file object directly
            const imageFile = image.file;
            // Tag the image with isPrimary for the API
            imageFile.isPrimary = image.isPrimary;
            return imageFile;
          } else if (image.id) {
            // For existing images, include ID and primary flag
            return {
              id: image.id,
              isPrimary: image.isPrimary
            };
          }
          return null;
        }).filter(Boolean); // Remove any nulls
        
        // Filter out empty specifications
        productData.specifications = productData.specifications.filter(
          spec => spec.name.trim() !== '' && spec.value.trim() !== ''
        );
        
        // Emit save event with product data
        emit('save', productData);
      } catch (err) {
        console.error("Error preparing form data:", err);
        errors.value.push("An error occurred while submitting the form. Please try again.");
      } finally {
        submitting.value = false;
      }
    };
    
    return {
      formData,
      submitting,
      errors,
      previewImages,
      generateSlug,
      handleFileUpload,
      removeImage,
      setPrimaryImage,
      addSpecification,
      removeSpecification,
      submitForm
    };
  }
};
</script>

<style scoped>
/* Additional specific styling for the form component */
.dragging {
  background-color: rgba(59, 130, 246, 0.1);
}
</style> 