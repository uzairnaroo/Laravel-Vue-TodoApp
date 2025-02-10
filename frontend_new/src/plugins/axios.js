import axios from 'axios';
import store from '../store';

const apiClient = axios.create({
    baseURL: 'http://localhost/api',
    withCredentials: false,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
});

// Add a request interceptor
apiClient.interceptors.request.use(config => {
    const token = store.state.auth.token;
    console.log('Injecting Token:', token);
    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
}, error => {
    return Promise.reject(error);
});

export default apiClient;
