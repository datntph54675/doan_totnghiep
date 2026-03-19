<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Middleware\EnsureRole;
use App\Http\Controllers\GuideAuthController;
use App\Http\Controllers\UserAuthController;

Route::get('/', function () {
    return view('welcome');
});

// User auth (default)
Route::get('login', [UserAuthController::class, 'showLogin'])->name('login');
Route::post('login', [UserAuthController::class, 'login'])->name('login.post');
Route::get('register', [UserAuthController::class, 'showRegister'])->name('register');
Route::post('register', [UserAuthController::class, 'register'])->name('register.post');

// Forgot / reset password (user)
Route::middleware('guest')->group(function () {
    Route::get('forgot-password', [UserAuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('forgot-password', [UserAuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('reset-password/{token}', [UserAuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('reset-password', [UserAuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware(['auth', EnsureRole::class . ':user'])->group(function () {
    Route::post('logout', [UserAuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [UserAuthController::class, 'dashboard'])->name('dashboard');

    Route::get('profile', [UserAuthController::class, 'showProfile'])->name('profile.show');
    Route::post('profile', [UserAuthController::class, 'updateProfile'])->name('profile.update');

    Route::get('password/change', [UserAuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('password/change', [UserAuthController::class, 'changePassword'])->name('password.change.post');
});

// Admin auth
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');

    Route::middleware(['auth', EnsureRole::class . ':admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
    });
});
// Guide auth
Route::prefix('guide')->group(function () {
    Route::get('login', [GuideAuthController::class, 'showLogin'])->name('guide.login');
    Route::post('login', [GuideAuthController::class, 'login'])->name('guide.login.post');

    Route::middleware(['auth', EnsureRole::class . ':tour_guide'])->group(function () {
        Route::post('logout', [GuideAuthController::class, 'logout'])->name('guide.logout');
        Route::get('dashboard', [GuideAuthController::class, 'dashboard'])->name('guide.dashboard');
    });
});