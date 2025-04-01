import { useTranslation } from 'react-i18next';
import FreeCoursesSection from '../../../components/user/home/FreeCoursesSection';
import PopularCoursesSection from '../../../components/user/home/PopularCoursesSection';
import TrendingCoursesSection from '../../../components/user/home/TrendingCoursesSection';
import CategoriesSection from '../../../components/user/home/CategoriesSection';
import HeroSection from '../../../components/user/home/HeroSection';
import styles from './HomePage.module.css';

const HomePage = () => {
  const { t } = useTranslation('home');

  return (
    <div className={styles.homePage}>
      <HeroSection />
      <PopularCoursesSection />
      <CategoriesSection />
      <FreeCoursesSection />
      <TrendingCoursesSection />
    </div>
  );
};

export default HomePage; 