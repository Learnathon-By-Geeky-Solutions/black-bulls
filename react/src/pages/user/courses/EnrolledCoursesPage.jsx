import React, { useState } from 'react';
import { useEnrolledCourses } from '../../../hooks/user/course/useEnrolledCourses';
import CourseCard from '../../../components/user/home/CourseCard';
import styles from './EnrolledCoursesPage.module.css';
import { useTranslation } from 'react-i18next';

const EnrolledCoursesPage = () => {
    const [activeTab, setActiveTab] = useState('IN_PROGRESS');
    const { data, isLoading, error } = useEnrolledCourses(activeTab);
    const { t } = useTranslation('course');

    if (isLoading) {
        return <div className={styles.loading}>Loading...</div>;
    }

    if (error) {
        return <div className={styles.error}>Error: {error.message}</div>;
    }

    const courses = data?.data || [];

    return (
        <div className={styles.container}>
            <div className={styles.header}>
                <h1>{t('enrolledCourses.myCourses')}</h1>
                <div className={styles.tabs}>
                    <button
                        className={`${styles.tab} ${activeTab === 'IN_PROGRESS' ? styles.active : ''}`}
                        onClick={() => setActiveTab('IN_PROGRESS')}
                    >
                        {t('enrolledCourses.inProgress')}
                    </button>
                    <button
                        className={`${styles.tab} ${activeTab === 'COMPLETED' ? styles.active : ''}`}
                        onClick={() => setActiveTab('COMPLETED')}
                    >
                        {t('enrolledCourses.completed')}
                    </button>
                </div>
            </div>

            {courses.length === 0 ? (
                <div className={styles.emptyState}>
                    <p>{t('enrolledCourses.noCourses')}</p>
                </div>
            ) : (
                <div className={styles.courseGrid}>
                    {courses.map((course) => (
                        <CourseCard key={course.id} course={course} />
                    ))}
                </div>
            )}
        </div>
    );
};

export default EnrolledCoursesPage; 