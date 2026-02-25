@extends('backend.layouts.dashboard.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .stat-card { border-radius: 16px; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.08); transition: transform 0.2s; }
    .stat-card:hover { transform: translateY(-2px); }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
</style>

<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Welcome, {{ Auth::user()->name ?? 'Patient' }}!</h1>
    <p class="text-muted mb-0">Here’s your health service summary</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Total Bookings</p>
                    <h3 class="fw-bold mb-0">{{ $totalBookings }}</h3>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Upcoming Services</p>
                    <h3 class="fw-bold mb-0">{{ $upcomingServices }}</h3>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1">Invoices</p>
                    <h3 class="fw-bold mb-0">{{ $totalInvoices }}</h3>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Available services (opened by admin) --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Available Services</h5>
        <a href="{{ route('patient.services.index') }}" class="btn btn-primary btn-sm">Browse all</a>
    </div>
    <div class="card-body p-0">
        @if (isset($availableServices) && $availableServices->isNotEmpty())
            <ul class="list-group list-group-flush">
                @foreach ($availableServices->take(5) as $service)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $service->name }}</strong>
                            <br><small class="text-muted">{{ ucfirst($service->service_type) }} · {{ number_format($service->base_price, 2) }}</small>
                        </div>
                        <a href="{{ route('patient.services.show', $service->slug) }}" class="btn btn-sm btn-outline-primary">View & Request</a>
                    </li>
                @endforeach
            </ul>
            @if ($availableServices->count() > 5)
                <div class="text-center py-2 border-top">
                    <a href="{{ route('patient.services.index') }}" class="btn btn-sm btn-link">View all {{ $availableServices->count() }} services</a>
                </div>
            @endif
        @else
            <div class="text-center py-4 text-muted">
                <i class="bi bi-bag display-6"></i>
                <p class="mb-0 mt-2">No services available at the moment</p>
                <small>Services opened by admin will appear here</small>
            </div>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 fw-bold">Recent Bookings</h5>
    </div>
    <div class="card-body p-0">
        @if ($latestBookings->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-calendar-x display-4"></i>
                <p class="mb-0 mt-2">No bookings yet</p>
                <a href="{{ route('patient.services.index') }}" class="btn btn-primary btn-sm mt-2">Browse services</a>
            </div>
        @else
            <ul class="list-group list-group-flush">
                @foreach ($latestBookings as $booking)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ optional($booking->service)->name ?? 'N/A' }}</strong>
                            <br><small class="text-muted">{{ optional(optional($booking->caregiver)->user)->name ?? 'Caregiver' }} · {{ $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d M Y') : 'N/A' }}</small>
                        </div>
                        <span class="badge 
                            @if($booking->status === 'pending') bg-warning text-dark
                            @elseif($booking->status === 'accepted') bg-info
                            @elseif($booking->status === 'completed') bg-success
                            @else bg-secondary @endif">
                            {{ ucfirst($booking->status ?? 'N/A') }}
                        </span>
                        <a href="{{ route('patient.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
