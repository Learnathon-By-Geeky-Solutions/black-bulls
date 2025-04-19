import React from 'react';
import './AdminLayout.module.css';
import PropTypes from 'prop-types';

const AdminContent = ({ children }) => {
    return <main className="admin-content">{children}</main>;
};

AdminContent.propTypes = {
    children: PropTypes.node.isRequired,
};

export default AdminContent;