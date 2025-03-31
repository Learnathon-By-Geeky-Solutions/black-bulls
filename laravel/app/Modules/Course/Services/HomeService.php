<?php

namespace App\Modules\Course\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\Category;
use Illuminate\Support\Facades\DB;
use Exception;

class HomeService
{
    protected $courseRepository;
    protected $categoryRepository;

    public function __construct()
    {
        $this->courseRepository = app()->make(RepositoryInterface::class, ['model' => new Course()]);
        $this->categoryRepository = app()->make(RepositoryInterface::class, ['model' => new Category()]);
    }

    public function getFreeCourses(): array
    {
        try {
            $courses = $this->courseRepository->getAll(
                ['*'],
                ['price' => 0],
                ['categories', 'instructor'],
                6
            );
            
            return [
                'is_success' => true,
                'message' => 'Free courses retrieved successfully',
                'data' => $courses,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve free courses: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function getPopularCourses(): array
    {
        try {
            $query = Course::select('courses.*')
                ->withCount('enrollments')
                ->with(['categories', 'instructor'])
                ->orderBy('enrollments_count', 'desc')
                ->take(6);

            $courses = $this->courseRepository->executeQuery($query);
            
            return [
                'is_success' => true,
                'message' => 'Popular courses retrieved successfully',
                'data' => $courses,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve popular courses: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function getTrendingCourses(): array
    {
        try {
            $query = Course::select('*')
                ->with(['categories', 'instructor'])
                ->orderBy('created_at', 'desc')
                ->take(6);

            $courses = $this->courseRepository->executeQuery($query);
            
            return [
                'is_success' => true,
                'message' => 'Trending courses retrieved successfully',
                'data' => $courses,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve trending courses: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function getCategories(): array
    {
        try {
            $query = Category::select('*')
                ->take(100);

            $categories = $this->categoryRepository->executeQuery($query);
            
            return [
                'is_success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve categories: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }
}
