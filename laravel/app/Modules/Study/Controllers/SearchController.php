<?php

namespace App\Modules\Study\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Study\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function searchCourseContent(Request $request, int $courseId): JsonResponse
    {
        $search = $request->query('search', '');
        $response = $this->searchService->searchCourseContent($courseId, $search);
        
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }
}
