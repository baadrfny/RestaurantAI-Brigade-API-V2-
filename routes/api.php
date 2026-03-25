<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PlateController;
use App\Http\Controllers\Api\IngredientController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\AdminStatsController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    
    // Profile Management
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Plates with Scores
    Route::get('/plates', [PlateController::class, 'index']); 
    Route::get('/plates/{id}', [PlateController::class, 'show']);

    // AI Recommendations
    Route::post('/recommendations/analyze/{plate_id}', [RecommendationController::class, 'analyzePlate']);
    Route::get('/recommendations/{plate_id}', [RecommendationController::class, 'show']);
    Route::get('/recommendations', [RecommendationController::class, 'index']);
    
    Route::middleware('can:admin-only')->group(function () {
        
        Route::get('/admin/stats', [AdminStatsController::class, 'index']);
        
    });
});
