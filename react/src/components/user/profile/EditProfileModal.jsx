import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import PropTypes from 'prop-types';
import styles from './EditProfileModal.module.css';

const EditProfileModal = ({ user, onClose, onSave }) => {
  const { t } = useTranslation('profile');
  const [formData, setFormData] = useState({
    name: user.name || '',
    phone: user.phone || '',
    profile_picture: null,
    user_details: {
      designation: user.user_details?.designation || '',
      institution: user.user_details?.institution || '',
      dept: user.user_details?.dept || '',
      address: user.user_details?.address || ''
    }
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    if (name.includes('.')) {
      const [parent, child] = name.split('.');
      setFormData(prev => ({
        ...prev,
        [parent]: {
          ...prev[parent],
          [child]: value
        }
      }));
    } else {
      setFormData(prev => ({
        ...prev,
        [name]: value
      }));
    }
  };

  const handleFileChange = (e) => {
    setFormData(prev => ({
      ...prev,
      profile_picture: e.target.files[0]
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    onSave(formData);
  };

  return (
    <div className={styles.modalOverlay}>
      <div className={styles.modalContent}>
        <h2>{t('profile.editProfile')}</h2>
        <form onSubmit={handleSubmit}>
          <div className={styles.formGroup}>
            <label>{t('profile.name')}</label>
            <input
              type="text"
              name="name"
              value={formData.name}
              onChange={handleChange}
            />
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.phone')}</label>
            <input
              type="text"
              name="phone"
              value={formData.phone}
              onChange={handleChange}
            />
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.profilePicture')}</label>
            <input
              type="file"
              accept="image/*"
              onChange={handleFileChange}
            />
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.designation')}</label>
            <input
              type="text"
              name="user_details.designation"
              value={formData.user_details.designation}
              onChange={handleChange}
            />
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.institution')}</label>
            <input
              type="text"
              name="user_details.institution"
              value={formData.user_details.institution}
              onChange={handleChange}
            />
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.department')}</label>
            <input
              type="text"
              name="user_details.dept"
              value={formData.user_details.dept}
              onChange={handleChange}
            />
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.address')}</label>
            <textarea
              name="user_details.address"
              value={formData.user_details.address}
              onChange={handleChange}
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

EditProfileModal.propTypes = {
  user: PropTypes.shape({
    name: PropTypes.string,
    phone: PropTypes.string,
    user_details: PropTypes.shape({
      designation: PropTypes.string,
      institution: PropTypes.string,
      dept: PropTypes.string,
      address: PropTypes.string
    })
  }).isRequired,
  onClose: PropTypes.func.isRequired,
  onSave: PropTypes.func.isRequired
};

export default EditProfileModal; 