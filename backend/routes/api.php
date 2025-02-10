<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Task Routes
    Route::apiResource('tasks', TaskController::class);

    // Additional Task Actions
    Route::post('tasks/{task}/complete', [TaskController::class, 'markAsCompleted']);
    Route::post('tasks/{task}/incomplete', [TaskController::class, 'markAsIncomplete']);
    Route::post('tasks/{task}/archive', [TaskController::class, 'archiveTask']);
    Route::post('tasks/{task}/restore', [TaskController::class, 'restoreTask']);
});

// Testing Route
Route::get('/', function () {
    return "testing";
});
