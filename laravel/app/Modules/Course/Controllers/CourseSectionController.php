<?php

namespace App\Modules\Course\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Requests\CreateCourseSectionRequest;
use App\Modules\Course\Requests\UpdateCourseSectionRequest;
use App\Modules\Course\Services\CourseSectionService;
use Illuminate\Http\JsonResponse;

class CourseSectionController extends Controller
{
    protected $courseSectionService;

    public function __construct(CourseSectionService $courseSectionService)
    {
        $this->courseSectionService = $courseSectionService;
    }

    public function getAll(): JsonResponse
    {
        $response = $this->courseSectionService->getAll();
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function getById(int $id): JsonResponse
    {
        $response = $this->courseSectionService->getById($id);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function create(CreateCourseSectionRequest $request): JsonResponse
    {
        $response = $this->courseSectionService->create($request->validated());
        
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function update(UpdateCourseSectionRequest $request, int $id): JsonResponse
    {
        $response = $this->courseSectionService->update($id, $request->validated());
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function delete(int $id): JsonResponse
    {
        $response = $this->courseSectionService->delete($id);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message']
        ], $response['status']);
    }

    public function getByCourse(int $courseId): JsonResponse
    {
        $response = $this->courseSectionService->getByCourse($courseId);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }
} 