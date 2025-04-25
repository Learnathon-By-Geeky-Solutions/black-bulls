import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { privateApi } from '../../../config/axios';
import styles from '../styles/CourseItemView.module.css';

const McqView = () => {
    const { id } = useParams();
    const [mcq, setMcq] = useState(null);
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        privateApi.get(`/mcqs/${id}`)
            .then(response => {
                if (response.data.is_success) {
                    setMcq(response.data.data);
                } else {
                    setError(response.data.message || 'Failed to fetch MCQ details');
                }
                setLoading(false);
            })
            .catch(() => {
                setError('An error occurred while fetching MCQ details');
                setLoading(false);
            });
    }, [id]);

    if (loading) return <p>Loading...</p>;
    if (error) return <p className="text-danger">{error}</p>;

    return (
        <div className={styles.itemViewContainer}>
            <h1 className={styles.itemTitle}>MCQ Details</h1>
            <p className={styles.itemDescription}><strong>Question:</strong> {mcq.question}</p>
            <ul className={styles.itemDetails}>
                <li><strong>Options:</strong>
                    <ul>
                        {mcq.options.map((option) => (
                            <li key={option}>{option}</li>
                        ))}
                    </ul>
                </li>
                <li><strong>Correct Answer:</strong> {mcq.correct_answer}</li>
                <li><strong>Explanation:</strong> {mcq.explanation || 'N/A'}</li>
                <li><strong>Points:</strong> {mcq.points}</li>
                <li><strong>Published:</strong> {mcq.is_published ? 'Yes' : 'No'}</li>
                <li><strong>Created At:</strong> {new Date(mcq.created_at).toLocaleDateString()}</li>
                <li><strong>Updated At:</strong> {new Date(mcq.updated_at).toLocaleDateString()}</li>
            </ul>
        </div>
    );
};

export default McqView;