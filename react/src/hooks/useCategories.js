import { useState, useEffect } from 'react';
import { publicApi } from '../config/axios';

const useCategories = () => {
    const [categories, setCategories] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchData = async () => {
            try {
                setLoading(true);
                const response = await publicApi.get('/home/categories');
                
                if (response.data.is_success) {
                    // Ensures setting an array
                    setCategories(Array.isArray(response.data.data) ? response.data.data : []);
                } else {
                    setCategories([]);
                }
                setError(null);
            } catch (err) {
                console.error('Error fetching categories:', err);
                setError(err.response?.data?.message || 'Failed to fetch categories');
                setCategories([]); // Set empty array on error
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, []);

    return { categories, loading, error };
};

export default useCategories; 