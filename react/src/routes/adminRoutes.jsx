import { lazy } from 'react';
import AdminLayout from '../components/admin/layout/AdminLayout';
import ChapterManagement from '../pages/admin/courses/ChapterManagementPage';
import CoursePage from '../pages/user/courses/CoursePage';
import CourseForm from '../pages/admin/courses/CourseForm';
const CourseManagementPage = lazy(() => import('../pages/admin/courses/CourseManagementPage'));

const adminRoutes = [
  {
    path: '/admin',
    element: <AdminLayout />,
    children: [
      { path: 'courses', element: <CourseManagementPage /> },
      { path: 'courses/:id', element: <CoursePage/>},
      { path: 'courses/create', element: <CourseForm /> },
      { path: 'courses/:id/edit', element: <CourseForm /> },
      { path: 'chapters', element: <ChapterManagement /> },
    ],
  },
];

export default adminRoutes;