<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourseManagement\CategoryController;
use Illuminate\Support\Facades\Route;

Route::post('register',[AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function(){
    Route::post('logout',[AuthController::class, 'logout']);
    Route::post('refresh-token',[AuthController::class, 'refreshToken']);
});

Route::middleware(['auth:api', 'role:instructor'])->group(function(){
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
});
