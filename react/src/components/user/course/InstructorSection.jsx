import React from 'react';
import PropTypes from 'prop-types';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faUserCircle, faStar } from '@fortawesome/free-solid-svg-icons';
import './InstructorSection.css';

const InstructorSection = ({ instructor }) => {
  return (
    <div className="instructor-card">
      <div className="card-body">
        <h2 className="card-title">Instructor</h2>
        
        <div className="instructor-profile">
          {instructor?.profile_picture ? (
            <img
              src={instructor.profile_picture}
              alt={instructor.name}
              className="instructor-avatar"
            />
          ) : (
            <div className="instructor-avatar-fallback">
              <FontAwesomeIcon icon={faUserCircle} />
            </div>
          )}
          <div className="instructor-info">
            <h3>{instructor?.name}</h3>
            <p>{instructor?.email}</p>
            {instructor?.phone && (
              <p>{instructor.phone}</p>
            )}
            <div className="instructor-rating">
              <FontAwesomeIcon icon={faStar} className="text-warning" />
              <span>4.5 Instructor Rating</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

InstructorSection.propTypes = {
  instructor: PropTypes.shape({
    name: PropTypes.string,
    email: PropTypes.string,
    phone: PropTypes.string,
    profile_picture: PropTypes.string
  }).isRequired
};

export default InstructorSection; 