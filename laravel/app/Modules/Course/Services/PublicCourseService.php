<?php

namespace App\Modules\Course\Services;

use App\Modules\Course\Models\Course;
use Exception;

class PublicCourseService
{
    public function getCourseDetails(int $id): array
    {
        try {
            $course = Course::with([
                'instructor',
                'categories',
                'enrollments',
                'reviews',
                'sections' => function($query) {
                    $query->orderBy('order');
                },
                'sections.chapters' => function($query) {
                    $query->orderBy('order');
                },
                'sections.chapters.lessons' => function($query) {
                    $query->orderBy('order');
                }
            ])->find($id);

            if (!$course) {
                return [
                    'is_success' => false,
                    'message' => 'Course not found',
                    'data' => null,
                    'status' => 404
                ];
            }

            return [
                'is_success' => true,
                'message' => 'Course details retrieved successfully',
                'data' => $course,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve course details: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }
}
