<?php

namespace App\Modules\Course\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Course\Models\Chapter;
use Illuminate\Support\Facades\DB;
use Exception;

class ChapterService
{
    protected $chapterRepository;

    public function __construct()
    {
        $this->chapterRepository = app()->make(RepositoryInterface::class, ['model'=> new Chapter()]);
    }

    public function getAll(): array
    {
        try {
            $chapters = $this->chapterRepository->getAll();
            
            if ($chapters->isEmpty()) {
                return [
                    'is_success' => true,
                    'message' => 'No chapters found',
                    'data' => null,
                    'status' => 200
                ];
            }
            
            return [
                'is_success' => true,
                'message' => 'Chapters retrieved successfully',
                'data' => $chapters,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve chapters: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            DB::beginTransaction();
            
            $chapter = $this->chapterRepository->create($data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Chapter created successfully',
                'data' => $chapter,
                'status' => 201
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to create chapter: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function getById(int $id): array
    {
        try {
            $chapter = $this->chapterRepository->getById($id);
            
            return [
                'is_success' => true,
                'message' => 'Chapter retrieved successfully',
                'data' => $chapter,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve chapter: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            DB::beginTransaction();
            
            $chapter = $this->chapterRepository->update($id, $data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Chapter updated successfully',
                'data' => $chapter,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to update chapter: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            DB::beginTransaction();
            
            $this->chapterRepository->delete($id);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Chapter deleted successfully',
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to delete chapter: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }

    public function getBySection(int $sectionId): array
    {
        try {
            $chapters = Chapter::where('section_id', $sectionId)
                ->orderBy('order')
                ->get();
            
            return [
                'is_success' => true,
                'message' => 'Chapters retrieved successfully',
                'data' => $chapters,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve chapters: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }
}
