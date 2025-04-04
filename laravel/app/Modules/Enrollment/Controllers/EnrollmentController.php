<?php

namespace App\Modules\Enrollment\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Enrollment\Services\EnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EnrollmentController extends Controller
{
    protected $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    /**
     * Get all courses enrolled by the authenticated user
     */
    public function getEnrolledCourses(string $status): JsonResponse
    {
        $response = $this->enrollmentService->getEnrolledCourses($status);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    /**
     * Enroll the authenticated user in a course
     */
    public function enroll(Request $request, int $courseId): JsonResponse
    {
        $enrollment = $this->enrollmentService->enroll($courseId);
        return response()->json([
            'status' => 'success',
            'data' => $enrollment
        ], 201);
    }

    /**
     * Unenroll the authenticated user from a course
     */
    public function unenroll(int $courseId): JsonResponse
    {
        $this->enrollmentService->unenroll($courseId);
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully unenrolled from the course'
        ]);
    }
}
