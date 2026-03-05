@extends('backend.layouts.dashboard.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .patient-dash { font-family: 'Plus Jakarta Sans', sans-serif; }
    .welcome-banner {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        border-radius: 20px;
        padding: 1.75rem 2rem;
        color: #fff;
        margin-bottom: 1.75rem;
        box-shadow: 0 8px 32px rgba(2, 132, 199, 0.2);
    }
    .welcome-banner h1 { font-weight: 700; font-size: 1.5rem; margin-bottom: 0.2rem; }
    .welcome-banner p { opacity: 0.9; font-size: 0.9rem; margin: 0; }
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
</style>

<div class="patient-dash">
    <div class="welcome-banner">
        <h1>Hi, {{ Auth::user()->name ?? 'Patient' }}</h1>
        <p>Here’s your care overview</p>
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
                        <p class="text-muted small mb-0" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.02em;">Upcoming</p>
                        <h4 class="fw-bold mb-0 mt-1">{{ $upcomingServices }}</h4>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-clock-history"></i>
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

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="section-card card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span>Services</span>
                    <a href="{{ route('patient.services.index') }}" class="btn btn-primary btn-sm" style="border-radius: 8px;">Browse all</a>
                </div>
                <div class="card-body p-0">
                    @if (isset($availableServices) && $availableServices->isNotEmpty())
                        @foreach ($availableServices->take(4) as $service)
                            <div class="service-item d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <strong class="d-block">{{ $service->name }}</strong>
                                    <small class="text-muted">{{ ucfirst($service->service_type) }} · Rs {{ number_format($service->base_price, 0) }}</small>
                                </div>
                                <a href="{{ route('patient.bookings.create', ['service' => $service->slug]) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">Book</a>
                            </div>
                        @endforeach
                        @if ($availableServices->count() > 4)
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
                                    <small class="text-muted">{{ optional(optional($booking->caregiver)->user)->name ?? 'Caregiver' }} · {{ $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d M Y') : '—' }}</small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge badge-status
                                        @if($booking->status === 'pending') bg-warning text-dark
                                        @elseif($booking->status === 'accepted') bg-info
                                        @elseif($booking->status === 'completed') bg-success
                                        @else bg-secondary @endif">
                                        {{ ucfirst($booking->status ?? 'N/A') }}
                                    </span>
                                    <a href="{{ route('patient.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">View</a>
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
