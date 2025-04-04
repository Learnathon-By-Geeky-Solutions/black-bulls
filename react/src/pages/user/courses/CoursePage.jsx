import React from 'react';
import { useParams } from 'react-router-dom';
import { useCourse } from '../../../hooks/user/course/useCourse';
import CourseHero from '../../../components/user/course/CourseHero';
import InstructorSection from '../../../components/user/course/InstructorSection';
import CourseChapters from '../../../components/user/course/CourseChapters';
import LoadingSpinner from '../../../components/common/Loading/Loading';
import ErrorMessage from '../../../components/common/Error/ErrorMessage';

const CoursePage = () => {
  const { id } = useParams();
  const { course, loading, error } = useCourse(id);

  if (loading) return <LoadingSpinner />;
  if (error) return <ErrorMessage message={error} />;
  if (!course) return <ErrorMessage message="Course not found" />;

  return (
    <div className="min-h-screen bg-gray-50">
      <CourseHero 
        course={course} 
        instructor={course.instructor}
      />
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div className="lg:col-span-2">
            <CourseChapters 
              course={course}
              sections={course.sections}
            />
          </div>
          <div className="lg:col-span-1">
            <InstructorSection 
              instructor={course.instructor}
              course={course}
            />
          </div>
        </div>
      </div>
    </div>
  );
};

export default CoursePage; 