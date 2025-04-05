import { lazy } from 'react';
import MainLayout from '../components/user/layout/MainLayout';
import CoursePage from '../pages/user/courses/CoursePage';

const HomePage = lazy(() => import('../pages/user/home/HomePage'));
const CourseListingPage = lazy(() => import('../pages/user/courses/CourseListingPage'));
const EnrolledCoursesPage = lazy(() => import('../pages/user/courses/EnrolledCoursesPage'));
const LoginPage = lazy(() => import('../pages/user/auth/LoginPage'));
const RegisterPage = lazy(() => import('../pages/user/auth/RegisterPage'));
const ProfilePage = lazy(() => import('../pages/user/profile/ProfilePage'));

const userRoutes = [
  {
    path: '/',
    element: <MainLayout />,
    children: [
      { path: '', element: <HomePage /> },
      { path: '/profile', element: <ProfilePage /> },
    ]
  },
  {
    path: '/learn',
    element: <MainLayout />,
    children: [
      { path: '', element: <CourseListingPage /> },
      { path: 'courses/:id', element: <CoursePage /> },
      { path: 'my-courses', element: <EnrolledCoursesPage /> },
    ]
  },
  {
    path: '/courses/:type',
    element: <CourseListingPage />
  },
  {
    path: '/login',
    element: <LoginPage />
  },
  {
    path: '/register',
    element: <RegisterPage />
  }
];

export default userRoutes; 