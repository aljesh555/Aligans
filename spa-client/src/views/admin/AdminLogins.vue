<template>
  <div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-800">Admin Login Management</h1>
      <button 
        @click="showAddModal = true" 
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md"
      >
        Add New Admin
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

    <!-- Admin Logins list -->
    <div v-else class="bg-white shadow rounded-lg overflow-hidden">
      <!-- Table content here -->
    </div>

    <!-- Add Admin Modal -->
    <div v-if="showAddModal">
      <!-- Modal content here -->
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal">
      <!-- Modal content here -->
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted } from 'vue';

export default {
  name: 'AdminLoginsAdmin',
  setup() {
    const adminLogins = ref([]);
    const loading = ref(true);
    const error = ref(null);
    const showAddModal = ref(false);
    const showDeleteModal = ref(false);
    const adminToDelete = ref(null);
    const newAdmin = ref({
      email: '',
      password: '',
      is_active: true
    });

    // Fetch all admin logins
    const fetchAdminLogins = async () => {
      loading.value = true;
      error.value = null;

      try {
        const response = await axios.get('http://localhost:8000/api/admin/admin-logins');
        adminLogins.value = response.data;
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to load admin logins';
        console.error('Error fetching admin logins:', err);
      } finally {
        loading.value = false;
      }
    };

    // Add new admin login
    const addAdmin = async () => {
      try {
        await axios.post('http://localhost:8000/api/admin/admin-logins', newAdmin.value);
        showAddModal.value = false;
        newAdmin.value = { email: '', password: '', is_active: true };
        await fetchAdminLogins();
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to add admin login';
        console.error('Error adding admin login:', err);
      }
    };

    // Toggle admin status (activate/deactivate)
    const toggleAdminStatus = async (id, status) => {
      try {
        await axios.put(`http://localhost:8000/api/admin/admin-logins/${id}`, {
          is_active: status
        });
        await fetchAdminLogins();
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to update admin status';
        console.error('Error updating admin status:', err);
      }
    };

    // Confirm admin deletion
    const confirmDeleteAdmin = (admin) => {
      adminToDelete.value = admin;
      showDeleteModal.value = true;
    };

    // Delete admin login
    const deleteAdmin = async () => {
      if (!adminToDelete.value) return;

      try {
        await axios.delete(`http://localhost:8000/api/admin/admin-logins/${adminToDelete.value.id}`);
        showDeleteModal.value = false;
        adminToDelete.value = null;
        await fetchAdminLogins();
      } catch (err) {
        error.value = err.response?.data?.message || 'Failed to delete admin login';
        console.error('Error deleting admin login:', err);
      }
    };

    onMounted(fetchAdminLogins);

    return {
      adminLogins,
      loading,
      error,
      showAddModal,
      showDeleteModal,
      adminToDelete,
      newAdmin,
      fetchAdminLogins,
      addAdmin,
      toggleAdminStatus,
      confirmDeleteAdmin,
      deleteAdmin
    };
  }
};
</script> 

<style scoped>
/* Component-specific styles only */
</style> 