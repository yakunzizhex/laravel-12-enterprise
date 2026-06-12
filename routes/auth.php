<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // MFA routes
    Route::get('/mfa/setup', [AuthController::class, 'showMfaSetup'])->name('mfa.setup');
    Route::post('/mfa/setup/totp', [AuthController::class, 'setupTotp'])->name('mfa.totp.setup');
    Route::post('/mfa/verify/totp', [AuthController::class, 'verifyTotp'])->name('mfa.totp.verify');
    Route::get('/mfa/verify', [AuthController::class, 'showMfaVerify'])->name('mfa.verify');
    Route::post('/mfa/verify', [AuthController::class, 'verifyMfa'])->name('mfa.verify.confirm');
});
