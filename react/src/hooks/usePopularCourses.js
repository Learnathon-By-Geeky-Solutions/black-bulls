import { useState, useEffect } from 'react';
import { publicApi } from '../config/axios';

const usePopularCourses = () => {
    const [courses, setCourses] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchData = async () => {
            try {
                setLoading(true);
                const response = await publicApi.get('/home/popular-courses');
                
                if (response.data.is_success) {
                    // Ensure we're setting an array
                    setCourses(Array.isArray(response.data.data) ? response.data.data : []);
                } else {
                    setCourses([]);
                }
                setError(null);
            } catch (err) {
                console.error('Error fetching popular courses:', err);
                setError(err.response?.data?.message || 'Failed to fetch popular courses');
                setCourses([]); // Set empty array on error
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, []);

    return { courses, loading, error };
};

export default usePopularCourses; 