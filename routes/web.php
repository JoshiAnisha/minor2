<?php

use Illuminate\Support\Facades\Route;

// Backend Controllers
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\Backend\Auth\AuthController;
use App\Http\Controllers\Backend\NotificationsController;

// Patient Controllers
use App\Http\Controllers\Backend\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Backend\Patient\ProfileController as PatientProfileController;
use App\Http\Controllers\Backend\Patient\BookingController as PatientBookingController;
use App\Http\Controllers\Backend\Patient\ServiceController as PatientServiceController;
use App\Http\Controllers\Backend\Patient\ServiceRequestController as PatientServiceRequestController;
use App\Http\Controllers\Backend\Patient\InvoiceController as PatientInvoiceController;
use App\Http\Controllers\Backend\Patient\ReviewController as PatientReviewController;
use App\Http\Controllers\Backend\Patient\CaregiverController as PatientCaregiverController;

// Caregiver Controllers
use App\Http\Controllers\Caregiver\CaregiverController;
use App\Http\Controllers\Caregiver\ServiceRequestController;
use App\Http\Controllers\Caregiver\CaregiverBookingController;
use App\Http\Controllers\Caregiver\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\PatientController as AdminPatientController;
use App\Http\Controllers\Admin\CaregiverController as AdminCaregiverController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\InvoiceController as AdminInvoiceController;

// Chat
use App\Http\Controllers\ChatController;

// ------------------------
// Home Route
// ------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');


