import { useState, useEffect } from 'react';
import { publicApi } from '../../../../config/axios';

export const useCourseChapters = (courseId) => {
  const [chapters, setChapters] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchChapters = async () => {
      try {
        setLoading(true);
        const response = await publicApi.get(`/learn/courses/${courseId}/sections`);
        if (response.data.is_success) {
          const sections = response.data.data;
          
          // Fetch lessons for each section
          const sectionsWithLessons = await Promise.all(
            sections.map(async (section) => {
              const lessonsResponse = await publicApi.get(`/learn/courses/${courseId}/sections/${section.id}/lessons`);
              return {
                ...section,
                lessons: lessonsResponse.data.is_success ? lessonsResponse.data.data : [],
                lessons_count: lessonsResponse.data.is_success ? lessonsResponse.data.data.length : 0,
              };
            })
          );
          
          setChapters(sectionsWithLessons);
        } else {
          setError(response.data.message);
        }
      } catch (err) {
        setError(err.response?.data?.message || 'Failed to fetch course chapters');
      } finally {
        setLoading(false);
      }
    };

    if (courseId) {
      fetchChapters();
    }
  }, [courseId]);

  return { chapters, loading, error };
}; 