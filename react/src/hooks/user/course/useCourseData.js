import { useState, useEffect } from 'react';
import { publicApi } from '../../../config/axios';

const useCourseData = (endpoint, errorMessage) => {
    const [courses, setCourses] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchData = async () => {
            try {
                setLoading(true);
                const response = await publicApi.get(endpoint);
                
                if (response.data.is_success) {
                    // Handle both regular and nested data structures
                    const data = response.data.data;
                    if (endpoint === '/home/free-courses') {
                        // Free courses have a nested data structure
                        setCourses(data);
                    } else {
                        // Other endpoints have a flat data structure
                        setCourses(Array.isArray(data) ? data : []);
                    }
                } else {
                    setCourses([]);
                }
                setError(null);
            } catch (err) {
                console.error(`Error fetching ${endpoint}:`, err);
                setError(err.response?.data?.message || errorMessage);
                setCourses([]);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, [endpoint, errorMessage]);

    return { courses, loading, error };
};

export default useCourseData; 