// ------------------------
// Authentication Routes (rate-limited to prevent brute force)
// ------------------------
Route::prefix('auth')->name('backend.auth.')->group(function () {

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post')
        ->middleware('throttle:register');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post')
        ->middleware('throttle:login');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Forgot / Reset Password (rate-limited)
    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendReset'])->name('password.email')
        ->middleware('throttle:password');

    Route::get('/reset-password/{token}', [AuthController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update')
        ->middleware('throttle:password');
});


// ------------------------
// Patient Routes (Authenticated)
// ------------------------
Route::prefix('patient')->name('patient.')->middleware(['auth', 'role:patient', 'prevent.cache'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [PatientProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [PatientProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [PatientProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/health-reports', [PatientProfileController::class, 'storeHealthReport'])->name('profile.health-reports.store');
    Route::delete('/profile/health-reports/{health_report}', [PatientProfileController::class, 'destroyHealthReport'])->name('profile.health-reports.destroy');
    Route::get('/profile/health-reports/{health_report}/view', [PatientProfileController::class, 'viewHealthReport'])->name('profile.health-reports.view');

    // Invoices
    Route::get('/invoices', [PatientInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}', [PatientInvoiceController::class, 'show'])->name('invoices.show');

    // Reviews
    Route::get('/reviews', [PatientReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/create', [PatientReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [PatientReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [PatientReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [PatientReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [PatientReviewController::class, 'destroy'])->name('reviews.destroy');

    // Services
    Route::get('/services', [PatientServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{slug}', [PatientServiceController::class, 'show'])->name('services.show');
    Route::post('/services/book', [PatientServiceController::class, 'book'])->name('services.book');

    // Service Requests (patient's submitted requests)
    Route::get('/service-requests', [PatientServiceRequestController::class, 'index'])->name('service-requests.index');
    Route::get('/service-requests/custom', [PatientServiceRequestController::class, 'createCustom'])->name('service-requests.create-custom');
    Route::post('/bids/{bid}/accept', [PatientServiceRequestController::class, 'acceptBid'])->name('bids.accept');
    Route::post('/bids/{bid}/reject', [PatientServiceRequestController::class, 'rejectBid'])->name('bids.reject');
    Route::get('/caregiver/{caregiver}', [PatientCaregiverController::class, 'show'])->name('caregiver.show');
    Route::get('/caregiver/{caregiver}/certificate', [PatientCaregiverController::class, 'certificate'])->name('caregiver.certificate');
    Route::get('/caregiver/{caregiver}/photo', [PatientCaregiverController::class, 'photo'])->name('caregiver.photo');

    // Bookings
    Route::get('/bookings', [PatientBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [PatientBookingController::class, 'create'])->name('bookings.create');
    Route::get('/bookings/{id}', [PatientBookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{id}/review', [PatientReviewController::class, 'createForBooking'])->name('bookings.review.create');
    Route::post('/bookings/{id}/review', [PatientReviewController::class, 'storeForBooking'])->name('bookings.review.store');

    // Notifications
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationsController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/read', [NotificationsController::class, 'markAsRead'])->name('notifications.mark-read');
});


// ------------------------
// Caregiver Routes (Authenticated)
// ------------------------
Route::middleware(['auth', 'role:caregiver', 'prevent.cache'])
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

        Route::post('/booking/{id}/complete', [CaregiverBookingController::class, 'complete'])
            ->name('booking.complete');

        Route::post('/booking/{id}/cancel', [CaregiverBookingController::class, 'cancel'])
            ->name('booking.cancel');

        Route::post('/booking/{id}/mark-paid', [CaregiverBookingController::class, 'markPaid'])
            ->name('booking.mark-paid');

        // ========================
        // Patient Profile & Reviews
        // ========================
        Route::get('/patient/{patient}', [CaregiverBookingController::class, 'showPatient'])
            ->name('patient.show');
        Route::get('/patient/{patient}/health-reports/{health_report}/view', [CaregiverBookingController::class, 'viewPatientHealthReport'])
            ->name('patient.health-report.view');

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

        Route::post('/service-request/{id}/reject', [ServiceRequestController::class, 'reject'])
            ->name('service.reject');

        Route::post('/service-request/bid', [ServiceRequestController::class, 'placeBid'])
            ->name('service.placeBid');

        // ========================
        // Profile
        // ========================
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
        Route::post('/profile', [ProfileController::class, 'update'])
            ->name('profile.update.post');

        Route::get('/profile/certificate', [ProfileController::class, 'viewCertificate'])
            ->name('profile.certificate');

        Route::get('/profile/photo', [ProfileController::class, 'viewProfilePhoto'])
            ->name('profile.photo');

        // Notifications
        Route::get('/notifications', [NotificationsController::class, 'index'])
            ->name('notifications.index');
        Route::post('/notifications/mark-all-read', [NotificationsController::class, 'markAllAsRead'])
            ->name('notifications.mark-all-read');
        Route::post('/notifications/{id}/read', [NotificationsController::class, 'markAsRead'])
            ->name('notifications.mark-read');
    });



// ------------------------
// Admin Routes (Authenticated)
// ------------------------
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin', 'prevent.cache'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [PatientDashboardController::class, 'profile'])->name('profile');

    // Manage Patients
    Route::patch('/patients/{patient}/toggle-active', [AdminPatientController::class, 'toggleActive'])->name('patients.toggle-active');
    Route::get('/patients/{patient}/health-reports/{health_report}/view', [AdminPatientController::class, 'viewHealthReport'])->name('patients.health-report.view');
    Route::delete('/patients/{patient}/health-reports/{health_report}', [AdminPatientController::class, 'destroyHealthReport'])->name('patients.health-report.destroy');
    Route::resource('/patients', AdminPatientController::class);

    // Manage Caregivers
    Route::resource('/caregivers', AdminCaregiverController::class);
    Route::patch('/caregivers/{caregiver}/toggle-status', [AdminCaregiverController::class, 'toggleStatus'])->name('caregivers.toggle-status');

    // Manage Services (admin opens services for patient booking)
    Route::resource('/services', AdminServiceController::class);

    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');

    // Invoices
    Route::get('/invoices', [AdminInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}', [AdminInvoiceController::class, 'show'])->name('invoices.show');

    // Feedback
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback');
    Route::delete('/feedback/{id}', [FeedbackController::class, 'destroy'])->name('feedback.delete');

    // Route aliases for existing views
    Route::redirect('/patient', '/admin/patients', 301)->name('patient');
    Route::redirect('/caregiver', '/admin/caregivers', 301)->name('caregiver');
    Route::redirect('/appointment', '/admin/appointments', 301)->name('appointment');
});


// ------------------------
// Chat API
// ------------------------
Route::post('/api/chat', [ChatController::class, 'sendMessage'])->name('chat.send');
