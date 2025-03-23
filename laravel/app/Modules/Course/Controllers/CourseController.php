<?php

namespace App\Modules\Course\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Requests\CourseRequest;
use App\Modules\Course\Services\CourseService;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function getAll(): JsonResponse
    {
        $courses = $this->courseService->getAll();
        return response()->json([
            'is_success' => true,
            'data' => $courses
        ]);
    }

    public function getById(int $id): JsonResponse
    {
        $course = $this->courseService->getById($id);
        return response()->json([
            'is_success' => true,
            'data' => $course
        ]);
    }

    public function create(CourseRequest $request): JsonResponse
    {
        $course = $this->courseService->create($request->validated());
        return response()->json([
            'is_success' => true,
            'data' => $course
        ], 201);
    }

    public function update(CourseRequest $request, int $id): JsonResponse
    {
        $course = $this->courseService->update($id, $request->validated());
        return response()->json([
            'is_success' => true,
            'data' => $course
        ]);
    }

    public function delete(int $id): JsonResponse
    {
        $this->courseService->delete($id);
        return response()->json([
            'is_success' => true,
            'message' => 'Course deleted successfully'
        ]);
    }

    public function getUserCourses(): JsonResponse
    {
        $courses = $this->courseService->getUserCourses();
        return response()->json([
            'is_success' => true,
            'data' => $courses
        ]);
    }
}
