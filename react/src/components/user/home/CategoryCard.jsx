import { Link } from 'react-router-dom';
import styles from './Section.module.css';

const CategoryCard = ({ category }) => {
  return (
    <Link to={`/categories/${category.id}`} className={styles.categoryCard}>
      <div className={styles.categoryImage}>
        <img src={category.image} alt={category.name} />
      </div>
      <div className={styles.categoryContent}>
        <h3>{category.name}</h3>
        <p className={styles.description}>{category.description?.substring(0, 100)}...</p>
      </div>
    </Link>
  );
};

export default CategoryCard; 