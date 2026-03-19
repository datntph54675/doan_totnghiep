<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Middleware\EnsureRole;

Route::get('/', function () {
    return view('welcome');
});

// Admin auth
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');

    Route::middleware(['auth', EnsureRole::class . ':admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
        // Tour admin CRUD
        Route::resource('tours', App\Http\Controllers\Admin\TourController::class, ['as' => 'admin']);
        // Category admin CRUD
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class, ['as' => 'admin']);
    });
});
