<script setup>
import { ref, onMounted } from 'vue';
import { categoryApi } from '../api/categories';

const emit = defineEmits(['select']);

const categories = ref([]);
const loading = ref(false);
const selectedCategory = ref(null);

const fetchCategories = async () => {
  loading.value = true;
  try {
    const response = await categoryApi.getAll({ root_only: true });
    categories.value = response.data;
  } catch (error) {
    console.error('Failed to fetch categories:', error);
  } finally {
    loading.value = false;
  }
};

const selectCategory = (category) => {
  selectedCategory.value = category;
  emit('select', category);
};

onMounted(() => {
  fetchCategories();
});
</script>

<template>
  <div class="card p-4">
    <h3 class="font-bold text-lg mb-4">Categories</h3>
    
    <div v-if="loading" class="text-center py-4">
      <p class="text-gray-500">Loading...</p>
    </div>
    
    <div v-else class="space-y-2">
      <button
        @click="selectCategory(null)"
        :class="[
          'w-full text-left px-4 py-2 rounded-lg transition-colors',
          selectedCategory === null 
            ? 'bg-primary-600 text-white' 
            : 'hover:bg-gray-100'
        ]"
      >
        All Products
      </button>
      
      <button
        v-for="category in categories"
        :key="category.id"
        @click="selectCategory(category)"
        :class="[
          'w-full text-left px-4 py-2 rounded-lg transition-colors',
          selectedCategory?.id === category.id 
            ? 'bg-primary-600 text-white' 
            : 'hover:bg-gray-100'
        ]"
      >
        {{ category.name }}
        <span class="text-sm opacity-75 ml-2">({{ category.products?.length || 0 }})</span>
      </button>
    </div>
  </div>
</template>

