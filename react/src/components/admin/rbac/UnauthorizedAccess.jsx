import React from 'react';
import './UnauthorizedAccess.css';

const UnauthorizedAccess = () => {
  return (
    <div className="unauthorized-container">
      <h1 className="unauthorized-title">Access Denied</h1>
      <p className="unauthorized-message">You do not have the necessary permissions to access this page.</p>
    </div>
  );
};

export default UnauthorizedAccess;