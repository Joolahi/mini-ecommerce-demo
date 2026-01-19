import axios from 'axios';

const apiClient = axios.create({
    baseURL : 'http://localhost:8000/api/v1/',
    headers : {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true,
});

apiClient.interceptors.request.use(
  (config) => {
    const sessionId = localStorage.getItem('cart_session_id');
    if (sessionId) {
      config.headers['X-Session-ID'] = sessionId;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

apiClient.interceptors.response.use(
  (response) => {
    const sessionId = response.headers['x-session-id'];
    if (sessionId) {
      localStorage.setItem('cart_session_id', sessionId);
    }
    return response;
  },
  (error) => {
    console.error('API Error:', error.response?.data || error.message);
    return Promise.reject(error);
  }
);

export default apiClient;