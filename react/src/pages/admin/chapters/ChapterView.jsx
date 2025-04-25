import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { privateApi } from '../../../config/axios';
import styles from '../styles/CourseItemView.module.css';

const ChapterView = () => {
    const { id } = useParams();
    const [chapter, setChapter] = useState(null);
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        privateApi.get(`/chapters/${id}`)
            .then(response => {
                if (response.data.is_success) {
                    setChapter(response.data.data);
                } else {
                    setError(response.data.message || 'Failed to fetch chapter details');
                }
                setLoading(false);
            })
            .catch(() => {
                setError('An error occurred while fetching chapter details');
                setLoading(false);
            });
    }, [id]);

    if (loading) return <p>Loading...</p>;
    if (error) return <p className="text-danger">{error}</p>;

    return (
        <div className={styles.itemViewContainer}>
            <h1 className={styles.itemTitle}>{chapter.title}</h1>
            <p className={styles.itemDescription}>{chapter.description}</p>
            <ul className={styles.itemDetails}>
                <li><strong>Order:</strong> {chapter.order}</li>
                <li><strong>Published:</strong> {chapter.is_published ? 'Yes' : 'No'}</li>
                <li><strong>Created At:</strong> {new Date(chapter.created_at).toLocaleDateString()}</li>
                <li><strong>Last Modified:</strong> {new Date(chapter.updated_at).toLocaleDateString()}</li>
            </ul>
        </div>
    );
};

export default ChapterView;