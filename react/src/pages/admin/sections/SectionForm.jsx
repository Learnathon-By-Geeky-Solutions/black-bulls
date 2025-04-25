import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { privateApi } from '../../../config/axios';
import styles from '../styles/CourseItemForm.module.css';

const SectionForm = () => {
    const [sectionData, setSectionData] = useState({
        title: '',
        description: '',
        course_id: null, // Use course_id as a number
        order: 0, // Add order field with default value
    });
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const [courses, setCourses] = useState([]);
    const navigate = useNavigate();
    const { id } = useParams();
    const isEdit = Boolean(id);

    useEffect(() => {
        if (isEdit) {
            setLoading(true);
            privateApi.get(`/sections/${id}`)
                .then(response => {
                    const fetchedData = response.data.data;
                    setSectionData({
                        title: fetchedData.title || '',
                        description: fetchedData.description || '',
                        course_id: fetchedData.course_id || null, // Ensure course_id is a number or null
                        order: fetchedData.order || 0, // Fetch order field
                    });
                    setLoading(false);
                })
                .catch(() => {
                    setError('Failed to fetch section details');
                    setLoading(false);
                });
        }
    }, [isEdit, id]);

    useEffect(() => {
        privateApi.get('/courses')
            .then(response => {
                const fetchedCourses = Array.isArray(response.data.data?.data) ? response.data.data.data : [];
                console.log('Fetched courses:', fetchedCourses);
                setCourses(fetchedCourses);
            })
            .catch(() => {
                setError('Failed to fetch courses');
                setCourses([]); // Ensure courses is an empty array on error
            });
    }, []);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setSectionData({ ...sectionData, [name]: name === 'course_id' ? Number(value) : value }); // Convert course_id to number
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setLoading(true);

        const formData = { ...sectionData };
        if (isEdit) {
            formData._method = 'PUT'; // Add _method for edit condition
        }

        privateApi.post(isEdit ? `/sections/${id}` : '/sections', formData)
            .then(() => navigate('/admin/sections'))
            .catch(() => {
                setError('Failed to save section');
                setLoading(false);
            });
    };

    if (loading) return <p>Loading...</p>;
    if (error) return <p className="text-danger">{error}</p>;

    return (
        <div className={styles.formContainer}>
            <h1>{isEdit ? 'Edit Section' : 'Create Section'}</h1>
            <form onSubmit={handleSubmit}>
                <div className={styles.formGroup}>
                    <label htmlFor="title">Title</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value={sectionData.title}
                        onChange={handleChange}
                        required
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="description">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        value={sectionData.description}
                        onChange={handleChange}
                        required
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="course_id">Course</label>
                    <select
                        id="course_id"
                        name="course_id"
                        value={sectionData.course_id || ''} // Ensure the selected value matches course_id
                        onChange={handleChange}
                        required
                    >
                        <option value="" disabled>Select a course</option>
                        {courses.map(course => (
                            <option key={course.id} value={course.id}>
                                {course.title}
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
                        value={sectionData.order}
                        onChange={handleChange}
                        required
                    />
                </div>
                <button type="submit">{isEdit ? 'Update Section' : 'Create Section'}</button>
            </form>
        </div>
    );
};

export default SectionForm;