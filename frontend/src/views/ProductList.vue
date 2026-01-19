<script setup>
import { ref, onMounted } from 'vue';
import ProductCard from '../components/ProductCard.vue';
import SearchBar from '../components/SearchBar.vue';
import CategoryFilter from '../components/CategoryFilter.vue';
import { fetchProducts } from '../api/products';
import { categoryApi } from '../api/categories';

const products = ref([]);
const loading = ref(false);
const error = ref(null);
const searchQuery = ref('');
const selectedCategory = ref(null); 

const fProducts = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    if (searchQuery.value) {
      const response = await fetchProducts.search(searchQuery.value);
      products.value = response.data;
    } else if (selectedCategory.value) {
      const response = await categoryApi.getProductsByCategory(selectedCategory.value.slug);
      products.value = response.data;
    } else {
      const response = await fetchProducts.getAll();
      products.value = response.data;
    }
  } catch (err) {
    error.value = 'Failed to load products';
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const handleSearch = (query) => {
  searchQuery.value = query;
  selectedCategory.value = null;
  fProducts();
};

const handleClearSearch = () => {
  searchQuery.value = '';
  fProducts();
};

const handleCategorySelect = (category) => {
  selectedCategory.value = category;
  searchQuery.value = '';
  fProducts();
};

onMounted(() => {
  fProducts();
});
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Search Bar -->
    <div class="mb-8">
      <SearchBar @search="handleSearch" @clear="handleClearSearch" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <!-- Sidebar - Categories -->
      <aside class="lg:col-span-1">
        <CategoryFilter @select="handleCategorySelect" />
      </aside>

      <!-- Main Content - Products -->
      <main class="lg:col-span-3">
        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
          <p class="mt-4 text-gray-500">Loading products...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="text-center py-12">
          <p class="text-red-500">{{ error }}</p>
        </div>

        <!-- Empty State -->
        <div v-else-if="products.length === 0" class="text-center py-12">
          <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
          </svg>
          <p class="text-gray-500">No products found</p>
        </div>

        <!-- Products Grid -->
        <div v-else>
          <div class="flex items-center justify-between mb-6">
            <p class="text-gray-600">{{ products.length }} products found</p>
          </div>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <ProductCard
              v-for="product in products"
              :key="product.id"
              :product="product"
            />
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

