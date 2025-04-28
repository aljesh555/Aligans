<template>
  <div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-800">Logo Management</h1>
      <button 
        @click="showAddModal = true" 
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md"
      >
        Add New Logo
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

    <!-- Logo list -->
    <div v-else class="bg-white shadow rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image Path</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="logo in logos" :key="logo.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <img :src="logo.image_url || logo.image_path" alt="Logo" class="h-10 w-auto">
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ logo.image_path }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span 
                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                :class="logo.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
              >
                {{ logo.is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ new Date(logo.created_at).toLocaleDateString() }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
              <button 
                v-if="!logo.is_active"
                @click="activateLogo(logo.id)" 
                class="text-indigo-600 hover:text-indigo-900"
              >
                Set Active
              </button>
              <button 
                @click="confirmDeleteLogo(logo)" 
                class="text-red-600 hover:text-red-900"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Add Logo Modal -->
    <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Add New Logo</h2>
        
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="logo">
            Logo Image
          </label>
          <input 
            ref="fileInput"
            @change="handleFileUpload"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
            id="logo" 
            type="file" 
            accept="image/*"
          >
        </div>
        
        <div class="mb-4">
          <label class="flex items-center">
            <input 
              v-model="newLogo.is_active" 
              type="checkbox" 
              class="form-checkbox h-5 w-5 text-indigo-600"
            >
            <span class="ml-2 text-gray-700">Set as active logo</span>
          </label>
        </div>
        
        <div class="flex justify-end">
          <button 
            @click="showAddModal = false" 
            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2"
          >
            Cancel
          </button>
          <button 
            @click="addLogo" 
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
            :disabled="!logoFile"
          >
            Add Logo
          </button>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Confirm Delete</h2>
        <p class="mb-4">Are you sure you want to delete this logo?</p>
        
        <div class="flex justify-end">
          <button 
            @click="showDeleteModal = false" 
            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2"
          >
            Cancel
          </button>
          <button 
            @click="deleteLogo" 
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
import { ref, onMounted } from 'vue';

export default {
  name: 'LogosAdmin',
  setup() {
    const logos = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const showAddModal = ref(false);
    const showDeleteModal = ref(false);
    const logoToDelete = ref(null);
    const fileInput = ref(null);
    const logoFile = ref(null);
    
    const newLogo = ref({
      is_active: false
    });

    // Handle file upload
    const handleFileUpload = (event) => {
      logoFile.value = event.target.files[0];
    };

    // Fetch all logos
    const fetchLogos = async () => {
      loading.value = true;
      error.value = null;

      try {
        const response = await axios.get('http://localhost:8000/api/logos');
        logos.value = response.data;
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to load logos';
        console.error('Error fetching logos:', err);
      } finally {
        loading.value = false;
      }
    };

    // Add new logo
    const addLogo = async () => {
      if (!logoFile.value) {
        error.value = 'Please select an image file';
        return;
      }

      const formData = new FormData();
      formData.append('logo', logoFile.value);
      formData.append('is_active', newLogo.value.is_active ? '1' : '0');

      try {
        await axios.post('http://localhost:8000/api/logos', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        
        showAddModal.value = false;
        newLogo.value = { 
          is_active: false
        };
        logoFile.value = null;
        
        if (fileInput.value) {
          fileInput.value.value = ''; // Reset file input
        }
        
        await fetchLogos();
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to add logo';
        console.error('Error adding logo:', err);
      }
    };

    // Activate logo
    const activateLogo = async (id) => {
      try {
        await axios.put(`http://localhost:8000/api/logos/${id}/activate`);
        await fetchLogos();
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to activate logo';
        console.error('Error activating logo:', err);
      }
    };

    // Confirm logo deletion
    const confirmDeleteLogo = (logo) => {
      logoToDelete.value = logo;
      showDeleteModal.value = true;
    };

    // Delete logo
    const deleteLogo = async () => {
      if (!logoToDelete.value) return;

      try {
        await axios.delete(`http://localhost:8000/api/logos/${logoToDelete.value.id}`);
        showDeleteModal.value = false;
        logoToDelete.value = null;
        await fetchLogos();
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to delete logo';
        console.error('Error deleting logo:', err);
      }
    };

    onMounted(fetchLogos);

    return {
      logos,
      loading,
      error,
      showAddModal,
      showDeleteModal,
      newLogo,
      fileInput,
      logoFile,
      handleFileUpload,
      fetchLogos,
      addLogo,
      activateLogo,
      confirmDeleteLogo,
      deleteLogo
    };
  }
};
</script> 