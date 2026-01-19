<script setup>
import { useCartStore } from '../stores/cart';

defineEmits(['toggle-cart']);
const cartStore = useCartStore();
</script>

<template>
  <nav class="bg-white shadow-sm sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <h1 class="text-2xl font-bold text-primary-600">Mini E-commerce</h1>
        </div>

        <!-- Cart Button -->
        <button
          @click="$emit('toggle-cart')"
          class="relative p-2 hover:bg-gray-100 rounded-lg transition-colors"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
          
          <span
            v-if="cartStore.itemCount > 0"
            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"
          >
            {{ cartStore.itemCount }}
          </span>
        </button>
      </div>
    </div>
  </nav>
</template>