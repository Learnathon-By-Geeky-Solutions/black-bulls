<?php

use App\Modules\Content\Controllers\VideoController;
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
});
