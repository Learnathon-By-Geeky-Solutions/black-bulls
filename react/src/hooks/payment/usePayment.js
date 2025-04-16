import { useState } from 'react';
import { privateApi } from '../../config/axios';
import { useNavigate } from 'react-router-dom';

export const usePayment = () => {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const navigate = useNavigate();

    const initiatePayment = async (paymentData) => {
        try {
            setLoading(true);
            setError(null);
            const response = await privateApi.post('/payments/initiate', paymentData);

            console.log('Payment API Response:', response.data); // Log the response for debugging

            if (response.data?.is_success) {
                // Redirect to SSLCommerz payment page
                window.location.href = response.data.data.GatewayPageURL;
            }
            else if (response.data.is_success && response.data.data?.redirect_url) {
                // Redirect to the provided URL
                window.location.href = response.data.data.redirect_url;
            }
            else {
                setError(response.data?.message || 'Unexpected error occurred during payment initiation.');
            }
            return response.data;
        } catch (err) {
            console.error('Payment API Error:', err); // Log the error for debugging
            setError(err.response?.data?.message || 'Payment initiation failed. Please try again later.');
        } finally {
            setLoading(false);
        }
    };

    const handlePaymentSuccess = async (data) => {
        try {
            setLoading(true);
            const response = await privateApi.success(data);
            if (response.data.is_success) {
                navigate('/payment/success');
            } else {
                setError(response.data.message);
            }
        } catch (err) {
            setError(err.response?.data?.message || 'Payment success handling failed');
        } finally {
            setLoading(false);
        }
    };

    const handlePaymentFailure = async (data) => {
        try {
            setLoading(true);
            const response = await privateApi.fail(data);
            if (!response.data.is_success) {
                navigate('/payment/failed');
            }
        } catch (err) {
            setError(err.response?.data?.message || 'Payment failure handling failed');
        } finally {
            setLoading(false);
        }
    };

    const handlePaymentCancel = async (data) => {
        try {
            setLoading(true);
            const response = await privateApi.cancel(data);
            if (!response.data.is_success) {
                navigate('/payment/cancelled');
            }
        } catch (err) {
            setError(err.response?.data?.message || 'Payment cancellation handling failed');
        } finally {
            setLoading(false);
        }
    };

    return {
        loading,
        error,
        initiatePayment,
        handlePaymentSuccess,
        handlePaymentFailure,
        handlePaymentCancel
    };
};