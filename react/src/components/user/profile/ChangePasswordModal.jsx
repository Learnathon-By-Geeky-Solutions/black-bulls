import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import styles from './ChangePasswordModal.module.css';

const ChangePasswordModal = ({ onClose, onSave }) => {
  const { t } = useTranslation('profile');
  const [formData, setFormData] = useState({
    current_password: '',
    password: '',
    password_confirmation: ''
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    onSave(formData);
  };

  return (
    <div className={styles.modalOverlay}>
      <div className={styles.modalContent}>
        <h2>{t('profile.changePassword')}</h2>
        <form onSubmit={handleSubmit}>
          <div className={styles.formGroup}>
            <label>{t('profile.currentPassword')}</label>
            <input
              type="password"
              name="current_password"
              value={formData.current_password}
              onChange={handleChange}
              required
            />
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.newPassword')}</label>
            <input
              type="password"
              name="password"
              value={formData.password}
              onChange={handleChange}
              required
            />
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.confirmPassword')}</label>
            <input
              type="password"
              name="password_confirmation"
              value={formData.password_confirmation}
              onChange={handleChange}
              required
            />
          </div>

          <div className={styles.modalActions}>
            <button type="button" onClick={onClose} className={styles.cancelButton}>
              {t('profile.cancel')}
            </button>
            <button type="submit" className={styles.saveButton}>
              {t('profile.save')}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default ChangePasswordModal; 