import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useNavigate } from 'react-router-dom';
import { useDispatch } from 'react-redux';
import { useProfile } from '../../../hooks/user/profile/useProfile';
import { clearUser } from '../../../redux/slices/userSlice';
import EditProfileModal from '../../../components/user/profile/EditProfileModal';
import ChangePasswordModal from '../../../components/user/profile/ChangePasswordModal';
import styles from './Profile.module.css';

const ProfilePage = () => {
  const { t } = useTranslation('profile');
  const navigate = useNavigate();
  const dispatch = useDispatch();
  const { user, isLoading, error, updateProfile, updatePassword } = useProfile();
  const [showEditModal, setShowEditModal] = useState(false);
  const [showPasswordModal, setShowPasswordModal] = useState(false);

  const handleLogout = () => {
    localStorage.removeItem(import.meta.env.VITE_AUTH_TOKEN_KEY);
    dispatch(clearUser());
    navigate('/');
  };

  const handleUpdateProfile = async (formData) => {
    try {
      const result = await updateProfile(formData);
      if (result.success) {
        setShowEditModal(false);
      }
    } catch (error) {
      console.error('Failed to update profile:', error);
    }
  };

  const handleUpdatePassword = async (formData) => {
    try {
      const result = await updatePassword(formData);
      if (result.success) {
        setShowPasswordModal(false);
      }
    } catch (error) {
      console.error('Failed to update password:', error);
    }
  };

  if (isLoading) {
    return (
      <div className={styles.profileContainer}>
        <div className={styles.loading}>
          <div className={styles.spinner}></div>
          <p>{t('profile.loading')}</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className={styles.profileContainer}>
        <div className={styles.error}>
          <p>{error}</p>
          <button onClick={() => window.location.reload()}>
            {t('profile.retry')}
          </button>
        </div>
      </div>
    );
  }

  if (!user) {
    return (
      <div className={styles.profileContainer}>
        <div className={styles.error}>
          <p>{t('profile.noUser')}</p>
          <button onClick={() => navigate('/login')}>
            {t('profile.login')}
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className={styles.profileContainer}>
      <div className={styles.profileCard}>
        <div className={styles.profileHeader}>
          <div className={styles.avatar}>
            {user.profile_picture ? (
              <img 
                src={user.profile_picture} 
                alt={user.name} 
                className={styles.avatarImage}
              />
            ) : (
              <span className={styles.avatarLetter}>
                {user.name.charAt(0).toUpperCase()}
              </span>
            )}
          </div>
          <h1>{user.name}</h1>
          <p className={styles.role}>{user.role}</p>
        </div>

        <div className={styles.profileInfo}>
          <div className={styles.infoGroup}>
            <label>{t('profile.email')}</label>
            <p>{user.email}</p>
          </div>

          <div className={styles.infoGroup}>
            <label>{t('profile.phone')}</label>
            <p>{user.phone || '-'}</p>
          </div>

          {user.user_details && (
            <>
              <div className={styles.infoGroup}>
                <label>{t('profile.designation')}</label>
                <p>{user.user_details.designation || '-'}</p>
              </div>

              <div className={styles.infoGroup}>
                <label>{t('profile.institution')}</label>
                <p>{user.user_details.institution || '-'}</p>
              </div>

              <div className={styles.infoGroup}>
                <label>{t('profile.department')}</label>
                <p>{user.user_details.dept || '-'}</p>
              </div>

              <div className={styles.infoGroup}>
                <label>{t('profile.address')}</label>
                <p>{user.user_details.address || '-'}</p>
              </div>
            </>
          )}
        </div>

        <div className={styles.profileActions}>
          <button 
            className={styles.editButton}
            onClick={() => setShowEditModal(true)}
          >
            {t('profile.editProfile')}
          </button>
          <button 
            className={styles.changePasswordButton}
            onClick={() => setShowPasswordModal(true)}
          >
            {t('profile.changePassword')}
          </button>
          <button 
            className={styles.logoutButton}
            onClick={handleLogout}
          >
            {t('profile.logout')}
          </button>
        </div>
      </div>

      {showEditModal && (
        <EditProfileModal
          user={user}
          onClose={() => setShowEditModal(false)}
          onSave={handleUpdateProfile}
        />
      )}

      {showPasswordModal && (
        <ChangePasswordModal
          onClose={() => setShowPasswordModal(false)}
          onSave={handleUpdatePassword}
        />
      )}
    </div>
  );
};

export default ProfilePage; 