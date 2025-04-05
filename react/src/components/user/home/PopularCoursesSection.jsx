import useCourseData from '../../../hooks/user/course/useCourseData';
import CourseSection from './CourseSection';

const PopularCoursesSection = () => {
    const { courses, loading, error } = useCourseData(
        '/home/popular-courses',
        'Failed to fetch popular courses'
    );

    return (
        <CourseSection
            titleKey="home:popularCourses.title"
            subtitleKey="home:popularCourses.subtitle"
            viewAllKey="home:popularCourses.viewAll"
            viewAllLink="/home/popular-courses"
            courses={courses}
            loading={loading}
            error={error}
        />
    );
};

export default PopularCoursesSection; 