import React from 'react';
import { Navigate } from 'react-router-dom';
import useAuthorization from '../../../hooks/useAuthorization';
import UnauthorizedAccess from './UnauthorizedAccess';
import PropTypes from 'prop-types';

const AuthorizationWrapper = ({ allowedRoles, children }) => {
  const { isAuthorized, redirect } = useAuthorization(allowedRoles);

  if (redirect === '/login') {
    return <Navigate to={redirect} replace />;
  }

  if (!isAuthorized) {
    return <UnauthorizedAccess />;
  }

  return children;
};

AuthorizationWrapper.propTypes = {
  allowedRoles: PropTypes.arrayOf(PropTypes.string).isRequired,
  children: PropTypes.node.isRequired,
};

export default AuthorizationWrapper;