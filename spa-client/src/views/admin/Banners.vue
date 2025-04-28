<template>
  <div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-800">Banner Management</h1>
      <button 
        @click="showAddBannerModal" 
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
        aria-label="Add new banner"
      >
        Add New Banner
      </button>
    </div>

    <!-- Loading state -->
    <div v-if="loading" class="flex justify-center my-12">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
    </div>

    <!-- Error state -->
    <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded my-4">
      <p>{{ error }}</p>
    </div>

    <!-- Banner list -->
    <div v-else class="bg-white shadow rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="banner in banners" :key="banner.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <img :src="banner.image_url" alt="Banner" class="h-16 w-auto object-cover rounded-md">
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              <div class="max-w-xs truncate">
                <a v-if="banner.image_link" :href="banner.image_link" target="_blank" class="text-blue-600 hover:underline">
                  {{ banner.image_link }}
                </a>
                <span v-else class="text-gray-400">No link</span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span 
                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                :class="banner.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
              >
                {{ banner.is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              <div v-if="banner.starts_at || banner.ends_at" class="flex flex-col">
                <span v-if="banner.starts_at">From: {{ formatDate(banner.starts_at) }}</span>
                <span v-if="banner.ends_at">To: {{ formatDate(banner.ends_at) }}</span>
              </div>
              <span v-else>Always</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ banner.order || 0 }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
              <button 
                @click="editBanner(banner)"
                class="text-blue-600 hover:text-blue-900"
              >
                Edit
              </button>
              <button 
                @click="toggleBannerStatus(banner)" 
                class="text-indigo-600 hover:text-indigo-900"
              >
                {{ banner.is_active ? 'Deactivate' : 'Activate' }}
              </button>
              <button 
                @click="confirmDeleteBanner(banner)" 
                class="text-red-600 hover:text-red-900"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal with transitions and accessibility -->
    <transition name="modal">
      <div v-if="showAddModal || showEditModal" 
           class="fixed inset-0 z-50 overflow-auto modal-container"
           role="dialog"
           aria-modal="true"
           :aria-label="showEditModal ? 'Edit Banner' : 'Add New Banner'"
           @click.self="closeModal">
        <div class="min-h-screen px-2 sm:px-4 py-6 sm:py-8 text-center flex items-center justify-center">
          <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-black opacity-60"></div>
          </div>
          
          <!-- Modal panel with transitions -->
          <transition name="modal-content">
            <div ref="modalRef"
                 class="bg-white rounded-lg text-left w-full max-w-lg shadow-2xl modal-panel max-h-[90vh] overflow-y-auto transform custom-scrollbar relative mx-auto"
                 @click.stop>
              <!-- Header with sticky positioning -->
              <div class="flex justify-between items-center border-b pb-4 sticky top-0 bg-white z-10 px-4 sm:px-6 pt-4 sm:pt-6">
                <h2 class="text-xl font-bold text-gray-800" id="modal-title">{{ showEditModal ? 'Edit Banner' : 'Add New Banner' }}</h2>
                <button ref="closeButtonRef"
                        @click="closeModal" 
                        class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300 rounded-full p-1 transition-colors"
                        aria-label="Close dialog">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              
              <!-- Form content -->
              <div class="px-4 sm:px-6 py-4">
                <!-- Image upload -->
                <div class="mb-5">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="banner">
                    Banner Image <span v-if="showAddModal" class="text-red-500">*</span>
                  </label>
                  <div 
                    :class="uploadAreaClasses"
                    class="border-2 border-dashed border-gray-300 rounded-lg px-6 py-8 text-center cursor-pointer hover:border-indigo-500 transition-colors"
                    @click="$refs.fileInput.click()"
                    @dragover="handleDragOver"
                    @dragleave="handleDragLeave"
                    @drop="handleDrop"
                  >
                    <input 
                      ref="fileInput"
                      @change="handleFileUpload"
                      class="hidden" 
                      id="banner" 
                      type="file" 
                      accept="image/*"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-600">
                      <span v-if="bannerFile">Selected: {{ bannerFile.name }}</span>
                      <span v-else>
                        <span class="text-indigo-600 font-medium">Click to upload</span> or drag and drop
                      </span>
                    </p>
                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                  </div>
                  <p v-if="formErrors.image" class="text-red-500 text-xs mt-1">{{ formErrors.image }}</p>
                  
                  <!-- Dimension guidance -->
                  <div class="mt-2 p-3 bg-blue-50 border border-blue-100 rounded-md">
                    <p class="text-sm text-blue-800 font-medium">Recommended Banner Dimensions:</p>
                    <p class="text-xs text-blue-700 mt-1">
                      <strong>Ideal:</strong> 1920px × 600px (16:5 ratio)<br>
                      <strong>Minimum:</strong> 1200px × 400px
                    </p>
                    <p class="text-xs text-blue-600 mt-1">
                      For best results, ensure important content is centered or in the left third of the image.
                    </p>
                  </div>
                  
                  <div v-if="showEditModal && currentBanner.image_url" class="mt-4">
                    <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                    <img :src="currentBanner.image_url" alt="Current banner" class="h-24 object-cover rounded-md">
                  </div>
                </div>

                <!-- Image Link -->
                <div class="mb-5">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="image_link">
                    Image Link
                  </label>
                  <input 
                    v-model="currentBanner.image_link"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                    id="image_link" 
                    type="text" 
                    placeholder="https://example.com/page"
                  >
                  <p class="text-sm text-gray-500 mt-1">Link to navigate when the banner is clicked</p>
                </div>

                <!-- Date range -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5 form-grid">
                  <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="starts_at">
                      Start Date
                    </label>
                    <input 
                      v-model="currentBanner.starts_at"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                      id="starts_at" 
                      type="date"
                    >
                  </div>
                  <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="ends_at">
                      End Date
                    </label>
                    <input 
                      v-model="currentBanner.ends_at"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                      id="ends_at" 
                      type="date"
                    >
                  </div>
                </div>

                <!-- Display order -->
                <div class="mb-5">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="order">
                    Display Order
                  </label>
                  <input 
                    v-model="currentBanner.order"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                    id="order" 
                    type="number" 
                    min="0"
                    placeholder="0"
                  >
                  <p class="text-sm text-gray-500 mt-1">Lower numbers display first</p>
                </div>
                
                <!-- Active checkbox -->
                <div class="mb-5">
                  <label class="flex items-center space-x-3">
                    <input 
                      v-model="currentBanner.is_active" 
                      type="checkbox" 
                      class="form-checkbox h-5 w-5 text-indigo-600 rounded"
                    >
                    <span class="text-gray-700 font-medium">Active</span>
                  </label>
                </div>
                
                <!-- Footer with action buttons -->
                <div class="flex justify-end pt-4 border-t sticky bottom-0 bg-white z-10 mt-6">
                  <button 
                    @click="closeModal" 
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-6 rounded-md mr-3 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400"
                  >
                    Cancel
                  </button>
                  <button 
                    @click="saveBanner" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-md shadow-md hover:shadow-lg transition-all flex items-center focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    :disabled="isSubmitting"
                  >
                    <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ showEditModal ? 'Update' : 'Add' }} Banner
                  </button>
                </div>
              </div>
            </div>
          </transition>
        </div>
      </div>
    </transition>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Confirm Delete</h2>
        <p class="mb-4">Are you sure you want to delete this banner?</p>
        
        <div class="flex justify-end">
          <button 
            @click="showDeleteModal = false" 
            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2"
          >
            Cancel
          </button>
          <button 
            @click="deleteBanner" 
            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted, computed, onBeforeUnmount, nextTick } from 'vue';

