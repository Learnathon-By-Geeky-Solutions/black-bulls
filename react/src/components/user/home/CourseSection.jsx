import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import styles from './Section.module.css';
import CourseCard from './CourseCard';

const CourseSection = ({ 
    titleKey, 
    subtitleKey, 
    viewAllKey, 
    viewAllLink, 
    courses, 
    loading, 
    error,
    maxDisplay = 4 
}) => {
    const { t } = useTranslation(['home', 'common']);

    if (loading) {
        return <div className={styles.loading}>{t('common:loading')}</div>;
    }

    if (error) {
        return <div className={styles.error}>{t('common:error')}</div>;
    }

    return (
        <section className={styles.section}>
            <div className={styles.sectionHeader}>
                <h2>{t(titleKey)}</h2>
                <p>{t(subtitleKey)}</p>
            </div>
            <div className={styles.courseGrid}>
                {courses?.slice(0, maxDisplay).map((course) => (
                    <CourseCard key={course.id} course={course} />
                ))}
            </div>
            {courses?.length > maxDisplay && (
                <div className={styles.viewAllContainer}>
                    <Link to={viewAllLink} className={styles.viewAllButton}>
                        {t(viewAllKey)}
                    </Link>
                </div>
            )}
        </section>
    );
};

export default CourseSection; 