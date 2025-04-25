import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { privateApi } from '../../../config/axios';
import styles from '../styles/CourseItemView.module.css';

const VideoView = () => {
    const { id } = useParams();
    const [video, setVideo] = useState(null);
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        privateApi.get(`/videos/${id}`)
            .then(response => {
                if (response.data.is_success) {
                    setVideo(response.data.data);
                } else {
                    setError(response.data.message || 'Failed to fetch video details');
                }
                setLoading(false);
            })
            .catch(() => {
                setError('An error occurred while fetching video details');
                setLoading(false);
            });
    }, [id]);

    if (loading) return <p>Loading...</p>;
    if (error) return <p className="text-danger">{error}</p>;

    return (
        <div className={styles.itemViewContainer}>
            <h1 className={styles.itemTitle}>{video.title}</h1>
            <p className={styles.itemDescription}>{video.description}</p>
            <div className={styles.videoContainer}>
                <video controls className={styles.videoPlayer}>
                    <source src={video.url} type="video/mp4" />
                    <track
                        src="captions.vtt"
                        kind="captions"
                        srcLang="en"
                        label="English"
                    />
                    Your browser does not support the video tag.
                </video>
            </div>
            <ul className={styles.itemDetails}>
                <li><strong>Duration:</strong> {Math.floor(video.duration / 60)} minutes</li>
                <li><strong>Published:</strong> {video.is_published ? 'Yes' : 'No'}</li>
                <li><strong>Created At:</strong> {new Date(video.created_at).toLocaleDateString()}</li>
                <li><strong>Updated At:</strong> {new Date(video.updated_at).toLocaleDateString()}</li>
            </ul>
        </div>
    );
};

export default VideoView;