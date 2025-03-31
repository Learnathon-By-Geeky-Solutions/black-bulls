<?php

namespace App\Modules\Course\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Course\Services\HomeService;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    protected $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function getFreeCourses(): JsonResponse
    {
        $response = $this->homeService->getFreeCourses();
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function getPopularCourses(): JsonResponse
    {
        $response = $this->homeService->getPopularCourses();
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function getTrendingCourses(): JsonResponse
    {
        $response = $this->homeService->getTrendingCourses();
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function getCategories(): JsonResponse
    {
        $response = $this->homeService->getCategories();
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }
}
