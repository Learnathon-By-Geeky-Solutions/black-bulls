<?php

namespace App\Modules\Course\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Course\Models\Lesson;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LessonService
{
    protected $lessonRepository;

    public function __construct()
    {
        $this->lessonRepository = app()->make(RepositoryInterface::class, ['model'=> new Lesson()]);
    }

    public function getAll(): array
    {
        try {
            $lessons = $this->lessonRepository->getAll();
            
            if ($lessons->isEmpty()) {
                return [
                    'is_success' => true,
                    'message' => 'No lessons found',
                    'data' => null,
                    'status' => 200
                ];
            }
            
            return [
                'is_success' => true,
                'message' => 'Lessons retrieved successfully',
                'data' => $lessons,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve lessons: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            DB::beginTransaction();
            
            $lesson = $this->lessonRepository->create($data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Lesson created successfully',
                'data' => $lesson,
                'status' => 201
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to create lesson: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function getById(int $id): array
    {
        try {
            $lesson = $this->lessonRepository->getById($id);
            
            return [
                'is_success' => true,
                'message' => 'Lesson retrieved successfully',
                'data' => $lesson,
                'status' => 200
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'is_success' => false,
                'message' => 'Lesson not found',
                'data' => null,
                'status' => 404
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve lesson: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            DB::beginTransaction();
            
            $lesson = $this->lessonRepository->update($id, $data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Lesson updated successfully',
                'data' => $lesson,
                'status' => 200
            ];
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return [
                'is_success' => false,
                'message' => 'Lesson not found',
                'data' => null,
                'status' => 404
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to update lesson: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            DB::beginTransaction();
            
            $this->lessonRepository->delete($id);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Lesson deleted successfully',
                'status' => 200
            ];
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return [
                'is_success' => false,
                'message' => 'Lesson not found',
                'status' => 404
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to delete lesson: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }

    public function getByChapter(int $chapterId): array
    {
        try {
            $lessons = Lesson::where('chapter_id', $chapterId)
                ->orderBy('order')
                ->get();
            
            return [
                'is_success' => true,
                'message' => 'Lessons retrieved successfully',
                'data' => $lessons,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve lessons: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }
}
