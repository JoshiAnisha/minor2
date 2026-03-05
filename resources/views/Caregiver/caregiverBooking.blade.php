@extends('Caregiver.layouts.app')

@section('content')
<style>
    .section-title { font-weight: 700; margin-bottom: 1rem; }
    .booking-card { border-radius: 16px; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
</style>

<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">My Bookings</h1>
    <p class="text-muted mb-0">Manage your pending, active, and completed bookings</p>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Pending --}}
<h5 class="section-title text-warning"><i class="bi bi-hourglass-split me-2"></i>Pending</h5>
@forelse ($pendingBookings as $index => $item)
    @php
        $isBid = $item instanceof \App\Models\Bid;
        $sr = $isBid ? $item->serviceRequest : $item;
    @endphp
    <div class="card booking-card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <strong>{{ optional($sr->user ?? $sr->patient?->user)->name ?? 'N/A' }}</strong> — {{ optional($sr->service)->name ?? 'N/A' }}
                    <br>
                    <small class="text-muted">{{ $sr->preferred_time ? \Carbon\Carbon::parse($sr->preferred_time)->format('d M Y, h:i A') : '-' }} · {{ $sr->location ?? '-' }}</small>
                </div>
                <div class="col-md-4 text-md-end mt-2 mt-md-0">
                    <span class="badge bg-secondary me-2">Rs {{ $isBid ? number_format($item->proposed_price, 0) : number_format($sr->service->base_price ?? 0, 0) }}</span>
                    @if ($isBid)
                        <span class="badge bg-warning text-dark">Awaiting patient</span>
                    @else
                        <a href="{{ route('caregiver.service.requests') }}" class="btn btn-sm btn-primary">Accept or Bid</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    <p class="text-muted">No pending requests.</p>
@endforelse

{{-- In Progress --}}
<h5 class="section-title text-info mt-5"><i class="bi bi-arrow-repeat me-2"></i>In Progress</h5>
@forelse ($acceptedBookings as $booking)
    <div class="card booking-card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <strong>{{ optional($booking->patient->user)->name ?? 'N/A' }}</strong> — {{ optional($booking->service)->name ?? 'N/A' }}
                    <br>
                    <small class="text-muted">{{ $booking->date_time ? \Carbon\Carbon::parse($booking->date_time)->format('d M Y, h:i A') : '-' }}</small>
                </div>
                <div class="col-md-4 text-md-end mt-2 mt-md-0">
                    <span class="badge bg-info me-2">Rs {{ number_format($booking->price ?? 0, 0) }}</span>
                    <form action="{{ route('caregiver.booking.complete', $booking->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Mark Completed</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@empty
    <p class="text-muted">No bookings in progress.</p>
@endforelse

{{-- Completed --}}
<h5 class="section-title text-success mt-5"><i class="bi bi-check-circle me-2"></i>Completed</h5>
@forelse ($completedBookings as $booking)
    <div class="card booking-card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    @if (!empty($booking->patients_id))
                        <a href="{{ route('caregiver.patient.show', $booking->patients_id) }}" class="text-decoration-none fw-semibold">{{ optional($booking->patient->user)->name ?? 'N/A' }}</a>
                    @else
                        <strong>{{ optional($booking->patient->user)->name ?? 'N/A' }}</strong>
                    @endif
                    — {{ optional($booking->service)->name ?? 'N/A' }}
                    <br>
                    <small class="text-muted">{{ $booking->date_time ? \Carbon\Carbon::parse($booking->date_time)->format('d M Y') : '-' }}</small>
                </div>
                <div class="col-md-4 text-md-end mt-2 mt-md-0">
                    <span class="badge bg-success me-2">Completed</span>
                    @if(($booking->payment_status ?? '') === 'paid')
                        <span class="badge bg-primary me-2">Paid</span>
                    @else
                        <span class="badge bg-warning text-dark me-2">Pending payment</span>
                        <form action="{{ route('caregiver.booking.mark-paid', $booking->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Mark payment as received?');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">Mark payment received</button>
                        </form>
                    @endif
                    @if (!empty($booking->patients_id))
                        <a href="{{ route('caregiver.review.create', $booking->patients_id) }}" class="btn btn-sm btn-outline-primary">Leave Review</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    <p class="text-muted">No completed bookings yet.</p>
@endforelse
@endsection
