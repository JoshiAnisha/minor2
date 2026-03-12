@extends('patient.layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">My Bookings</h1>
    <p class="text-muted small mb-0">Your care appointments</p>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 small">
        {{ session('success') }}
        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
    </div>
@endif

@php
    $activeBookings = $bookings->whereIn('status', ['pending', 'accepted', 'completed']);
    $cancelledBookings = $bookings->where('status', 'cancelled');
@endphp

@if ($bookings->isEmpty())
    <div class="card border-0 shadow-sm text-center py-5">
        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
        <p class="text-muted mt-3 mb-3">No bookings yet</p>
        <a href="{{ route('patient.services.index') }}" class="btn btn-primary btn-sm">Book a service</a>
    </div>
@else
    @if($activeBookings->isNotEmpty())
        <div class="row g-4">
            @foreach ($activeBookings as $booking)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0 fw-bold">{{ optional($booking->service)->name ?? 'Service' }}</h5>
                                <span class="badge
                                    @if($booking->status === 'pending') bg-warning text-dark
                                    @elseif($booking->status === 'accepted') bg-info
                                    @elseif($booking->status === 'completed') bg-success
                                    @else bg-secondary @endif" style="font-size: 0.7rem;">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                            <p class="text-muted small mb-1">
                                <i class="bi bi-person me-1"></i> @if($booking->caregiver)<a href="{{ route('patient.caregiver.show', $booking->caregiver) }}" class="text-decoration-none">{{ optional($booking->caregiver->user)->name ?? '—' }}</a>@else{{ optional(optional($booking->caregiver)->user)->name ?? '—' }}@endif
                            </p>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-calendar me-1"></i> {{ $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d M Y') : '—' }}
                            </p>
                            <p class="mb-3 fw-bold">Rs {{ number_format($booking->price ?? 0, 0) }}</p>
                            @php $bookingId = $booking->getKey(); @endphp
                            @if($bookingId)
                                <a href="{{ route('patient.bookings.show', ['id' => $bookingId]) }}" class="btn btn-primary btn-sm w-100 mb-1" style="border-radius: 10px;">View details</a>
                                @if($booking->status === 'completed' && !in_array($booking->getKey(), $reviewedBookingIds ?? []))
                                    <a href="{{ route('patient.bookings.review.create', $bookingId) }}" class="btn btn-outline-success btn-sm w-100" style="border-radius: 10px;"><i class="bi bi-star me-1"></i>Leave review</a>
                                @endif
                            @else
                                <a href="{{ route('patient.bookings.index') }}" class="btn btn-outline-secondary btn-sm w-100" style="border-radius: 10px;">View details</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($cancelledBookings->isNotEmpty())
        <h5 class="h6 fw-bold mt-5 mb-3 text-secondary"><i class="bi bi-x-circle me-2"></i>Cancelled requests</h5>
        <p class="text-muted small mb-3">The following requests were cancelled by the caregiver.</p>
        <div class="row g-4">
            @foreach ($cancelledBookings as $booking)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 border-secondary border-opacity-25" style="border-radius: 16px; border-width: 1px !important;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0 fw-bold">{{ optional($booking->service)->name ?? 'Service' }}</h5>
                                <span class="badge bg-secondary" style="font-size: 0.7rem;">Cancelled</span>
                            </div>
                            <p class="text-muted small mb-1">
                                <i class="bi bi-person me-1"></i> @if($booking->caregiver)<a href="{{ route('patient.caregiver.show', $booking->caregiver) }}" class="text-decoration-none">{{ optional($booking->caregiver->user)->name ?? '—' }}</a>@else{{ optional(optional($booking->caregiver)->user)->name ?? '—' }}@endif
                            </p>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-calendar me-1"></i> {{ $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d M Y') : '—' }}
                            </p>
                            <p class="mb-0 fw-bold">Rs {{ number_format($booking->price ?? 0, 0) }}</p>
                            <p class="text-muted small mt-2 mb-0">This booking was cancelled by the caregiver. You can place a new request from Services if needed.</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endif
@endsection
