<template>
  <div class="categories-page">
    <header class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Categories</h1>
      <router-link to="/admin/categories/create" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
        Add New Category
      </router-link>
    </header>

    <!-- Loading state -->
    <div v-if="loading" class="flex justify-center">
      <div class="loader"></div>
    </div>

    <!-- Error state -->
    <div v-else-if="error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
      <p>{{ error }}</p>
    </div>

    <!-- Data table -->
    <div v-else class="bg-white shadow-md rounded-lg overflow-hidden">
      <div class="p-4 flex items-center justify-between flex-wrap gap-4 border-b">
        <div class="flex items-center w-full md:w-auto">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search categories..."
            class="px-3 py-2 border border-gray-300 rounded-md w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
            @input="debouncedSearch"
          />
        </div>
      </div>

      <!-- Categories table -->
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="categories.length === 0">
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No categories found</td>
          </tr>
          <tr v-for="category in categories" :key="category.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              {{ category.name }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
              <div>
                <img 
                  v-if="category.hasStoredImage"
                  :src="`/api/logos/${category.id}/image`" 
                  :alt="category.name"
                  class="w-12 h-12 object-cover rounded"
                />
                <img 
                  v-else-if="category.logo_url"
                  :src="category.logo_url" 
                  :alt="category.name"
                  class="w-12 h-12 object-cover rounded"
                />
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
              {{ category.slug }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
              {{ category.product_count || 0 }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="{
                'px-2 py-1 text-xs rounded-full': true,
                'bg-green-100 text-green-800': category.status === 'active',
                'bg-gray-100 text-gray-800': category.status === 'inactive'
              }">
                {{ capitalizeFirstLetter(category.status) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <router-link :to="`/admin/categories/${category.id}/edit`" class="text-blue-600 hover:text-blue-900 mr-3">
                Edit
              </router-link>
              <button @click="confirmDeleteCategory(category)" class="text-red-600 hover:text-red-900">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination controls -->
      <div class="px-6 py-3 flex items-center justify-between border-t border-gray-200">
        <div class="flex-1 flex justify-between md:hidden">
          <button
            @click="loadCategories(currentPage - 1)"
            :disabled="currentPage <= 1"
            :class="{
              'px-4 py-2 border rounded-md': true,
              'bg-gray-200 text-gray-400 cursor-not-allowed': currentPage <= 1,
              'bg-white text-gray-700 hover:bg-gray-50': currentPage > 1
            }"
          >
            Previous
          </button>
          <button
            @click="loadCategories(currentPage + 1)"
            :disabled="currentPage >= totalPages"
            :class="{
              'ml-3 px-4 py-2 border rounded-md': true,
              'bg-gray-200 text-gray-400 cursor-not-allowed': currentPage >= totalPages,
              'bg-white text-gray-700 hover:bg-gray-50': currentPage < totalPages
            }"
          >
            Next
          </button>
        </div>
        <div class="hidden md:flex-1 md:flex md:items-center md:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Showing
              <span class="font-medium">{{ (currentPage - 1) * perPage + 1 }}</span>
              to
              <span class="font-medium">{{ Math.min(currentPage * perPage, totalItems) }}</span>
              of
              <span class="font-medium">{{ totalItems }}</span>
              results
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
              <button
                @click="loadCategories(currentPage - 1)"
                :disabled="currentPage <= 1"
                :class="{
                  'relative inline-flex items-center px-2 py-2 rounded-l-md border text-sm font-medium': true,
                  'bg-gray-200 text-gray-400 cursor-not-allowed': currentPage <= 1,
                  'bg-white text-gray-500 hover:bg-gray-50': currentPage > 1
                }"
              >
                Previous
              </button>
              <button
                v-for="page in paginationPages"
                :key="page"
                @click="loadCategories(page)"
                :class="{
                  'relative inline-flex items-center px-4 py-2 border text-sm font-medium': true,
                  'bg-blue-50 border-blue-500 text-blue-600': page === currentPage,
                  'bg-white text-gray-500 hover:bg-gray-50': page !== currentPage
                }"
              >
                {{ page }}
              </button>
              <button
                @click="loadCategories(currentPage + 1)"
                :disabled="currentPage >= totalPages"
                :class="{
                  'relative inline-flex items-center px-2 py-2 rounded-r-md border text-sm font-medium': true,
                  'bg-gray-200 text-gray-400 cursor-not-allowed': currentPage >= totalPages,
                  'bg-white text-gray-500 hover:bg-gray-50': currentPage < totalPages
                }"
              >
                Next
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteConfirm" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Category</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete the category "{{ categoryToDelete?.name }}"? This action cannot be undone.
                  </p>
                  <p v-if="categoryToDelete?.product_count > 0" class="mt-2 text-sm text-red-500">
                    Warning: This category has {{ categoryToDelete.product_count }} products associated with it.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button 
              @click="deleteCategory" 
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm"
              :disabled="deleteSubmitting"
            >
              {{ deleteSubmitting ? 'Deleting...' : 'Delete' }}
            </button>
            <button 
              @click="closeDeleteConfirm" 
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
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
import { ref, reactive, computed, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'CategoriesView',
  setup() {
    // State variables
    const categories = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const searchQuery = ref('');
    const searchTimeout = ref(null);
    
    // Pagination
    const currentPage = ref(1);
    const perPage = ref(10);
    const totalItems = ref(0);
    const totalPages = ref(1);
    
    // Delete confirmation
    const showDeleteConfirm = ref(false);
    const categoryToDelete = ref(null);
    const deleteSubmitting = ref(false);
    
    // Computed properties
    const paginationPages = computed(() => {
      const pages = [];
      const maxVisiblePages = 5;
      
      if (totalPages.value <= maxVisiblePages) {
        // Show all pages if there are few
        for (let i = 1; i <= totalPages.value; i++) {
          pages.push(i);
        }
      } else {
        // Calculate which pages to show
        let startPage = Math.max(1, currentPage.value - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages.value, startPage + maxVisiblePages - 1);
        
        // Adjust if we're near the end
        if (endPage - startPage + 1 < maxVisiblePages) {
          startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        
        for (let i = startPage; i <= endPage; i++) {
          pages.push(i);
        }
      }
      
      return pages;
    });
    
    // Methods
    const loadCategories = async (page = 1) => {
      if (page < 1 || (totalPages.value > 0 && page > totalPages.value)) {
        return;
      }
      
      loading.value = true;
      error.value = null;
      currentPage.value = page;
      
      try {
        const params = {
          page: page,
          per_page: perPage.value
        };
        
        if (searchQuery.value) {
          params.search = searchQuery.value;
        }
        
        const response = await axios.get('/api/admin/categories', { 
          params,
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        
        console.log('Categories response:', response.data);
        
        categories.value = response.data.data;
        totalItems.value = response.data.meta.total;
        totalPages.value = Math.ceil(totalItems.value / perPage.value);
      } catch (err) {
        console.error('Error loading categories:', err);
        error.value = 'Failed to load categories. Please try again.';
      } finally {
        loading.value = false;
      }
    };
    
    const debouncedSearch = () => {
      if (searchTimeout.value) {
        clearTimeout(searchTimeout.value);
      }
      
      searchTimeout.value = setTimeout(() => {
        currentPage.value = 1;
        loadCategories(1);
      }, 500);
    };
    
    // Delete methods
    const confirmDeleteCategory = (category) => {
      categoryToDelete.value = category;
      showDeleteConfirm.value = true;
    };
    
    const closeDeleteConfirm = () => {
      showDeleteConfirm.value = false;
      categoryToDelete.value = null;
    };
    
    const deleteCategory = async () => {
      if (!categoryToDelete.value) return;
      
      deleteSubmitting.value = true;
      
      try {
        console.log('Deleting category:', categoryToDelete.value.id);
        
        await axios.delete(`/api/admin/categories/${categoryToDelete.value.id}`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        
        closeDeleteConfirm();
        loadCategories(currentPage.value);
      } catch (err) {
        console.error('Error deleting category:', err);
        
        // Handle validation errors from server
        if (err.response && err.response.data && err.response.data.errors) {
          error.value = err.response.data.message || 'Failed to delete category';
        } else {
          // Generic error
          error.value = 'Failed to delete category. Please try again.';
        }
      } finally {
        deleteSubmitting.value = false;
      }
    };
    
    const capitalizeFirstLetter = (string) => {
      if (!string) return '';
      return string.charAt(0).toUpperCase() + string.slice(1);
    };
    
    // Load data on component mount
    onMounted(() => {
      loadCategories();
    });
    
    return {
      // State
      categories,
      loading,
      error,
      searchQuery,
      
      // Pagination
      currentPage,
      perPage,
      totalItems,
      totalPages,
      paginationPages,
      
      // Delete confirmation
      showDeleteConfirm,
      categoryToDelete,
      deleteSubmitting,
      
      // Methods
      loadCategories,
      debouncedSearch,
      confirmDeleteCategory,
      closeDeleteConfirm,
      deleteCategory,
      capitalizeFirstLetter
    };
  }
};
</script>

<style scoped>
.loader {
  border: 4px solid rgba(0, 0, 0, 0.1);
  border-left-color: #3498db;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style> 