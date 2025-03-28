<?php

namespace App\Modules\Course\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Requests\CreateChapterRequest;
use App\Modules\Course\Requests\UpdateChapterRequest;
use App\Modules\Course\Services\ChapterService;
use Illuminate\Http\JsonResponse;

class ChapterController extends Controller
{
    protected $chapterService;

    public function __construct(ChapterService $chapterService)
    {
        $this->chapterService = $chapterService;
    }

    public function getAll(): JsonResponse
    {
        $response = $this->chapterService->getAll();
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function getById(int $id): JsonResponse
    {
        $response = $this->chapterService->getById($id);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function create(CreateChapterRequest $request): JsonResponse
    {
        $response = $this->chapterService->create($request->validated());
        
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function update(UpdateChapterRequest $request, int $id): JsonResponse
    {
        $response = $this->chapterService->update($id, $request->validated());
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function delete(int $id): JsonResponse
    {
        $response = $this->chapterService->delete($id);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message']
        ], $response['status']);
    }

    public function getBySection(int $sectionId): JsonResponse
    {
        $response = $this->chapterService->getBySection($sectionId);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }
}
