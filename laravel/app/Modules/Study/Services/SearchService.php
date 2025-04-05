<?php

namespace App\Modules\Study\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Course\Models\CourseSection;
use App\Modules\Course\Models\Chapter;
use App\Modules\Course\Models\Lesson;
use Exception;

class SearchService
{
    protected $sectionRepository;
    protected $chapterRepository;
    protected $lessonRepository;

    public function __construct()
    {
        $this->sectionRepository = app()->make(RepositoryInterface::class, ['model' => new CourseSection()]);
        $this->chapterRepository = app()->make(RepositoryInterface::class, ['model' => new Chapter()]);
        $this->lessonRepository = app()->make(RepositoryInterface::class, ['model' => new Lesson()]);
    }

    private function searchInContent($repository, array $conditions, string $search): object
    {
        return $repository->getAllWithParameters(
            ['*'],
            $conditions,
            [],
            []
        )->filter(function ($item) use ($search) {
            return str_contains(strtolower($item->title), strtolower($search)) ||
                   str_contains(strtolower($item->description), strtolower($search));
        })->values();
    }

    public function searchCourseContent(int $courseId, string $search): array
    {
        try {
            if (empty($search)) {
                $sections = $this->sectionRepository->getAllWithParameters(
                    ['*'],
                    ['course_id' => $courseId],
                    [],
                    []
                );
                
                $chapters = $this->chapterRepository->getAllWithParameters(
                    ['*'],
                    ['course_section_id' => ['in' => $sections->pluck('id')->toArray()]],
                    [],
                    []
                );
                
                $lessons = $this->lessonRepository->getAllWithParameters(
                    ['*'],
                    ['chapter_id' => ['in' => $chapters->pluck('id')->toArray()]],
                    ['tutorial', 'videos', 'mcqs', 'transcripts'],
                    []
                );
                
                return $this->successResponse('All course content retrieved successfully', [
                    'sections' => $sections,
                    'chapters' => $chapters,
                    'lessons' => $lessons
                ]);
            }

            // Search sections
            $sections = $this->searchInContent(
                $this->sectionRepository,
                ['course_id' => $courseId],
                $search
            );
            
            // Search chapters
            $chapters = $this->searchInContent(
                $this->chapterRepository,
                ['course_section_id' => ['in' => $sections->pluck('id')->toArray()]],
                $search
            );
            
            // Search lessons with relationships
            $lessons = $this->lessonRepository->getAllWithParameters(
                ['*'],
                ['chapter_id' => ['in' => $chapters->pluck('id')->toArray()]],
                ['tutorial', 'videos', 'mcqs', 'transcripts'],
                []
            )->filter(function ($lesson) use ($search) {
                return str_contains(strtolower($lesson->title), strtolower($search)) ||
                       str_contains(strtolower($lesson->description), strtolower($search));
            })->values();
            
            return $this->successResponse('Search results retrieved successfully', [
                'sections' => $sections,
                'chapters' => $chapters,
                'lessons' => $lessons
            ]);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to search course content', $e);
        }
    }

    private function successResponse(string $message, $data = null): array
    {
        return [
            'is_success' => true,
            'message' => $message,
            'data' => $data,
            'status' => 200
        ];
    }

    private function errorResponse(string $message, Exception $e): array
    {
        return [
            'is_success' => false,
            'message' => $message . ': ' . $e->getMessage(),
            'data' => null,
            'status' => 500
        ];
    }
}
