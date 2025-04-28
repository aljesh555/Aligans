<template>
  <div class="p-4 sm:p-6 lg:p-8">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Products</h1>
        <p class="mt-2 text-sm text-gray-700">A list of all products in your store.</p>
      </div>
      <button 
        @click="openProductModal()" 
        class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
      >
        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Add New Product
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-10">
      <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 border-l-4 border-red-400 p-4 my-6">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-red-700">{{ error }}</p>
          <button 
            @click="loadProducts(1)" 
            class="mt-2 text-sm font-medium text-red-700 hover:text-red-900"
          >
            Try again
          </button>
        </div>
      </div>
    </div>

    <!-- Data Table -->
    <div v-else>
      <!-- Search and Filter Bar -->
      <div class="flex flex-col md:flex-row gap-4 md:items-center mb-6">
        <div class="flex-1">
          <div class="relative rounded-md shadow-sm">
            <input
              v-model="searchTerm"
              type="text"
              placeholder="Search products..."
              class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-10 sm:text-sm border-gray-300 rounded-md"
            />
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>
        </div>
        <div class="w-full md:w-64">
          <select
            v-model="selectedCategory"
            class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
          >
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
        </div>
      </div>

      <!-- Products Table -->
      <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Product
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                SKU
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Category
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Price
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Stock
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody v-if="filteredProducts.length === 0" class="bg-white divide-y divide-gray-200">
            <tr>
              <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                <p v-if="searchTerm || selectedCategory">No products match your search criteria.</p>
                <p v-else>No products found. Click "Add New Product" to create one.</p>
              </td>
            </tr>
          </tbody>
          <tbody v-else class="bg-white divide-y divide-gray-200">
            <tr v-for="product in paginatedProducts" :key="product.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-10 w-10 flex-shrink-0">
                    <img class="h-10 w-10 rounded-md object-cover" :src="product.thumbnail || 'https://via.placeholder.com/60x60'" alt="" />
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ product.short_description }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ product.sku }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ getCategoryName(product.category_id) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div v-if="product.on_sale" class="text-sm">
                  <span class="text-gray-900 font-medium">Rs.{{ product.discount_price.toFixed(2) }}</span>
                  <span class="text-gray-500 line-through ml-2">Rs.{{ product.price.toFixed(2) }}</span>
                  <span v-if="product.discount_percent" class="ml-2 px-2 bg-red-100 text-red-800 text-xs rounded-full">
                    -{{ product.discount_percent }}%
                  </span>
                </div>
                <div v-else class="text-sm text-gray-900 font-medium">
                  Rs.{{ product.price.toFixed(2) }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ product.stock }}</div>
                <div v-if="product.stock <= 5" class="text-xs text-red-500">
                  Low Stock
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                      :class="{
                        'bg-green-100 text-green-800': product.status === 'active',
                        'bg-red-100 text-red-800': product.status === 'inactive',
                        'bg-yellow-100 text-yellow-800': product.stock === 0
                      }">
                  {{ product.status === 'active' ? 'Active' : 
                     product.status === 'inactive' ? 'Inactive' : 'Out of Stock' }}
                </span>
                <span v-if="product.featured" class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                  Featured
                </span>
                <span v-if="product.is_new_arrival" class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                  New
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button 
                  @click="openProductModal(product)" 
                  class="text-blue-600 hover:text-blue-900 mr-3"
                >
                  Edit
                </button>
                <button 
                  @click="confirmDelete(product)" 
                  class="text-red-600 hover:text-red-900"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Controls -->
      <div v-if="totalPages > 1" class="flex items-center justify-between py-3 mt-4">
        <div class="flex-1 flex justify-between sm:hidden">
          <button
            @click="currentPage > 1 && (currentPage--)"
            :disabled="currentPage === 1"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
          >
            Previous
          </button>
          <button
            @click="currentPage < totalPages && (currentPage++)"
            :disabled="currentPage === totalPages"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
          >
            Next
          </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Showing
              <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
              to
              <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredProducts.length) }}</span>
              of
              <span class="font-medium">{{ filteredProducts.length }}</span>
              results
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <button
                @click="currentPage > 1 && (currentPage--)"
                :disabled="currentPage === 1"
                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
              >
                <span class="sr-only">Previous</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
              </button>
              <template v-for="page in paginationRange" :key="page">
                <button
                  v-if="page !== '...'"
                  @click="currentPage = page"
                  :class="[
                    page === currentPage
                      ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                  ]"
                >
                  {{ page }}
                </button>
                <span
                  v-else
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                >
                  ...
                </span>
              </template>
              <button
                @click="currentPage < totalPages && (currentPage++)"
                :disabled="currentPage === totalPages"
                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
              >
                <span class="sr-only">Next</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Product Form Modal -->
    <div v-if="productModalOpen" class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closeProductModal"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
          <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              {{ editingProduct ? 'Edit Product' : 'Add New Product' }}
            </h3>
            <button @click="closeProductModal" class="text-gray-400 hover:text-gray-500">
              <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="max-h-[80vh] overflow-y-auto">
            <product-form
              :product="editingProduct"
              :categories="categories"
              @save="saveProduct"
              @cancel="closeProductModal"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="deleteModalOpen" class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="cancelDelete"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                  Delete Product
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete the product "{{ productToDelete?.name }}"? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              @click="deleteProduct"
              type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              :disabled="deleting"
            >
              {{ deleting ? 'Deleting...' : 'Delete' }}
            </button>
            <button
              @click="cancelDelete"
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import ProductForm from '@/components/admin/ProductForm.vue';
import AdminProductService from '@/services/adminProduct.service';

