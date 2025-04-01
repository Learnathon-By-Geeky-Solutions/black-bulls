import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { publicApi } from '../../../config/axios';
import styles from './Auth.module.css';

const LoginPage = () => {
  const { t } = useTranslation('auth');
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    email: '',
    password: ''
  });
  const [error, setError] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setIsLoading(true);

    try {
      const response = await publicApi.post('/login', formData);
      if (response.data.is_success) {
        localStorage.setItem(import.meta.env.VITE_AUTH_TOKEN_KEY, response.data.token.access_token);
        navigate('/profile');
      } else {
        setError(response.data.message || t('login.failed'));
      }
    } catch (err) {
      const errorMessage = err.response?.data?.message || 
                          err.response?.data?.errors || 
                          t('login.failed');
      setError(errorMessage);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className={styles.authContainer}>
      <div className={styles.authCard}>
        <h2>{t('login.title')}</h2>
        <p>{t('login.subtitle')}</p>
        
        {error && <div className={styles.error}>{error}</div>}
        
        <form onSubmit={handleSubmit} className={styles.authForm}>
          <div className={styles.formGroup}>
            <label htmlFor="email">{t('login.email')}</label>
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
            <label htmlFor="password">{t('login.password')}</label>
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
          
          <button 
            type="submit" 
            className={styles.submitButton}
            disabled={isLoading}
          >
            {isLoading ? t('login.loading') : t('login.submit')}
          </button>
        </form>
        
        <div className={styles.authLinks}>
          <p>
            {t('login.noAccount')}{' '}
            <a href="/register">{t('login.register')}</a>
          </p>
          <a href="/forgot-password" className={styles.forgotPassword}>
            {t('login.forgotPassword')}
          </a>
        </div>
      </div>
    </div>
  );
};

export default LoginPage; 