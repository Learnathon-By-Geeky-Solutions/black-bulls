import { Link } from 'react-router-dom';
import PropTypes from 'prop-types';
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

CategoryCard.propTypes = {
  category: PropTypes.shape({
    id: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired,
    image: PropTypes.string.isRequired,
    name: PropTypes.string.isRequired,
    description: PropTypes.string
  }).isRequired
};

export default CategoryCard; 