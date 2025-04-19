import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useSelector, useDispatch } from 'react-redux';
import { useTranslation } from 'react-i18next';
import { clearUser } from '../../../redux/slices/userSlice';
import styles from './AdminHeader.module.css';
import { FaBars } from 'react-icons/fa';
import PropTypes from 'prop-types';

const AdminHeader = ({ onToggleSidebar }) => {
    const { t } = useTranslation();
    const navigate = useNavigate();
    const dispatch = useDispatch();
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const user = useSelector((state) => state.user.user);

    const handleLogout = () => {
        localStorage.removeItem(import.meta.env.VITE_AUTH_TOKEN_KEY);
        dispatch(clearUser());
        navigate('/');
    };

    return (
        <header className={styles['admin-header']}>
            <button onClick={onToggleSidebar} className={styles['toggle-sidebar-btn']}>
                <FaBars />
            </button>
            <div className={styles['user-menu']}>
                <button
                    className={styles['profile-button']}
                    onClick={() => setIsMenuOpen(!isMenuOpen)}
                >
                    {user?.profile_picture ? (
                        <img
                            src={user.profile_picture}
                            alt={user.name}
                            className={styles['profile-image']}
                        />
                    ) : (
                        <span className={styles['profile-letter']}>
                            {user?.name?.charAt(0).toUpperCase()}
                        </span>
                    )}
                </button>
                {isMenuOpen && (
                    <div className={styles['dropdown-menu']}>
                        <Link to="/profile" onClick={() => setIsMenuOpen(false)}>
                            {t('nav.profile')}
                        </Link>
                        <button onClick={handleLogout}>{t('nav.logout')}</button>
                    </div>
                )}
            </div>
        </header>
    );
};

AdminHeader.propTypes = {
    onToggleSidebar: PropTypes.func.isRequired,
};

export default AdminHeader;