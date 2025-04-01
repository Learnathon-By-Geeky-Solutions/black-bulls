import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { useFreeCourses } from '../../../hooks/useFreeCourses';
import CourseCard from './CourseCard';
import styles from './Section.module.css';

const FreeCoursesSection = () => {
  const { t } = useTranslation(['home', 'common']);
  const { courses, isLoading, error } = useFreeCourses();

  if (isLoading) return <div className={styles.loading}>{t('common:loading')}</div>;
  if (error) return <div className={styles.error}>{t('common:error')}</div>;

  return (
    <section className={styles.section}>
      <div className={styles.sectionHeader}>
        <h2>{t('home:freeCourses.title')}</h2>
        <p>{t('home:freeCourses.subtitle')}</p>
      </div>
      <div className={styles.courseGrid}>
        {courses?.slice(0, 4).map((course) => (
          <CourseCard key={course.id} course={course} />
        ))}
      </div>
      {courses?.length > 3 && (
        <div className={styles.viewAllContainer}>
          <Link to="/courses/free-courses" className={styles.viewAllButton}>
            {t('home:freeCourses.viewAll')}
          </Link>
        </div>
      )}
    </section>
  );
};

export default FreeCoursesSection; 