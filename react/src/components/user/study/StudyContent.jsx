import React, { useState, useEffect } from 'react';
import { useSelector } from 'react-redux';
import { useTranslation } from 'react-i18next';
import useStudyService from '../../../hooks/user/study/useStudyService';
import VideoPlayer from './VideoPlayer';
import TranscriptViewer from './TranscriptViewer';
import QuizTaker from './QuizTaker';
import styles from './StudyContent.module.css';

const StudyContent = () => {
    const { t } = useTranslation('study');
    const { activeLesson, activeContentType } = useSelector(state => state.study);
    const { getLessonItems } = useStudyService();
    const [content, setContent] = useState(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchContent = async () => {
            if (!activeLesson || !activeContentType) {
                setContent(null);
                return;
            }

            setLoading(true);
            setError(null);

            try {
                let videosResponse, transcriptsResponse, mcqsResponse;

                switch (activeContentType) {
                    case 'lesson':
                        [videosResponse, transcriptsResponse] = await Promise.all([
                            getLessonItems(activeLesson, 'video'),
                            getLessonItems(activeLesson, 'transcript')
                        ]);
                        
                        if (videosResponse.is_success && transcriptsResponse.is_success) {
                            setContent({
                                type: 'lesson',
                                videos: videosResponse.data,
                                transcripts: transcriptsResponse.data
                            });
                        } else {
                            setError(videosResponse.message || transcriptsResponse.message);
                        }
                        break;
                    case 'quiz':
                        mcqsResponse = await getLessonItems(activeLesson, 'mcq');
                        if (mcqsResponse.is_success) {
                            setContent({
                                type: 'quiz',
                                mcqs: mcqsResponse.data
                            });
                        } else {
                            setError(mcqsResponse.message);
                        }
                        break;
                    default:
                        setError('Invalid content type');
                }
            } catch (err) {
                console.error('Error fetching content:', err);
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };

        fetchContent();
    }, [activeLesson, activeContentType, getLessonItems]);

    if (!activeLesson || !activeContentType) {
        return (
            <div className={styles.emptyContent}>
                <h3>{t('selectContent')}</h3>
                <p>{t('selectContentMessage')}</p>
            </div>
        );
    }

    if (loading) {
        return <div className={styles.loading}>{t('loading')}</div>;
    }

    if (error) {
        return <div className={styles.error}>{error}</div>;
    }

    if (!content) {
        return <div className={styles.emptyContent}>{t('noContent')}</div>;
    }

    return (
        <div className={styles.studyContent}>
            {content.type === 'lesson' ? (
                <>
                    <VideoPlayer videos={content.videos} />
                    <TranscriptViewer transcripts={content.transcripts} />
                </>
            ) : (
                <QuizTaker mcqs={content.mcqs} lessonId={activeLesson} />
            )}
        </div>
    );
};

export default StudyContent; 