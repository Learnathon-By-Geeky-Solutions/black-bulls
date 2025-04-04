import { Link } from 'react-router-dom';
import PropTypes from 'prop-types';
import styles from './Section.module.css';

const CourseCard = ({ course }) => {
  return (
    <Link to={`/learn/courses/${course.id}`} className={styles.courseCard}>
      <div className={styles.courseImage}>
        <img src={course.thumbnail} alt={course.title} />
      </div>
      <div className={styles.courseContent}>
        <h3>{course.title}</h3>
        <p className={styles.description}>{course.description?.substring(0, 100)}...</p>
        <div className={styles.categories}>
          {course.categories?.map((category) => (
            <span key={category.id} className={styles.categoryTag}>
              {category.name}
            </span>
          ))}
        </div>
        <div className={styles.instructor}>
          Instructor: {course.instructor?.name}
        </div>
        <div className={styles.price}>
          {course.price === 0 ? 'Free' : `$${course.price}`}
        </div>
      </div>
    </Link>
  );
};

CourseCard.propTypes = {
  course: PropTypes.shape({
    id: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired,
    thumbnail: PropTypes.string.isRequired,
    title: PropTypes.string.isRequired,
    description: PropTypes.string,
    categories: PropTypes.arrayOf(PropTypes.shape({
      id: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired,
      name: PropTypes.string.isRequired
    })),
    instructor: PropTypes.shape({
      name: PropTypes.string.isRequired
    }),
    price: PropTypes.number.isRequired
  }).isRequired
};

export default CourseCard; 