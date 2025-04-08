import React from 'react';
import { Card, ProgressBar } from 'react-bootstrap';
import './StudyComponents.css';
import PropTypes from 'prop-types';

const QuizFeedback = ({ quiz, results }) => {
    const score = results.score;
    const totalQuestions = quiz.questions.length;
    const percentage = (score / totalQuestions) * 100;

    return (
        <Card className="quiz-feedback">
            <Card.Header>
                <h4>Quiz Results</h4>
            </Card.Header>
            <Card.Body>
                <div className="score-summary mb-4">
                    <h5>Your Score: {score}/{totalQuestions}</h5>
                    <ProgressBar 
                        now={percentage} 
                        label={`${Math.round(percentage)}%`}
                        variant={percentage >= 70 ? 'success' : 'danger'}
                    />
                </div>

                <div className="question-feedback">
                    {quiz.questions.map((question, index) => {
                        const userAnswer = results.answers[question.id];
                        const correctAnswer = question.options.find(opt => opt.is_correct);
                        const isCorrect = userAnswer === correctAnswer.id;

                        return (
                            <div 
                                key={question.id} 
                                className={`question-result mb-3 p-3 ${isCorrect ? 'correct' : 'incorrect'}`}
                            >
                                <h5>Question {index + 1}</h5>
                                <p>{question.question}</p>
                                <div className="answer-feedback">
                                    <p>
                                        Your answer: {question.options.find(opt => opt.id === userAnswer)?.option}
                                    </p>
                                    {!isCorrect && (
                                        <p>
                                            Correct answer: {correctAnswer.option}
                                        </p>
                                    )}
                                </div>
                            </div>
                        );
                    })}
                </div>
            </Card.Body>
        </Card>
    );
};

QuizFeedback.propTypes = {
    quiz: PropTypes.shape({
        questions: PropTypes.arrayOf(PropTypes.shape({
            id: PropTypes.number.isRequired,
            question: PropTypes.string.isRequired,
            options: PropTypes.arrayOf(PropTypes.shape({
                id: PropTypes.number.isRequired,
                option: PropTypes.string.isRequired,
                is_correct: PropTypes.bool.isRequired
            })).isRequired
        })).isRequired
    }).isRequired,
    results: PropTypes.shape({
        score: PropTypes.number.isRequired,
        answers: PropTypes.object.isRequired
    }).isRequired
};

export default QuizFeedback; 