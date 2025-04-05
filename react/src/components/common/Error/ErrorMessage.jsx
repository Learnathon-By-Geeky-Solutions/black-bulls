import React from 'react';
import PropTypes from 'prop-types';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faExclamationCircle } from '@fortawesome/free-solid-svg-icons';

const ErrorMessage = ({ message, className = '' }) => {
  return (
    <div className={`flex items-center justify-center min-h-[400px] ${className}`}>
      <div className="bg-white p-6 rounded-lg shadow-sm max-w-md w-full">
        <div className="flex items-center justify-center mb-4">
          <FontAwesomeIcon icon={faExclamationCircle} className="text-red-500 text-2xl" />
        </div>
        <div className="text-center">
          <h3 className="text-lg font-medium text-gray-900 mb-2">Error</h3>
          <p className="text-gray-600">{message}</p>
        </div>
      </div>
    </div>
  );
};

ErrorMessage.propTypes = {
  message: PropTypes.string.isRequired,
  className: PropTypes.string
};

export default ErrorMessage; 