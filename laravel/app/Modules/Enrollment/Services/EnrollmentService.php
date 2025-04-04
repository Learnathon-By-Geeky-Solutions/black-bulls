<?php

namespace App\Modules\Enrollment\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Course\Models\Course;
use App\Modules\Enrollment\Models\CourseEnrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class EnrollmentService
{
    protected $courseRepository;
    protected $enrollmentRepository;

    public function __construct()
    {
        $this->courseRepository = app()->make(RepositoryInterface::class, ['model' => new Course()]);
        $this->enrollmentRepository = app()->make(RepositoryInterface::class, ['model' => new CourseEnrollment()]);
    }

    /**
     * Get all courses enrolled by the authenticated user
     */
    public function getEnrolledCourses(string $status): array
    {
        try {
            $userId = Auth::id();

            $query = Course::select('courses.*')
                ->join('course_enrollments', 'courses.id', '=', 'course_enrollments.course_id')
                ->join('users', 'course_enrollments.user_id', '=', 'users.id')
                ->where('course_enrollments.user_id', $userId)
                ->where('course_enrollments.status', 'active')
                ->when($status === 'IN_PROGRESS', function($query){
                    return $query->whereNull('course_enrollments.completed_at');
                }, function($query){
                    return $query->whereNotNull('course_enrollments.completed_at');
                });

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

    /**
     * Enroll the authenticated user in a course
     */
    public function enroll(int $courseId): array
    {
        try {
            DB::beginTransaction();
            
            $userId = Auth::id();
            $course = $this->courseRepository->getById($courseId);

            // Check if already enrolled
            $existingEnrollment = $this->enrollmentRepository->getByIdWithParameters(
                $courseId,
                ['*'],
                [
                    'user_id' => $userId
                ],
                [],
                true
            );

            if ($existingEnrollment) {
                if ($existingEnrollment->status === 'dropped') {
                    $existingEnrollment->update(['status' => 'active']);
                    DB::commit();
                    return [
                        'is_success' => true,
                        'message' => 'Course enrollment reactivated successfully',
                        'data' => $existingEnrollment,
                        'status' => 200
                    ];
                }
                DB::commit();
                return [
                    'is_success' => true,
                    'message' => 'Already enrolled in this course',
                    'data' => $existingEnrollment,
                    'status' => 200
                ];
            }

            // Create new enrollment
            $enrollment = $this->enrollmentRepository->create([
                'course_id' => $courseId,
                'user_id' => $userId,
                'price_paid' => $course->discount_price ?? $course->price,
                'status' => 'active'
            ]);
            
            DB::commit();
            
            return [
                'is_success' => true,
                'message' => 'Successfully enrolled in the course',
                'data' => $enrollment,
                'status' => 201
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'is_success' => false,
                'message' => 'Failed to enroll in course: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    /**
     * Unenroll the authenticated user from a course
     */
    public function unenroll(int $courseId): array
    {
        try {
            DB::beginTransaction();
            
            $userId = Auth::id();
            
            $enrollment = $this->enrollmentRepository->getByIdWithParameters(
                $courseId,
                ['*'],
                [
                    'user_id' => $userId
                ],
                [],
                true
            );

            if (!$enrollment) {
                throw new Exception('Enrollment not found');
            }

            $enrollment->update(['status' => 'dropped']);
            
            DB::commit();
            
            return [
                'is_success' => true,
                'message' => 'Successfully unenrolled from the course',
                'data' => null,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'is_success' => false,
                'message' => 'Failed to unenroll from course: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }
}
