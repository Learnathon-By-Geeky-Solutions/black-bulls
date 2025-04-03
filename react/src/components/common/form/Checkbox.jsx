import PropTypes from 'prop-types';
import styles from './FormElements.module.css';

const Checkbox = ({
  label,
  name,
  checked,
  onChange,
  error,
  required = false,
  disabled = false,
  className = '',
  ...props
}) => {
  return (
    <div className={`${styles.formGroup} ${styles.checkboxGroup} ${className}`}>
      <label className={styles.checkboxLabel}>
        <input
          type="checkbox"
          name={name}
          checked={checked}
          onChange={onChange}
          required={required}
          disabled={disabled}
          className={styles.checkbox}
          {...props}
        />
        <span className={styles.checkboxText}>{label}</span>
      </label>
      {error && <span className={styles.errorMessage}>{error}</span>}
    </div>
  );
};

Checkbox.propTypes = {
  label: PropTypes.string.isRequired,
  name: PropTypes.string.isRequired,
  checked: PropTypes.bool,
  onChange: PropTypes.func.isRequired,
  error: PropTypes.oneOfType([PropTypes.string, PropTypes.arrayOf(PropTypes.string)]),
  required: PropTypes.bool,
  disabled: PropTypes.bool,
  className: PropTypes.string
};

export default Checkbox; 