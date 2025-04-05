<?php

namespace App\Modules\Study\Services;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Course\Models\Lesson;
use App\Modules\Course\Models\Chapter;
use App\Modules\Content\Models\Mcq;
use App\Modules\Content\Models\Transcript;
use App\Modules\Content\Models\Video;
use App\Modules\Study\Models\UserProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class LessonService
{
    protected $lessonRepository;
    protected $videoRepository;
    protected $mcqRepository;
    protected $transcriptRepository;
    protected $userProgressRepository;
    protected $chapterRepository;

    public function __construct()
    {
        $this->lessonRepository = app()->make(RepositoryInterface::class, ['model' => new Lesson()]);
        $this->videoRepository = app()->make(RepositoryInterface::class, ['model' => new Video()]);
        $this->mcqRepository = app()->make(RepositoryInterface::class, ['model' => new Mcq()]);
        $this->transcriptRepository = app()->make(RepositoryInterface::class, ['model' => new Transcript()]);
        $this->userProgressRepository = app()->make(RepositoryInterface::class, ['model' => new UserProgress()]);
        $this->chapterRepository = app()->make(RepositoryInterface::class, ['model' => new Chapter()]);
    }

    public function getLesson(int $lessonId): array
    {
        try {
            $lesson = $this->lessonRepository->getByIdWithParameters(
                $lessonId,
                ['*'],
                [],
                ['tutorial', 'videos', 'mcqs', 'transcripts']
            );
            
            return [
                'is_success' => true,
                'message' => 'Lesson retrieved successfully',
                'data' => $lesson,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve lesson: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function getLessonItems(int $lessonId, string $item): array
    {
        try {
            $repository = $item.'Repository';
            $mcqs = $this->$repository->getAll(
                ['*'],
                [
                    $item.'able_type' => 'App\Modules\Course\Models\Lesson',
                    $item.'able_id' => $lessonId
                ]
            );
            
            return [
                'is_success' => true,
                'message' => 'Lesson ' .$item. 's retrieved successfully',
                'data' => $mcqs,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve lesson ' .$item. 's: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function submitQuizAnswers(int $lessonId, array $answers): array
    {
        try {
            DB::beginTransaction();
            
            $userId = Auth::id();
            $mcqs = $this->mcqRepository->getAll(['*'], [
                'mcqable_type' => Lesson::class,
                'mcqable_id' => $lessonId
            ]);
            
            $totalScore = 0;
            $maxScore = 0;
            
            foreach ($mcqs as $mcq) {
                $maxScore += $mcq->points;
                if (isset($answers[$mcq->id]) && $answers[$mcq->id] === $mcq->correct_answer) {
                    $totalScore += $mcq->points;
                }
            }
            
            $percentage = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
            
            // Store quiz attempt
            $this->userProgressRepository->create([
                'user_id' => $userId,
                'lesson_id' => $lessonId,
                'quiz_score' => $percentage,
                'quiz_completed' => true
            ]);
            
            DB::commit();
            
            return [
                'is_success' => true,
                'message' => 'Quiz submitted successfully',
                'data' => [
                    'score' => $totalScore,
                    'max_score' => $maxScore,
                    'percentage' => $percentage
                ],
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'is_success' => false,
                'message' => 'Failed to submit quiz: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function completeLesson(int $lessonId): array
    {
        try {
            DB::beginTransaction();
            
            $userId = Auth::id();
            
            $this->userProgressRepository->create([
                'user_id' => $userId,
                'lesson_id' => $lessonId,
                'is_completed' => true
            ]);
            
            DB::commit();
            
            return [
                'is_success' => true,
                'message' => 'Lesson marked as completed',
                'data' => null,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'is_success' => false,
                'message' => 'Failed to complete lesson: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function completeQuiz(int $lessonId): array
    {
        try {
            DB::beginTransaction();
            
            $userId = Auth::id();
            
            $this->userProgressRepository->create([
                'user_id' => $userId,
                'lesson_id' => $lessonId,
                'quiz_completed' => true
            ]);
            
            DB::commit();
            
            return [
                'is_success' => true,
                'message' => 'Quiz marked as completed',
                'data' => null,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'is_success' => false,
                'message' => 'Failed to complete quiz: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }

    public function getChapterLessons(int $chapterId): array
    {
        try {
            $lessons = $this->lessonRepository->getAllWithParameters(
                ['*'],
                ['chapter_id' => $chapterId],
                ['tutorial', 'videos', 'mcqs', 'transcripts']
            );
            
            return [
                'is_success' => true,
                'message' => 'Chapter lessons retrieved successfully',
                'data' => $lessons,
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'is_success' => false,
                'message' => 'Failed to retrieve chapter lessons: ' . $e->getMessage(),
                'data' => null,
                'status' => 500
            ];
        }
    }
}
