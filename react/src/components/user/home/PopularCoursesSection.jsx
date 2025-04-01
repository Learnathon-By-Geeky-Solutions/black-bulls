import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import styles from './Section.module.css';
import usePopularCourses from '../../../hooks/usePopularCourses';
import CourseCard from './CourseCard';

const PopularCoursesSection = () => {
  const { t } = useTranslation(['home', 'common']);
  const { courses, loading, error } = usePopularCourses();

  if (loading) {
    return <div className={styles.loading}>{t('common:loading')}</div>;
  }

  if (error) {
    return <div className={styles.error}>{t('common:error')}</div>;
  }

  return (
    <section className={styles.section}>
      <div className={styles.sectionHeader}>
        <h2>{t('home:popularCourses.title')}</h2>
        <p>{t('home:popularCourses.subtitle')}</p>
      </div>
      <div className={styles.courseGrid}>
        {courses?.slice(0, 4).map((course) => (
          <CourseCard key={course.id} course={course} />
        ))}
      </div>
      {courses?.length > 4 && (
        <div className={styles.viewAllContainer}>
          <Link to="/courses/popular" className={styles.viewAllButton}>
            {t('home:popularCourses.viewAll')}
          </Link>
        </div>
      )}
    </section>
  );
};

export default PopularCoursesSection; 