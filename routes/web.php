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
use App\Http\Controllers\Admin\GuideFeedbackController as AdminGuideFeedbackController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GuideFeedbackController;
use App\Http\Controllers\UserFeedbackController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/gioi-thieu', [PageController::class, 'about'])->name('about');
Route::get('/lien-he', [PageController::class, 'contact'])->name('contact');
Route::post('/lien-he', [PageController::class, 'contactSubmit'])->name('contact.submit');

// Google OAuth routes
Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

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

Route::middleware('auth')->group(function () {
    Route::get('/tours/{id}/booking', [BookingController::class, 'create'])->name('user.booking');
    Route::post('/tours/{id}/booking', [BookingController::class, 'store'])->name('user.booking.store');

    Route::get('/profile', [App\Http\Controllers\UserProfileController::class, 'edit'])->name('user.profile');
    Route::get('/profile/bookings', [App\Http\Controllers\UserProfileController::class, 'bookings'])->name('user.bookings');
    Route::put('/profile', [App\Http\Controllers\UserProfileController::class, 'updateProfile'])->name('user.profile.update');
    Route::put('/profile/password', [App\Http\Controllers\UserProfileController::class, 'updatePassword'])->name('user.password.update');
    Route::post('/booking/{bookingId}/cancel', [BookingController::class, 'cancel'])->name('user.booking.cancel');
    Route::get('/booking/{bookingId}/success', [BookingController::class, 'success'])->name('user.booking.success');
    Route::post('/booking/{bookingId}/feedback', [UserFeedbackController::class, 'store'])->name('user.booking.feedback');
});

// Payment Routes
Route::middleware('auth')->group(function () {
    Route::get('/booking/{id}/payment', [App\Http\Controllers\PaymentController::class, 'chooseMethod'])->name('payment.choose');
    Route::post('/booking/{id}/pay-vnpay', [App\Http\Controllers\PaymentController::class, 'payVnpay'])->name('payment.vnpay');
    Route::get('/booking/{id}/pay-vietqr', [App\Http\Controllers\PaymentController::class, 'payVietqr'])->name('payment.vietqr');
    Route::post('/booking/{id}/vietqr-confirm', [App\Http\Controllers\PaymentController::class, 'vietqrConfirm'])->name('payment.vietqr.confirm');
    Route::post('/booking/{id}/pay-momo', [App\Http\Controllers\PaymentController::class, 'payMomo'])->name('payment.momo');
    Route::get('/payment/momo/mock/{booking_id}', [App\Http\Controllers\PaymentController::class, 'momoMock'])->name('momo.mock');
});
Route::get('/payment/vnpay-return', [App\Http\Controllers\PaymentController::class, 'vnpayReturn'])->name('payment.vnpay.return');
Route::get('/payment/momo-return', [App\Http\Controllers\PaymentController::class, 'momoReturn'])->name('payment.momo.return');

// Admin auth
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');

    Route::middleware(['auth', EnsureRole::class . ':admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');

        Route::resource('categories', CategoryController::class);
        Route::patch('categories/{category}/unhide', [CategoryController::class, 'unhide'])->name('categories.unhide');

        Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
        Route::patch('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::patch('users/{id}/toggle-blacklist', [UserController::class, 'toggleBlacklist'])->name('users.toggle-blacklist');

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
        Route::resource('bookings', AdminBookingController::class)->only(['index', 'show', 'update']);
        Route::post('bookings/{id}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
        Route::post('bookings/{id}/refund', [AdminBookingController::class, 'refund'])->name('bookings.refund');

        // Feedback
        Route::get('feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::post('feedback/hide/{id}', [FeedbackController::class, 'hide'])->name('feedback.hide');
        Route::post('feedback/unhide/{id}', [FeedbackController::class, 'unhide'])->name('feedback.unhide');

        // Guide Feedback
        Route::resource('guide-feedback', AdminGuideFeedbackController::class)->only(['index', 'show']);
        Route::patch('guide-feedback/{id}/status', [AdminGuideFeedbackController::class, 'updateStatus'])->name('guide-feedback.updateStatus');
        Route::delete('guide-feedback/{id}', [AdminGuideFeedbackController::class, 'destroy'])->name('guide-feedback.destroy');

        // Contacts
        Route::resource('contacts', ContactController::class)->only(['index', 'show', 'update', 'destroy']);
    });
});

Route::prefix('guide')->group(function () {
    Route::get('login', [GuideAuthController::class, 'showLogin'])->name('guide.login');
    Route::post('login', [GuideAuthController::class, 'login'])->name('guide.login.post');

    Route::middleware(['auth', EnsureRole::class . ':tour_guide'])->group(function () {
        Route::post('logout', [GuideAuthController::class, 'logout'])->name('guide.logout');
        Route::get('dashboard', [\App\Http\Controllers\GuideController::class, 'dashboard'])->name('guide.dashboard');

        // Assignment confirmation routes
        Route::get('assignments', [\App\Http\Controllers\GuideController::class, 'assignmentList'])->name('guide.assignments');
        Route::post('assignments/{id}/accept', [\App\Http\Controllers\GuideController::class, 'acceptAssignment'])->name('guide.assignments.accept');
        Route::post('assignments/{id}/reject', [\App\Http\Controllers\GuideController::class, 'rejectAssignment'])->name('guide.assignments.reject');

        Route::get('tour/{scheduleId}', [\App\Http\Controllers\GuideController::class, 'tourDetail'])->name('guide.tour.detail');
        Route::get('tour/{scheduleId}/attendance', [\App\Http\Controllers\GuideController::class, 'attendance'])->name('guide.attendance');
        Route::post('tour/{scheduleId}/attendance', [\App\Http\Controllers\GuideController::class, 'markAttendance'])->name('guide.attendance.mark');
        Route::get('tour/{scheduleId}/itinerary', [\App\Http\Controllers\GuideController::class, 'itinerary'])->name('guide.itinerary');
        Route::put('itinerary/{itineraryId}', [\App\Http\Controllers\GuideController::class, 'updateItinerary'])->name('guide.itinerary.update');
        Route::get('profile', [\App\Http\Controllers\GuideController::class, 'profile'])->name('guide.profile');
        Route::put('profile', [\App\Http\Controllers\GuideController::class, 'updateProfile'])->name('guide.profile.update');
        Route::get('customers', [\App\Http\Controllers\GuideController::class, 'customerList'])->name('guide.customers');

        // Guide Feedback routes
        Route::get('feedback/create', [GuideFeedbackController::class, 'create'])->name('guide.feedback.create');
        Route::post('feedback', [GuideFeedbackController::class, 'store'])->name('guide.feedback.store');
        Route::get('feedback', [GuideFeedbackController::class, 'list'])->name('guide.feedback.list');
        Route::get('feedback/{id}', [GuideFeedbackController::class, 'show'])->name('guide.feedback.show');
    });
});

// Errors pages test

// Route::get('/404', function () {
//     return view('errors.404');
// })->name('error.404');

// Route::get('/403', function () {
//     return view('errors.403');
// })->name('error.403');

// Route::get('/500', function () {
//     return view('errors.500');
// })->name('error.500');
