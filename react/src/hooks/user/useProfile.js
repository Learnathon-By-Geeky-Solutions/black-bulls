import { useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import { privateApi } from '../../config/axios';
import { setUser, setLoading, setError, clearUser } from '../../redux/slices/userSlice';

export const useProfile = () => {
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const { user, isLoading, error } = useSelector((state) => state.user);

    const fetchProfile = async () => {
        try {
            dispatch(setLoading(true));
            const token = localStorage.getItem(import.meta.env.VITE_AUTH_TOKEN_KEY);
            
            if (!token) {
                dispatch(clearUser());
                navigate('/login');
                return;
            }
            
            const response = await privateApi.get('/profile');
            
            if (response.data.is_success) {
                dispatch(setUser(response.data.data));
            } else {
                dispatch(setError(response.data.message));
            }
        } catch (err) {
            console.error('Profile fetch error:', err);
            if (err.response?.status === 401) {
                localStorage.removeItem(import.meta.env.VITE_AUTH_TOKEN_KEY);
                dispatch(clearUser());
                navigate('/login');
            } else {
                dispatch(setError(err.response?.data?.message || 'Failed to fetch profile'));
            }
        } finally {
            dispatch(setLoading(false));
        }
    };

    // Fetch profile on mount if we have a token but no user
    useEffect(() => {
        const token = localStorage.getItem(import.meta.env.VITE_AUTH_TOKEN_KEY);
        if (token && !user) {
            fetchProfile();
        }
    }, []);

    const updateProfile = async (profileData) => {
        try {
            dispatch(setLoading(true));
            const response = await privateApi.put('/profile', profileData);
            if (response.data.is_success) {
                dispatch(setUser(response.data.data));
                return { success: true, data: response.data.data };
            }
            return { success: false, error: response.data.message };
        } catch (err) {
            return { success: false, error: err.response?.data?.message || 'Failed to update profile' };
        } finally {
            dispatch(setLoading(false));
        }
    };

    const updatePassword = async (passwordData) => {
        try {
            dispatch(setLoading(true));
            const response = await privateApi.put('/profile/password', passwordData);
            if (response.data.is_success) {
                return { success: true };
            }
            return { success: false, error: response.data.message };
        } catch (err) {
            return { success: false, error: err.response?.data?.message || 'Failed to update password' };
        } finally {
            dispatch(setLoading(false));
        }
    };

    return {
        user,
        isLoading,
        error,
        fetchProfile,
        updateProfile,
        updatePassword,
        refetch: fetchProfile
    };
}; 