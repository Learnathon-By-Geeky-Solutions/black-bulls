import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { privateApi } from '../../../config/axios';
import styles from '../styles/CourseItemView.module.css';

const LessonView = () => {
    const { id } = useParams();
    const [lesson, setLesson] = useState(null);
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        privateApi.get(`/lessons/${id}`)
            .then(response => {
                if (response.data.is_success) {
                    setLesson(response.data.data);
                } else {
                    setError(response.data.message || 'Failed to fetch lesson details');
                }
                setLoading(false);
            })
            .catch(() => {
                setError('An error occurred while fetching lesson details');
                setLoading(false);
            });
    }, [id]);

    if (loading) return <p>Loading...</p>;
    if (error) return <p className="text-danger">{error}</p>;

    return (
        <div className={styles.itemViewContainer}>
            <h1 className={styles.itemTitle}>{lesson.title}</h1>
            <p className={styles.itemDescription}>{lesson.description}</p>
            <ul className={styles.itemDetails}>
                <li><strong>Order:</strong> {lesson.order}</li>
                <li><strong>Chapter ID:</strong> {lesson.chapter_id}</li>
                <li><strong>Published:</strong> {lesson.is_published ? 'Yes' : 'No'}</li>
                <li><strong>Created At:</strong> {new Date(lesson.created_at).toLocaleDateString()}</li>
                <li><strong>Updated At:</strong> {new Date(lesson.updated_at).toLocaleDateString()}</li>
            </ul>
        </div>
    );
};

export default LessonView;