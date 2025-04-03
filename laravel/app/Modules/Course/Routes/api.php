<?php

use App\Modules\Course\Controllers\CourseController;
use App\Modules\Course\Controllers\CategoryController;
use App\Modules\Course\Controllers\ChapterController;
use App\Modules\Course\Controllers\CourseSectionController;
use App\Modules\Course\Controllers\LessonController;
use App\Modules\Course\Controllers\HomeController;
use App\Modules\Course\Controllers\PublicCourseController;
use Illuminate\Support\Facades\Route;

// Public routes for home page
Route::prefix('home')->group(function () {
    Route::get('/free-courses', [HomeController::class, 'getFreeCourses']);
    Route::get('/popular-courses', [HomeController::class, 'getPopularCourses']);
    Route::get('/trending-courses', [HomeController::class, 'getTrendingCourses']);
    Route::get('/categories', [HomeController::class, 'getCategories']);
});

// Public course viewing routes
Route::prefix('learn')->group(function () {
    Route::get('/courses/{id}', [PublicCourseController::class, 'getCourseDetails']);
});

// Category routes for admins
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'getAll']);
        Route::get('/{id}', [CategoryController::class, 'getById']);
        Route::post('/', [CategoryController::class, 'create']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'delete']);
    });
});

// Course routes for instructors
Route::middleware(['auth:api', 'role:instructor'])->group(function () {
    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'getAll']);
        Route::get('/{id}', [CourseController::class, 'getById']);
        Route::post('/', [CourseController::class, 'create']);
        Route::put('/{id}', [CourseController::class, 'update']);
        Route::delete('/{id}', [CourseController::class, 'delete']);
        Route::get('/user/courses', [CourseController::class, 'getUserCourses']);
    });

    // Course Section routes
    Route::prefix('sections')->group(function () {
        Route::get('/', [CourseSectionController::class, 'getAll']);
        Route::get('/{id}', [CourseSectionController::class, 'getById']);
        Route::post('/', [CourseSectionController::class, 'create']);
        Route::put('/{id}', [CourseSectionController::class, 'update']);
        Route::delete('/{id}', [CourseSectionController::class, 'delete']);
        Route::get('/course/{courseId}', [CourseSectionController::class, 'getByCourse']);
    });

    // Chapter routes
    Route::prefix('chapters')->group(function () {
        Route::get('/', [ChapterController::class, 'getAll']);
        Route::get('/{id}', [ChapterController::class, 'getById']);
        Route::post('/', [ChapterController::class, 'create']);
        Route::put('/{id}', [ChapterController::class, 'update']);
        Route::delete('/{id}', [ChapterController::class, 'delete']);
        Route::get('/section/{sectionId}', [ChapterController::class, 'getBySection']);
    });

    // Lesson routes
    Route::prefix('lessons')->group(function () {
        Route::get('/', [LessonController::class, 'getAll']);
        Route::get('/{id}', [LessonController::class, 'getById']);
        Route::post('/', [LessonController::class, 'create']);
        Route::put('/{id}', [LessonController::class, 'update']);
        Route::delete('/{id}', [LessonController::class, 'delete']);
        Route::get('/chapter/{chapterId}', [LessonController::class, 'getByChapter']);
    });
});
