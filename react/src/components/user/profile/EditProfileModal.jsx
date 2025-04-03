import PropTypes from 'prop-types';
import { useState, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { useProfile } from '../../../hooks/user/profile/useProfile';
import { toast } from 'react-toastify';
import Modal from '../../common/Modal';
import { Input, TextArea, FileInput } from '../../common/form';

const EditProfileModal = ({ user, onClose }) => {
  const { t } = useTranslation('profile');
  const { updateProfile, isLoading } = useProfile();
  const [formData, setFormData] = useState({
    name: '',
    phone: '',
    profile_picture: null,
    designation: '',
    institution: '',
    dept: '',
    address: ''
  });
  const [previewUrl, setPreviewUrl] = useState(null);
  const [errors, setErrors] = useState({});

  useEffect(() => {
    if (user) {
      setFormData({
        name: user.name || '',
        phone: user.phone || '',
        profile_picture: null,
        designation: user.user_details?.designation || '',
        institution: user.user_details?.institution || '',
        dept: user.user_details?.dept || '',
        address: user.user_details?.address || ''
      });
      setPreviewUrl(user.profile_picture || null);
    }
  }, [user]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
    if (errors[name]) {
      setErrors(prev => ({
        ...prev,
        [name]: null
      }));
    }
  };

  const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setFormData(prev => ({
        ...prev,
        profile_picture: file
      }));
      setPreviewUrl(URL.createObjectURL(file));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});

    const result = await updateProfile(formData);
    if (result.success) {
      toast.success(result.message);
      onClose();
    } else if (result.errors) {
      setErrors(result.errors);
    } else {
      toast.error(result.message || t('profile.updateError'));
    }
  };

  return (
    <Modal
      title={t('profile.editProfile')}
      onClose={onClose}
      onSubmit={handleSubmit}
      isLoading={isLoading}
    >
      <Input
        label={t('profile.name')}
        name="name"
        value={formData.name}
        onChange={handleChange}
        error={errors.name}
        required
      />

      <Input
        label={t('profile.phone')}
        name="phone"
        value={formData.phone}
        onChange={handleChange}
        error={errors.phone}
        required
      />

      <FileInput
        label={t('profile.profilePicture')}
        name="profile_picture"
        onChange={handleFileChange}
        error={errors.profile_picture}
        accept="image/*"
        previewUrl={previewUrl}
      />

      <Input
        label={t('profile.designation')}
        name="designation"
        value={formData.designation}
        onChange={handleChange}
        error={errors.designation}
      />

      <Input
        label={t('profile.institution')}
        name="institution"
        value={formData.institution}
        onChange={handleChange}
        error={errors.institution}
      />

      <Input
        label={t('profile.department')}
        name="dept"
        value={formData.dept}
        onChange={handleChange}
        error={errors.dept}
      />

      <TextArea
        label={t('profile.address')}
        name="address"
        value={formData.address}
        onChange={handleChange}
        error={errors.address}
        rows={4}
      />
    </Modal>
  );
};

EditProfileModal.propTypes = {
  user: PropTypes.shape({
    name: PropTypes.string,
    phone: PropTypes.string,
    profile_picture: PropTypes.string,
    user_details: PropTypes.shape({
      designation: PropTypes.string,
      institution: PropTypes.string,
      dept: PropTypes.string,
      address: PropTypes.string
    })
  }),
  onClose: PropTypes.func.isRequired
};

export default EditProfileModal; 