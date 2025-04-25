import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { privateApi } from '../../../config/axios';
import styles from '../styles/CourseItemForm.module.css';

const McqForm = () => {
    const [mcqData, setMcqData] = useState({
        question: '',
        options: ['', '', '', ''], // Default 4 options
        correct_answer: '',
        explanation: '',
        points: 1,
        mcqable_type: 'App\\Modules\\Course\\Models\\Lesson', // Default value
        mcqable_id: 1, // Default value
    });
    const [lessons, setLessons] = useState([]); // State to store lessons
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const navigate = useNavigate();
    const { id } = useParams();
    const isEdit = Boolean(id);

    useEffect(() => {
        if (isEdit) {
            setLoading(true);
            privateApi.get(`/mcqs/${id}`)
                .then(response => {
                    const fetchedData = response.data.data;
                    setMcqData({
                        question: fetchedData.question || '',
                        options: fetchedData.options || ['', '', '', ''],
                        correct_answer: fetchedData.correct_answer || '',
                        explanation: fetchedData.explanation || '',
                        points: fetchedData.points || 1,
                        mcqable_type: fetchedData.mcqable_type || 'App\\Modules\\Course\\Models\\Lesson',
                        mcqable_id: fetchedData.mcqable_id || 1,
                    });
                    setLoading(false);
                })
                .catch(() => {
                    setError('Failed to fetch MCQ details');
                    setLoading(false);
                });
        }
    }, [isEdit, id]);

    useEffect(() => {
        // Fetch lessons to populate the dropdown
        privateApi.get('/lessons')
            .then(response => {
                const fetchedLessons = Array.isArray(response.data.data?.data) ? response.data.data.data : [];
                setLessons(fetchedLessons);
            })
            .catch(() => {
                setError('Failed to fetch lessons');
            });
    }, []);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setMcqData({
            ...mcqData,
            [name]: name === 'points' ? Number(value) : value,
        });
    };

    const handleOptionChange = (index, value) => {
        const updatedOptions = [...mcqData.options];
        updatedOptions[index] = value;
        setMcqData({ ...mcqData, options: updatedOptions });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setLoading(true);

        const formData = { ...mcqData, options: mcqData.options }; // Ensure options is sent as an array
        if (isEdit) {
            formData._method = 'PUT';
        }

        privateApi.post(isEdit ? `/mcqs/${id}` : '/mcqs', formData)
            .then(() => navigate('/admin/mcqs'))
            .catch(() => {
                setError('Failed to save MCQ');
                setLoading(false);
            });
    };

    const renderLessonOptions = () => {
        return lessons.map(lesson => (
            <option key={lesson.id} value={lesson.id}>
                {lesson.title}
            </option>
        ));
    };

    if (loading) return <p>Loading...</p>;
    if (error) return <p className="text-danger">{error}</p>;

    return (
        <div className={styles.formContainer}>
            <h1>{isEdit ? 'Edit MCQ' : 'Create MCQ'}</h1>
            <form onSubmit={handleSubmit}>
                <div className={styles.formGroup}>
                    <label htmlFor="question">Question</label>
                    <textarea
                        id="question"
                        name="question"
                        value={mcqData.question}
                        onChange={handleChange}
                        required
                    />
                </div>
                <div className={styles.formGroup}>
                    <label>Options</label>
                    {mcqData.options.map((option, index) => (
                        <input
                            key={index}
                            type="text"
                            value={option}
                            onChange={(e) => handleOptionChange(index, e.target.value)}
                            placeholder={`Option ${index + 1}`}
                            required
                        />
                    ))}
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="correct_answer">Correct Answer</label>
                    <input
                        type="text"
                        id="correct_answer"
                        name="correct_answer"
                        value={mcqData.correct_answer}
                        onChange={handleChange}
                        required
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="explanation">Explanation</label>
                    <textarea
                        id="explanation"
                        name="explanation"
                        value={mcqData.explanation}
                        onChange={handleChange}
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="points">Points</label>
                    <input
                        type="number"
                        id="points"
                        name="points"
                        value={mcqData.points}
                        onChange={handleChange}
                        required
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="lesson_id">Select Lesson</label>
                    <select
                        id="lesson_id"
                        name="mcqable_id"
                        value={mcqData.mcqable_id}
                        onChange={handleChange}
                        required
                    >
                        <option value="" disabled>Select a lesson</option>
                        {renderLessonOptions()}
                    </select>
                </div>
                <button type="submit">{isEdit ? 'Update MCQ' : 'Create MCQ'}</button>
            </form>
        </div>
    );
};

export default McqForm;