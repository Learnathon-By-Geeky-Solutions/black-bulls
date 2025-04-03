import PropTypes from 'prop-types';
import styles from './FormElements.module.css';

const FileInput = ({
  label,
  name,
  onChange,
  error,
  accept,
  required = false,
  disabled = false,
  className = '',
  previewUrl,
  ...props
}) => {
  return (
    <div className={`${styles.formGroup} ${className}`}>
      {label && <label htmlFor={name}>{label}</label>}
      <input
        type="file"
        id={name}
        name={name}
        onChange={onChange}
        accept={accept}
        required={required}
        disabled={disabled}
        className={`${styles.fileInput} ${error ? styles.error : ''}`}
        {...props}
      />
      {previewUrl && (
        <div className={styles.previewContainer}>
          <img src={previewUrl} alt="Preview" className={styles.previewImage} />
        </div>
      )}
      {error && <span className={styles.errorMessage}>{error}</span>}
    </div>
  );
};

FileInput.propTypes = {
  label: PropTypes.string,
  name: PropTypes.string.isRequired,
  onChange: PropTypes.func.isRequired,
  error: PropTypes.oneOfType([PropTypes.string, PropTypes.arrayOf(PropTypes.string)]),
  accept: PropTypes.string,
  required: PropTypes.bool,
  disabled: PropTypes.bool,
  className: PropTypes.string,
  previewUrl: PropTypes.string
};

export default FileInput; 