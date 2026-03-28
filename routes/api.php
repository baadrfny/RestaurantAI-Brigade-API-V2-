<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PlateController;
use App\Http\Controllers\Api\IngredientController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\AdminStatsController;
use App\Http\Controllers\Api\AdminPlateController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    
    // Profile Management
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']); 
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/categories/{id}/plates', [CategoryController::class, 'plates']);

    // Plates with Scores
    Route::get('/plates', [PlateController::class, 'index']); 
    Route::get('/plates/{id}', [PlateController::class, 'show']);

    // Ingredients
    Route::get('/ingredients', [IngredientController::class, 'index']);
    Route::get('/ingredients/{id}', [IngredientController::class, 'show']);

    // AI Recommendations
    Route::post('/recommendations/analyze/{plate_id}', [RecommendationController::class, 'analyzePlate']);
    Route::get('/recommendations/{plate_id}', [RecommendationController::class, 'show']);
    Route::get('/recommendations', [RecommendationController::class, 'index']);
    
    Route::middleware('can:admin-only')->group(function () {
        
        Route::get('/admin/stats', [AdminStatsController::class, 'index']);
        
        // Admin Category CRUD
        Route::post('/admin/categories', [CategoryController::class, 'store']);
        Route::put('/admin/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/admin/categories/{id}', [CategoryController::class, 'destroy']);
        
        // Admin Plate CRUD
        Route::get('/admin/plates', [AdminPlateController::class, 'index']);
        Route::post('/admin/plates', [AdminPlateController::class, 'store']);
        Route::get('/admin/plates/{id}', [AdminPlateController::class, 'show']);
        Route::put('/admin/plates/{id}', [AdminPlateController::class, 'update']);
        Route::delete('/admin/plates/{id}', [AdminPlateController::class, 'destroy']);
        
        // Admin Ingredient CRUD
        Route::post('/admin/ingredients', [IngredientController::class, 'store']);
        Route::put('/admin/ingredients/{id}', [IngredientController::class, 'update']);
        Route::delete('/admin/ingredients/{id}', [IngredientController::class, 'destroy']);
        
    });
});
