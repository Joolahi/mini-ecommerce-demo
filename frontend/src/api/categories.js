import apiClient from './client';

export const categoryApi = {
    async getAll(params = {}) {
        const response = await apiClient.get('/categories', { params });
        return response.data;
    },

    async getBySlug(slug) {
        const response = await apiClient.get(`/categories/${slug}`);
        return response.data;
    },

    async getProductsByCategory(slug, params = {}){
        const response = await apiClient.get(`/categories/${slug}/products`, { params });
        return response.data;   
    }
};