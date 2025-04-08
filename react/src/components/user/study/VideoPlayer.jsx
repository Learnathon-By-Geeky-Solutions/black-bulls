import React from 'react';
import { useTranslation } from 'react-i18next';
import styles from './VideoPlayer.module.css';
import PropTypes from 'prop-types';

const VideoPlayer = ({ videos }) => {
    const { t } = useTranslation('study');

    if (!videos || videos.length === 0) {
        return (
            <div className={styles.noVideo}>
                <h3>{t('noVideoAvailable')}</h3>
                <p>{t('noVideoAvailableMessage')}</p>
            </div>
        );
    }

    return (
        <div className={styles.videoContainer}>
            <h3>{t('videos')}</h3>
            <div className={styles.videoList}>
                {videos.map(video => (
                    <div key={video.id} className={styles.videoItem}>
                        <video 
                            controls 
                            className={styles.video}
                            poster={video.thumbnail}
                        >
                            <source src={video.url} type="video/mp4" />
                            <track kind="captions" src="" label={t('captions')} />
                            {t('videoNotSupported')}
                        </video>
                        <div className={styles.videoInfo}>
                            <h4>{video.title}</h4>
                            <p>{video.description}</p>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
};

VideoPlayer.propTypes = {
    videos: PropTypes.arrayOf(PropTypes.shape({
        id: PropTypes.number.isRequired,
        title: PropTypes.string.isRequired,
        url: PropTypes.string.isRequired,
        thumbnail: PropTypes.string,
        description: PropTypes.string
    })).isRequired
};

export default VideoPlayer; 