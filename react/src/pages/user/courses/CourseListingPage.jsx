import { useParams } from 'react-router-dom';
import { useQuery } from '@tanstack/react-query';
import { publicApi } from '../../../config/axios';
import CourseCard from '../../../components/user/home/CourseCard';
import styles from './CourseListingPage.module.css';

const CourseListingPage = () => {
  const { type } = useParams();
  
  const getTitle = () => {
    switch (type) {
      case 'free':
        return 'Free Courses';
      case 'popular':
        return 'Most Popular Courses';
      case 'trending':
        return 'Trending Courses';
      default:
        return 'All Courses';
    }
  };

  const getEndpoint = () => {
    switch (type) {
      case 'free':
        return '/courses/free';
      case 'popular':
        return '/courses/popular';
      case 'trending':
        return '/courses/trending';
      default:
        return '/courses';
    }
  };

  const { data: courses, isLoading, error } = useQuery({
    queryKey: ['courses', type],
    queryFn: async () => {
      const response = await publicApi.get(getEndpoint());
      return response.data.data;
    }
  });

  if (isLoading) return <div className={styles.loading}>Loading...</div>;
  if (error) return <div className={styles.error}>Error loading courses</div>;

  return (
    <div className={styles.courseListingPage}>
      <div className={styles.sectionHeader}>
        <h2>{getTitle()}</h2>
        <p>Explore our collection of {getTitle().toLowerCase()}</p>
      </div>
      <div className={styles.courseGrid}>
        {courses?.map((course) => (
          <CourseCard key={course.id} course={course} />
        ))}
      </div>
    </div>
  );
};

export default CourseListingPage; 