@extends('backend.layouts.dashboard.app')

@section('title', 'My Bookings')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">My Bookings</h1>
    <p class="text-muted mb-0">View and manage your care appointments</p>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($bookings->isEmpty())
    <div class="card border-0 shadow-sm text-center py-5">
        <i class="bi bi-calendar-x text-muted display-4"></i>
        <h5 class="mt-3 text-muted">No bookings yet</h5>
        <p class="text-muted">Request a service to get started</p>
        <a href="{{ route('patient.services.index') }}" class="btn btn-primary">Browse services</a>
    </div>
@else
    <div class="row g-4">
        @foreach ($bookings as $booking)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ optional($booking->service)->name ?? 'Service' }}</h5>
                            <span class="badge 
                                @if($booking->status === 'pending') bg-warning text-dark
                                @elseif($booking->status === 'accepted') bg-info
                                @elseif($booking->status === 'completed') bg-success
                                @else bg-secondary @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-person me-1"></i> {{ optional($booking->caregiver->user)->name ?? 'N/A' }}
                        </p>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-calendar me-1"></i> {{ $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d M Y') : 'N/A' }}
                        </p>
                        <p class="mb-3"><strong>Rs {{ number_format($booking->price ?? 0, 0) }}</strong></p>
                        <a href="{{ route('patient.bookings.show', $booking->id) }}" class="btn btn-primary btn-sm w-100">View details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
