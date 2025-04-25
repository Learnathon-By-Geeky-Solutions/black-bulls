import { useSelector } from 'react-redux';

const useAuthorization = (allowedRoles) => {
  const user = useSelector((state) => state.user.user);

  if (!user) {
    return { isAuthorized: false, redirect: '/login' };
  }

  const roles = user.roles ? user.roles.map((role) => role.name) : [];
  const isAuthorized = roles.some((role) => allowedRoles.includes(role));

  return { isAuthorized, redirect: isAuthorized ? null : '/unauthorized' };
};

export default useAuthorization;