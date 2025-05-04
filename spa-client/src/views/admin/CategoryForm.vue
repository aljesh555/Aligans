<template>
  <div class="category-form-page">
    <div class="bg-white shadow-md rounded-lg p-6">
      <h1 class="text-2xl font-bold text-gray-800 mb-6">
        {{ isEditing ? 'Edit Category' : 'Add New Category' }}
      </h1>

      <div v-if="successMessage" class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
        {{ successMessage }}
      </div>

      <div v-if="errorMessage" class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
        {{ errorMessage }}
      </div>

      <form @submit.prevent="saveCategory">
        <!-- Name Field -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Name <span class="text-red-500">*</span>
          </label>
          <input
            v-model="form.name"
            type="text"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': errors.name }"
            placeholder="Category name"
          />
          <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
        </div>

        <!-- Slug Field -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Slug
          </label>
          <input
            v-model="form.slug"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': errors.slug }"
            placeholder="category-slug (leave empty to auto-generate)"
          />
          <p v-if="errors.slug" class="mt-1 text-sm text-red-600">{{ errors.slug[0] }}</p>
          <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate from name</p>
        </div>

        <!-- Description Field -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Description
          </label>
          <textarea
            v-model="form.description"
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': errors.description }"
            placeholder="Category description"
          ></textarea>
          <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description[0] }}</p>
        </div>

        <!-- Image URL Field -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Main Image URL
          </label>
          <input
            v-model="form.image_url"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': errors.image_url }"
            placeholder="https://example.com/image.jpg"
          />
          <p v-if="errors.image_url" class="mt-1 text-sm text-red-600">{{ errors.image_url[0] }}</p>
          <div v-if="form.image_url" class="mt-2">
            <img :src="form.image_url" alt="Category preview" class="h-40 w-auto object-cover rounded border">
          </div>
        </div>

        <!-- Parent Category Field -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Parent Category
          </label>
          <select
            v-model="form.parent_id"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': errors.parent_id }"
          >
            <option :value="null">None (Top-level category)</option>
            <option 
              v-for="parent in parentCategories" 
              :key="parent.id" 
              :value="parent.id"
              :disabled="isEditing && parent.id === Number(categoryId)"
            >
              {{ parent.name }}
            </option>
          </select>
          <p v-if="errors.parent_id" class="mt-1 text-sm text-red-600">{{ errors.parent_id[0] }}</p>
        </div>

        <!-- Status Field -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Status
          </label>
          <select
            v-model="form.status"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': errors.status }"
          >
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
          <p v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status[0] }}</p>
        </div>

        <div class="flex justify-end mt-6 gap-3">
          <router-link
            to="/admin/categories"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors"
          >
            Cancel
          </router-link>
          <button 
            type="submit" 
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
            :disabled="isSubmitting"
          >
            {{ isSubmitting ? 'Saving...' : 'Save Category' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

export default {
  name: 'CategoryForm',
  
  setup() {
    const route = useRoute();
    const router = useRouter();
    
    const categoryId = computed(() => route.params.id);
    const isEditing = computed(() => !!categoryId.value);
    
    const form = reactive({
      name: '',
      slug: '',
      description: '',
      parent_id: null,
      image_url: '',
      status: 'active'
    });
    
    const parentCategories = ref([]);
    const errors = reactive({});
    const isSubmitting = ref(false);
    const successMessage = ref('');
    const errorMessage = ref('');

    // Load parent categories for the dropdown
    const loadParentCategories = async () => {
      try {
        const response = await axios.get('/api/admin/parent-categories', {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        parentCategories.value = response.data.data;
      } catch (err) {
        console.error('Error loading parent categories:', err);
        errorMessage.value = 'Failed to load parent categories. Please refresh the page.';
      }
    };

    // Load category data if editing
    const loadCategory = async () => {
      if (!isEditing.value) return;
      
      try {
        const response = await axios.get(`/api/admin/categories/${categoryId.value}`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        
        const category = response.data.data;
        
        // Update form with category data
        Object.keys(form).forEach(key => {
          if (category[key] !== undefined) {
            form[key] = category[key];
          }
        });
      } catch (err) {
        console.error('Error loading category:', err);
        errorMessage.value = 'Failed to load category data. Please try again.';
      }
    };

    // Save category function
    const saveCategory = async () => {
      // Clear previous messages and errors
      successMessage.value = '';
      errorMessage.value = '';
      Object.keys(errors).forEach(key => delete errors[key]);
      
      isSubmitting.value = true;
      
      try {
        // Log the data being sent
        console.log('Submitting category data:', form);
        
        let response;
        if (isEditing.value) {
          // Update existing category
          response = await axios.put(`/api/admin/categories/${categoryId.value}`, form, {
            headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
          });
        } else {
          // Create new category
          response = await axios.post('/api/admin/categories', form, {
            headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
          });
        }
        
        // Log the response
        console.log('API Response:', response.data);
        
        successMessage.value = isEditing.value 
          ? 'Category updated successfully!' 
          : 'Category created successfully!';
        
        // Redirect to categories list after a short delay
        setTimeout(() => {
          router.push('/admin/categories');
        }, 1500);
      } catch (err) {
        console.error('Error saving category:', err);
        
        // Handle validation errors
        if (err.response && err.response.data && err.response.data.errors) {
          Object.assign(errors, err.response.data.errors);
        } else {
          // Generic error message
          errorMessage.value = err.response?.data?.message || 'Failed to save category. Please try again.';
        }
      } finally {
        isSubmitting.value = false;
      }
    };

    // Initialize component
    onMounted(() => {
      loadParentCategories();
      loadCategory();
    });

    return {
      categoryId,
      isEditing,
      form,
      parentCategories,
      errors,
      isSubmitting,
      successMessage,
      errorMessage,
      saveCategory
    };
  }
};
</script> 