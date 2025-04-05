<?php

namespace App\Modules\Study\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Study\Services\LessonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    protected $lessonService;

    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }

    public function getLesson(int $lessonId): JsonResponse
    {
        $response = $this->lessonService->getLesson($lessonId);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function getLessonItems(int $lessonId, string $item): JsonResponse
    {
        $response = $this->lessonService->getLessonItems($lessonId, $item);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function submitQuizAnswers(Request $request, int $lessonId): JsonResponse
    {
        $response = $this->lessonService->submitQuizAnswers($lessonId, $request->all());
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function completeLesson(int $lessonId): JsonResponse
    {
        $response = $this->lessonService->completeLesson($lessonId);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function completeQuiz(int $lessonId): JsonResponse
    {
        $response = $this->lessonService->completeQuiz($lessonId);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function getChapterLessons(int $chapterId)
    {
        $response = $this->lessonService->getChapterLessons($chapterId);
        return response()->json($response, $response['status']);
    }
}
