@extends('backend.layouts.dashboard.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .patient-dash { font-family: 'Plus Jakarta Sans', sans-serif; }
    .welcome-banner {
        background: #fff;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #64748b;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .welcome-banner .welcome-name { font-size: 1.25rem; font-weight: 600; color: #1e293b; margin: 0; letter-spacing: -0.01em; }
    .stat-card {
        border-radius: 14px;
        border: none;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
    .stat-card .stat-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.35rem;
    }
    .section-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .section-card .card-header {
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        padding: 1rem 1.25rem;
        font-weight: 600;
        font-size: 1rem;
    }
    .service-item {
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s;
    }
    .service-item:last-child { border-bottom: none; }
    .service-item:hover { background: #f8fafc; }
    .booking-item {
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .booking-item:last-child { border-bottom: none; }
    .badge-status { font-size: 0.7rem; padding: 0.3em 0.6em; font-weight: 500; }
    .empty-state { padding: 2rem 1.5rem; text-align: center; color: #64748b; }
    .empty-state i { font-size: 2.5rem; opacity: 0.4; margin-bottom: 0.5rem; }
    .custom-request-card {
        background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
        border: 1px solid #99f6e4;
        border-radius: 12px;
        padding: 0.9rem 1.25rem;
    }
    .custom-request-card a { font-weight: 600; color: #0d9488; }
    .quick-link-card {
        background: #fff;
        border-radius: 14px;
        padding: 1rem;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.04);
        transition: transform 0.2s, box-shadow 0.2s;
        color: #1e293b;
    }
    .quick-link-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(14, 165, 233, 0.15); color: #0284c7; border-color: rgba(14, 165, 233, 0.2); }
    .quick-link-card .icon-wrap {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem;
        font-size: 1.25rem;
    }
    .quick-link-card .label { font-size: 0.8rem; font-weight: 600; display: block; }
    .dashboard-visual { background: #f8fafc; border: 1px solid #e2e8f0; }
    .dashboard-visual-img-wrap {
        position: relative;
        overflow: hidden;
        min-height: 380px;
        background: #f1f5f9;
    }
    .dashboard-visual-img {
        display: block;
        width: 100%;
        height: 100%;
        min-height: 380px;
        max-height: 420px;
        object-fit: cover;
        object-position: center;
        animation: dashboardImgFadeIn 0.8s ease-out;
        transition: transform 0.5s ease, filter 0.4s ease;
    }
    .dashboard-visual:hover .dashboard-visual-img {
        transform: scale(1.03);
        filter: brightness(1.02);
    }
    .dashboard-visual-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(248,250,252,0.4) 0%, transparent 50%);
        pointer-events: none;
        animation: overlayFadeIn 1s ease-out 0.3s both;
    }
    .dashboard-visual-text { animation: dashboardTextSlide 0.6s ease-out; }
    @keyframes dashboardImgFadeIn {
        from { opacity: 0; transform: scale(0.98); }
        to { opacity: 1; transform: scale(1); }
    }
    @keyframes overlayFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes dashboardTextSlide {
        from { opacity: 0; transform: translateX(-12px); }
        to { opacity: 1; transform: translateX(0); }
    }
</style>

<div class="patient-dash">
    <div class="welcome-banner">
        <h1 class="welcome-name">Welcome back, {{ Auth::user()->name ?? 'Patient' }}</h1>
    </div>

    @if(isset($profileComplete) && !$profileComplete)
        <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="border-radius: 14px; border-left: 4px solid #eab308;">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-25 p-2"><i class="bi bi-exclamation-triangle-fill text-warning"></i></div>
                <div>
                    <h6 class="fw-bold mb-1">Complete your profile</h6>
                    <p class="mb-0 small text-muted">You must complete your profile (name, email, contact number, and address) before making a service request or accepting an offer from a caregiver.</p>
                </div>
            </div>
            <a href="{{ route('patient.profile.edit') }}" class="btn btn-warning">Complete profile</a>
        </div>
    @endif

    {{-- Quick links --}}
    <div class="mb-4">
        <h2 class="h6 text-uppercase fw-semibold text-muted mb-3">Quick links</h2>
        <div class="row g-2 g-sm-3">
            <div class="col-6 col-md-4 col-lg">
                <a href="{{ route('patient.services.index') }}" class="quick-link-card text-decoration-none d-block h-100">
                    <div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-heart-pulse"></i></div>
                    <span class="label">Services</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <a href="{{ route('patient.bookings.index') }}" class="quick-link-card text-decoration-none d-block h-100">
                    <div class="icon-wrap bg-info bg-opacity-10 text-info"><i class="bi bi-calendar-check"></i></div>
                    <span class="label">Bookings</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <a href="{{ route('patient.service-requests.index') }}" class="quick-link-card text-decoration-none d-block h-100">
                    <div class="icon-wrap bg-warning bg-opacity-10 text-warning"><i class="bi bi-clipboard2-pulse"></i></div>
                    <span class="label">My Requests</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <a href="{{ route('patient.invoices.index') }}" class="quick-link-card text-decoration-none d-block h-100">
                    <div class="icon-wrap bg-success bg-opacity-10 text-success"><i class="bi bi-receipt"></i></div>
                    <span class="label">Invoices</span>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg">
                <a href="{{ route('patient.profile.show') }}" class="quick-link-card text-decoration-none d-block h-100">
                    <div class="icon-wrap bg-dark bg-opacity-10 text-dark"><i class="bi bi-person-circle"></i></div>
                    <span class="label">Profile</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-4">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.02em;">Bookings</p>
                        <h4 class="fw-bold mb-0 mt-1">{{ $totalBookings }}</h4>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.02em;">My Requests</p>
                        <h4 class="fw-bold mb-0 mt-1">{{ $myRequestsCount }}</h4>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-clipboard2-pulse"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-0" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.02em;">Invoices</p>
                        <h4 class="fw-bold mb-0 mt-1">{{ $totalInvoices }}</h4>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Dashboard visual: in-home care – image always visible --}}
    <div class="dashboard-visual rounded-3 overflow-hidden shadow-sm mb-4">
        <div class="row g-0 align-items-stretch">
            <div class="col-lg-5 p-4 p-lg-5 d-flex align-items-center bg-white">
                <div class="dashboard-visual-text">
                    <h3 class="h5 fw-bold mb-3 text-dark">Care When and Where You Need It</h3>
                    <p class="text-secondary mb-0 lh-base" style="font-size: 0.9375rem;">From booking a nurse to viewing invoices—everything in one dashboard. Simple, secure, and designed around you.</p>
                </div>
            </div>
            <div class="col-lg-7 dashboard-visual-img-wrap">
                <img src="{{ asset('images/patient-dashboard-hero.png') }}" alt="In-home care dashboard" class="dashboard-visual-img" width="800" height="400" loading="eager">
                <div class="dashboard-visual-overlay" aria-hidden="true"></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="section-card card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span>Services</span>
                    <a href="{{ route('patient.services.index') }}" class="btn btn-primary btn-sm" style="border-radius: 8px;">Browse all</a>
                </div>
                <div class="card-body p-0">
                    @if (isset($availableServices) && $availableServices->isNotEmpty())
                        @foreach ($availableServices->take(2) as $service)
                            <div class="service-item d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <strong class="d-block">{{ $service->name }}</strong>
                                    <small class="text-muted">{{ ucfirst($service->service_type) }} · Rs {{ number_format($service->base_price, 0) }}</small>
                                </div>
                                <a href="{{ route('patient.bookings.create', ['service' => $service->slug]) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">Book</a>
                            </div>
                        @endforeach
                        @if ($availableServices->count() > 2)
                            <div class="px-3 py-2 border-top bg-light text-center">
                                <a href="{{ route('patient.services.index') }}" class="small fw-medium text-decoration-none">View all {{ $availableServices->count() }} services</a>
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="bi bi-heart-pulse d-block"></i>
                            <p class="mb-1 small">No services yet</p>
                            <a href="{{ route('patient.service-requests.create-custom') }}" class="btn btn-sm btn-outline-primary mt-2">Request custom</a>
                        </div>
                    @endif
                    <div class="p-3 border-top">
                        <div class="custom-request-card">
                            <strong class="d-block mb-0">Something else?</strong>
                            <a href="{{ route('patient.service-requests.create-custom') }}" class="small">Request a custom service →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="section-card card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Recent bookings</span>
                    <a href="{{ route('patient.bookings.index') }}" class="btn btn-link btn-sm p-0 text-decoration-none fw-medium">View all</a>
                </div>
                <div class="card-body p-0">
                    @if ($latestBookings->isEmpty())
                        <div class="empty-state">
                            <i class="bi bi-calendar-x d-block"></i>
                            <p class="mb-1 small">No bookings yet</p>
                            <a href="{{ route('patient.services.index') }}" class="btn btn-primary btn-sm mt-2">Book a service</a>
                        </div>
                    @else
                        @foreach ($latestBookings as $booking)
                            <div class="booking-item">
                                <div>
                                    <strong class="d-block">{{ optional($booking->service)->name ?? 'Service' }}</strong>
                                    <small class="text-muted">@if($booking->caregiver)<a href="{{ route('patient.caregiver.show', $booking->caregiver) }}" class="text-decoration-none">{{ optional($booking->caregiver->user)->name ?? 'Caregiver' }}</a>@else{{ optional(optional($booking->caregiver)->user)->name ?? 'Caregiver' }}@endif · {{ $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d M Y') : '—' }}</small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge badge-status
                                        @if($booking->status === 'pending') bg-warning text-dark
                                        @elseif($booking->status === 'accepted') bg-info
                                        @elseif($booking->status === 'completed') bg-success
                                        @else bg-secondary @endif">
                                        {{ ucfirst($booking->status ?? 'N/A') }}
                                    </span>
                                    @php $bookingId = $booking->getKey(); @endphp
                                    @if($bookingId)
                                        <a href="{{ route('patient.bookings.show', ['id' => $bookingId]) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    @else
                                        <a href="{{ route('patient.bookings.index') }}" class="btn btn-sm btn-outline-secondary">View</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
