<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\Backend\Auth\AuthController;
use App\Http\Controllers\Backend\Patient\DashboardController;
use App\Http\Controllers\Backend\Patient\ProfileController;
use App\Http\Controllers\Backend\Patient\BookingController;
use App\Http\Controllers\Backend\Patient\ServiceController;
use App\Http\Controllers\Backend\Patient\InvoiceController;
use App\Http\Controllers\Backend\Patient\ReviewController;

use App\Http\Controllers\Caregiver\CaregiverController;
use App\Http\Controllers\Caregiver\ShiftTimeController;
use App\Http\Controllers\Caregiver\ServiceRequestController;
use App\Http\Controllers\Caregiver\CaregiverBookingController;
use App\Http\Controllers\Admin\PatientController as AdminPatientController;
use App\Http\Controllers\Admin\CaregiverController as AdminCaregiverController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\ChatController;            

Route::get('/', [HomeController::class, 'index'])->name('home');


// Authentication Routes
Route::prefix('auth')->name('backend.auth.')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Password
    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendReset'])->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');
});

// Patient Routes (Only for authenticated users)
Route::prefix('patient')->name('patient.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');

    // Services
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});


Route::get('/Caregiver/dashboard', [CaregiverController::class, 'index'])->name('caregiver.dashboard');
Route::get('/caregiver/services', [ServiceRequestController::class, 'serviceRequest'])
    ->name('caregiver.services');
Route::get('/caregiver/profile', [ProfileController::class, 'edit'])
    ->name('caregiver.profile');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('admin.dashboard');
Route::get('/admin/profile', [DashboardController::class, 'profile'])
    ->middleware('auth')
    ->name('admin.profile');

Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('Caregiver.edit');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('Caregiver.update');
Route::get('/caregiver/view-certificate', [ProfileController::class, 'viewCertificate'])->name('caregiver.viewCertificate');


Route::get('/service-requests', [ServiceRequestController::class, 'serviceRequest'])->name('Caregiver.serviceRequest');
Route::post('/service-requests/{id}/accept', [ServiceRequestController::class, 'acceptBasePrice'])->name('caregiver.acceptBasePrice');
Route::post('/service-requests/bid', [ServiceRequestController::class, 'placeBid'])->name('caregiver.placeBid');


// Route::get('/bookings', [CaregiverBookingController::class, 'index'])->name('caregiver.bookings');
// Route::post('/bookings/{bookingId}/complete', [CaregiverBookingController::class, 'markCompleted'])->name('caregiver.bookings.complete');

Route::get('/caregiver/bookings', [CaregiverBookingController::class, 'bookings'])
    ->name('caregiver.bookings');

Route::post('/caregiver/bookings/bid/{bid}/accept', [CaregiverBookingController::class, 'acceptBid'])
    ->name('caregiver.bookings.acceptBid');

Route::post('/caregiver/bookings/{booking}/complete', [CaregiverBookingController::class, 'complete'])
    ->name('caregiver.bookings.complete');

// Caregiver: Patient profile (route param, not query string)
Route::get('/caregiver/patients/{patient}', [CaregiverBookingController::class, 'showPatient'])
    ->middleware('auth')
    ->name('caregiver.patients.show');

// Caregiver: Review routes
Route::get('/caregiver/reviews/create/{patient}', [CaregiverBookingController::class, 'createReview'])
    ->middleware('auth')
    ->name('caregiver.reviews.create');

Route::post('/caregiver/reviews', [CaregiverBookingController::class, 'storeReview'])
    ->middleware('auth')
    ->name('caregiver.reviews.store');

    Route::get('admin/appointments', [AppointmentController::class, 'index'])
     ->name('admin.appointment');

Route::post('/api/chat', [ChatController::class, 'sendMessage'])
    ->name('chat.send');


