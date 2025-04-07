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
            
            return $this->successResponse('Lesson retrieved successfully', $lesson);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve lesson', $e);
        }
    }

    public function getLessonItems(int $lessonId, string $item): array
    {
        try {
            $repository = $item.'Repository';
            $items = $this->$repository->getAll(
                ['*'],
                [
                    $item.'able_type' => 'App\Modules\Course\Models\Lesson',
                    $item.'able_id' => $lessonId
                ]
            );
            
            return $this->successResponse("Lesson {$item}s retrieved successfully", $items);
        } catch (Exception $e) {
            return $this->errorResponse("Failed to retrieve lesson {$item}s", $e);
        }
    }

    public function submitQuizAnswers(int $lessonId, array $answers): array
    {
        try {
            $userId = Auth::id();
            
            // Check if quiz has already been taken
            $existingProgress = $this->userProgressRepository->getAll(
                ['*'],
                [
                    'user_id' => $userId,
                    'lesson_id' => $lessonId,
                    'quiz_completed' => true
                ],
                [],
                1
            )->first();
            
            if ($existingProgress) {
                return $this->successResponse('Quiz already completed', [
                    'score' => $existingProgress->quiz_score,
                    'max_score' => 100,
                    'percentage' => $existingProgress->quiz_score,
                    'message' => 'You have already completed this quiz'
                ]);
            }
            
            DB::beginTransaction();
            
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
            
            return $this->successResponse('Quiz submitted successfully', [
                'score' => $totalScore,
                'max_score' => $maxScore,
                'percentage' => $percentage
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to submit quiz', $e);
        }
    }

    private function createUserProgress(int $lessonId, array $data): void
    {
        $userId = Auth::id();
        $this->userProgressRepository->create(array_merge([
            'user_id' => $userId,
            'lesson_id' => $lessonId
        ], $data));
    }

    private function completeContent(int $lessonId, string $type): array
    {
        try {
            DB::beginTransaction();
            
            $field = $type === 'lesson' ? 'is_completed' : 'quiz_completed';
            $this->createUserProgress($lessonId, [$field => true]);
            
            DB::commit();
            
            $message = $type === 'lesson' ? 'Lesson marked as completed' : 'Quiz marked as completed';
            return $this->successResponse($message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = $type === 'lesson' ? 'Failed to complete lesson' : 'Failed to complete quiz';
            return $this->errorResponse($message, $e);
        }
    }

    public function completeLesson(int $lessonId): array
    {
        return $this->completeContent($lessonId, 'lesson');
    }

    public function completeQuiz(int $lessonId): array
    {
        return $this->completeContent($lessonId, 'quiz');
    }

    public function getChapterLessons(int $chapterId): array
    {
        try {
            $lessons = $this->lessonRepository->getAllWithParameters(
                ['*'],
                ['chapter_id' => $chapterId],
                ['tutorial', 'videos', 'mcqs', 'transcripts']
            );
            
            return $this->successResponse('Chapter lessons retrieved successfully', $lessons);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve chapter lessons', $e);
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
