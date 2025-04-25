import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { privateApi } from '../../../config/axios';
import styles from '../styles/CourseItemForm.module.css';

const LessonForm = () => {
    const [lessonData, setLessonData] = useState({
        title: '',
        description: '',
        chapter_id: null,
        order: 0,
    });
    const [chapters, setChapters] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const navigate = useNavigate();
    const { id } = useParams();
    const isEdit = Boolean(id);

    useEffect(() => {
        if (isEdit) {
            setLoading(true);
            privateApi.get(`/lessons/${id}`)
                .then(response => {
                    const fetchedData = response.data.data;
                    setLessonData({
                        title: fetchedData.title || '',
                        description: fetchedData.description || '',
                        chapter_id: fetchedData.chapter_id || null,
                        order: fetchedData.order || 0,
                    });
                    setLoading(false);
                })
                .catch(() => {
                    setError('Failed to fetch lesson details');
                    setLoading(false);
                });
        }
    }, [isEdit, id]);

    useEffect(() => {
        privateApi.get('/chapters')
            .then(response => {
                const fetchedChapters = Array.isArray(response.data.data?.data) ? response.data.data.data : [];
                setChapters(fetchedChapters);
            })
            .catch(() => {
                setError('Failed to fetch chapters');
            });
    }, []);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setLessonData({ ...lessonData, [name]: name === 'chapter_id' || name === 'order' ? Number(value) : value });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setLoading(true);

        const formData = { ...lessonData };
        if (isEdit) {
            formData._method = 'PUT';
        }

        privateApi.post(isEdit ? `/lessons/${id}` : '/lessons', formData)
            .then(() => navigate('/admin/lessons'))
            .catch(() => {
                setError('Failed to save lesson');
                setLoading(false);
            });
    };

    if (loading) return <p>Loading...</p>;
    if (error) return <p className="text-danger">{error}</p>;

    return (
        <div className={styles.formContainer}>
            <h1>{isEdit ? 'Edit Lesson' : 'Create Lesson'}</h1>
            <form onSubmit={handleSubmit}>
                <div className={styles.formGroup}>
                    <label htmlFor="title">Title</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value={lessonData.title}
                        onChange={handleChange}
                        required
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="description">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        value={lessonData.description}
                        onChange={handleChange}
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="chapter_id">Chapter</label>
                    <select
                        id="chapter_id"
                        name="chapter_id"
                        value={lessonData.chapter_id || ''}
                        onChange={handleChange}
                        required
                    >
                        <option value="" disabled>Select a chapter</option>
                        {chapters.map(chapter => (
                            <option key={chapter.id} value={chapter.id}>
                                {chapter.title}
                            </option>
                        ))}
                    </select>
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="order">Order</label>
                    <input
                        type="number"
                        id="order"
                        name="order"
                        value={lessonData.order}
                        onChange={handleChange}
                        required
                    />
                </div>
                <button type="submit">{isEdit ? 'Update Lesson' : 'Create Lesson'}</button>
            </form>
        </div>
    );
};

export default LessonForm;