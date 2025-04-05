<?php

namespace App\Modules\Study\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Course\Models\Lesson;
use App\Modules\Course\Models\Chapter;
use App\Modules\Course\Models\CourseSection;
use Exception;

class SearchService
{
    protected $lessonRepository;
    protected $chapterRepository;
    protected $sectionRepository;

    public function __construct()
    {
        $this->lessonRepository = app()->make(RepositoryInterface::class, ['model' => new Lesson()]);
        $this->chapterRepository = app()->make(RepositoryInterface::class, ['model' => new Chapter()]);
        $this->sectionRepository = app()->make(RepositoryInterface::class, ['model' => new CourseSection()]);
    }

    public function searchCourseContent(int $courseId, string $search): array
    {
        try {
            // If search is empty, return all content
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
                
                return [
                    'is_success' => true,
                    'message' => 'All course content retrieved successfully',
                    'data' => [
                        'sections' => $sections,
                        'chapters' => $chapters,
                        'lessons' => $lessons
                    ],
                    'status' => 200
                ];
            }

            // Search sections
            $sections = $this->sectionRepository->getAllWithParameters(
                ['*'],
                ['course_id' => $courseId],
                [],
                []
            )->filter(function ($section) use ($search) {
                return str_contains(strtolower($section->title), strtolower($search)) ||
                       str_contains(strtolower($section->description), strtolower($search));
            });
            
            // Search chapters
            $chapters = $this->chapterRepository->getAllWithParameters(
                ['*'],
                ['course_section_id' => ['in' => $sections->pluck('id')->toArray()]],
                [],
                []
            )->filter(function ($chapter) use ($search) {
                return str_contains(strtolower($chapter->title), strtolower($search)) ||
                       str_contains(strtolower($chapter->description), strtolower($search));
            });
            
            // Search lessons
            $lessons = $this->lessonRepository->getAllWithParameters(
                ['*'],
                ['chapter_id' => ['in' => $chapters->pluck('id')->toArray()]],
                ['tutorial', 'videos', 'mcqs', 'transcripts'],
                []
            )->filter(function ($lesson) use ($search) {
                return str_contains(strtolower($lesson->title), strtolower($search)) ||
                       str_contains(strtolower($lesson->description), strtolower($search));
            });
            
            return [
                'is_success' => true,
                'message' => 'Search results retrieved successfully',
                'data' => [
                    'sections' => $sections->values(),
                    'chapters' => $chapters->values(),
                    'lessons' => $lessons->values()
                ],
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to search course content: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }
}
