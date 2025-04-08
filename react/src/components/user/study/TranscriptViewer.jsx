import React from 'react';
import { useTranslation } from 'react-i18next';
import styles from './TranscriptViewer.module.css';
import PropTypes from 'prop-types';

const TranscriptViewer = ({ transcripts }) => {
    const { t } = useTranslation('study');

    if (!transcripts || transcripts.length === 0) {
        return (
            <div className={styles.noTranscript}>
                <h3>{t('noTranscriptAvailable')}</h3>
                <p>{t('noTranscriptAvailableMessage')}</p>
            </div>
        );
    }

    return (
        <div className={styles.transcriptContainer}>
            <h3>{t('transcripts')}</h3>
            <div className={styles.transcriptList}>
                {transcripts.map(transcript => (
                    <div key={transcript.id} className={styles.transcriptItem}>
                        <h4>{transcript.title}</h4>
                        <div className={styles.transcriptContent}>
                            {transcript.content}
                        </div>
                        {transcript.timestamps && (
                            <div className={styles.timestamps}>
                                {JSON.parse(transcript.timestamps).map((ts, index) => (
                                    <div key={`${transcript.id}-${ts.start}-${ts.end}`} className={styles.timestamp}>
                                        <span className={styles.time}>
                                            {ts.start} - {ts.end}
                                        </span>
                                        <span className={styles.text}>{ts.text}</span>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                ))}
            </div>
        </div>
    );
};

TranscriptViewer.propTypes = {
    transcripts: PropTypes.arrayOf(PropTypes.shape({
        id: PropTypes.number.isRequired,
        title: PropTypes.string.isRequired,
        content: PropTypes.string.isRequired,
        timestamps: PropTypes.string
    })).isRequired
};

export default TranscriptViewer; 