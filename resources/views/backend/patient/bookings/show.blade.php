@extends('backend.layouts.dashboard.app')

@section('title', 'Booking Details')

@section('content')
<div class="mb-3">
    <a href="{{ route('patient.bookings.index') }}" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Bookings</a>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
            <h3 class="h5 fw-bold mb-0">{{ optional($booking->service)->name ?? 'Booking' }}</h3>
            <span class="badge
                @if($booking->status === 'pending') bg-warning text-dark
                @elseif($booking->status === 'accepted') bg-info
                @elseif($booking->status === 'completed') bg-success
                @else bg-secondary @endif">
                {{ ucfirst($booking->status) }}
            </span>
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <p class="text-muted small mb-0">Caregiver</p>
                <p class="mb-0 fw-semibold">{{ optional($booking->caregiver->user)->name ?? '—' }}</p>
            </div>
            <div class="col-md-6">
                <p class="text-muted small mb-0">Date & time</p>
                <p class="mb-0">{{ $booking->date_time ? \Carbon\Carbon::parse($booking->date_time)->format('d M Y, h:i A') : '—' }}</p>
            </div>
            <div class="col-md-6">
                <p class="text-muted small mb-0">Location</p>
                <p class="mb-0">{{ $booking->location ?? '—' }}</p>
            </div>
            <div class="col-md-6">
                <p class="text-muted small mb-0">Fee</p>
                <p class="mb-0 fw-bold text-primary">Rs {{ number_format($booking->price ?? 0, 0) }}</p>
            </div>
            <div class="col-md-6">
                <p class="text-muted small mb-0">Payment</p>
                <span class="badge @if(($booking->payment_status ?? '') === 'paid') bg-success @elseif(($booking->payment_status ?? '') === 'failed') bg-danger @else bg-warning text-dark @endif">
                    {{ ucfirst($booking->payment_status ?? 'pending') }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
