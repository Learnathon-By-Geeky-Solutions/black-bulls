import PropTypes from 'prop-types';
import styles from './Modal.module.css';

const Modal = ({ 
  title, 
  children, 
  onClose, 
  onSubmit, 
  isLoading, 
  submitText = 'Save',
  cancelText = 'Cancel',
  showActions = true
}) => {
  return (
    <div className={styles.modalOverlay}>
      <div className={styles.modalContent}>
        <h2>{title}</h2>
        <form onSubmit={onSubmit}>
          {children}
          {showActions && (
            <div className={styles.modalActions}>
              <button 
                type="button" 
                onClick={onClose} 
                className={styles.cancelButton}
                disabled={isLoading}
              >
                {cancelText}
              </button>
              <button 
                type="submit" 
                className={styles.saveButton}
                disabled={isLoading}
              >
                {isLoading ? 'Saving...' : submitText}
              </button>
            </div>
          )}
        </form>
      </div>
    </div>
  );
};

Modal.propTypes = {
  title: PropTypes.string.isRequired,
  children: PropTypes.node.isRequired,
  onClose: PropTypes.func.isRequired,
  onSubmit: PropTypes.func.isRequired,
  isLoading: PropTypes.bool,
  submitText: PropTypes.string,
  cancelText: PropTypes.string,
  showActions: PropTypes.bool
};

export default Modal; 