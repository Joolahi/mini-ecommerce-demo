<script setup>
import { useCartStore } from '../stores/cart';

defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['close']);
const cartStore = useCartStore();

const close = () => {
  emit('close');
};
</script>

<template>
  <div
    :class="[
      'fixed inset-y-0 right-0 w-full max-w-md bg-white shadow-2xl transform transition-transform duration-300 z-50',
      isOpen ? 'translate-x-0' : 'translate-x-full'
    ]"
  >
    <div class="flex flex-col h-full">
      <!-- Header -->
      <div class="flex items-center justify-between p-4 border-b">
        <h2 class="text-xl font-bold">Shopping Cart ({{ cartStore.itemCount }})</h2>
        <button @click="close" class="text-gray-400 hover:text-gray-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Cart Items -->
      <div class="flex-1 overflow-y-auto p-4">
        <div v-if="cartStore.loading" class="text-center py-8">
          <p class="text-gray-500">Loading...</p>
        </div>
        
        <div v-else-if="cartStore.items.length === 0" class="text-center py-8">
          <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
          <p class="text-gray-500">Your cart is empty</p>
        </div>
        
        <div v-else class="space-y-4">
          <div
            v-for="item in cartStore.items"
            :key="item.id"
            class="flex gap-4 p-4 border rounded-lg"
          >
            <div class="w-20 h-20 bg-gray-100 rounded flex-shrink-0">
              <img
                v-if="item.product?.images?.[0]"
                :src="item.product.images[0]"
                :alt="item.product.name"
                class="w-full h-full object-cover rounded"
              />
            </div>
            
            <div class="flex-1 min-w-0">
              <h3 class="font-semibold text-sm mb-1 truncate">{{ item.product?.name }}</h3>
              <p class="text-primary-600 font-bold mb-2">${{ item.price }}</p>
              
              <div class="flex items-center gap-2">
                <button
                  @click="cartStore.updateItem(item.id, item.quantity - 1)"
                  :disabled="item.quantity <= 1"
                  class="w-8 h-8 flex items-center justify-center border rounded hover:bg-gray-100 disabled:opacity-50"
                >
                  -
                </button>
                <span class="w-8 text-center">{{ item.quantity }}</span>
                <button
                  @click="cartStore.updateItem(item.id, item.quantity + 1)"
                  class="w-8 h-8 flex items-center justify-center border rounded hover:bg-gray-100"
                >
                  +
                </button>
                <button
                  @click="cartStore.removeItem(item.id)"
                  class="ml-auto text-red-500 hover:text-red-700"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div v-if="cartStore.items.length > 0" class="border-t p-4">
        <div class="flex justify-between items-center mb-4">
          <span class="text-lg font-semibold">Total:</span>
          <span class="text-2xl font-bold text-primary-600">${{ cartStore.total.toFixed(2) }}</span>
        </div>
        
        <button class="btn btn-primary w-full mb-2">
          Proceed to Checkout
        </button>
        
        <button
          @click="cartStore.clearCart"
          class="btn btn-secondary w-full"
        >
          Clear Cart
        </button>
      </div>
    </div>
  </div>

  <!-- Overlay -->
  <div
    v-if="isOpen"
    @click="close"
    class="fixed inset-0 bg-black bg-opacity-50 z-40"
  ></div>
</template>