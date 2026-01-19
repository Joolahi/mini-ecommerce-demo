<script setup>
import { computed, ref } from 'vue';
import { useCartStore } from '../stores/cart';

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
});

const cartStore = useCartStore();
const loading = ref(false);

const discountPercentage = computed(() => {
  if (!props.product.compare_price) return 0;
  return Math.round(
    ((props.product.compare_price - props.product.price) / props.product.compare_price) * 100
  );
});

const addToCart = async () => {
  loading.value = true;
  const success = await cartStore.addItem(
    props.product.id,
    1,
    props.product.tenant_id
  );
  
  if (success) {
    alert('Product added to cart!');
  } else {
    alert('Failed to add product to cart');
  }
  loading.value = false;
};
</script>

<template>
  <div class="card hover:shadow-lg transition-shadow duration-300">
    <div class="aspect-square bg-gray-100 relative overflow-hidden">
      <img 
        v-if="product.images && product.images.length > 0"
        :src="product.images[0]" 
        :alt="product.name"
        class="w-full h-full object-cover"
      />
      <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div>
      
      <div v-if="product.compare_price" class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-lg text-sm font-bold">
        -{{ discountPercentage }}%
      </div>
    </div>
    
    <div class="p-4">
      <p class="text-xs text-gray-500 mb-1">{{ product.category?.name }}</p>
      <h3 class="font-semibold text-lg mb-2 line-clamp-2">{{ product.name }}</h3>
      <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ product.short_description }}</p>
      
      <div class="flex items-center justify-between mb-3">
        <div>
          <p class="text-2xl font-bold text-primary-600">${{ product.price }}</p>
          <p v-if="product.compare_price" class="text-sm text-gray-400 line-through">
            ${{ product.compare_price }}
          </p>
        </div>
        
        <div class="text-right">
          <p v-if="product.stock > 0" class="text-sm text-green-600 font-medium">
            In Stock
          </p>
          <p v-else class="text-sm text-red-600 font-medium">
            Out of Stock
          </p>
        </div>
      </div>
      
      <button 
        @click="addToCart"
        :disabled="product.stock === 0 || loading"
        class="btn btn-primary w-full disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <span v-if="loading">Adding...</span>
        <span v-else>Add to Cart</span>
      </button>
    </div>
  </div>
</template>