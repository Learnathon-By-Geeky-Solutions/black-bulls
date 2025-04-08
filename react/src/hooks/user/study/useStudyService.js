import { useCallback } from 'react';
import { privateApi } from '../../../config/axios';

const useStudyService = () => {
    const getCourseDetails = useCallback(async (courseId) => {
        try {
            const response = await privateApi.get(`/learn/courses/${courseId}`);
            return {
                is_success: true,
                message: response.data.message,
                data: response.data.data
            };
        } catch (error) {
            return {
                is_success: false,
                message: error.response?.data?.message || 'Failed to fetch course details'
            };
        }
    }, []);

    const getCourseSections = useCallback(async (courseId) => {
        try {
            const response = await privateApi.get(`/study/courses/${courseId}/sections`);
            return {
                is_success: true,
                message: response.data.message,
                data: response.data.data.data,
                pagination: {
                    current_page: response.data.data.current_page,
                    last_page: response.data.data.last_page,
                    per_page: response.data.data.per_page,
                    total: response.data.data.total,
                    links: response.data.data.links
                }
            };
        } catch (error) {
            return {
                is_success: false,
                message: error.response?.data?.message || 'Failed to fetch course sections'
            };
        }
    }, []);

    const getSectionChapters = useCallback(async (sectionId) => {
        try {
            const response = await privateApi.get(`/study/sections/${sectionId}/chapters`);
            console.log('Section Chapters API Response:', response.data);
            return {
                is_success: true,
                message: response.data.message,
                data: response.data.data.map(chapter => ({
                    id: chapter.id,
                    title: chapter.title
                }))
            };
        } catch (error) {
            console.error('Section Chapters API Error:', error);
            return {
                is_success: false,
                message: error.response?.data?.message || 'Failed to fetch section chapters',
                data: []
            };
        }
    }, []);

    const getChapterLessons = useCallback(async (chapterId) => {
        try {
            const response = await privateApi.get(`/study/chapters/${chapterId}/lessons`);
            console.log('Chapter Lessons API Response:', response.data);
            return {
                is_success: true,
                message: response.data.message,
                data: {
                    lessons: response.data.data.map(lesson => ({
                        id: lesson.id,
                        title: lesson.title,
                        hasQuiz: lesson.mcqs && lesson.mcqs.length > 0
                    }))
                }
            };
        } catch (error) {
            console.error('Chapter Lessons API Error:', error);
            return {
                is_success: false,
                message: error.response?.data?.message || 'Failed to fetch chapter lessons',
                data: {
                    lessons: []
                }
            };
        }
    }, []);

    const getLessonDetails = useCallback(async (lessonId) => {
        try {
            const response = await privateApi.get(`/study/lessons/${lessonId}`);
            return response.data;
        } catch (error) {
            return {
                is_success: false,
                message: error.response?.data?.message || 'Failed to fetch lesson details'
            };
        }
    }, []);

    const submitQuizAnswers = useCallback(async (lessonId, answers) => {
        try {
            const response = await privateApi.post(`/study/lessons/${lessonId}/quizzes/submit`, answers);
            return {
                is_success: true,
                message: response.data.message,
                data: response.data.data
            };
        } catch (error) {
            if (error.response?.status === 409) { // Conflict - quiz already taken
                // Return the existing quiz data from the error response
                return {
                    is_success: true,
                    message: 'Quiz already completed',
                    data: error.response.data.data
                };
            }
            return {
                is_success: false,
                message: error.response?.data?.message || 'Failed to submit quiz answers',
                data: null
            };
        }
    }, []);

    const completeLesson = useCallback(async (lessonId) => {
        try {
            const response = await privateApi.post(`/study/lessons/${lessonId}/complete`);
            return response.data;
        } catch (error) {
            return {
                is_success: false,
                message: error.response?.data?.message || 'Failed to mark lesson as completed'
            };
        }
    }, []);

    const getLessonItems = useCallback(async (lessonId, itemType) => {
        try {
            const response = await privateApi.get(`/study/lessons/${lessonId}/${itemType}`);
            console.log(`${itemType} API Response:`, response.data);
            return {
                is_success: true,
                message: response.data.message,
                data: response.data.data.data || []
            };
        } catch (error) {
            console.error(`Error fetching ${itemType}:`, error);
            return {
                is_success: false,
                message: error.response?.data?.message || `Failed to fetch lesson ${itemType}`,
                data: []
            };
        }
    }, []);

    return {
        getCourseDetails,
        getCourseSections,
        getSectionChapters,
        getChapterLessons,
        getLessonDetails,
        submitQuizAnswers,
        completeLesson,
        getLessonItems
    };
};

export default useStudyService; 