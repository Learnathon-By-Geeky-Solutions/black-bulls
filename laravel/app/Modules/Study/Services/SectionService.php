<?php

namespace App\Modules\Study\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Course\Models\CourseSection;
use Illuminate\Support\Facades\Auth;
use Exception;

class SectionService
{
    protected $sectionRepository;

    public function __construct()
    {
        $this->sectionRepository = app()->make(RepositoryInterface::class, ['model' => new CourseSection()]);
    }

    public function getCourseSections(int $courseId): array
    {
        try {
            $sections = $this->sectionRepository->getAll(['*'], ['course_id' => $courseId]);
            
            return [
                'is_success' => true,
                'message' => 'Course sections retrieved successfully',
                'data' => $sections,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve course sections: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function getSectionChapters(int $sectionId): array
    {
        try {
            $section = $this->sectionRepository->getById($sectionId);
            $chapters = $section->chapters()->with(['lessons', 'mcqs'])->get();
            
            return [
                'is_success' => true,
                'message' => 'Section chapters retrieved successfully',
                'data' => $chapters,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve section chapters: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function getSectionProgress(int $sectionId): array
    {
        try {
            $userId = Auth::id();
            $section = $this->sectionRepository->getById($sectionId);
            
            // Get all lessons through chapters
            $totalLessons = 0;
            $completedLessons = 0;
            
            foreach ($section->chapters as $chapter) {
                $chapterLessons = $chapter->lessons;
                $totalLessons += $chapterLessons->count();
                
                $completedLessons += $chapterLessons->filter(function ($lesson) use ($userId) {
                    return $lesson->userProgress()
                        ->where('user_id', $userId)
                        ->where('is_completed', true)
                        ->exists();
                })->count();
            }
            
            $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
            
            return [
                'is_success' => true,
                'message' => 'Section progress retrieved successfully',
                'data' => [
                    'total_lessons' => $totalLessons,
                    'completed_lessons' => $completedLessons,
                    'progress_percentage' => $progress
                ],
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve section progress: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }
}
