import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import styles from './Section.module.css';
import useCourseData from '../../../hooks/user/course/useCourseData';
import CategoryCard from './CategoryCard';

const CategoriesSection = () => {
  const { t } = useTranslation(['home', 'common']);
  const { courses: categories, loading, error } = useCourseData(
    '/home/categories',
    'Failed to fetch categories'
  );

  if (loading) {
    return <div className={styles.loading}>{t('common:loading')}</div>;
  }

  if (error) {
    return <div className={styles.error}>{t('common:error')}</div>;
  }

  return (
    <section className={styles.section}>
      <div className={styles.sectionHeader}>
        <h2>{t('home:categories.title')}</h2>
        <p>{t('home:categories.subtitle')}</p>
      </div>
      <div className={styles.categoriesGrid}>
        {categories?.slice(0, 8).map((category) => (
          <CategoryCard key={category.id} category={category} />
        ))}
      </div>
      {categories?.length > 8 && (
        <div className={styles.viewAllContainer}>
          <Link to="/home/categories" className={styles.viewAllButton}>
            {t('home:categories.viewAll')}
          </Link>
        </div>
      )}
    </section>
  );
};

export default CategoriesSection; 