export default {
  name: 'AdminProducts',
  components: {
    ProductForm
  },
  setup() {
    // State
    const products = ref([]);
    const categories = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const searchTerm = ref('');
    const selectedCategory = ref('');
    const productModalOpen = ref(false);
    const editingProduct = ref(null);
    const deleteModalOpen = ref(false);
    const productToDelete = ref(null);
    const deleting = ref(false);
    
    // Pagination
    const currentPage = ref(1);
    const itemsPerPage = 10;
    
    // Load products and categories
    onMounted(async () => {
      try {
        await Promise.all([
          loadProducts(),
          loadCategories()
        ]);
      } catch (err) {
        console.error('Error during initial data loading:', err);
      }
    });
    
    // Watch for search or category changes to reset pagination
    watch([searchTerm, selectedCategory], () => {
      currentPage.value = 1;
    });
    
    // Filtered products based on search and category
    const filteredProducts = computed(() => {
      return products.value.filter(product => {
        // Filter by search term
        const matchesSearch = !searchTerm.value || 
          product.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
          product.sku.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
          product.short_description.toLowerCase().includes(searchTerm.value.toLowerCase());
        
        // Filter by category
        const matchesCategory = !selectedCategory.value || product.category_id == selectedCategory.value;
        
        return matchesSearch && matchesCategory;
      });
    });
    
    // Paginated products
    const paginatedProducts = computed(() => {
      const start = (currentPage.value - 1) * itemsPerPage;
      const end = start + itemsPerPage;
      return filteredProducts.value.slice(start, end);
    });
    
    // Calculate total pages
    const totalPages = computed(() => {
      return Math.ceil(filteredProducts.value.length / itemsPerPage);
    });
    
    // Calculate pagination range to display
    const paginationRange = computed(() => {
      if (totalPages.value <= 7) {
        return Array.from({ length: totalPages.value }, (_, i) => i + 1);
      }
      
      // Always include first and last page
      const range = [1];
      
      if (currentPage.value <= 3) {
        range.push(2, 3, 4, '...', totalPages.value);
      } else if (currentPage.value >= totalPages.value - 2) {
        range.push(
          '...',
          totalPages.value - 3,
          totalPages.value - 2,
          totalPages.value - 1,
          totalPages.value
        );
      } else {
        range.push(
          '...',
          currentPage.value - 1,
          currentPage.value,
          currentPage.value + 1,
          '...',
          totalPages.value
        );
      }
      
      return range;
    });
    
    // Helper to get category name
    const getCategoryName = (categoryId) => {
      const category = categories.value.find(c => c.id === categoryId);
      return category ? category.name : 'Uncategorized';
    };
    
    // API Functions
    
    // Load products
    const loadProducts = async (page = 1) => {
      loading.value = true;
      error.value = null;
      
      try {
        const response = await AdminProductService.getProducts();
        products.value = response.data;
      } catch (err) {
        console.error('Error loading products:', err);
        error.value = 'Failed to load products. Please try again.';
      } finally {
        loading.value = false;
      }
    };
    
    // Load categories
    const loadCategories = async () => {
      try {
        const response = await axios.get('/api/categories');
        categories.value = response.data.data;
      } catch (err) {
        console.error('Error loading categories:', err);
        // Not setting main error since categories are secondary
      }
    };
    
    // Modal Functions
    
    // Open product form modal
    const openProductModal = (product = null) => {
      editingProduct.value = product;
      productModalOpen.value = true;
    };
    
    // Close product form modal
    const closeProductModal = () => {
      productModalOpen.value = false;
      editingProduct.value = null;
    };
    
    // Confirm delete
    const confirmDelete = (product) => {
      productToDelete.value = product;
      deleteModalOpen.value = true;
    };
    
    // Cancel delete
    const cancelDelete = () => {
      deleteModalOpen.value = false;
      productToDelete.value = null;
    };
    
    // Product CRUD
    
    // Save product (create or update)
    const saveProduct = async (productData) => {
      loading.value = true;
      
      try {
        if (editingProduct.value) {
          // Update existing product
          await AdminProductService.updateProduct(editingProduct.value.id, productData);
        } else {
          // Create new product
          await AdminProductService.createProduct(productData);
        }
        
        // Reload products and close modal
        await loadProducts();
        closeProductModal();
      } catch (err) {
        console.error('Error saving product:', err);
        alert(err.response?.data?.message || 'Failed to save product. Please try again.');
      } finally {
        loading.value = false;
      }
    };
    
    // Delete product
    const deleteProduct = async () => {
      if (!productToDelete.value) return;
      
      deleting.value = true;
      
      try {
        await AdminProductService.deleteProduct(productToDelete.value.id);
        
        // Remove from local array instead of reloading all products
        products.value = products.value.filter(p => p.id !== productToDelete.value.id);
        
        // Close modal
        cancelDelete();
      } catch (err) {
        console.error('Error deleting product:', err);
        alert(err.response?.data?.message || 'Failed to delete product. Please try again.');
      } finally {
        deleting.value = false;
      }
    };
    
    return {
      // State
      products,
      categories,
      loading,
      error,
      searchTerm,
      selectedCategory,
      productModalOpen,
      editingProduct,
      deleteModalOpen,
      productToDelete,
      deleting,
      
      // Computed
      filteredProducts,
      paginatedProducts,
      totalPages,
      currentPage,
      paginationRange,
      
      // Methods
      loadProducts,
      getCategoryName,
      openProductModal,
      closeProductModal,
      confirmDelete,
      cancelDelete,
      saveProduct,
      deleteProduct
    };
  }
};
</script>

<style scoped>
/* Empty by design - Tailwind handles most styling */
</style> 