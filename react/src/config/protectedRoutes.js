// List of routes that require authentication
export const protectedRoutes = [
  '/dashboard',
  '/profile',
  '/settings',
  '/my-courses',
  '/course/create',
  '/course/edit',
  '/course/delete'
];

// Check if a route requires authentication
export const isProtectedRoute = (path) => {
  return protectedRoutes.some(route => path.startsWith(route));
}; 