import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { privateApi } from '../../../config/axios';
import styles from './CourseForm.module.css';

const CourseForm = () => {
    const [courseData, setCourseData] = useState({
        title: '',
        description: '',
        thumbnail: '',
        price: '',
        discount_price: '',
        status: 'draft',
    });
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const navigate = useNavigate();
    const { id } = useParams();
    const isEdit = Boolean(id);

    useEffect(() => {
        if (isEdit) {
            setLoading(true);
            privateApi.get(`/courses/${id}`)
                .then(response => {
                    const fetchedData = response.data.data; // Access nested data
                    setCourseData(prevState => ({
                        ...prevState,
                        title: fetchedData.title || '',
                        description: fetchedData.description || '',
                        price: fetchedData.price || '',
                        discount_price: fetchedData.discount_price || '',
                        status: fetchedData.status || 'draft',
                        thumbnail: fetchedData.thumbnail || '' // Added thumbnail
                    }));
                    setLoading(false);
                })
                .catch(err => {
                    setError('Failed to fetch course details');
                    setLoading(false);
                });
        }
    }, [isEdit, id]);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setCourseData({ ...courseData, [name]: value });
    };

    const handleFileChange = (e) => {
        const { name, files } = e.target;
        setCourseData({ ...courseData, [name]: files[0] });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setLoading(true);

        const data = new FormData();
        Object.keys(courseData).forEach(key => {
            if (key === 'thumbnail' && (!courseData[key] || typeof courseData[key] === 'string')) {
                return;
            }
            data.append(key, courseData[key]);
        });

        if (isEdit) {
            data.append('_method', 'PUT');
        }

        const apiCall = privateApi.post(isEdit ? `/courses/${id}` : '/courses', data);

        apiCall
            .then(() => {
                navigate('/admin/courses');
            })
            .catch(err => {
                setError('Failed to save course');
                setLoading(false);
            });
    };

    if (loading) return <p>Loading...</p>;
    if (error) return <p className="text-danger">{error}</p>;

    return (
        <div className={styles.courseFormContainer}>
            <h1>{isEdit ? 'Edit Course' : 'Create Course'}</h1>
            <form onSubmit={handleSubmit}>
                <div className={styles.formGroup}>
                    <label htmlFor="title" className={styles.label}>Title</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value={courseData.title}
                        onChange={handleChange}
                        className={styles.input}
                        required
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="description" className={styles.label}>Description</label>
                    <textarea
                        id="description"
                        name="description"
                        value={courseData.description}
                        onChange={handleChange}
                        className={styles.textarea}
                        required
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="thumbnail" className={styles.label}>Thumbnail</label>
                    <input
                        type="file"
                        id="thumbnail"
                        name="thumbnail"
                        onChange={handleFileChange}
                        className={styles.input}
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="price" className={styles.label}>Price</label>
                    <input
                        type="number"
                        id="price"
                        name="price"
                        value={courseData.price}
                        onChange={handleChange}
                        className={styles.input}
                        required
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="discount_price" className={styles.label}>Discount Price</label>
                    <input
                        type="number"
                        id="discount_price"
                        name="discount_price"
                        value={courseData.discount_price}
                        onChange={handleChange}
                        className={styles.input}
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="status" className={styles.label}>Status</label>
                    <select
                        id="status"
                        name="status"
                        value={courseData.status}
                        onChange={handleChange}
                        className={styles.input}
                        required
                    >
                        <option value="draft">Draft</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <button type="submit" className={`${styles.courseFormButton} ${styles.courseFormButtonPrimary}`}>
                    {isEdit ? 'Update Course' : 'Create Course'}
                </button>
            </form>
        </div>
    );
};

export default CourseForm;