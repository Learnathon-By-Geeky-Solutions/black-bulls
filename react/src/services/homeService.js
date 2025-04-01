import api from './api';

const homeService = {
    getFreeCourses: async () => {
        try {
            const response = await api.get('/home/free-courses');
            return response.data;
        } catch (error) {
            console.error('Error fetching free courses:', error);
            throw error;
        }
    },

    getPopularCourses: async () => {
        try {
            const response = await api.get('/home/popular-courses');
            return response.data;
        } catch (error) {
            console.error('Error fetching popular courses:', error);
            throw error;
        }
    },

    getTrendingCourses: async () => {
        try {
            const response = await api.get('/home/trending-courses');
            return response.data;
        } catch (error) {
            console.error('Error fetching trending courses:', error);
            throw error;
        }
    },

    getCategories: async () => {
        try {
            const response = await api.get('/home/categories');
            return response.data;
        } catch (error) {
            console.error('Error fetching categories:', error);
            throw error;
        }
    }
};

export default homeService; 