<template>
  <div class="category-select">
    <label :for="id" class="select-label">{{ label }}</label>
    <div class="select-wrapper" :class="{ 'is-error': error }">
      <select 
        :id="id" 
        v-model="selectedValue" 
        class="select-input"
        :disabled="isLoading || disabled"
        :required="required"
      >
        <option value="" disabled>{{ placeholder }}</option>
        <option v-for="category in categories" :key="category.id" :value="category.id">
          {{ category.name }}
        </option>
      </select>
      <div class="select-icon">
        <svg v-if="isLoading" class="spinner" viewBox="0 0 50 50">
          <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </div>
    </div>
    <span v-if="error" class="error-text">{{ error }}</span>
    <span v-else-if="helperText" class="helper-text">{{ helperText }}</span>
  </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

export default {
  name: 'CategorySelect',
  props: {
    modelValue: {
      type: [String, Number],
      default: ''
    },
    label: {
      type: String,
      default: 'Category'
    },
    placeholder: {
      type: String,
      default: 'Select a category'
    },
    id: {
      type: String,
      default: 'category-select'
    },
    error: {
      type: String,
      default: ''
    },
    helperText: {
      type: String,
      default: ''
    },
    required: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    preloadedCategories: {
      type: Array,
      default: () => []
    }
  },
  
  emits: ['update:modelValue', 'categories-loaded'],
  
  setup(props, { emit }) {
    const categories = ref(props.preloadedCategories || []);
    const isLoading = ref(false);
    const selectedValue = ref(props.modelValue);
    const hasError = ref(false);
    
    const fetchCategories = async () => {
      // If categories are already provided, don't fetch
      if (categories.value.length > 0) {
        return;
      }
      
      isLoading.value = true;
      hasError.value = false;
      
      try {
        const response = await axios.get('/api/categories');
        
        if (response.data && response.data.data) {
          categories.value = response.data.data;
          emit('categories-loaded', categories.value);
        } else {
          // Fallback mock data in case API is not available
          categories.value = [
            { id: 1, name: 'Sports Equipment' },
            { id: 2, name: 'Jerseys' },
            { id: 3, name: 'Footwear' },
            { id: 4, name: 'Accessories' },
            { id: 5, name: 'Training Gear' }
          ];
          emit('categories-loaded', categories.value);
          console.warn('Using mock categories data as API response format was unexpected');
        }
      } catch (error) {
        console.error('Error fetching categories:', error);
        hasError.value = true;
        
        // Fallback to mock data
        categories.value = [
          { id: 1, name: 'Sports Equipment' },
          { id: 2, name: 'Jerseys' },
          { id: 3, name: 'Footwear' },
          { id: 4, name: 'Accessories' },
          { id: 5, name: 'Training Gear' }
        ];
        emit('categories-loaded', categories.value);
      } finally {
        isLoading.value = false;
      }
    };
    
    // Watch for external model value changes
    watch(() => props.modelValue, (newValue) => {
      selectedValue.value = newValue;
    });
    
    // Watch for internal selection changes and emit update
    watch(selectedValue, (newValue) => {
      emit('update:modelValue', newValue);
    });
    
    // Fetch categories on component mount
    onMounted(() => {
      fetchCategories();
    });
    
    return {
      categories,
      selectedValue,
      isLoading,
      hasError,
      fetchCategories
    };
  }
};
</script>

<style scoped>
.category-select {
  display: flex;
  flex-direction: column;
  width: 100%;
}

.select-label {
  display: block;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: #4b5563;
}

.select-wrapper {
  position: relative;
  width: 100%;
}

.select-input {
  width: 100%;
  padding: 0.625rem 0.75rem;
  padding-right: 2.5rem;
  appearance: none;
  background-color: #fff;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.select-input:focus {
  outline: none;
  border-color: #4f46e5;
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
}

.select-input:disabled {
  background-color: #f3f4f6;
  cursor: not-allowed;
  opacity: 0.7;
}

.select-icon {
  position: absolute;
  top: 0;
  right: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  width: 2.5rem;
  pointer-events: none;
  color: #6b7280;
}

.is-error .select-input {
  border-color: #dc2626;
}

.error-text {
  margin-top: 0.25rem;
  font-size: 0.75rem;
  color: #dc2626;
}

.helper-text {
  margin-top: 0.25rem;
  font-size: 0.75rem;
  color: #6b7280;
}

/* Loading spinner */
.spinner {
  animation: rotate 2s linear infinite;
  width: 18px;
  height: 18px;
}

.spinner .path {
  stroke: #6b7280;
  stroke-linecap: round;
  animation: dash 1.5s ease-in-out infinite;
}

@keyframes rotate {
  100% {
    transform: rotate(360deg);
  }
}

@keyframes dash {
  0% {
    stroke-dasharray: 1, 150;
    stroke-dashoffset: 0;
  }
  50% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -35;
  }
  100% {
    stroke-dasharray: 90, 150;
    stroke-dashoffset: -124;
  }
}
</style> 