import PropTypes from 'prop-types';
import styles from './FormElements.module.css';

const Radio = ({
  label,
  name,
  value,
  checked,
  onChange,
  error,
  required = false,
  disabled = false,
  className = '',
  ...props
}) => {
  return (
    <div className={`${styles.formGroup} ${styles.radioGroup} ${className}`}>
      <label className={styles.radioLabel}>
        <input
          type="radio"
          name={name}
          value={value}
          checked={checked}
          onChange={onChange}
          required={required}
          disabled={disabled}
          className={styles.radio}
          {...props}
        />
        <span className={styles.radioText}>{label}</span>
      </label>
      {error && <span className={styles.errorMessage}>{error}</span>}
    </div>
  );
};

Radio.propTypes = {
  label: PropTypes.string.isRequired,
  name: PropTypes.string.isRequired,
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired,
  checked: PropTypes.bool,
  onChange: PropTypes.func.isRequired,
  error: PropTypes.oneOfType([PropTypes.string, PropTypes.arrayOf(PropTypes.string)]),
  required: PropTypes.bool,
  disabled: PropTypes.bool,
  className: PropTypes.string
};

export default Radio; 