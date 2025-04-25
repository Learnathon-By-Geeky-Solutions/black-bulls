import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { privateApi } from '../../../config/axios';
import styles from '../styles/CourseItemForm.module.css';

const VideoForm = () => {
    const [videoData, setVideoData] = useState({
        title: '',
        description: '',
        url: null,
        thumbnail: null,
        duration: '',
        videoable_type: 'App\\Modules\\Course\\Models\\Lesson',
        videoable_id: 1,
    });
    const [existingVideoUrl, setExistingVideoUrl] = useState(null);
    const [existingThumbnailUrl, setExistingThumbnailUrl] = useState(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);
    const [lessons, setLessons] = useState([]);
    const navigate = useNavigate();
    const { id } = useParams();
    const isEdit = Boolean(id);

    useEffect(() => {
        if (isEdit) {
            setLoading(true);
            privateApi.get(`/videos/${id}`)
                .then(response => {
                    const fetchedData = response.data.data;
                    setVideoData({
                        title: fetchedData.title || '',
                        description: fetchedData.description || '',
                        url: null,
                        thumbnail: null,
                        duration: fetchedData.duration ? fetchedData.duration / 60 : '',
                        videoable_type: fetchedData.videoable_type || 'App\\Modules\\Course\\Models\\Lesson',
                        videoable_id: fetchedData.videoable_id || 1,
                    });
                    setExistingVideoUrl(fetchedData.url);
                    setExistingThumbnailUrl(fetchedData.thumbnail);
                    setLoading(false);
                })
                .catch(() => {
                    setError('Failed to fetch video details');
                    setLoading(false);
                });
        }
    }, [isEdit, id]);

    useEffect(() => {
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
        const { name, value, files } = e.target;
        setVideoData({
            ...videoData,
            [name]: (() => {
                if (files) return files[0];
                if (name === 'duration') return Number(value);
                return value;
            })(),
        });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setLoading(true);

        const formData = new FormData();
        Object.keys(videoData).forEach(key => {
            if (videoData[key] !== null) {
                formData.append(key, key === 'duration' ? videoData[key] * 60 : videoData[key]);
            }
        });
        if (isEdit) {
            formData.append('_method', 'PUT');
        }

        privateApi.post(isEdit ? `/videos/${id}` : '/videos', formData)
            .then(() => navigate('/admin/videos'))
            .catch(() => {
                setError('Failed to save video');
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
            <h1>{isEdit ? 'Edit Video' : 'Create Video'}</h1>
            <form onSubmit={handleSubmit}>
                <div className={styles.formGroup}>
                    <label htmlFor="title">Title</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value={videoData.title}
                        onChange={handleChange}
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="description">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        value={videoData.description}
                        onChange={handleChange}
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="url">Video File</label>
                    {existingVideoUrl && (
                        <div>
                            <video controls width="300">
                                <source src={existingVideoUrl} type="video/mp4" />
                                <track
                                    src="captions.vtt"
                                    kind="captions"
                                    srcLang="en"
                                    label="English"
                                />
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    )}
                    <input
                        type="file"
                        id="url"
                        name="url"
                        accept="video/*"
                        onChange={handleChange}
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="thumbnail">Thumbnail Image</label>
                    {existingThumbnailUrl && (
                        <div>
                            <img src={existingThumbnailUrl} alt="Thumbnail" width="150" />
                        </div>
                    )}
                    <input
                        type="file"
                        id="thumbnail"
                        name="thumbnail"
                        accept="image/*"
                        onChange={handleChange}
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="duration">Duration (in minutes)</label>
                    <input
                        type="number"
                        id="duration"
                        name="duration"
                        value={videoData.duration}
                        onChange={handleChange}
                    />
                </div>
                <div className={styles.formGroup}>
                    <label htmlFor="lesson_id">Select Lesson</label>
                    <select
                        id="lesson_id"
                        name="videoable_id"
                        value={videoData.videoable_id}
                        onChange={handleChange}
                        required
                    >
                        <option value="" disabled>Select a lesson</option>
                        {renderLessonOptions()}
                    </select>
                </div>
                <button type="submit">{isEdit ? 'Update Video' : 'Create Video'}</button>
            </form>
        </div>
    );
};

export default VideoForm;