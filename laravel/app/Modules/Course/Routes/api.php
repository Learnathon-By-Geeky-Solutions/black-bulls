<?php

use App\Modules\Course\Controllers\CourseController;
use App\Modules\Course\Controllers\CategoryController;
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
