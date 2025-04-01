import { useTranslation } from 'react-i18next';
import styles from './LanguageSwitcher.module.css';

const LanguageSwitcher = () => {
  const { i18n } = useTranslation();

  const changeLanguage = (lng) => {
    i18n.changeLanguage(lng);
  };

  return (
    <div className={styles.languageSwitcher}>
      <button
        className={`${styles.langBtn} ${i18n.language === 'en' ? styles.active : ''}`}
        onClick={() => changeLanguage('en')}
      >
        EN
      </button>
      <button
        className={`${styles.langBtn} ${i18n.language === 'bn' ? styles.active : ''}`}
        onClick={() => changeLanguage('bn')}
      >
        BN
      </button>
    </div>
  );
};

export default LanguageSwitcher; 