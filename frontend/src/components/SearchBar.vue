<script setup>
import { ref } from 'vue';

const emit = defineEmits(['search', 'clear']);
const searchQuery = ref('');

let searchTimeout = null;

const onSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    emit('search', searchQuery.value);
  }, 500);
};

const clearSearch = () => {
  searchQuery.value = '';
  emit('clear');
};
</script>
<template>
  <div class="relative max-w-2xl mx-auto">
    <div class="relative">
      <input
        v-model="searchQuery"
        @input="onSearch"
        type="text"
        placeholder="Search products..."
        class="input pl-12 pr-4"
      />
      <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
      <button
        v-if="searchQuery"
        @click="clearSearch"
        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>
</template>