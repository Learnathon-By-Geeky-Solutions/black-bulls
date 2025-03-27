<?php

namespace App\Modules\Course\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Requests\CreateLessonRequest;
use App\Modules\Course\Requests\UpdateLessonRequest;
use App\Modules\Course\Services\LessonService;
use Illuminate\Http\JsonResponse;

class LessonController extends Controller
{
    protected $lessonService;

    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }

    public function getAll(): JsonResponse
    {
        $response = $this->lessonService->getAll();
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function getById(int $id): JsonResponse
    {
        $response = $this->lessonService->getById($id);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function create(CreateLessonRequest $request): JsonResponse
    {
        $response = $this->lessonService->create($request->validated());
        
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function update(UpdateLessonRequest $request, int $id): JsonResponse
    {
        $response = $this->lessonService->update($id, $request->validated());
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function delete(int $id): JsonResponse
    {
        $response = $this->lessonService->delete($id);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message']
        ], $response['status']);
    }

    public function getByChapter(int $chapterId): JsonResponse
    {
        $response = $this->lessonService->getByChapter($chapterId);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }
}
