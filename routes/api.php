<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->group(function () {
    // Public routes
    Route::post('/auth/register', [AuthApiController::class, 'register']);
    Route::post('/auth/login', [AuthApiController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthApiController::class, 'me']);
        Route::post('/auth/logout', [AuthApiController::class, 'logout']);
        Route::post('/auth/password', [AuthApiController::class, 'changePassword']);
        Route::post('/auth/refresh', [AuthApiController::class, 'refresh']);

        // User management
        Route::resource('users', UserApiController::class);
    });
});
