import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import useStudyService from '../../../hooks/user/study/useStudyService';
import { Container, Row, Col, Card, Pagination, Image, Badge } from 'react-bootstrap';
import { FaCalendar } from 'react-icons/fa';
import ErrorBoundary from '../../../components/common/ErrorBoundary';
import './StudyPages.css';

const CourseOverviewPage = () => {
    const { t } = useTranslation('study');
    const { id } = useParams();
    const navigate = useNavigate();
    const { getCourseSections, getCourseDetails } = useStudyService();
    const [course, setCourse] = useState(null);
    const [sections, setSections] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [pagination, setPagination] = useState({
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 0,
        links: []
    });

    useEffect(() => {
        const fetchData = async () => {
            try {
                setLoading(true);
                const [courseResponse, sectionsResponse] = await Promise.all([
                    getCourseDetails(id),
                    getCourseSections(id)
                ]);

                if (courseResponse.is_success && sectionsResponse.is_success) {
                    setCourse(courseResponse.data);
                    setSections(sectionsResponse.data);
                    setPagination(sectionsResponse.pagination);
                } else {
                    setError(courseResponse.message || sectionsResponse.message);
                }
            } catch (err) {
                setError(t('error.fetch_course'));
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, [id, getCourseDetails, getCourseSections, t]);

    const handleSectionClick = (sectionId) => {
        navigate(`/study/courses/section/${sectionId}`);
    };

    const handlePageChange = async (page) => {
        try {
            setLoading(true);
            const response = await getCourseSections(id, page);
            if (response.is_success) {
                setSections(response.data);
                setPagination(response.pagination);
            } else {
                setError(response.message);
            }
        } catch (err) {
            setError(t('error.fetch_sections'));
        } finally {
            setLoading(false);
        }
    };

    if (loading) return <div className="text-center p-5">{t('loading')}</div>;
    if (error) return <div className="text-center p-5 text-danger">{error}</div>;

    return (
        <ErrorBoundary>
            <Container className="py-4">
                {/* Course Header */}
                <Card className="course-header mb-4">
                    <div className="course-thumbnail-wrapper">
                        <Image 
                            src={course?.thumbnail || '/placeholder-course.png'} 
                            alt={course?.title}
                            className="course-thumbnail"
                        />
                    </div>
                    <div className="course-info">
                        <h1 className="course-title mb-3">{course?.title}</h1>
                        <p className="course-description text-muted mb-4">
                            {course?.description}
                        </p>
                        <div className="course-meta">
                            <div className="meta-item">
                                <span className="meta-icon">📚</span>
                                <span className="meta-text">
                                    {sections.length} {t('course.sections')}
                                </span>
                            </div>
                            <div className="meta-item">
                                <span className="meta-icon">⏱️</span>
                                <span className="meta-text">
                                    {course?.duration || '0'} {t('course.hours')}
                                </span>
                            </div>
                            <div className="meta-item">
                                <span className="meta-icon">📅</span>
                                <span className="meta-text">
                                    {t('course.updated')} {new Date(course?.updated_at).toLocaleDateString()}
                                </span>
                            </div>
                        </div>
                    </div>
                </Card>

                {/* Sections */}
                <div className="sections-container">
                    <div className="sections-header">
                        <h2 className="sections-title mb-4">{t('course.content')}</h2>
                        <div className="sections-summary">
                            <span>{sections.length} {t('course.sections')}</span>
                            <span>•</span>
                            <span>{pagination.total} {t('course.lessons_total')}</span>
                        </div>
                    </div>
                    <Row>
                        {sections.length === 0 ? (
                            <Col>
                                <Card className="text-center">
                                    <Card.Body>
                                        <p>{t('course.no_sections')}</p>
                                    </Card.Body>
                                </Card>
                            </Col>
                        ) : (
                            sections.map((section) => (
                                <Col key={section.id} md={6} lg={4} className="mb-4">
                                    <Card 
                                        className="section-card h-100 cursor-pointer shadow-sm"
                                        onClick={() => handleSectionClick(section.id)}
                                    >
                                        <div className="section-card-header">
                                            <div className="section-order">{section.order}</div>
                                            <Badge 
                                                bg={section.is_published ? "success" : "warning"}
                                                className="section-status"
                                            >
                                                {t(`course.status.${section.is_published ? 'published' : 'draft'}`)}
                                            </Badge>
                                        </div>
                                        <Card.Body>
                                            <Card.Title className="section-title">
                                                {section.title}
                                            </Card.Title>
                                            <Card.Text className="section-description">
                                                {section.description}
                                            </Card.Text>
                                            <div className="section-meta">
                                                <small className="text-muted">
                                                    <FaCalendar className="me-1" />
                                                    {new Date(section.created_at).toLocaleDateString()}
                                                </small>
                                                <div className="section-progress">
                                                    <div className="progress">
                                                    <progress 
                                                        value={section.progress || 0} 
                                                        max="100"
                                                        className="progress-bar"
                                                    />
                                                    </div>
                                                    <span className="progress-text">
                                                        {section.progress || 0}% {t('course.progress.complete')}
                                                    </span>
                                                </div>
                                            </div>
                                        </Card.Body>
                                    </Card>
                                </Col>
                            ))
                        )}
                    </Row>
                    
                    {pagination.last_page > 1 && (
                        <div className="d-flex justify-content-center mt-4">
                            <Pagination>
                                {pagination.links.map((link, index) => (
                                    <Pagination.Item
                                        key={link.label || index}
                                        active={link.active}
                                        disabled={!link.url}
                                        onClick={() => link.url && handlePageChange(link.label)}
                                    >
                                        <span dangerouslySetInnerHTML={{ __html: link.label }} />
                                    </Pagination.Item>
                                ))}
                            </Pagination>
                        </div>
                    )}
                </div>
            </Container>
        </ErrorBoundary>
    );
};

export default CourseOverviewPage; 