export default {
  name: 'BannersAdmin',
  setup() {
    const banners = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const showAddModal = ref(false);
    const showEditModal = ref(false);
    const showDeleteModal = ref(false);
    const bannerToDelete = ref(null);
    const fileInput = ref(null);
    const bannerFile = ref(null);
    const isSubmitting = ref(false);
    const formErrors = ref({});
    
    const defaultBanner = {
      image_url: '',
      image_link: '',
      order: 0,
      is_active: true,
      starts_at: '',
      ends_at: ''
    };

    const currentBanner = ref({...defaultBanner});
    
    const modalRef = ref(null);
    const closeButtonRef = ref(null);
    
    const isDragging = ref(false);
    
    // Handle ESC key to close modal
    const handleKeyDown = (event) => {
      if (event.key === 'Escape' && (showAddModal.value || showEditModal.value)) {
        closeModal();
      }
    };
    
    // Focus management for modal
    const trapFocus = (e) => {
      if (!modalRef.value) return;
      
      const focusableElements = modalRef.value.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
      );
      
      if (focusableElements.length === 0) return;
      
      const firstElement = focusableElements[0];
      const lastElement = focusableElements[focusableElements.length - 1];
      
      // If shift+tab on first element, move to last element
      if (e.shiftKey && e.key === 'Tab' && document.activeElement === firstElement) {
        e.preventDefault();
        lastElement.focus();
      }
      
      // If tab on last element, move to first element
      if (!e.shiftKey && e.key === 'Tab' && document.activeElement === lastElement) {
        e.preventDefault();
        firstElement.focus();
      }
    };
    
    // Enhanced modal close function with focus management
    const closeModal = () => {
      // Store active element before closing to restore focus later
      const activeElement = document.activeElement;
      
      showAddModal.value = false;
      showEditModal.value = false;
      currentBanner.value = {...defaultBanner};
      bannerFile.value = null;
      formErrors.value = {};
      
      if (fileInput.value) {
        fileInput.value.value = ''; // Reset file input
      }
      
      // Restore focus after modal is closed
      nextTick(() => {
        if (activeElement && activeElement !== document.body) {
          activeElement.focus();
        }
      });
    };
    
    // When a modal is opened, set focus to the close button
    const setInitialFocus = () => {
      nextTick(() => {
        if (closeButtonRef.value) {
          closeButtonRef.value.focus();
        }
      });
    };
    
    // Watch for modal open/close to manage focus
    const watchModals = () => {
      if (showAddModal.value || showEditModal.value) {
        document.addEventListener('keydown', handleKeyDown);
        document.addEventListener('keydown', trapFocus);
        setInitialFocus();
      } else {
        document.removeEventListener('keydown', handleKeyDown);
        document.removeEventListener('keydown', trapFocus);
      }
    };
    
    // Handle image file upload
    const handleFileUpload = (event) => {
      const file = event.target.files[0];
      if (file) {
        bannerFile.value = file;
      }
    };
    
    // Handle file drag events
    const handleDragOver = (event) => {
      event.preventDefault();
      isDragging.value = true;
    };
    
    const handleDragLeave = () => {
      isDragging.value = false;
    };
    
    const handleDrop = (event) => {
      event.preventDefault();
      isDragging.value = false;
      
      const files = event.dataTransfer.files;
      if (files.length > 0) {
        const file = files[0];
        // Check if file is an image
        if (file.type.match('image.*')) {
          bannerFile.value = file;
        } else {
          formErrors.value.image = 'Please upload an image file';
        }
      }
    };
    
    // Computed class for upload area
    const uploadAreaClasses = computed(() => {
      return {
        'upload-area': true,
        'dragover': isDragging.value
      };
    });

    // Fetch all banners
    const fetchBanners = async () => {
      loading.value = true;
      error.value = null;

      try {
        // Use a more explicit request to avoid issues with column names
        const response = await axios.get('http://localhost:8000/api/banners', {
          params: {
            all: true // Additional parameter to indicate we want all banners
          }
        });
        
        if (response.data && Array.isArray(response.data)) {
          banners.value = response.data;
        } else {
          error.value = 'Invalid response format from server';
          console.error('Invalid banners data:', response.data);
        }
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to load banners';
        console.error('Error fetching banners:', err);
      } finally {
        loading.value = false;
      }
    };

    // Edit banner with focus management
    const editBanner = (banner) => {
      currentBanner.value = {...banner};
      showEditModal.value = true;
      watchModals();
    };

    // Validate form before submission
    const validateForm = () => {
      formErrors.value = {};
      
      if (showAddModal.value && !bannerFile.value) {
        formErrors.value.image = 'Image is required';
      }
      
      return Object.keys(formErrors.value).length === 0;
    };

    // Save banner (add or update)
    const saveBanner = async () => {
      if (!validateForm()) {
        return;
      }
      
      isSubmitting.value = true;
      error.value = null;

      const formData = new FormData();
      formData.append('image_link', currentBanner.value.image_link || '');
      formData.append('order', currentBanner.value.order || 0);
      formData.append('is_active', currentBanner.value.is_active ? '1' : '0');
      formData.append('starts_at', currentBanner.value.starts_at || '');
      formData.append('ends_at', currentBanner.value.ends_at || '');
      
      if (bannerFile.value) {
        formData.append('image', bannerFile.value);
      }

      try {
        if (showAddModal.value) {
          await axios.post('http://localhost:8000/api/banners', formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          });
        } else {
          await axios.post(`http://localhost:8000/api/banners/${currentBanner.value.id}?_method=PUT`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          });
        }
        
        closeModal();
        await fetchBanners();
      } catch (err) {
        console.error(`Error ${showAddModal.value ? 'adding' : 'updating'} banner:`, err);
        
        if (err.response && err.response.data && err.response.data.errors) {
          // Handle validation errors from Laravel
          formErrors.value = err.response.data.errors;
        } else {
          error.value = err.response?.data?.message || `Failed to ${showAddModal.value ? 'add' : 'update'} banner`;
        }
      } finally {
        isSubmitting.value = false;
      }
    };

    // Toggle banner status
    const toggleBannerStatus = async (banner) => {
      try {
        await axios.put(`http://localhost:8000/api/banners/${banner.id}`, {
          ...banner,
          is_active: !banner.is_active
        });
        await fetchBanners();
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to update banner status';
        console.error('Error updating banner status:', err);
      }
    };

    // Confirm banner deletion
    const confirmDeleteBanner = (banner) => {
      bannerToDelete.value = banner;
      showDeleteModal.value = true;
    };

    // Delete banner
    const deleteBanner = async () => {
      if (!bannerToDelete.value) return;

      try {
        await axios.delete(`http://localhost:8000/api/banners/${bannerToDelete.value.id}`);
        showDeleteModal.value = false;
        bannerToDelete.value = null;
        await fetchBanners();
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to delete banner';
        console.error('Error deleting banner:', err);
      }
    };

    // Format date
    const formatDate = (dateString) => {
      if (!dateString) return '';
      return new Date(dateString).toLocaleDateString();
    };
    
    // Make sure to add watchModals when adding a new banner
    const showAddBannerModal = () => {
      showAddModal.value = true;
      watchModals();
    };
    
    // Reset event listeners when component is unmounted
    onBeforeUnmount(() => {
      document.removeEventListener('keydown', handleKeyDown);
      document.removeEventListener('keydown', trapFocus);
    });

    onMounted(() => {
      fetchBanners();
    });

    return {
      banners,
      loading,
      error,
      showAddModal,
      showEditModal,
      showDeleteModal,
      currentBanner,
      fileInput,
      bannerFile,
      handleFileUpload,
      fetchBanners,
      closeModal,
      editBanner,
      saveBanner,
      toggleBannerStatus,
      confirmDeleteBanner,
      deleteBanner,
      formatDate,
      isSubmitting,
      formErrors,
      isDragging,
      handleDragOver,
      handleDragLeave,
      handleDrop,
      uploadAreaClasses,
      showAddBannerModal,
      modalRef,
      closeButtonRef
    };
  }
};
</script>

<style scoped>
/* Modal transition animations */
.modal-enter-active, .modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from, .modal-leave-to {
  opacity: 0;
}

.modal-content-enter-active, .modal-content-leave-active {
  transition: all 0.3s ease;
}

.modal-content-enter-from, .modal-content-leave-to {
  opacity: 0;
  transform: translateY(20px);
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .modal-container {
    padding: 0.5rem;
  }
  
  .modal-panel {
    max-height: 90vh;
  }
  
  .form-grid {
    grid-template-columns: 1fr;
  }
}

/* Better drag and drop styling */
.upload-area {
  transition: all 0.2s ease;
}

.upload-area:hover, .upload-area.dragover {
  border-color: #4f46e5;
  background-color: rgba(79, 70, 229, 0.05);
}

/* Improved scrollbar styling for webkit browsers */
.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e0;
  border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #a0aec0;
}
</style> 