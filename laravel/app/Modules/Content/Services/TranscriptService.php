<?php

namespace App\Modules\Content\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Content\Models\Transcript;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TranscriptService
{
    protected $transcriptRepository;
    private const TRANSCRIPT_NOT_FOUND = 'Transcript not found';

    public function __construct()
    {
        $this->transcriptRepository = app()->make(RepositoryInterface::class, ['model'=> new Transcript()]);
    }

    public function getAll(): array
    {
        try {
            $transcripts = $this->transcriptRepository->getAll();
            
            if ($transcripts->isEmpty()) {
                return [
                    'is_success' => true,
                    'message' => 'No transcripts found',
                    'data' => null,
                    'status' => 200
                ];
            }
            
            return [
                'is_success' => true,
                'message' => 'Transcripts retrieved successfully',
                'data' => $transcripts,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve transcripts: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            DB::beginTransaction();
            
            $transcript = $this->transcriptRepository->create($data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Transcript created successfully',
                'data' => $transcript,
                'status' => 201
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to create transcript: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function getById(int $id): array
    {
        try {
            $transcript = $this->transcriptRepository->getById($id);
            
            return [
                'is_success' => true,
                'message' => 'Transcript retrieved successfully',
                'data' => $transcript,
                'status' => 200
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'is_success' => false,
                'message' => self::TRANSCRIPT_NOT_FOUND,
                'data' => null,
                'status' => 404
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve transcript: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            DB::beginTransaction();
            
            $transcript = $this->transcriptRepository->update($id, $data);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Transcript updated successfully',
                'data' => $transcript,
                'status' => 200
            ];
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return [
                'is_success' => false,
                'message' => self::TRANSCRIPT_NOT_FOUND,
                'data' => null,
                'status' => 404
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to update transcript: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            DB::beginTransaction();
            
            $this->transcriptRepository->delete($id);
            
            DB::commit();

            return [
                'is_success' => true,
                'message' => 'Transcript deleted successfully',
                'status' => 200
            ];
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return [
                'is_success' => false,
                'message' => self::TRANSCRIPT_NOT_FOUND,
                'status' => 404
            ];
        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'is_success' => false,
                'message' => 'Failed to delete transcript: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }

    public function getByTranscriptable(string $type, int $id): array
    {
        try {
            $transcripts = Transcript::where('transcriptable_type', $type)
                ->where('transcriptable_id', $id)
                ->get();
            
            return [
                'is_success' => true,
                'message' => 'Transcripts retrieved successfully',
                'data' => $transcripts,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve transcripts: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }
}
