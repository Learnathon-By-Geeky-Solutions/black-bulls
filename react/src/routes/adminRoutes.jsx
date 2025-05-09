import AdminLayout from '../components/admin/layout/AdminLayout';
import CoursePage from '../pages/user/courses/CoursePage';
import CourseForm from '../pages/admin/courses/CourseForm';
import SectionManagementPage from '../pages/admin/sections/SectionManagementPage';
import SectionForm from '../pages/admin/sections/SectionForm';
import SectionView from '../pages/admin/sections/SectionView';
import ChapterManagementPage from '../pages/admin/chapters/ChapterManagementPage';
import ChapterForm from '../pages/admin/chapters/ChapterForm';
import CourseManagementPage from '../pages/admin/courses/CourseManagementPage';
import ChapterView from '../pages/admin/chapters/ChapterView';
import LessonManagementPage from '../pages/admin/lessons/LessonManagementPage';
import LessonForm from '../pages/admin/lessons/LessonForm';
import LessonView from '../pages/admin/lessons/LessonView';
import VideoManagementPage from '../pages/admin/videos/VideoManagementPage';
import VideoForm from '../pages/admin/videos/VideoForm';
import VideoView from '../pages/admin/videos/VideoView';
import McqManagementPage from '../pages/admin/mcqs/McqManagementPage';
import McqForm from '../pages/admin/mcqs/McqForm';
import McqView from '../pages/admin/mcqs/McqView';
import PropTypes from 'prop-types';
import AuthorizationWrapper from '../components/admin/rbac/AuthorizationWrapper';

const ProtectedRoute = ({ children }) => {
  return (
    <AuthorizationWrapper allowedRoles={['admin', 'instructor']}>
      {children}
    </AuthorizationWrapper>
  );
};

ProtectedRoute.propTypes = {
  children: PropTypes.node.isRequired,
};

const adminRoutes = [
  {
    path: '/admin',
    element: (
      <ProtectedRoute>
        <AdminLayout />
      </ProtectedRoute>
    ),
    children: [
      { path: 'courses', element: <CourseManagementPage /> },
      { path: 'courses/:id', element: <CoursePage /> },
      { path: 'courses/create', element: <CourseForm /> },
      { path: 'courses/:id/edit', element: <CourseForm /> },
      { path: 'sections', element: <SectionManagementPage /> },
      { path: 'sections/:id', element: <SectionView /> },
      { path: 'sections/create', element: <SectionForm /> },
      { path: 'sections/:id/edit', element: <SectionForm /> },
      { path: 'chapters', element: <ChapterManagementPage /> },
      { path: 'chapters/:id', element: <ChapterView /> },
      { path: 'chapters/create', element: <ChapterForm /> },
      { path: 'chapters/:id/edit', element: <ChapterForm /> },
      { path: 'lessons', element: <LessonManagementPage /> },
      { path: 'lessons/:id', element: <LessonView /> },
      { path: 'lessons/create', element: <LessonForm /> },
      { path: 'lessons/:id/edit', element: <LessonForm /> },
      { path: 'videos', element: <VideoManagementPage /> },
      { path: 'videos/:id', element: <VideoView /> },
      { path: 'videos/create', element: <VideoForm /> },
      { path: 'videos/:id/edit', element: <VideoForm /> },
      { path: 'mcqs', element: <McqManagementPage /> },
      { path: 'mcqs/:id', element: <McqView /> },
      { path: 'mcqs/create', element: <McqForm /> },
      { path: 'mcqs/:id/edit', element: <McqForm /> },
    ],
  },
];

export default adminRoutes;