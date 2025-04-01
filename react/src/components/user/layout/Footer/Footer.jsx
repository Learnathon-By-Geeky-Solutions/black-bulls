import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { FacebookIcon, TwitterIcon, LinkedInIcon } from '../../../common/icons/SocialIcons';
import styles from './Footer.module.css';

const Footer = () => {
  const { t } = useTranslation();

  return (
    <footer className={styles.footer}>
      <div className={styles.container}>
        <div className={styles.grid}>
          <div className={styles.section}>
            <h3>{t('footer.about')}</h3>
            <Link to="/about">{t('footer.aboutUs')}</Link>
            <Link to="/careers">{t('footer.careers')}</Link>
            <Link to="/press">{t('footer.press')}</Link>
          </div>
          
          <div className={styles.section}>
            <h3>{t('footer.resources')}</h3>
            <Link to="/blog">{t('footer.blog')}</Link>
            <Link to="/help">{t('footer.help')}</Link>
            <Link to="/mobile">{t('footer.mobile')}</Link>
          </div>
          
          <div className={styles.section}>
            <h3>{t('footer.legal')}</h3>
            <Link to="/terms">{t('footer.terms')}</Link>
            <Link to="/privacy">{t('footer.privacy')}</Link>
            <Link to="/cookies">{t('footer.cookies')}</Link>
          </div>
          
          <div className={styles.section}>
            <h3>{t('footer.connect')}</h3>
            <div className={styles.social}>
              <button type="button" className={styles.socialButton} aria-label="Facebook">
                <FacebookIcon />
              </button>
              <button type="button" className={styles.socialButton} aria-label="Twitter">
                <TwitterIcon />
              </button>
              <button type="button" className={styles.socialButton} aria-label="LinkedIn">
                <LinkedInIcon />
              </button>
            </div>
          </div>
        </div>
        
        <div className={styles.bottom}>
          <p>&copy; {new Date().getFullYear()} {t('platform.name')}. {t('footer.copyright')}</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer; 