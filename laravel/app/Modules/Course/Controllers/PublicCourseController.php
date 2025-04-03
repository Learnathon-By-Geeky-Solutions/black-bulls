<?php

namespace App\Modules\Course\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Services\PublicCourseService;
use Illuminate\Http\JsonResponse;

class PublicCourseController extends Controller
{
    protected $publicCourseService;

    public function __construct(PublicCourseService $publicCourseService)
    {
        $this->publicCourseService = $publicCourseService;
    }

    public function getCourseDetails(int $id): JsonResponse
    {
        $response = $this->publicCourseService->getCourseDetails($id);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }
}
