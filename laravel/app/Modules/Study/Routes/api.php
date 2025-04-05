<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Study\Controllers\SectionController;
use App\Modules\Study\Controllers\LessonController;
use App\Modules\Study\Controllers\SearchController;

Route::prefix('study')->group(function () {
    // Section routes
    Route::get('/courses/{course}/sections', [SectionController::class, 'getCourseSections']);
    Route::get('/sections/{section}/chapters', [SectionController::class, 'getSectionChapters']);
    Route::get('/sections/{section}/progress', [SectionController::class, 'getSectionProgress']);

    // Lesson routes
    Route::get('/chapters/{chapter}/lessons', [LessonController::class, 'getChapterLessons']);
    Route::get('/lessons/{lesson}', [LessonController::class, 'getLesson']);
    Route::get('/lessons/{lesson}/{item}', [LessonController::class, 'getLessonItems']);

    Route::post('/lessons/{lesson}/quizzes/submit', [LessonController::class, 'submitQuizAnswers']);
    Route::post('/lessons/{lesson}/complete', [LessonController::class, 'completeLesson']);
    Route::post('/lessons/{lesson}/quizzes/complete', [LessonController::class, 'completeQuiz']);
    // Search route
    Route::get('/courses/{course}/search', [SearchController::class, 'searchCourseContent']);
});
