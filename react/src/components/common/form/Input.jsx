import PropTypes from 'prop-types';
import styles from './FormElements.module.css';

const Input = ({
  label,
  name,
  type = 'text',
  value,
  onChange,
  error,
  placeholder,
  required = false,
  disabled = false,
  className = '',
  ...props
}) => {
  return (
    <div className={`${styles.formGroup} ${className}`}>
      {label && <label htmlFor={name}>{label}</label>}
      <input
        id={name}
        name={name}
        type={type}
        value={value}
        onChange={onChange}
        placeholder={placeholder}
        required={required}
        disabled={disabled}
        className={`${styles.input} ${error ? styles.error : ''}`}
        {...props}
      />
      {error && (
        <span className={styles.errorMessage}>
          {Array.isArray(error) ? error[0] : error}
        </span>
      )}
    </div>
  );
};

Input.propTypes = {
  label: PropTypes.string,
  name: PropTypes.string.isRequired,
  type: PropTypes.oneOf([
    'text',
    'password',
    'email',
    'number',
    'tel',
    'url',
    'search',
    'date',
    'time',
    'datetime-local',
    'month',
    'week',
    'color'
  ]),
  value: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  onChange: PropTypes.func.isRequired,
  error: PropTypes.oneOfType([PropTypes.string, PropTypes.arrayOf(PropTypes.string)]),
  placeholder: PropTypes.string,
  required: PropTypes.bool,
  disabled: PropTypes.bool,
  className: PropTypes.string
};

export default Input; 