<?php

use Illuminate\Support\Facades\Route;

// Backend Controllers
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\Backend\Auth\AuthController;

// Patient Controllers
use App\Http\Controllers\Backend\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Backend\Patient\ProfileController as PatientProfileController;
use App\Http\Controllers\Backend\Patient\BookingController as PatientBookingController;
use App\Http\Controllers\Backend\Patient\ServiceController as PatientServiceController;
use App\Http\Controllers\Backend\Patient\InvoiceController as PatientInvoiceController;
use App\Http\Controllers\Backend\Patient\ReviewController as PatientReviewController;

// Caregiver Controllers
use App\Http\Controllers\Caregiver\CaregiverController;
use App\Http\Controllers\Caregiver\ShiftTimeController;
use App\Http\Controllers\Caregiver\ServiceRequestController;
use App\Http\Controllers\Caregiver\CaregiverBookingController;
use App\Http\Controllers\Caregiver\ProfileController;  

// Admin Controllers
use App\Http\Controllers\Admin\PatientController as AdminPatientController;
use App\Http\Controllers\Admin\CaregiverController as AdminCaregiverController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\AppointmentController; 
use App\Http\Controllers\Admin\DashboardController;   

// Chat
use App\Http\Controllers\ChatController;

// ------------------------
// Home Route
// ------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');


// ------------------------
// Authentication Routes
// ------------------------
Route::prefix('auth')->name('backend.auth.')->group(function () {

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Forgot / Reset Password
    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendReset'])->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');
});


// ------------------------
// Patient Routes (Authenticated)
// ------------------------
Route::prefix('patient')->name('patient.')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [PatientProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [PatientProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [PatientProfileController::class, 'update'])->name('profile.update');

    // Invoices
    Route::get('/invoices', [PatientInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}', [PatientInvoiceController::class, 'show'])->name('invoices.show');

    // Reviews
    Route::get('/reviews', [PatientReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/create', [PatientReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [PatientReviewController::class, 'store'])->name('reviews.store');

    // Services
    Route::get('/services', [PatientServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{slug}', [PatientServiceController::class, 'show'])->name('services.show');
    Route::post('/services/book', [PatientServiceController::class, 'book'])->name('services.book');

    // Bookings
    Route::get('/bookings', [PatientBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [PatientBookingController::class, 'show'])->name('bookings.show');
});


// ------------------------
// Caregiver Routes (Authenticated)
// ------------------------
Route::middleware(['auth'])
    ->prefix('caregiver')
    ->name('caregiver.')
    ->group(function () {

        // ========================
        // Dashboard
        // ========================
        Route::get('/dashboard', [CaregiverController::class, 'index'])
            ->name('dashboard');

        // ========================
        // Bookings & Bids
        // ========================
        Route::get('/bookings', [CaregiverBookingController::class, 'bookings'])
            ->name('bookings');

        Route::post('/bid/{bid}/accept', [CaregiverBookingController::class, 'acceptBid'])
            ->name('bid.accept');

        Route::post('/booking/{booking}/complete', [CaregiverBookingController::class, 'complete'])
            ->name('booking.complete');

        // ========================
        // Patient Profile & Reviews
        // ========================
        Route::get('/patient/{patient}', [CaregiverBookingController::class, 'showPatient'])
            ->name('patient.show');

        Route::get('/patient/{patient}/review', [CaregiverBookingController::class, 'createReview'])
            ->name('review.create');

        Route::post('/patient/review', [CaregiverBookingController::class, 'storeReview'])
            ->name('review.store');

        // ========================
        // Service Requests & Bidding
        // ========================
        Route::get('/service-requests', [ServiceRequestController::class, 'serviceRequest'])
            ->name('service.requests');

        Route::post('/service-request/{id}/accept-base', [ServiceRequestController::class, 'acceptBasePrice'])
            ->name('service.acceptBase');

        Route::post('/service-request/bid', [ServiceRequestController::class, 'placeBid'])
            ->name('service.placeBid');

        // ========================
        // Shift Time / Availability
        // ========================
        Route::get('/shift-time', [ShiftTimeController::class, 'index'])
            ->name('shift.index');

        Route::post('/shift-time', [ShiftTimeController::class, 'store'])
            ->name('shift.store');

        // ========================
        // Profile
        // ========================
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::get('/profile/certificate', [ProfileController::class, 'viewCertificate'])
            ->name('profile.certificate');
    });



// ------------------------
// Admin Routes (Authenticated)
// ------------------------
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [PatientDashboardController::class, 'profile'])->name('profile');

    // Manage Patients
    Route::resource('/patients', AdminPatientController::class);

    // Manage Caregivers
    Route::resource('/caregivers', AdminCaregiverController::class);

    // Manage Services
    Route::resource('/services', AdminServiceController::class);

    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
});


// ------------------------
// Chat API
// ------------------------
Route::post('/api/chat', [ChatController::class, 'sendMessage'])->name('chat.send');
