import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { FaChevronDown, FaChevronRight, FaVideo, FaQuestionCircle } from 'react-icons/fa';
import { useDispatch } from 'react-redux';
import useStudyService from '../../../hooks/user/study/useStudyService';
import { setActiveLesson } from '../../../redux/slices/studySlice';
import styles from './Sidebar.module.css';
import PropTypes from 'prop-types';

const Sidebar = ({ isCollapsed }) => {
Sidebar.propTypes = {
    isCollapsed: PropTypes.bool.isRequired,
};
    const { t } = useTranslation('study');
    const { id } = useParams();
    const dispatch = useDispatch();
    const { getSectionChapters, getChapterLessons } = useStudyService();
    const [chapters, setChapters] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [expandedChapters, setExpandedChapters] = useState(new Set());
    const [chapterLessons, setChapterLessons] = useState({});
    const [activeContent, setActiveContent] = useState({ type: null, id: null });

    useEffect(() => {
        const fetchChapters = async () => {
            try {
                console.log('Fetching chapters for section ID:', id);
                const response = await getSectionChapters(id);
                console.log('API Response:', response);
                
                if (response.is_success) {
                    console.log('Chapters data:', response.data);
                    setChapters(response.data || []);
                } else {
                    console.error('API Error:', response.message);
                    setError(response.message);
                }
            } catch (err) {
                console.error('Fetch Error:', err);
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };

        if (id) {
            fetchChapters();
        } else {
            console.error('No section ID found in URL');
            setError('No section ID found');
            setLoading(false);
        }
    }, [id, getSectionChapters]);

    const fetchChapterLessons = async (chapterId) => {
        try {
            console.log('Fetching lessons for chapter ID:', chapterId);
            const response = await getChapterLessons(chapterId);
            console.log('Lessons response:', response);

            if (response.is_success) {
                setChapterLessons(prev => ({
                    ...prev,
                    [chapterId]: response.data.lessons
                }));
            } else {
                console.error('Failed to fetch lessons:', response.message);
            }
        } catch (err) {
            console.error('Error fetching lessons:', err);
        }
    };

    const toggleChapter = async (chapterId) => {
        setExpandedChapters(prev => {
            const newSet = new Set(prev);
            if (newSet.has(chapterId)) {
                newSet.delete(chapterId);
            } else {
                newSet.add(chapterId);
                if (!chapterLessons[chapterId]) {
                    fetchChapterLessons(chapterId);
                }
            }
            return newSet;
        });
    };

    const handleContentSelect = (type, id) => {
        setActiveContent({ type, id });
        dispatch(setActiveLesson({ type, id }));
    };

    if (loading) return <div className={styles.sidebarLoading}>{t('loading')}</div>;
    if (error) return <div className={styles.sidebarError}>{error}</div>;
    if (!chapters.length) return <div className={styles.sidebarEmpty}>{t('noChapters')}</div>;

    return (
        <div className={`${styles.studySidebar} ${isCollapsed ? styles.collapsed : ''}`}>
            <div className={styles.sidebarContent}>
                {chapters.map(chapter => (
                    <div key={chapter.id} className={styles.chapterItem}>
                        <button 
                            className={`${styles.chapterHeader} ${expandedChapters.has(chapter.id) ? styles.expanded : ''}`}
                            onClick={() => toggleChapter(chapter.id)}
                            aria-expanded={expandedChapters.has(chapter.id)}
                        >
                            <span className={styles.chapterIcon}>
                                {expandedChapters.has(chapter.id) ? <FaChevronDown /> : <FaChevronRight />}
                            </span>
                            <span className={styles.chapterTitle}>{chapter.title}</span>
                        </button>
                        {expandedChapters.has(chapter.id) && (
                            <div className={styles.chapterContent}>
                                {chapterLessons[chapter.id]?.map(lesson => (
                                    <React.Fragment key={lesson.id}>
                                        <button 
                                            className={`${styles.lessonItem} ${
                                                activeContent.type === 'lesson' && activeContent.id === lesson.id ? styles.active : ''
                                            }`}
                                            onClick={() => handleContentSelect('lesson', lesson.id)}
                                        >
                                            <FaVideo className={styles.lessonIcon} />
                                            <span className={styles.lessonTitle}>{lesson.title}</span>
                                        </button>
                                        {lesson.hasQuiz && (
                                            <button 
                                                className={`${styles.quizItem} ${
                                                    activeContent.type === 'quiz' && activeContent.id === lesson.id ? styles.active : ''
                                                }`}
                                                onClick={() => handleContentSelect('quiz', lesson.id)}
                                            >
                                                <FaQuestionCircle className={styles.quizIcon} />
                                                <span className={styles.quizTitle}>Take Quiz</span>
                                            </button>
                                        )}
                                    </React.Fragment>
                                ))}
                            </div>
                        )}
                    </div>
                ))}
            </div>
        </div>
    );
};

export default Sidebar; 