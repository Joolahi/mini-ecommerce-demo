import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { cartService } from '../api/cart';

export const useCartStore = defineStore('cart', () => {
  const items = ref([]);
  const loading = ref(false);
  const error = ref(null);

  const itemCount = computed(() => {
    return items.value.reduce((total, item) => total + item.quantity, 0);
  });

  const total = computed(() => {
    return items.value.reduce((total, item) => total + item.subtotal, 0);
  });

  async function fetchCart() {
    loading.value = true;
    error.value = null;
    try {
      const response = await cartService.get();
      items.value = response.data;
    } catch (err) {
      error.value = err.message;
    } finally {
      loading.value = false;
    }
  }

  async function addItem(productId, quantity, tenantId) {
    loading.value = true;
    error.value = null;
    try {
      await cartService.add(productId, quantity, tenantId);
      await fetchCart();
      return true;
    } catch (err) {
      error.value = err.response?.data?.message || err.message;
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function updateItem(itemId, quantity) {
    try {
      await cartService.update(itemId, quantity);
      await fetchCart();
    } catch (err) {
      error.value = err.response?.data?.message || err.message;
    }
  }

  async function removeItem(itemId) {
    try {
      await cartService.remove(itemId);
      await fetchCart();
    } catch (err) {
      error.value = err.message;
    }
  }

  async function clearCart() {
    try {
      await cartService.clear();
      items.value = [];
    } catch (err) {
      error.value = err.message;
    }
  }

  return {
    items,
    loading,
    error,
    itemCount,
    total,
    fetchCart,
    addItem,
    updateItem,
    removeItem,
    clearCart,
  };
});