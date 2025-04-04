import { useState, useEffect } from 'react';
import { publicApi } from '../../../config/axios';

export const useCourse = (courseId) => {
  const [course, setCourse] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchCourse = async () => {
      try {
        setLoading(true);
        const response = await publicApi.get(`/learn/courses/${courseId}`);
        if (response.data.is_success) {
          const courseData = response.data.data;
          
          // Format the course data to include sections, chapters, and lessons
          const formattedCourse = {
            ...courseData,
            sections: courseData.sections?.map(section => ({
              ...section,
              chapters: section.chapters?.map(chapter => ({
                ...chapter,
                lessons: chapter.lessons || []
              }))
            })) || []
          };
          
          setCourse(formattedCourse);
        } else {
          setError(response.data.message);
        }
      } catch (err) {
        setError(err.response?.data?.message || 'Failed to fetch course');
      } finally {
        setLoading(false);
      }
    };

    if (courseId) {
      fetchCourse();
    }
  }, [courseId]);

  return { course, loading, error };
}; 