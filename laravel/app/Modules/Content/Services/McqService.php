<?php

namespace App\Modules\Content\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Content\Models\Mcq;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class McqService
{
    protected $mcqRepository;
    private const MCQ_NOT_FOUND = 'MCQ not found';

    public function __construct()
    {
        $this->mcqRepository = app()->make(RepositoryInterface::class, ['model'=> new Mcq()]);
    }

    public function getAll(): array
    {
        try {
            $mcqs = $this->mcqRepository->getAll();
            
            if ($mcqs->isEmpty()) {
                return [
                    'is_success' => true,
                    'message' => 'No MCQs found',
                    'data' => null,
                    'status' => 200
                ];
            }
            
            return [
                'is_success' => true,
                'message' => 'MCQs retrieved successfully',
                'data' => $mcqs,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve MCQs: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            DB::beginTransaction();
            
            $mcq = $this->mcqRepository->create($data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'MCQ created successfully',
                'data' => $mcq,
                'status' => 201
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to create MCQ: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function getById(int $id): array
    {
        try {
            $mcq = $this->mcqRepository->getById($id);
            
            return [
                'is_success' => true,
                'message' => 'MCQ retrieved successfully',
                'data' => $mcq,
                'status' => 200
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'is_success' => false,
                'message' => self::MCQ_NOT_FOUND,
                'data' => null,
                'status' => 404
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve MCQ: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            DB::beginTransaction();
            
            $mcq = $this->mcqRepository->update($id, $data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'MCQ updated successfully',
                'data' => $mcq,
                'status' => 200
            ];
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return [
                'is_success' => false,
                'message' => self::MCQ_NOT_FOUND,
                'data' => null,
                'status' => 404
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to update MCQ: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            DB::beginTransaction();
            
            $this->mcqRepository->delete($id);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'MCQ deleted successfully',
                'status' => 200
            ];
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return [
                'is_success' => false,
                'message' => self::MCQ_NOT_FOUND,
                'status' => 404
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to delete MCQ: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }

    public function getByMcqable(string $type, int $id): array
    {
        try {
            $mcqs = Mcq::where('mcqable_type', $type)
                ->where('mcqable_id', $id)
                ->get();
            
            return [
                'is_success' => true,
                'message' => 'MCQs retrieved successfully',
                'data' => $mcqs,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve MCQs: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }
}
