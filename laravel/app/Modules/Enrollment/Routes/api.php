<?php

use App\Modules\Enrollment\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;

// Enrollment routes
Route::middleware('auth:api')->group(function(){
    Route::prefix('enrollments')->group(function(){
        Route::get('/my-courses/{status}', [EnrollmentController::class, 'getEnrolledCourses']);
    });
});
