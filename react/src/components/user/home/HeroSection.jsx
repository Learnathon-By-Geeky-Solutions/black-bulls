import { Link } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import styles from './HeroSection.module.css';

const HeroSection = () => {
  const { t } = useTranslation('home');

  return (
    <section className={styles.hero}>
      <div className={styles.heroContent}>
        <h1>{t('hero.title')}</h1>
        <p>{t('hero.subtitle')}</p>
        <Link to="/courses" className={styles.ctaButton}>
          {t('hero.getStarted')}
        </Link>
      </div>
    </section>
  );
};

export default HeroSection; 