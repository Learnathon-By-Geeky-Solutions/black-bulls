import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { privateApi } from '../../../config/axios';
import styles from '../styles/CourseItemForm.module.css';

const ChapterForm = () => {
    const [chapterData, setChapterData] = useState({
        title: '',
        description: '',
        course_section_id: null,
        order: 0,
    });
    const [sections, setSections] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const navigate = useNavigate();
    const { id } = useParams();
    const isEdit = Boolean(id);

    useEffect(() => {
        if (isEdit) {
            setLoading(true);
            privateApi.get(`/chapters/${id}`)
                .then(response => {
                    const fetchedData = response.data.data;
                    setChapterData({
                        title: fetchedData.title || '',
                        description: fetchedData.description || '',
                        course_section_id: fetchedData.course_section_id || null,
                        order: fetchedData.order || 0,
                    });
                    setLoading(false);
                })
                .catch(() => {
                    setError('Failed to fetch chapter details');
                    setLoading(false);
                });
        }
    }, [isEdit, id]);

    useEffect(() => {
        privateApi.get('/sections')
            .then(response => {
                const fetchedSections = Array.isArray(response.data.data?.data) ? response.data.data.data : [];
                setSections(fetchedSections);
            })
            .catch(() => {
                setError('Failed to fetch sections');
            });
    }, []);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setChapterData({ ...chapterData, [name]: name === 'course_section_id' || name === 'order' ? Number(value) : value });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setLoading(true);

        const formData = { ...chapterData };
        if (isEdit) {
            formData._method = 'PUT';
        }

        privateApi.post(isEdit ? `/chapters/${id}` : '/chapters', formData)
            .then(() => navigate('/admin/chapters'))
            .catch(() => {
                setError('Failed to save chapter');
                setLoading(false);
            });
    };

    if (loading) return <p>Loading...</p>;
    if (error) return <p className="text-danger">{error}</p>;

    return (
        <div className={styles.formContainer}>
            <h1>{isEdit ? 'Edit Chapter' : 'Create Chapter'}</h1>
            <form onSubmit={handleSubmit}>
                <div className={styles.formGroup}>
                    <label htmlFor="title">Title</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value={chapterData.title}
                        onChange={handleChange}
                        required
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="description">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        value={chapterData.description}
                        onChange={handleChange}
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="course_section_id">Section</label>
                    <select
                        id="course_section_id"
                        name="course_section_id"
                        value={chapterData.course_section_id || ''}
                        onChange={handleChange}
                        required
                    >
                        <option value="" disabled>Select a section</option>
                        {sections.map(section => (
                            <option key={section.id} value={section.id}>
                                {section.title}
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
                        value={chapterData.order}
                        onChange={handleChange}
                        required
                    />
                </div>
                <button type="submit">{isEdit ? 'Update Chapter' : 'Create Chapter'}</button>
            </form>
        </div>
    );
};

export default ChapterForm;