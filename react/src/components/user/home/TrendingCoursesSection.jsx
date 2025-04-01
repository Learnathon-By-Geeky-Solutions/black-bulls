import useCourseData from '../../../hooks/user/course/useCourseData';
import CourseSection from './CourseSection';

const TrendingCoursesSection = () => {
    const { courses, loading, error } = useCourseData(
        '/home/trending-courses',
        'Failed to fetch trending courses'
    );

    return (
        <CourseSection
            titleKey="home:trendingCourses.title"
            subtitleKey="home:trendingCourses.subtitle"
            viewAllKey="home:trendingCourses.viewAll"
            viewAllLink="/courses/trending"
            courses={courses}
            loading={loading}
            error={error}
        />
    );
};

export default TrendingCoursesSection; 