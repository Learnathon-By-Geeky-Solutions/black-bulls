import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';
import useStudyService from '../../../hooks/user/study/useStudyService';
import styles from './QuizTaker.module.css';
import PropTypes from 'prop-types';

const QuizTaker = ({ mcqs, lessonId }) => {
    const { t, i18n } = useTranslation('study');
    const { submitQuizAnswers } = useStudyService();
    const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
    const [selectedAnswers, setSelectedAnswers] = useState({});
    const [showResults, setShowResults] = useState(false);
    const [score, setScore] = useState(null);
    const [submitting, setSubmitting] = useState(false);
    const [error, setError] = useState(null);

    const convertToBengaliNumber = (number) => {
        if (i18n.language === 'bn') {
            // Handle decimal numbers
            if (typeof number === 'string' && number.includes('.')) {
                const [whole, decimal] = number.split('.');
                return `${convertToBengaliNumber(whole)}.${convertToBengaliNumber(decimal)}`;
            }
            // Handle regular numbers
            return number.toString().split('').map(digit => t(`numbers.${digit}`)).join('');
        }
        return number;
    };

    if (!mcqs || mcqs.length === 0) {
        return (
            <div className={styles.noQuiz}>
                <h3>{t('noQuizAvailable')}</h3>
                <p>{t('noQuizAvailableMessage')}</p>
            </div>
        );
    }

    const currentQuestion = mcqs[currentQuestionIndex];

    const handleAnswerSelect = (questionId, answer) => {
        setSelectedAnswers(prev => ({
            ...prev,
            [questionId]: answer
        }));
    };

    const handleNext = async () => {
        if (currentQuestionIndex < mcqs.length - 1) {
            setCurrentQuestionIndex(prev => prev + 1);
        } else {
            await submitQuiz();
        }
    };

    const submitQuiz = async () => {
        setSubmitting(true);
        setError(null);

        try {
            const formattedAnswers = Object.entries(selectedAnswers).reduce((acc, [questionId, answer]) => {
                acc[questionId] = answer;
                return acc;
            }, {});

            const response = await submitQuizAnswers(lessonId, formattedAnswers);
            
            if (response.is_success) {
                // Ensure score data is properly formatted
                const formattedScore = {
                    ...response.data,
                    percentage: parseFloat(response.data.percentage) || 0,
                    score: parseFloat(response.data.score) || 0,
                    max_score: parseFloat(response.data.max_score) || 0
                };
                setScore(formattedScore);
                setShowResults(true);
            } else {
                setError(response.message || t('submitError'));
            }
        } catch (err) {
            console.error('Quiz submission error:', err);
            if (err.response?.status === 409) {
                // Quiz already taken - ensure score data is properly formatted
                const formattedScore = {
                    ...err.response.data.data,
                    percentage: parseFloat(err.response.data.data.percentage) || 0,
                    score: parseFloat(err.response.data.data.score) || 0,
                    max_score: parseFloat(err.response.data.data.max_score) || 0
                };
                setScore(formattedScore);
                setShowResults(true);
            } else {
                setError(t('submitError'));
            }
        } finally {
            setSubmitting(false);
        }
    };

    const getButtonText = () => {
        if (submitting) return t('submitting');
        return currentQuestionIndex === mcqs.length - 1 ? t('quizSubmit') : t('quizNext');
    };

    if (showResults && score) {
        return (
            <div className={styles.quizResults}>
                <h2>{t('quizResults')}</h2>
                <div className={styles.scoreContainer}>
                    <div className={styles.score}>
                        <span className={styles.scoreLabel}>
                            {t('quizScore', { 
                                score: convertToBengaliNumber(score.percentage.toFixed(2))
                            })}
                        </span>
                        <div className={styles.scoreDetails}>
                            <p>
                                {t('pointsEarned', { 
                                    earned: convertToBengaliNumber(score.score), 
                                    total: convertToBengaliNumber(score.max_score)
                                })}
                            </p>
                            {score.message && <p className={styles.statusMessage}>{t('quizAlreadyCompleted')}</p>}
                        </div>
                    </div>
                </div>
                <div className={styles.answersReview}>
                    {mcqs.map((mcq, index) => (
                        <div key={mcq.id} className={styles.answerItem}>
                            <h4>{t('question')} {convertToBengaliNumber(index + 1)}</h4>
                            <p>{mcq.question}</p>
                            <p className={styles.correctAnswer}>
                                {t('correctAnswer')}: {mcq.correct_answer}
                            </p>
                            {mcq.explanation && (
                                <p className={styles.explanation}>{mcq.explanation}</p>
                            )}
                        </div>
                    ))}
                </div>
            </div>
        );
    }

    return (
        <div className={styles.quizContainer}>
            <div className={styles.quizHeader}>
                <h2>{t('quiz')}</h2>
                <div className={styles.progressContainer}>
                    <div className={styles.progressBar}>
                        <div 
                            className={styles.progressFill}
                            style={{ width: `${((currentQuestionIndex + 1) / mcqs.length) * 100}%` }}
                        />
                    </div>
                    <p className={styles.progressText}>
                        {t('questionProgress', { 
                            current: convertToBengaliNumber(currentQuestionIndex + 1), 
                            total: convertToBengaliNumber(mcqs.length)
                        })}
                    </p>
                </div>
            </div>
            
            <div className={styles.questionContainer}>
                <h3>{currentQuestion.question}</h3>
                <div className={styles.options}>
                    {currentQuestion.options.map((option, index) => (
                        <div 
                            key={index}
                            className={`${styles.option} ${
                                selectedAnswers[currentQuestion.id] === option ? styles.selected : ''
                            }`}
                            onClick={() => handleAnswerSelect(currentQuestion.id, option)}
                        >
                            {option}
                        </div>
                    ))}
                </div>
            </div>

            {error && <div className={styles.error}>{error}</div>}

            <div className={styles.quizControls}>
                <button 
                    className={styles.nextButton}
                    onClick={handleNext}
                    disabled={!selectedAnswers[currentQuestion.id] || submitting}
                >
                    {getButtonText()}
                </button>
            </div>
        </div>
    );
};

QuizTaker.propTypes = {
    mcqs: PropTypes.arrayOf(PropTypes.shape({
        id: PropTypes.number.isRequired,
        question: PropTypes.string.isRequired,
        correct_answer: PropTypes.string.isRequired,
        explanation: PropTypes.string
    })).isRequired,
    lessonId: PropTypes.number.isRequired
};

export default QuizTaker; 