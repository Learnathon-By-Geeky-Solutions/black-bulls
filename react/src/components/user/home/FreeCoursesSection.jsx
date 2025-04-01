import useCourseData from '../../../hooks/user/course/useCourseData';
import CourseSection from './CourseSection';

const FreeCoursesSection = () => {
    const { courses, loading, error } = useCourseData(
        '/home/free-courses',
        'Failed to fetch free courses'
    );

    // Transform the courses data to handle the nested structure
    const transformedCourses = courses?.data || [];

    return (
        <CourseSection
            titleKey="home:freeCourses.title"
            subtitleKey="home:freeCourses.subtitle"
            viewAllKey="home:freeCourses.viewAll"
            viewAllLink="/courses/free-courses"
            courses={transformedCourses}
            loading={loading}
            error={error}
            maxDisplay={4}
        />
    );
};

export default FreeCoursesSection; 