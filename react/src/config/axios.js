import axios from 'axios';

// Public API instance for endpoints that don't require authentication
export const publicApi = axios.create({
    baseURL: import.meta.env.VITE_API_URL || '/api',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'multipart/form-data',
    },
});

// Private API instance for endpoints that require authentication
export const privateApi = axios.create({
    baseURL: import.meta.env.VITE_API_URL || '/api',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'multipart/form-data',
    },
    withCredentials: true, // Enable credentials for cross-origin requests
});

// Add a request interceptor to privateApi to add auth token
privateApi.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem(import.meta.env.VITE_AUTH_TOKEN_KEY);
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error instanceof Error ? error : new Error(error));
    }
);

// Add response interceptors to handle common errors
publicApi.interceptors.response.use(
    (response) => response,
    (error) => {
        return Promise.reject(error instanceof Error ? error : new Error(error));
    }
);

privateApi.interceptors.response.use(
    (response) => response,
    (error) => {
        return Promise.reject(error instanceof Error ? error : new Error(error));
    }
);

export default {
    public: publicApi,
    private: privateApi,
};