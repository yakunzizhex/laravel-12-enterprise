<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::post('/password', [UserController::class, 'changePassword'])->name('password.change');
});

Route::prefix('admin')
    ->middleware(['auth', 'can:manage_users'])
    ->group(function () {
        Route::resource('roles', RoleController::class);
    });
