<?php

namespace App\Modules\Content\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Content\Requests\CreateMcqRequest;
use App\Modules\Content\Requests\UpdateMcqRequest;
use App\Modules\Content\Services\McqService;
use Illuminate\Http\JsonResponse;

class McqController extends Controller
{
    protected $mcqService;

    public function __construct(McqService $mcqService)
    {
        $this->mcqService = $mcqService;
    }

    public function getAll(): JsonResponse
    {
        $response = $this->mcqService->getAll();
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function getById(int $id): JsonResponse
    {
        $response = $this->mcqService->getById($id);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function create(CreateMcqRequest $request): JsonResponse
    {
        $response = $this->mcqService->create($request->validated());
        
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function update(UpdateMcqRequest $request, int $id): JsonResponse
    {
        $response = $this->mcqService->update($id, $request->validated());
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function delete(int $id): JsonResponse
    {
        $response = $this->mcqService->delete($id);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message']
        ], $response['status']);
    }

    public function getByMcqable(string $type, int $id): JsonResponse
    {
        $response = $this->mcqService->getByMcqable($type, $id);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }
}
