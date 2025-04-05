import { useQuery } from '@tanstack/react-query';
import { privateApi } from '../../../config/axios';

export const useEnrolledCourses = (status) => {
    return useQuery({
        queryKey: ['enrolled-courses', status],
        queryFn: async () => {
            const response = await privateApi.get(`/enrollments/my-courses/${status}`);
            return response.data;
        },
    });
}; 