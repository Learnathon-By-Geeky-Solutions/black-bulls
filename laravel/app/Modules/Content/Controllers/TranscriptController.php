<?php

namespace App\Modules\Content\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Content\Requests\CreateTranscriptRequest;
use App\Modules\Content\Requests\UpdateTranscriptRequest;
use App\Modules\Content\Services\TranscriptService;
use Illuminate\Http\JsonResponse;

class TranscriptController extends Controller
{
    protected $transcriptService;

    public function __construct(TranscriptService $transcriptService)
    {
        $this->transcriptService = $transcriptService;
    }

    public function getAll(): JsonResponse
    {
        $response = $this->transcriptService->getAll();
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function getById(int $id): JsonResponse
    {
        $response = $this->transcriptService->getById($id);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function create(CreateTranscriptRequest $request): JsonResponse
    {
        $response = $this->transcriptService->create($request->validated());
        
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function update(UpdateTranscriptRequest $request, int $id): JsonResponse
    {
        $response = $this->transcriptService->update($id, $request->validated());
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function delete(int $id): JsonResponse
    {
        $response = $this->transcriptService->delete($id);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message']
        ], $response['status']);
    }

    public function getByTranscriptable(string $type, int $id): JsonResponse
    {
        $response = $this->transcriptService->getByTranscriptable($type, $id);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }
}
