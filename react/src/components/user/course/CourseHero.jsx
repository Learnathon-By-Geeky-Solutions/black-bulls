import React from 'react';
import PropTypes from 'prop-types';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faStar, faUserGraduate, faClock, faLanguage, faPlay, faFile, faInfinity, faMobile, faCertificate } from '@fortawesome/free-solid-svg-icons';
import './CourseHero.css';

const CourseHero = ({ course }) => {
  return (
    <div className="course-hero">
      <div className="container">
        <div className="row">
          <div className="col-lg-8">
            <div className="course-info">
              <h1 className="course-title">{course.title}</h1>
              
              <div className="course-thumbnail">
                {course.thumbnail ? (
                  <img src={course.thumbnail} alt={course.title} className="img-fluid" />
                ) : (
                  <div className="preview-placeholder">
                    <FontAwesomeIcon icon={faPlay} />
                  </div>
                )}
              </div>

              <p className="course-description">{course.description}</p>
              
              <div className="course-meta">
                <div className="rating">
                  <FontAwesomeIcon icon={faStar} className="text-warning" />
                  <span>4.5</span>
                  <span className="text-muted">(1,234 ratings)</span>
                </div>
                <div className="students">
                  <FontAwesomeIcon icon={faUserGraduate} />
                  <span>10,000+ students</span>
                </div>
                <div className="duration">
                  <FontAwesomeIcon icon={faClock} />
                  <span>10 hours</span>
                </div>
                <div className="language">
                  <FontAwesomeIcon icon={faLanguage} />
                  <span>English</span>
                </div>
              </div>

              <div className="instructor-info">
                <div className="instructor-avatar">
                  {course.instructor?.profile_picture ? (
                    <img 
                      src={course.instructor.profile_picture} 
                      alt={course.instructor.name}
                      className="instructor-avatar-img"
                    />
                  ) : (
                    <FontAwesomeIcon icon={faUserGraduate} className="instructor-avatar-icon" />
                  )}
                </div>
                <div className="instructor-details">
                  <p className="instructor-label">Created by</p>
                  <p className="instructor-name">{course.instructor?.name}</p>
                </div>
              </div>

            </div>
          </div>
          
          <div className="col-lg-4">
            <div className="course-card">
              <div className="course-pricing">
                <div className="price">
                  {course.discount_price ? (
                    <>
                      <span className="original-price">${parseFloat(course.price).toFixed(2)}</span>
                      <span className="current-price">${parseFloat(course.discount_price).toFixed(2)}</span>
                    </>
                  ) : (
                    <span className="current-price">${parseFloat(course.price).toFixed(2)}</span>
                  )}
                </div>
                
                <button className="btn btn-enroll w-100 mb-3">
                  Enroll Now
                </button>
                
                <div className="guarantee">
                  <p>30-Day Money-Back Guarantee</p>
                </div>
                
                <div className="course-includes">
                  <h5>This course includes:</h5>
                  <ul>
                    <li>
                      <FontAwesomeIcon icon={faPlay} />
                      <span>10 hours on-demand video</span>
                    </li>
                    <li>
                      <FontAwesomeIcon icon={faFile} />
                      <span>5 downloadable resources</span>
                    </li>
                    <li>
                      <FontAwesomeIcon icon={faInfinity} />
                      <span>Full lifetime access</span>
                    </li>
                    <li>
                      <FontAwesomeIcon icon={faMobile} />
                      <span>Access on mobile and TV</span>
                    </li>
                    <li>
                      <FontAwesomeIcon icon={faCertificate} />
                      <span>Certificate of completion</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <div className="course-details">
            <h4>Course Details</h4>
            <div className="course-metrics">
              <div className="metric-item">
                <span>Price</span>
                <span className="metric-value">
                  {course.discount_price ? (
                    <>
                      <span className="original-price">
                        ${parseFloat(course.price).toFixed(2)}
                      </span>
                      <span className="discount-price">
                        ${parseFloat(course.discount_price).toFixed(2)}
                      </span>
                    </>
                  ) : (
                    `$${parseFloat(course.price).toFixed(2)}`
                  )}
                </span>
              </div>
              
              <div className="metric-item">
                <span>Total Sections</span>
                <span className="metric-value">{course.sections?.length || 0}</span>
              </div>
              
              <div className="metric-item">
                <span>Total Lessons</span>
                <span className="metric-value">
                  {course.sections?.reduce((acc, section) => 
                    acc + (section.chapters?.reduce((chapterAcc, chapter) => 
                      chapterAcc + (chapter.lessons?.length || 0), 0
                    ) || 0), 0
                  )}
                </span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  );
};

CourseHero.propTypes = {
  course: PropTypes.shape({
    title: PropTypes.string,
    description: PropTypes.string,
    thumbnail: PropTypes.string,
    price: PropTypes.number,
    discount_price: PropTypes.number,
    instructor: PropTypes.shape({
      name: PropTypes.string,
      profile_picture: PropTypes.string
    }),
    sections: PropTypes.arrayOf(PropTypes.shape({
      chapters: PropTypes.arrayOf(PropTypes.shape({
        lessons: PropTypes.array
      }))
    }))
  }).isRequired
};

export default CourseHero; 