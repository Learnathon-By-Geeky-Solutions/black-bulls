import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { publicApi } from '../../../config/axios';
import styles from './Auth.module.css';

const RegisterPage = () => {
  const { t } = useTranslation('auth');
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    name: '',
    phone: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'student',
    profile_picture: null
  });
  const [error, setError] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  const handleChange = (e) => {
    const { name, value, type, files } = e.target;
    if (type === 'file') {
      setFormData({
        ...formData,
        [name]: files[0]
      });
    } else {
      setFormData({
        ...formData,
        [name]: value
      });
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    // Basic validation
    if (formData.password !== formData.password_confirmation) {
      setError(t('register.passwordMismatch'));
      return;
    }

    if (formData.password.length < 2) {
      setError(t('register.passwordLength'));
      return;
    }

    setIsLoading(true);

    try {
      const formDataToSend = new FormData();
      Object.keys(formData).forEach(key => {
        if (formData[key] !== null) {
          formDataToSend.append(key, formData[key]);
        }
      });

      const response = await publicApi.post('/register', formDataToSend, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });

      if (response.data.is_success) {
        localStorage.setItem(import.meta.env.VITE_AUTH_TOKEN_KEY, response.data.token.access_token);
        localStorage.setItem(import.meta.env.VITE_AUTH_USER_KEY, JSON.stringify(response.data.user));
        navigate('/profile');
      } else {
        setError(response.data.message || t('register.failed'));
      }
    } catch (err) {
      const errorMessage = err.response?.data?.message || 
                          err.response?.data?.errors || 
                          t('register.failed');
      setError(errorMessage);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className={styles.authContainer}>
      <div className={styles.authCard}>
        <h2>{t('register.title')}</h2>
        <p>{t('register.subtitle')}</p>
        
        {error && <div className={styles.error}>{error}</div>}
        
        <form onSubmit={handleSubmit} className={styles.authForm}>
          <div className={styles.formGroup}>
            <label htmlFor="name">{t('register.name')}</label>
            <input
              type="text"
              id="name"
              name="name"
              value={formData.name}
              onChange={handleChange}
              required
            />
          </div>

          <div className={styles.formGroup}>
            <label htmlFor="phone">{t('register.phone')}</label>
            <input
              type="tel"
              id="phone"
              name="phone"
              value={formData.phone}
              onChange={handleChange}
              required
              maxLength="14"
            />
          </div>

          <div className={styles.formGroup}>
            <label htmlFor="email">{t('register.email')}</label>
            <input
              type="email"
              id="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              required
            />
          </div>

          <div className={styles.formGroup}>
            <label htmlFor="role">{t('register.role')}</label>
            <select
              id="role"
              name="role"
              value={formData.role}
              onChange={handleChange}
              required
            >
              <option value="student">Student</option>
              <option value="instructor">Instructor</option>
            </select>
          </div>

          <div className={styles.formGroup}>
            <label htmlFor="profile_picture">{t('register.profilePicture')}</label>
            <input
              type="file"
              id="profile_picture"
              name="profile_picture"
              onChange={handleChange}
              accept="image/jpeg,image/jpg,image/png"
            />
          </div>
          
          <div className={styles.formGroup}>
            <label htmlFor="password">{t('register.password')}</label>
            <input
              type="password"
              id="password"
              name="password"
              value={formData.password}
              onChange={handleChange}
              required
              minLength="2"
            />
          </div>

          <div className={styles.formGroup}>
            <label htmlFor="password_confirmation">{t('register.confirmPassword')}</label>
            <input
              type="password"
              id="password_confirmation"
              name="password_confirmation"
              value={formData.password_confirmation}
              onChange={handleChange}
              required
            />
          </div>
          
          <button 
            type="submit" 
            className={styles.submitButton}
            disabled={isLoading}
          >
            {isLoading ? t('register.loading') : t('register.submit')}
          </button>
        </form>
        
        <div className={styles.authLinks}>
          <p>
            {t('register.haveAccount')}{' '}
            <a href="/login">{t('register.login')}</a>
          </p>
        </div>
      </div>
    </div>
  );
};

export default RegisterPage; 