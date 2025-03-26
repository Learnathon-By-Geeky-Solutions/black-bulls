<?php

use App\Modules\Course\Controllers\CourseController;
use App\Modules\Course\Controllers\CategoryController;
use App\Modules\Course\Controllers\ChapterController;
use App\Modules\Course\Controllers\CourseSectionController;
use Illuminate\Support\Facades\Route;

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
});

// Category routes for admins
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'delete']);
    });
});
