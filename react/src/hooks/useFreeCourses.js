import { useState, useEffect } from 'react';
import { publicApi } from '../config/axios';

export const useFreeCourses = () => {
    const [courses, setCourses] = useState([]);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchCourses = async () => {
            try {
                setIsLoading(true);
                const response = await publicApi.get('/home/free-courses');
                
                if (response.data.is_success) {
                    // Handle paginated data structure
                    const coursesData = response.data.data.data || [];
                    setCourses(Array.isArray(coursesData) ? coursesData : []);
                } else {
                    setCourses([]);
                }
                setError(null);
            } catch (err) {
                console.error('Error fetching free courses:', err);
                setError(err.message);
                setCourses([]); // Set empty array on error
            } finally {
                setIsLoading(false);
            }
        };

        fetchCourses();
    }, []);

    return { courses, isLoading, error };
}; 