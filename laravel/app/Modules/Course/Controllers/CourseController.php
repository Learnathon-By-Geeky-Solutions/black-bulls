<?php

namespace App\Modules\Course\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Requests\CreateCourseRequest;
use App\Modules\Course\Requests\UpdateCourseRequest;
use App\Modules\Course\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function getAll(): JsonResponse
    {
        $response = $this->courseService->getAll();
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function getById(int $id): JsonResponse
    {
        $response = $this->courseService->getById($id);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function create(CreateCourseRequest $request): JsonResponse
    {
        $response = $this->courseService->create($request->validated());
        
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function update(UpdateCourseRequest $request, int $id): JsonResponse
    {
        $response = $this->courseService->update($id, $request->validated());
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function delete(int $id): JsonResponse
    {
        $response = $this->courseService->delete($id);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message']
        ], $response['status']);
    }

    public function getUserCourses(): JsonResponse
    {
        $response = $this->courseService->getUserCourses();
        return response()->json([
            'is_success' => $response['is_success'] ?? true,
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status'] ?? 200);
    }
}
