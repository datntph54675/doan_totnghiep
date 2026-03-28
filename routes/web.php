<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Middleware\EnsureRole;
use App\Http\Controllers\GuideAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\DepartureScheduleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GuideController;
use App\Http\Controllers\Admin\GuideAssignmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TourUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\UserAuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes for Users
Route::controller(UserAuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', 'showLogin')->name('login');
        Route::post('login', 'login')->name('login.post');
        Route::get('register', 'showRegister')->name('register');
        Route::post('register', 'register')->name('register.post');
    });
    Route::post('logout', 'logout')->name('logout');

    // Password Reset Routes
    Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
});

// User - public tour pages
Route::get('/tours', [TourUserController::class, 'index'])->name('tours.index');
Route::get('/tours/{id}', [TourUserController::class, 'show'])->name('tours.show');
Route::get('/tours/{id}/booking', [BookingController::class, 'create'])->name('user.booking');
Route::post('/tours/{id}/booking', [BookingController::class, 'store'])->name('user.booking.store');
Route::get('/booking/{bookingId}/success', [BookingController::class, 'success'])->name('user.booking.success');

// User Profile & Settings
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\UserProfileController::class, 'edit'])->name('user.profile');
    Route::put('/profile', [App\Http\Controllers\UserProfileController::class, 'updateProfile'])->name('user.profile.update');
    Route::put('/profile/password', [App\Http\Controllers\UserProfileController::class, 'updatePassword'])->name('user.password.update');
});

// Admin auth
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');

    Route::middleware(['auth', EnsureRole::class . ':admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');

        Route::resource('categories', CategoryController::class);

        Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
        Route::patch('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

        // Guides
        Route::resource('guides', GuideController::class)->only(['index', 'edit', 'update', 'create', 'store', 'show']);
        Route::patch('guides/{id}/toggle-status', [GuideController::class, 'toggleStatus'])->name('guides.toggle-status');

        // Guide Assignments
        Route::resource('guide-assignments', GuideAssignmentController::class);

        // Tours
        Route::resource('tours', TourController::class);
        Route::get('tours/{tour}/departure-schedules', [TourController::class, 'departureSchedules'])->name('tours.departure-schedules.index');

        // Departure Schedules (Nested under Tours)
        Route::get('tours/{tour}/departure-schedules/create', [DepartureScheduleController::class, 'create'])->name('tours.departure-schedules.create');
        Route::post('tours/{tour}/departure-schedules', [DepartureScheduleController::class, 'store'])->name('tours.departure-schedules.store');
        Route::get('tours/{tour}/departure-schedules/{schedule}/edit', [DepartureScheduleController::class, 'edit'])->name('tours.departure-schedules.edit');
        Route::put('tours/{tour}/departure-schedules/{schedule}', [DepartureScheduleController::class, 'update'])->name('tours.departure-schedules.update');
        Route::delete('tours/{tour}/departure-schedules/{schedule}', [DepartureScheduleController::class, 'destroy'])->name('tours.departure-schedules.destroy');

        // Booking
        Route::resource('bookings', AdminBookingController::class)->only(['index', 'show', 'update', 'destroy']);

        // Feedback
        Route::get('feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::post('feedback/hide/{id}', [FeedbackController::class, 'hide'])->name('feedback.hide');

        // Bookings awaiting admin confirmation
        Route::get('bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
        Route::post('bookings/{id}/confirm', [\App\Http\Controllers\Admin\BookingController::class, 'confirm'])->name('bookings.confirm');
        Route::get('bookings/{id}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
    });
});

// Guide auth
Route::prefix('guide')->group(function () {
    Route::get('login', [GuideAuthController::class, 'showLogin'])->name('guide.login');
    Route::post('login', [GuideAuthController::class, 'login'])->name('guide.login.post');

    Route::middleware(['auth', EnsureRole::class . ':tour_guide'])->group(function () {
        Route::post('logout', [GuideAuthController::class, 'logout'])->name('guide.logout');
        Route::get('dashboard', [\App\Http\Controllers\GuideController::class, 'dashboard'])->name('guide.dashboard');
        Route::get('tour/{scheduleId}', [\App\Http\Controllers\GuideController::class, 'tourDetail'])->name('guide.tour.detail');
        Route::get('tour/{scheduleId}/attendance', [\App\Http\Controllers\GuideController::class, 'attendance'])->name('guide.attendance');
        Route::post('tour/{scheduleId}/attendance', [\App\Http\Controllers\GuideController::class, 'markAttendance'])->name('guide.attendance.mark');
        Route::get('tour/{scheduleId}/itinerary', [\App\Http\Controllers\GuideController::class, 'itinerary'])->name('guide.itinerary');
        Route::put('itinerary/{itineraryId}', [\App\Http\Controllers\GuideController::class, 'updateItinerary'])->name('guide.itinerary.update');
        Route::get('profile', [\App\Http\Controllers\GuideController::class, 'profile'])->name('guide.profile');
        Route::put('profile', [\App\Http\Controllers\GuideController::class, 'updateProfile'])->name('guide.profile.update');
        Route::get('customers', [\App\Http\Controllers\GuideController::class, 'customerList'])->name('guide.customers');
    });
});
