<?php

namespace App\Modules\Course\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Course\Models\CourseSection;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CourseSectionService
{
    protected $courseSectionRepository;

    public function __construct()
    {
        $this->courseSectionRepository = app()->make(RepositoryInterface::class, ['model'=> new CourseSection()]);
    }

    public function getAll(): array
    {
        try {
            $sections = $this->courseSectionRepository->getAll();
            
            if ($sections->isEmpty()) {
                return [
                    'is_success' => true,
                    'message' => 'No course sections found',
                    'data' => null,
                    'status' => 200
                ];
            }
            
            return [
                'is_success' => true,
                'message' => 'Course sections retrieved successfully',
                'data' => $sections,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve course sections: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            DB::beginTransaction();
            
            $section = $this->courseSectionRepository->create($data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Course section created successfully',
                'data' => $section,
                'status' => 201
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to create course section: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function getById(int $id): array
    {
        try {
            $section = $this->courseSectionRepository->getById($id);
            
            return [
                'is_success' => true,
                'message' => 'Course section retrieved successfully',
                'data' => $section,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve course section: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            DB::beginTransaction();
            
            $section = $this->courseSectionRepository->update($id, $data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Course section updated successfully',
                'data' => $section,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to update course section: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            DB::beginTransaction();
            
            $section = CourseSection::findOrFail($id);
            $this->courseSectionRepository->delete($id);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Course section deleted successfully',
                'status' => 200
            ];
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return [
                'is_success' => false,
                'message' => 'Course section not found',
                'status' => 404
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to delete course section: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }

    public function getByCourse(int $courseId): array
    {
        try {
            $sections = CourseSection::where('course_id', $courseId)
                ->orderBy('order')
                ->get();
            
            if ($sections->isEmpty()) {
                return [
                    'is_success' => true,
                    'message' => 'No course sections found for this course',
                    'data' => null,
                    'status' => 200
                ];
            }
            
            return [
                'is_success' => true,
                'message' => 'Course sections retrieved successfully',
                'data' => $sections,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve course sections: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }
} 