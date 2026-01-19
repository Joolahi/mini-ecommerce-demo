<script setup>
import { ref, onMounted } from 'vue';
import Navbar from './components/Navbar.vue';
import ProductList from './views/ProductList.vue';
import CartSidebar from './components/CartSidebar.vue';
import { useCartStore } from './stores/cart';

const cartStore = useCartStore();
const isCartOpen = ref(false);

const toggleCart = () => {
  isCartOpen.value = !isCartOpen.value;
};

onMounted(() => {
  cartStore.fetchCart();
});
</script>

<template>
  <div id="app" class="min-h-screen bg-gray-50">
    <Navbar @toggle-cart="toggleCart" />
    
    <ProductList />
    
    <CartSidebar
      :isOpen="isCartOpen"
      @close="toggleCart"
    />
  </div>
</template>