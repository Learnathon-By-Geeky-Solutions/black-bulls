import React, { useState } from 'react';
import PropTypes from 'prop-types';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faChevronDown, faChevronUp, faLock, faPlay } from '@fortawesome/free-solid-svg-icons';
import './CourseChapters.css';

const CourseChapters = ({ sections }) => {
  const [expandedSections, setExpandedSections] = useState({});
  const [allExpanded, setAllExpanded] = useState(false);

  const toggleSection = (sectionId) => {
    setExpandedSections(prev => ({
      ...prev,
      [sectionId]: !prev[sectionId]
    }));
  };

  const toggleAllSections = () => {
    const newExpandedState = !allExpanded;
    setAllExpanded(newExpandedState);
    const newExpandedSections = {};
    sections?.forEach(section => {
      newExpandedSections[section.id] = newExpandedState;
    });
    setExpandedSections(newExpandedSections);
  };

  return (
    <div className="course-chapters">
      <div className="container">
        <div className="row">
          <div className="col-12">
            <div className="chapters-header">
              <h2>Course Content</h2>
              <div className="chapters-meta">
                <span>{sections?.length || 0} sections</span>
                <span>•</span>
                <span>
                  {sections?.reduce((acc, section) => 
                    acc + (section.chapters?.reduce((chapterAcc, chapter) => 
                      chapterAcc + (chapter.lessons?.length || 0), 0
                    ) || 0), 0
                  )} lessons
                </span>
                <button 
                  className="btn btn-expand-all"
                  onClick={toggleAllSections}
                >
                  <FontAwesomeIcon icon={allExpanded ? faChevronUp : faChevronDown} />
                  {allExpanded ? 'Collapse All' : 'Expand All'}
                </button>
              </div>
            </div>

            <div className="sections-list">
              {sections?.map((section) => (
                <div key={section.id} className="section-card">
                  <div 
                    className="section-header"
                    onClick={() => toggleSection(section.id)}
                  >
                    <div className="section-title">
                      <h3>{section.title}</h3>
                      <span className="section-meta">
                        {section.chapters?.length || 0} chapters
                      </span>
                    </div>
                    <FontAwesomeIcon 
                      icon={expandedSections[section.id] ? faChevronUp : faChevronDown} 
                      className="toggle-icon"
                    />
                  </div>

                  {expandedSections[section.id] && (
                    <div className="chapters-list">
                      {section.chapters?.map((chapter) => (
                        <div key={chapter.id} className="chapter-item">
                          <div className="chapter-header">
                            <h4>{chapter.title}</h4>
                            <span className="chapter-meta">
                              {chapter.lessons?.length || 0} lessons
                            </span>
                          </div>
                          
                          <div className="lessons-list">
                            {chapter.lessons?.map((lesson) => (
                              <div key={lesson.id} className="lesson-item">
                                <div className="lesson-info">
                                  <FontAwesomeIcon 
                                    icon={lesson.is_published ? faPlay : faLock} 
                                    className={lesson.is_published ? 'text-primary' : 'text-muted'}
                                  />
                                  <span>{lesson.title}</span>
                                </div>
                                <span className="lesson-duration">
                                  {lesson.duration || '5 min'}
                                </span>
                              </div>
                            ))}
                          </div>
                        </div>
                      ))}
                    </div>
                  )}
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

CourseChapters.propTypes = {
  sections: PropTypes.arrayOf(PropTypes.shape({
    id: PropTypes.string.isRequired,
    title: PropTypes.string.isRequired,
    chapters: PropTypes.arrayOf(PropTypes.shape({
      id: PropTypes.string.isRequired,
      title: PropTypes.string.isRequired,
      lessons: PropTypes.arrayOf(PropTypes.shape({
        id: PropTypes.string.isRequired,
        title: PropTypes.string.isRequired,
        is_published: PropTypes.bool,
        duration: PropTypes.string
      }))
    }))
  })).isRequired
};

export default CourseChapters; 