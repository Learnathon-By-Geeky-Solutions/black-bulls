import React from 'react';
import { NavLink } from 'react-router-dom';
import './AdminSidebar.css';
import { FaBook, FaVideo, FaFileAlt, FaQuestionCircle, FaLayerGroup, FaListAlt, FaBars } from 'react-icons/fa';
import logo from '../../../assets/images/logo.png'; // Adjust the path to your logo image
import PropTypes from 'prop-types';

const AdminSidebar = ({ isCollapsed }) => {
    const menuItems = [
        { path: '/admin/courses', label: 'Course Management', icon: <FaBook /> },
        { path: '/admin/sections', label: 'Section Management', icon: <FaLayerGroup /> },
        { path: '/admin/chapters', label: 'Chapter Management', icon: <FaListAlt /> },
        { path: '/admin/lessons', label: 'Lesson Management', icon: <FaBars /> },
        { path: '/admin/videos', label: 'Video Management', icon: <FaVideo /> },
        { path: '/admin/transcripts', label: 'Transcript Management', icon: <FaFileAlt /> },
        { path: '/admin/mcqs', label: 'MCQ Management', icon: <FaQuestionCircle /> },
    ];

    return (
        <div className={`admin-sidebar ${isCollapsed ? 'collapsed' : ''}`}>
            <div className="sidebar-logo">
                <img src={logo} alt="Logo" className="logo" />
            </div>
            <ul className="sidebar-menu">
                {menuItems.map((item) => (
                    <li key={item.path} className="menu-item">
                        <NavLink to={item.path} activeClassName="active">
                            <span className="icon">{item.icon}</span>
                            {!isCollapsed && <span className="label">{item.label}</span>}
                        </NavLink>
                    </li>
                ))}
            </ul>
        </div>
    );
};

AdminSidebar.propTypes = {
    isCollapsed: PropTypes.bool.isRequired,
};

export default AdminSidebar;