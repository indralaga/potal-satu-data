<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DatasetController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DashboardController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Datasets
    Route::apiResource('datasets', DatasetController::class);
    Route::post('/datasets/{id}/download', [DatasetController::class, 'download']);

    // Users (Admin only)
    Route::middleware('admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::post('/users/{id}/approve', [UserController::class, 'approveUser']);
    });
});
