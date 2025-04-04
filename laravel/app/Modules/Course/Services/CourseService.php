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
use App\Modules\Common\Services\FileHandleService;

class CourseService
{
    protected $courseRepository;
    protected $fileHandleService;
    private const THUMBNAIL_PATH = 'thumbnails/courses';

    public function __construct(FileHandleService $fileHandleService)
    {
        $this->courseRepository = app()->make(RepositoryInterface::class, ['model'=> new Course()]);
        $this->fileHandleService = $fileHandleService;
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
            
            // Handle thumbnail upload
            if (isset($data['thumbnail'])) {
                $data['thumbnail'] = $this->fileHandleService->storeFile($data['thumbnail'], self::THUMBNAIL_PATH);
            }

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
            
            // Clean up uploaded thumbnail if there was an error
            if (isset($data['thumbnail'])) {
                $this->fileHandleService->deleteFile($data['thumbnail']);
            }
            
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
            
            $course = $this->courseRepository->getById($id);
            
            // Handle thumbnail upload
            if (isset($data['thumbnail'])) {
                // Delete old thumbnail
                $this->fileHandleService->deleteFile($course->thumbnail);
                $data['thumbnail'] = $this->fileHandleService->storeFile($data['thumbnail'], self::THUMBNAIL_PATH);
            }
            
            $course = $this->courseRepository->update($id, $data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Course updated successfully',
                'data' => $course,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded thumbnail if there was an error
            if (isset($data['thumbnail'])) {
                $this->fileHandleService->deleteFile($data['thumbnail']);
            }
            
            return [
                'is_success' => false,
                'message' => 'Failed to update course: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            DB::beginTransaction();
            
            $course = $this->courseRepository->getById($id);
            
            // Delete thumbnail
            $this->fileHandleService->deleteFile($course->thumbnail);
            
            $this->courseRepository->delete($id);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Course deleted successfully',
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to delete course: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }

    public function getUserCourses(): array
    {
        try {
            $userId = Auth::id();
            $courses = $this->courseRepository->getByIdWithParameters($userId);
            
            return [
                'is_success' => true,
                'message' => 'User courses retrieved successfully',
                'data' => $courses,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve user courses: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }
}
