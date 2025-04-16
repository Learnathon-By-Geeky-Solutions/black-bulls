<?php

namespace App\Modules\Enrollment\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Course\Models\Course;
use Illuminate\Support\Facades\Auth;
use Exception;

class EnrollmentService
{
    protected $courseRepository;

    public function __construct()
    {
        $this->courseRepository = app()->make(RepositoryInterface::class, ['model' => new Course()]);
    }

    public function getEnrolledCourses(string $status): array
    {
        try {
            $userId = Auth::id();

            $query = Course::select('courses.*')
                ->join('course_enrollments', 'courses.id', '=', 'course_enrollments.course_id')
                ->join('users', 'course_enrollments.user_id', '=', 'users.id')
                ->where('course_enrollments.user_id', $userId)
                ->where('course_enrollments.status', $status);

            $courses = $this->courseRepository->executeQuery($query);
            
            return [
                'is_success' => true,
                'message' => 'Enrolled courses retrieved successfully',
                'data' => $courses,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve enrolled courses: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }
}
