import apiClient from './client';

export const cartService = {
  async get() {
    const response = await apiClient.get('/cart');
    return response.data;
  },

  async add(productId, quantity, tenantId) {
    const response = await apiClient.post('/cart/add', {
      product_id: productId,
      quantity: quantity,
      tenant_id: tenantId,
    });
    return response.data;
  },

  async update(itemId, quantity) {
    const response = await apiClient.put(`/cart/update/${itemId}`, {
      quantity: quantity,
    });
    return response.data;
  },

  async remove(itemId) {
    const response = await apiClient.delete(`/cart/remove/${itemId}`);
    return response.data;
  },

  async clear() {
    const response = await apiClient.delete('/cart/clear');
    return response.data;
  },
};