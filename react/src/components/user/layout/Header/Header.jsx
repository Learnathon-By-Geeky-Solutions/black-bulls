import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { useSelector, useDispatch } from 'react-redux';
import { clearUser } from '../../../../redux/slices/userSlice';
import LanguageSwitcher from "../../../common/LanguageSwitcher/LanguageSwitcher";
import styles from './Header.module.css';

const Header = () => {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const dispatch = useDispatch();
  const [setIsMenuOpen] = useState(false);
  const user = useSelector((state) => state.user.user);

  const handleLogout = () => {
    localStorage.removeItem(import.meta.env.VITE_AUTH_TOKEN_KEY);
    dispatch(clearUser());
    navigate('/');
  };

  return (
    <header className={styles.header}>
      <div className={styles.container}>
        <div className={styles.left}>
          <Link to="/" className={styles.logo}>
            {t('platform.name')}
          </Link>
        </div>

        <div className={styles.right}>

          <LanguageSwitcher />

          {user ? (
            <div className={styles.userMenu}>
              <Link 
                to="/profile" 
                className={styles.profileButton}
                onClick={() => setIsMenuOpen(false)}
              >
                {user.profile_picture ? (
                  <img 
                    src={user.profile_picture} 
                    alt={user.name}
                    className={styles.profileImage}
                  />
                ) : (
                  <span className={styles.profileLetter}>
                    {user.name.charAt(0).toUpperCase()}
                  </span>
                )}
              </Link>
              <div className={styles.dropdownMenu}>
                <Link to="/profile" onClick={() => setIsMenuOpen(false)}>
                  {t('nav.profile')}
                </Link>
                <button onClick={handleLogout}>
                  {t('nav.logout')}
                </button>
                <Link to="/learn/my-courses">
                  {t('nav.myCourses')}
                </Link>
              </div>
            </div>
          ) : (
            <div className={styles.authButtons}>
              <Link to="/login" className={styles.loginButton}>
                {t('auth.login')}
              </Link>
              <Link to="/register" className={styles.signupButton}>
                {t('auth.signup')}
              </Link>
            </div>
          )}
        </div>
      </div>
    </header>
  );
};

export default Header; 