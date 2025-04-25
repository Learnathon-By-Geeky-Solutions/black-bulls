import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { privateApi } from '../../../config/axios';
import styles from '../styles/CourseItemView.module.css';

const SectionView = () => {
    const { id } = useParams();
    const [section, setSection] = useState(null);
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        privateApi.get(`/sections/${id}`)
            .then(response => {
                if (response.data.is_success) {
                    setSection(response.data.data);
                } else {
                    setError(response.data.message || 'Failed to fetch section details');
                }
                setLoading(false);
            })
            .catch(() => {
                setError('An error occurred while fetching section details');
                setLoading(false);
            });
    }, [id]);

    if (loading) return <p>Loading...</p>;
    if (error) return <p className="text-danger">{error}</p>;

    return (
        <div className={styles.itemViewContainer}>
            <h1 className={styles.itemTitle}>{section.title}</h1>
            <p className={styles.itemDescription}>{section.description}</p>
            <ul className={styles.itemDetails}>
                <li><strong>Order:</strong> {section.order}</li>
                <li><strong>Published:</strong> {section.is_published ? 'Yes' : 'No'}</li>
                <li><strong>Created At:</strong> {new Date(section.created_at).toLocaleDateString()}</li>
                <li><strong>Updated At:</strong> {new Date(section.updated_at).toLocaleDateString()}</li>
            </ul>
        </div>
    );
};

export default SectionView;