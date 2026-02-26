# SewaCare — In-Home Medical Services Platform

## Project Report

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Objectives](#2-objectives)
3. [Technology Stack](#3-technology-stack)
4. [System Architecture](#4-system-architecture)
5. [Modules and Features](#5-modules-and-features)
6. [Database Design](#6-database-design)
7. [Project Structure](#7-project-structure)
8. [Installation and Setup](#8-installation-and-setup)
9. [Environment Configuration](#9-environment-configuration)
10. [Future Enhancements](#10-future-enhancements)
11. [Team and Acknowledgements](#11-team-and-acknowledgements)

---

## 1. Project Overview

**SewaCare** is a web-based in-home medical services platform built specifically for Nepal. It connects patients who need professional healthcare at their homes with certified caregivers (nurses, physiotherapists, and medical staff). The platform streamlines the entire service workflow — from patient registration and service booking to caregiver assignment, bid management, and post-service review.

The name *SewaCare* is derived from the Nepali word **"Sewa"** (सेवा), meaning *service* or *care*, reflecting the platform's commitment to compassionate, home-based healthcare.

---

## 2. Objectives

- Provide a **digital bridge** between patients and qualified home-care professionals in Nepal.
- Simplify the **booking process** for in-home nursing, physiotherapy, and lab-test services.
- Offer caregivers a platform to **showcase their profiles**, manage shift availability, and respond to service requests.
- Give administrators a centralized **dashboard** to manage users, services, bookings, and feedback.
- Integrate an **AI-powered chatbot** (SewaBot) using Google Gemini to handle patient queries 24/7.
- Ensure **transparent pricing** through a bidding system where caregivers can propose custom rates.

---

## 3. Technology Stack

| Layer | Technology |
|---|---|
| **Backend Framework** | Laravel 12 (PHP 8.2+) |
| **Frontend** | Blade Templates, Bootstrap 5.3, Bootstrap Icons |
| **Database** | MySQL (configurable via Laravel migrations) |
| **AI / Chatbot** | Google Gemini API (`gemini-pro` model) |
| **HTTP Client** | Guzzle HTTP 7.x |
| **Build Tool** | Vite |
| **Package Manager** | Composer (PHP), npm (JS) |
| **Testing** | PestPHP 3.x, PHPUnit |
| **Code Quality** | Laravel Pint |

---

## 4. System Architecture

SewaCare follows the **MVC (Model-View-Controller)** architectural pattern provided by Laravel.

```
Browser / Client
       │
       ▼
  Laravel Router (routes/web.php)
       │
       ▼
  Controllers
  ├── Auth (Login, Register, ForgotPassword)
  ├── Admin (Dashboard, Patients, Caregivers, Services, Appointments, Reviews, Feedback)
  ├── Caregiver (Profile, ShiftTime, ServiceRequest, Bookings, Reviews)
  ├── Patient (Dashboard, Service)
  ├── GeminiController (AI Chatbot)
  └── ChatController (SewaBot Chat)
       │
       ▼
   Models (Eloquent ORM)          Views (Blade Templates)
   ├── User                       ├── frontend/
   ├── Patient                    ├── auth/
   ├── Caregiver                  ├── Patient/
   ├── Service                    ├── Caregiver/
   ├── ServiceRequest             └── admin/
   ├── Booking
   ├── Bids
   ├── Appointment
   ├── Reviews
   ├── Task_logs
   ├── Labtests
   ├── CaregiverShiftTime
   └── Chatbot_queries
       │
       ▼
    MySQL Database
```

### User Roles

The system supports three distinct user roles managed through the `users.role` column:

| Role | Description |
|---|---|
| `admin` | Full system access — manages users, services, bookings, feedback |
| `caregiver` | Browses service requests, places bids, manages profile and shifts |
| `patient` | Requests services, views bookings, submits feedback |

---

## 5. Modules and Features

### 5.1 Public / Landing Page

The landing page (`/`) presents SewaCare's value proposition to visitors before login:

- **Hero Section** — Introductory banner with a call-to-action.
- **Core Services** — Home Nursing, Physiotherapy, and Lab Tests with booking links.
- **Why Choose SewaCare** — Highlights: trusted professionals, home comfort, 24/7 support.
- **Testimonials** — Real client quotes from Kathmandu and Lalitpur.
- **How It Works** — Three-step process: Choose Service → Get Matched → Receive Care.
- **Trust Indicators** — Certified staff, transparent pricing, widespread coverage, satisfaction guarantee.
- **Footer** — Contact information (Kathmandu, Nepal), quick links, and social media.

### 5.2 Authentication Module

| Feature | Route | Description |
|---|---|---|
| Login | `GET/POST /login` | Email and password authentication |
| Registration | `GET/POST /register` | New user signup with role selection |
| Forgot Password | `GET /forgetpassword` | Password recovery flow |
| Logout | `GET/POST /logout` | Session termination |

### 5.3 Patient Module

Patients can:

- **View available services** — Browse the service catalogue.
- **Create service requests** — Specify service type, preferred time, location, shift type, and description.
- **View booking status** — Track their current and past bookings.
- **Receive bids from caregivers** — Accept a bid or the base price to confirm a booking.
- **Submit reviews and feedback** — Rate caregivers after service completion.
- **Use the AI chatbot** — Ask health-related or booking questions via SewaBot.

### 5.4 Caregiver Module

Caregivers can:

- **Manage their profile** — Update skills, field, bio, address, preferred shift, availability, and upload certificates.
- **Set shift times** — Configure daily availability windows via the shift-time manager.
- **Browse service requests** — View open patient requests matching their skills.
- **Accept base price or place a bid** — Accept a request at the base price, or propose a custom bid amount.
- **Manage bookings** — View confirmed bookings and mark them as completed.
- **View patient profiles** — Access assigned patient information during active bookings.
- **Write reviews** — Submit post-booking reviews for patients.

Key routes:

| Route | Feature |
|---|---|
| `GET /caregiver/dashboard` | Caregiver home dashboard |
| `GET /caregiver/profile` | View/edit profile |
| `GET /caregiver/shift-time` | Manage availability |
| `GET /service-requests` | Browse open service requests |
| `POST /service-requests/{id}/accept` | Accept at base price |
| `POST /service-requests/bid` | Place a custom bid |
| `GET /caregiver/bookings` | View confirmed bookings |
| `POST /caregiver/bookings/{booking}/complete` | Mark booking as done |

### 5.5 Admin Module

Administrators access a protected dashboard at `/admin/dashboard` (requires `auth` middleware + admin role check).

**Dashboard Metrics:**
- Total registered patients
- Total active caregivers (by `availability_status`)
- Total services listed
- Booking breakdown by status (Pending / In Process / Completed / Cancelled) displayed as a Chart.js chart

**Management Panels:**

| Panel | Route | Features |
|---|---|---|
| Patients | `/admin/patient` | View, edit, delete patient records |
| Caregivers | `/admin/caregiver` | View, edit, delete, toggle status |
| Services | `/admin/services/create` | Add new service categories |
| Appointments | `/admin/appointments` | View all appointments |
| Reviews | `/admin/reviews` | View and delete caregiver reviews |
| Feedback | `/admin/feedback` | View and delete user feedback |

### 5.6 AI Chatbot (SewaBot)

SewaBot is an AI-powered assistant embedded on every page as a floating chat widget.

- **Trigger**: A floating button (bottom-right corner) opens the chat panel.
- **Backend**: Powered by the **Google Gemini API** (`gemini-pro` model) via `GeminiService`.
- **Routes**: `POST /api/chat` and `POST /chatbot/message`
- **Capabilities**: Answers health queries, assists with booking information, provides general guidance.
- **UX**: "Bot is typing…" indicator, scrollable conversation history, CSRF-protected AJAX.

---

## 6. Database Design

The system uses **17 migration files** creating **15 tables** (2 migrations are schema alterations to existing tables):

| Table | Purpose |
|---|---|
| `users` | Core authentication — stores name, email, role, password |
| `patients` | Patient profile — medical history, prescriptions, health condition |
| `caregivers` | Caregiver profile — skills, field, bio, certificate, availability |
| `services` | Service catalogue — name, slug, details, base price, type |
| `service_requests` | Patient requests — service, location, preferred time, shift type |
| `bids` | Caregiver bids on service requests — price, status |
| `bookings` | Confirmed bookings — links patient, caregiver, service, request |
| `appointments` | Scheduled appointment slots |
| `reviews` | Post-service ratings and comments |
| `task_logs` | Audit log of caregiver task activity |
| `labtests` | Lab test orders and results |
| `chatbot_queries` | Stored chatbot conversation records |
| `caregiver_shift_times` | Caregiver daily availability windows |
| `cache` | Laravel cache store |
| `jobs` | Laravel queue jobs |

### Key Relationships

```
User ──(1:1)──► Patient
User ──(1:1)──► Caregiver
ServiceRequest ──(belongs to)──► Patient
ServiceRequest ──(belongs to)──► Service
ServiceRequest ──(has many)──► Bids
ServiceRequest ──(has many)──► Bookings
Booking ──(belongs to)──► Patient, Caregiver, Service, ServiceRequest
```

---

## 7. Project Structure

```
minor2/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/          # Admin panel controllers
│   │       ├── Caregiver/      # Caregiver-facing controllers
│   │       ├── Patient/        # Patient-facing controllers
│   │       ├── auth/           # Login, Register, ForgotPassword
│   │       ├── GeminiController.php
│   │       └── ChatController.php
│   ├── Models/                 # Eloquent models (User, Patient, Caregiver, ...)
│   ├── Services/
│   │   └── GeminiService.php   # Google Gemini API integration
│   └── Providers/
├── database/
│   ├── migrations/             # 17 migration files
│   ├── seeders/
│   └── factories/
├── resources/
│   └── views/
│       ├── index.blade.php     # Public landing page
│       ├── admin/              # Admin dashboard views
│       ├── Caregiver/          # Caregiver panel views
│       ├── Patient/            # Patient panel views
│       ├── auth/               # Login / registration views
│       └── frontend/layouts/   # Shared layouts
├── routes/
│   └── web.php                 # All application routes
├── public/
│   └── frontend/images/        # Project images and assets
├── .env.example                # Environment variable template
├── composer.json               # PHP dependencies
├── package.json                # JS dependencies
└── vite.config.js              # Frontend build config
```

---

## 8. Installation and Setup

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- MySQL 5.7+ or MariaDB

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/JoshiAnisha/minor2.git
cd minor2

# 2. Install PHP dependencies
composer install

# 3. Install JavaScript dependencies
npm install

# 4. Copy environment file and generate application key
cp .env.example .env
php artisan key:generate

# 5. Configure your database in .env (see next section)

# 6. Run database migrations
php artisan migrate

# 7. Build frontend assets
npm run build

# 8. Start the development server
php artisan serve
```

The application will be available at `http://127.0.0.1:8000`.

---

## 9. Environment Configuration

Edit the `.env` file with your local settings:

```ini
APP_NAME=SewaCare
APP_ENV=local
APP_KEY=           # generated by php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sewacare
DB_USERNAME=root
DB_PASSWORD=

# Google Gemini API key for the AI chatbot
GEMINI_API_KEY=your_gemini_api_key_here
```

To obtain a **Gemini API key**, visit [Google AI Studio](https://aistudio.google.com/app/apikey) and create a free key.

---

## 10. Future Enhancements

| Enhancement | Description |
|---|---|
| **Real-time Notifications** | Push notifications for booking updates using Laravel Echo + Pusher |
| **Online Payment Integration** | eSewa / Khalti payment gateways for Nepal |
| **SMS Alerts** | Booking confirmations via SMS (Sparrow SMS / Aakash SMS) |
| **Caregiver Location Tracking** | Live GPS tracking of caregivers en route |
| **Mobile Application** | React Native / Flutter app for patients and caregivers |
| **Video Consultation** | Integrated teleconsultation using WebRTC |
| **Lab Report Upload** | Digital delivery of lab results within the portal |
| **Advanced Analytics** | Admin analytics with charts for revenue, popular services, and coverage areas |
| **Multi-language Support** | Nepali (देवनागरी) and English interface |

---

## 11. Team and Acknowledgements

This project was developed as a **Minor Project** at the undergraduate level (Computer Engineering / IT program).

**Built With:**
- [Laravel](https://laravel.com) — The PHP framework for web artisans
- [Bootstrap](https://getbootstrap.com) — Responsive frontend framework
- [Google Gemini API](https://ai.google.dev) — AI language model for SewaBot
- [Chart.js](https://www.chartjs.org) — Interactive charts for the admin dashboard

---

*© 2025 SewaCare. Bringing Care to Your Doorstep.*
