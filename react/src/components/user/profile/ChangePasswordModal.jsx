import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import PropTypes from 'prop-types';
import { useProfile } from '../../../hooks/user/profile/useProfile';
import { toast } from 'react-toastify';
import styles from './ChangePasswordModal.module.css';

const ChangePasswordModal = ({ onClose }) => {
  const { t } = useTranslation('profile');
  const { updatePassword, isLoading } = useProfile();
  const [formData, setFormData] = useState({
    current_password: '',
    new_password: '',
    new_password_confirmation: ''
  });
  const [errors, setErrors] = useState({});
  const [formError, setFormError] = useState('');

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
    // Clear error when user types
    if (errors[name]) {
      setErrors(prev => ({
        ...prev,
        [name]: null
      }));
    }
    // Clear form error when user types
    if (formError) {
      setFormError('');
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({}); // Clear previous errors
    setFormError(''); // Clear previous form error

    const result = await updatePassword(formData);
    console.log('Password update result:', result);
    
    if (result.success) {
      toast.success(result.message);
      onClose();
    } else if (result.errors) {
      console.log('Setting validation errors:', result.errors);
      setErrors(result.errors);
    } else if (result.message) {
      console.log('Setting form error:', result.message);
      setFormError(result.message);
    }
  };

  return (
    <div className={styles.modalOverlay}>
      <div className={styles.modalContent}>
        <h2>{t('profile.changePassword')}</h2>
        {formError && (
          <div className={styles.formError}>
            {formError}
          </div>
        )}
        <form onSubmit={handleSubmit}>
          <div className={styles.formGroup}>
            <label>{t('profile.currentPassword')}</label>
            <input
              type="password"
              name="current_password"
              value={formData.current_password}
              onChange={handleChange}
              className={errors.current_password ? styles.error : ''}
            />
            {errors.current_password && (
              <span className={styles.errorMessage}>
                {Array.isArray(errors.current_password) 
                  ? errors.current_password[0] 
                  : errors.current_password}
              </span>
            )}
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.newPassword')}</label>
            <input
              type="password"
              name="new_password"
              value={formData.new_password}
              onChange={handleChange}
              className={errors.new_password ? styles.error : ''}
            />
            {errors.new_password && (
              <span className={styles.errorMessage}>
                {Array.isArray(errors.new_password) 
                  ? errors.new_password[0] 
                  : errors.new_password}
              </span>
            )}
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.confirmPassword')}</label>
            <input
              type="password"
              name="new_password_confirmation"
              value={formData.new_password_confirmation}
              onChange={handleChange}
              className={errors.new_password_confirmation ? styles.error : ''}
            />
            {errors.new_password_confirmation && (
              <span className={styles.errorMessage}>
                {Array.isArray(errors.new_password_confirmation) 
                  ? errors.new_password_confirmation[0] 
                  : errors.new_password_confirmation}
              </span>
            )}
          </div>

          <div className={styles.modalActions}>
            <button 
              type="button" 
              onClick={onClose} 
              className={styles.cancelButton}
              disabled={isLoading}
            >
              {t('profile.cancel')}
            </button>
            <button 
              type="submit" 
              className={styles.saveButton}
              disabled={isLoading}
            >
              {isLoading ? t('profile.saving') : t('profile.save')}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

ChangePasswordModal.propTypes = {
  onClose: PropTypes.func.isRequired
};

export default ChangePasswordModal; 