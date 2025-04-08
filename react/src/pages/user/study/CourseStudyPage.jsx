import React, { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { FaBars } from 'react-icons/fa';
import Sidebar from '../../../components/user/study/Sidebar';
import StudyContent from '../../../components/user/study/StudyContent';
import './StudyPages.css';

const CourseStudyPage = () => {
    const { t } = useTranslation('study');
    const [isSidebarCollapsed, setIsSidebarCollapsed] = useState(false);
    const [activeContent, setActiveContent] = useState(null);

    const handleContentSelect = (type, id, data) => {
        setActiveContent({ type, id, data });
    };

    const handleQuizComplete = (results) => {
        // Handle quiz completion here
        console.log('Quiz completed:', results);
    };

    return (
        <div className="study-page">
            <div className="container-fluid">
                <div className="row">
                    <div className={`sidebar-col ${isSidebarCollapsed ? 'collapsed' : ''}`}>
                        <div className="sidebar-toggle">
                            <button 
                                className="btn btn-link toggle-btn"
                                onClick={() => setIsSidebarCollapsed(!isSidebarCollapsed)}
                            >
                                <FaBars />
                            </button>
                        </div>
                        <Sidebar
                            activeContent={activeContent}
                            onContentSelect={handleContentSelect}
                            isCollapsed={isSidebarCollapsed}
                        />
                    </div>
                    <div className="content-col">
                        <StudyContent 
                            activeContent={activeContent}
                            onQuizComplete={handleQuizComplete}
                        />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default CourseStudyPage; 