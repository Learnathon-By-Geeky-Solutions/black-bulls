<?php

use App\Modules\Content\Controllers\VideoController;
use App\Modules\Content\Controllers\TranscriptController;
use App\Modules\Content\Controllers\McqController;
use Illuminate\Support\Facades\Route;

// Video routes for instructors
Route::middleware(['auth:api', 'role:instructor'])->group(function () {
    Route::prefix('videos')->group(function () {
        Route::get('/', [VideoController::class, 'getAll']);
        Route::get('/{id}', [VideoController::class, 'getById']);
        Route::post('/', [VideoController::class, 'create']);
        Route::put('/{id}', [VideoController::class, 'update']);
        Route::delete('/{id}', [VideoController::class, 'delete']);
        Route::get('/{type}/{id}', [VideoController::class, 'getByVideoable']);
    });

    // Transcript routes for instructors
    Route::prefix('transcripts')->group(function () {
        Route::get('/', [TranscriptController::class, 'getAll']);
        Route::get('/{id}', [TranscriptController::class, 'getById']);
        Route::post('/', [TranscriptController::class, 'create']);
        Route::put('/{id}', [TranscriptController::class, 'update']);
        Route::delete('/{id}', [TranscriptController::class, 'delete']);
        Route::get('/{type}/{id}', [TranscriptController::class, 'getByTranscriptable']);
    });

    // MCQ routes for instructors
    Route::prefix('mcqs')->group(function () {
        Route::get('/', [McqController::class, 'getAll']);
        Route::get('/{id}', [McqController::class, 'getById']);
        Route::post('/', [McqController::class, 'create']);
        Route::put('/{id}', [McqController::class, 'update']);
        Route::delete('/{id}', [McqController::class, 'delete']);
        Route::get('/{type}/{id}', [McqController::class, 'getByMcqable']);
    });
});
