<?php

namespace App\Modules\Study\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Study\Services\SectionService;
use Illuminate\Http\JsonResponse;

class SectionController extends Controller
{
    protected $sectionService;

    public function __construct(SectionService $sectionService)
    {
        $this->sectionService = $sectionService;
    }

    public function getCourseSections(int $courseId): JsonResponse
    {
        $response = $this->sectionService->getCourseSections($courseId);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function getSectionChapters(int $sectionId): JsonResponse
    {
        $response = $this->sectionService->getSectionChapters($sectionId);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function getSectionProgress(int $sectionId): JsonResponse
    {
        $response = $this->sectionService->getSectionProgress($sectionId);
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }
}
