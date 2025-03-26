<?php

namespace App\Modules\Course\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Course\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class CourseService
{
    protected $courseRepository;

    public function __construct()
    {
        $this->courseRepository = app()->make(RepositoryInterface::class, ['model'=> new Course()]);
    }

    public function getAll(): array
    {
            $courses = $this->courseRepository->getAll();
            
            return [
                'is_success' => true,
                'message' => 'Courses retrieved successfully',
                'data' => $courses,
                'status' => 200
            ];
    }

    public function create(array $data): array
    {
        try {
            DB::beginTransaction();
            
            $data['instructor_id'] = Auth::id();
            $course = $this->courseRepository->create($data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Course created successfully',
                'data' => $course,
                'status' => 201
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to create course: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function getById(int $id): array
    {
        $course = $this->courseRepository->getById($id);
        
        return [
            'is_success' => true,
            'message' => 'Course retrieved successfully',
            'data' => $course,
            'status' => 200
        ];

    }

    public function update(int $id, array $data): array
    {
        try {
            DB::beginTransaction();
            
            $course = $this->courseRepository->update($id, $data);
            
            DB::commit();

            return [
                'message' => 'Course updated successfully',
                'data' => $course
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to update course: ' . $e->getMessage()
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            DB::beginTransaction();
            
            $this->courseRepository->delete($id);
            
            DB::commit();

            return [
                'message' => 'Course deleted successfully'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to delete course: ' . $e->getMessage()
            ];
        }
    }

    public function getUserCourses(): array
    {
        try {
            $userId = Auth::id();
            $courses = $this->courseRepository->getByIdWithParameters($userId);
            
            return [
                'message' => 'User courses retrieved successfully',
                'data' => $courses
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve user courses: ' . $e->getMessage()
            ];
        }
    }
}
