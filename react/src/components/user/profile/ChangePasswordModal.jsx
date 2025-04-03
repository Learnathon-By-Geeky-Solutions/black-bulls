import PropTypes from 'prop-types';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useProfile } from '../../../hooks/user/profile/useProfile';
import { toast } from 'react-toastify';
import Modal from '../../common/Modal';
import { Input } from '../../common/form';
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
    <Modal
      title={t('profile.changePassword')}
      onClose={onClose}
      onSubmit={handleSubmit}
      isLoading={isLoading}
    >
      {formError && (
        <div className={styles.formError}>
          {formError}
        </div>
      )}
      <Input
        label={t('profile.currentPassword')}
        name="current_password"
        type="password"
        value={formData.current_password}
        onChange={handleChange}
        error={errors.current_password}
        required
      />

      <Input
        label={t('profile.newPassword')}
        name="new_password"
        type="password"
        value={formData.new_password}
        onChange={handleChange}
        error={errors.new_password}
        required
      />

      <Input
        label={t('profile.confirmPassword')}
        name="new_password_confirmation"
        type="password"
        value={formData.new_password_confirmation}
        onChange={handleChange}
        error={errors.new_password_confirmation}
        required
      />
    </Modal>
  );
};

ChangePasswordModal.propTypes = {
  onClose: PropTypes.func.isRequired
};

export default ChangePasswordModal; 