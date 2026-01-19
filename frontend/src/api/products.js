import apiClient from './client';

export const fetchProducts = {
    async getAll(params = {}) {
        const response = await apiClient.get('/products', { params });
        return response.data;
    },

    async getBySlug(slug) {
        const response = await apiClient.get(`/products/${slug}`);
        return response.data;
    },

    async search(query) {
        const response = await apiClient.get('/products/search', { params: { q: query } });
        return response.data;
    },
};