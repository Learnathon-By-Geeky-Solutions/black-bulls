import { useState, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import PropTypes from 'prop-types';
import { useProfile } from '../../../hooks/user/profile/useProfile';
import { toast } from 'react-toastify';
import styles from './EditProfileModal.module.css';

const EditProfileModal = ({ user, onClose }) => {
  const { t } = useTranslation('profile');
  const { updateProfile, isLoading, error } = useProfile();
  const [formData, setFormData] = useState({
    name: user.name || '',
    phone: user.phone || '',
    profile_picture: null,
    designation: user.user_details?.designation || '',
    institution: user.user_details?.institution || '',
    dept: user.user_details?.dept || '',
    address: user.user_details?.address || ''
  });

  useEffect(() => {
    if (error) {
      toast.error(error);
    }
  }, [error]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      // Validate file type
      if (!file.type.startsWith('image/')) {
        toast.error(t('profile.invalidFileType'));
        return;
      }
      // Validate file size (2MB limit)
      if (file.size > 2 * 1024 * 1024) {
        toast.error(t('profile.fileTooLarge'));
        return;
      }
      setFormData(prev => ({
        ...prev,
        profile_picture: file
      }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const result = await updateProfile(formData);
    
    if (result.success) {
      toast.success(result.message);
      onClose();
    } else {
      toast.error(result.message);
    }
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
              required
            />
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.phone')}</label>
            <input
              type="text"
              name="phone"
              value={formData.phone}
              onChange={handleChange}
              required
            />
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.profilePicture')}</label>
            <input
              type="file"
              accept="image/*"
              onChange={handleFileChange}
            />
            {formData.profile_picture && (
              <div className={styles.filePreview}>
                <img 
                  src={URL.createObjectURL(formData.profile_picture)} 
                  alt="Preview" 
                  className={styles.previewImage}
                />
              </div>
            )}
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.designation')}</label>
            <input
              type="text"
              name="designation"
              value={formData.designation}
              onChange={handleChange}
            />
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.institution')}</label>
            <input
              type="text"
              name="institution"
              value={formData.institution}
              onChange={handleChange}
            />
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.department')}</label>
            <input
              type="text"
              name="dept"
              value={formData.dept}
              onChange={handleChange}
            />
          </div>

          <div className={styles.formGroup}>
            <label>{t('profile.address')}</label>
            <textarea
              name="address"
              value={formData.address}
              onChange={handleChange}
            />
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

EditProfileModal.propTypes = {
  user: PropTypes.shape({
    name: PropTypes.string.isRequired,
    phone: PropTypes.string.isRequired,
    designation: PropTypes.string,
    institution: PropTypes.string,
    dept: PropTypes.string,
    address: PropTypes.string
  }).isRequired,
  onClose: PropTypes.func.isRequired
};

export default EditProfileModal; 