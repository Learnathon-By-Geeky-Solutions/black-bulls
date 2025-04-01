import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import styles from './Section.module.css';
import useTrendingCourses from '../../../hooks/useTrendingCourses';
import CourseCard from './CourseCard';

const TrendingCoursesSection = () => {
  const { t } = useTranslation(['home', 'common']);
  const { courses, loading, error } = useTrendingCourses();

  if (loading) {
    return <div className={styles.loading}>{t('common:loading')}</div>;
  }

  if (error) {
    return <div className={styles.error}>{t('common:error')}</div>;
  }

  return (
    <section className={styles.section}>
      <div className={styles.sectionHeader}>
        <h2>{t('home:trendingCourses.title')}</h2>
        <p>{t('home:trendingCourses.subtitle')}</p>
      </div>
      <div className={styles.courseGrid}>
        {courses?.slice(0, 4).map((course) => (
          <CourseCard key={course.id} course={course} />
        ))}
      </div>
      {courses?.length > 4 && (
        <div className={styles.viewAllContainer}>
          <Link to="/courses/trending" className={styles.viewAllButton}>
            {t('home:trendingCourses.viewAll')}
          </Link>
        </div>
      )}
    </section>
  );
};

export default TrendingCoursesSection; 