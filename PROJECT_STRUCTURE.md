# SewaCare - Project Folder & File Structure

```
sewacare/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/
│   │       │   ├── AppointmentController.php
│   │       │   ├── CaregiverController.php
│   │       │   ├── DashboardController.php
│   │       │   ├── FeedbackController.php
│   │       │   ├── PatientController.php
│   │       │   ├── ReviewController.php
│   │       │   └── ServiceController.php
│   │       ├── Backend/
│   │       │   ├── Auth/
│   │       │   │   └── AuthController.php
│   │       │   ├── Patient/
│   │       │   │   ├── BookingController.php
│   │       │   │   ├── DashboardController.php
│   │       │   │   ├── InvoiceController.php
│   │       │   │   ├── ProfileController.php
│   │       │   │   ├── ReviewController.php
│   │       │   │   ├── ServiceController.php
│   │       │   │   └── ServiceRequestController.php
│   │       │   └── HomeController.php
│   │       ├── Caregiver/
│   │       │   ├── CaregiverBookingController.php
│   │       │   ├── CaregiverController.php
│   │       │   ├── ProfileController.php
│   │       │   ├── ServiceRequestController.php
│   │       │   └── ShiftTimeController.php
│   │       ├── ChatController.php
│   │       ├── Controller.php
│   │       ├── GeminiController.php
│   │       └── PageController.php
│   └── Models/
│       ├── Appointment.php
│       ├── Bid.php
│       ├── Booking.php
│       ├── Caregiver.php
│       ├── CaregiverShiftTime.php
│       ├── Chatbot_Queries.php
│       ├── Invoice.php
│       ├── Labtest.php
│       ├── Patient.php
│       ├── Review.php
│       ├── Service.php
│       ├── ServiceRequest.php
│       ├── Task_logs.php
│       └── User.php
│
├── bootstrap/
│   ├── app.php
│   ├── cache/
│   └── providers.php
│
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   └── session.php
│
├── database/
│   ├── factories/
│   │   └── UserFactory.php
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2026_01_14_095330_create_patients_table.php
│   │   ├── 2026_01_14_095458_create_caregivers_table.php
│   │   ├── 2026_01_14_095625_create_services_table.php
│   │   ├── 2026_01_14_095745_create_bookings_table.php
│   │   ├── 2026_01_14_095937_create_appointments_table.php
│   │   ├── 2026_01_14_100941_create_service__requests_table.php
│   │   ├── 2026_01_14_101340_create_chatbot__queries_table.php
│   │   ├── 2026_01_14_101524_create_labtests_table.php
│   │   ├── 2026_01_14_101602_create_reviews_table.php
│   │   ├── 2026_01_21_102644_create_invoices_table.php
│   │   ├── 2026_01_22_150054_create_sessions_table.php
│   │   ├── 2026_01_22_192527_add_profile_photo_to_patients_table.php
│   │   ├── 2026_01_28_145551_create_caregiver_shift_times_table.php
│   │   ├── 2026_01_28_150052_create_task_logs_table.php
│   │   ├── 2026_02_01_182439_create_bids_table.php
│   │   ├── 2026_02_21_000001_add_service_request_id_to_bookings_table.php
│   │   ├── 2026_02_21_000002_add_shift_type_to_service_requests_table.php
│   │   └── 2026_02_21_000003_add_rejected_to_service_requests_status.php
│   └── seeders/
│       └── DatabaseSeeder.php
│
├── public/
│   ├── .htaccess
│   ├── index.php
│   └── robots.txt
│
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── admin/
│       │   ├── caregiver.blade.php
│       │   ├── caregiver-edit.blade.php
│       │   ├── dashboard.blade.php
│       │   ├── feedback.blade.php
│       │   ├── layouts/
│       │   │   ├── app.blade.php
│       │   │   └── sidebar.blade.php
│       │   ├── appointment.blade.php
│       │   ├── patient.blade.php
│       │   ├── patient-edit.blade.php
│       │   ├── service-create.blade.php
│       │   └── layouts/
│       ├── backend/
│       │   ├── auth/
│       │   │   ├── confirm-password.blade.php
│       │   │   ├── forgot-password.blade.php
│       │   │   ├── login.blade.php
│       │   │   ├── register.blade.php
│       │   │   ├── reset-password.blade.php
│       │   │   └── verify-email.blade.php
│       │   ├── home.blade.php
│       │   ├── layouts/
│       │   │   ├── auth/
│       │   │   │   └── app.blade.php
│       │   │   ├── dashboard/
│       │   │   │   ├── app.blade.php
│       │   │   │   └── sidebar.blade.php
│       │   │   └── partials/
│       │   │       ├── breadcrumbs.blade.php
│       │   │       ├── alerts.blade.php
│       │   │       ├── footer.blade.php
│       │   │       ├── navbar.blade.php
│       │   │       └── sidebar.blade.php
│       │   └── patient/
│       │       ├── bookings/
│       │       │   ├── index.blade.php
│       │       │   └── show.blade.php
│       │       ├── dashboard/
│       │       │   └── index.blade.php
│       │       ├── invoices/
│       │       │   ├── index.blade.php
│       │       │   └── show.blade.php
│       │       ├── profile/
│       │       │   ├── edit.blade.php
│       │       │   └── show.blade.php
│       │       ├── reviews/
│       │       │   ├── create.blade.php
│       │       │   └── index.blade.php
│       │       ├── service-requests/
│       │       │   └── index.blade.php
│       │       ├── services/
│       │       │   ├── index.blade.php
│       │       │   └── show.blade.php
│       │       └── ...
│       └── Caregiver/
│           ├── caregiverBooking.blade.php
│           ├── createReview.blade.php
│           ├── dashboard.blade.php
│           ├── edit.blade.php
│           ├── layouts/
│           │   ├── app.blade.php
│           │   └── sidebar.blade.php
│           ├── patientProfile.blade.php
│           ├── serviceRequest.blade.php
│           └── shiftTime.blade.php
│
├── routes/
│   ├── console.php
│   └── web.php
│
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
│
├── tests/
│   ├── Feature/
│   │   └── ExampleTest.php
│   ├── Unit/
│   │   └── ExampleTest.php
│   ├── Pest.php
│   └── TestCase.php
│
├── .env.example
├── .gitignore
├── artisan
├── composer.lock
├── package.json
├── phpunit.xml
├── README.md
├── PROJECT_STRUCTURE.md  (this file)
└── vite.config.js
```

## Quick Reference

| Purpose | Path |
|---------|------|
| **Models** | `app/Models/` |
| **Controllers** | `app/Http/Controllers/` |
| **Views** | `resources/views/` |
| **Routes** | `routes/web.php` |
| **Migrations** | `database/migrations/` |
| **Config** | `config/` |
