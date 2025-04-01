import { lazy } from 'react';
import MainLayout from '../components/user/layout/MainLayout';

const HomePage = lazy(() => import('../pages/user/home/HomePage'));
const CourseListingPage = lazy(() => import('../pages/user/courses/CourseListingPage'));
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