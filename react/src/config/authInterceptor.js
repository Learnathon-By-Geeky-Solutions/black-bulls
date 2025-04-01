import { isProtectedRoute } from './protectedRoutes';
import { store } from '../redux/store';
import { clearUser } from '../redux/slices/userSlice';

let navigateFunction = null;

export const setNavigate = (navigate) => {
    navigateFunction = navigate;
};

export const handleError = (error) => {
    if (error.response) {
        const currentPath = window.location.pathname;
        // Handle specific error cases
        switch (error.response.status) {
            case 401:
                // Handle unauthorized access
                localStorage.removeItem(import.meta.env.VITE_AUTH_TOKEN_KEY);
                store.dispatch(clearUser());
                // Only redirect to login if trying to access a protected route
                if (isProtectedRoute(currentPath)) {
                    if (navigateFunction) {
                        navigateFunction('/login');
                    } else {
                        window.location.href = '/login';
                    }
                }
                break;
            case 403:
                // Handle forbidden access
                break;
            case 404:
                // Handle not found
                break;
            case 422:
                // Handle validation errors
                break;
            case 500:
                // Handle server errors
                break;
            default:
                // Handle other errors
                break;
        }
    }
    return Promise.reject(error);
}; 