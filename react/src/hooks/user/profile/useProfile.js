import { useState, useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { setUser } from '../../../redux/slices/userSlice';
import { privateApi } from '../../../config/axios';
import { useTranslation } from 'react-i18next';

export const useProfile = () => {
    const dispatch = useDispatch();
    const { t } = useTranslation('profile');
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState(null);
    const user = useSelector((state) => state.user.user);

    useEffect(() => {
        const fetchUserProfile = async () => {
            try {
                setIsLoading(true);
                setError(null);
                const response = await privateApi.get('/profile');
                
                if (response.data.is_success) {
                    dispatch(setUser(response.data.data));
                } else {
                    throw new Error(response.data.message);
                }
            } catch (err) {
                setError(err.response?.data?.message || err.message || t('profile.fetchError'));
            } finally {
                setIsLoading(false);
            }
        };

        fetchUserProfile();
    }, [dispatch, t]);

    const updateProfile = async (formData) => {
        try {
            setIsLoading(true);
            setError(null);

            // Create FormData object
            const data = new FormData();
            
            // Add _method for Laravel's form method spoofing
            data.append('_method', 'PUT');
            
            // Append all fields directly with proper null checks
            data.append('name', formData.name || '');
            data.append('phone', formData.phone || '');
            data.append('designation', formData.designation || '');
            data.append('institution', formData.institution || '');
            data.append('dept', formData.dept || '');
            data.append('address', formData.address || '');
            
            // Append profile picture if exists
            if (formData.profile_picture) {
                data.append('profile_picture', formData.profile_picture);
            }

            // Log the actual FormData contents
            for (let pair of data.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            const response = await privateApi.post('/profile', data, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });

            console.log('Profile update response:', response.data);

            if (response.data.is_success) {
                dispatch(setUser(response.data.data));
                return {
                    success: true,
                    message: t('profile.updateSuccess')
                };
            } else {
                throw new Error(response.data.message);
            }
        } catch (err) {
            console.error('Profile update error:', err);
            const errorMessage = err.response?.data?.message || err.message || t('profile.updateError');
            setError(errorMessage);
            return {
                success: false,
                message: errorMessage
            };
        } finally {
            setIsLoading(false);
        }
    };

    const updatePassword = async (data) => {
        try {
            setIsLoading(true);
            setError(null);

            const response = await privateApi.put('/profile/password', data);

            if (response.data.is_success) {
                return {
                    success: true,
                    message: t('profile.passwordUpdateSuccess')
                };
            } else {
                // Handle non-validation errors (like incorrect current password)
                return {
                    success: false,
                    message: response.data.message
                };
            }
        } catch (err) {
            console.error('Password update error:', err);
            // Handle validation errors
            if (err.response?.data?.errors) {
                return {
                    success: false,
                    errors: err.response.data.errors
                };
            }
            // Handle other errors
            return {
                success: false,
                message: err.response?.data?.message || err.message || t('profile.passwordUpdateError')
            };
        } finally {
            setIsLoading(false);
        }
    };

    return {
        user,
        updateProfile,
        updatePassword,
        isLoading,
        error
    };
}; 