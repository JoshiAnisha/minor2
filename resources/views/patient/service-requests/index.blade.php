@extends('patient.layouts.app')

@section('title', 'My Requests')

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">My Requests</h1>
    <p class="text-muted small mb-0">Your service requests and caregiver offers</p>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 small">
        {{ session('success') }}
        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show py-2 small">
        {{ session('error') }}
        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
    </div>
@endif

@forelse ($serviceRequests as $request)
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1">{{ optional($request->service)->name ?? 'Service' }}</h5>
                    <span class="badge
                        @if($request->status === 'pending') bg-warning text-dark
                        @elseif($request->status === 'accepted') bg-success
                        @elseif($request->status === 'rejected') bg-danger
                        @elseif($request->status === 'cancelled') bg-secondary
                        @else bg-secondary @endif" style="font-size: 0.7rem;">
                        {{ ucfirst($request->status) }}
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if($request->status === 'pending')
                        <form action="{{ route('patient.service-requests.cancel', $request) }}" method="POST" class="d-inline" onsubmit="return confirm('Cancel this request? Caregivers who have sent offers will no longer see it.');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Cancel request</button>
                        </form>
                    @endif
                    <span class="fw-bold text-primary">Rs {{ number_format($request->effective_base_price ?? 0, 0) }}</span>
                </div>
            </div>
            <p class="text-muted small mb-0 mt-2">
                <i class="bi bi-geo-alt me-1"></i> {{ $request->location }}
                · <i class="bi bi-calendar me-1"></i>
                @if ($request->isLongTerm() && $request->start_date && $request->end_date)
                    {{ $request->start_date->format('d M Y') }} – {{ $request->end_date->format('d M Y') }}
                @else
                    {{ $request->preferred_time ? $request->preferred_time->format('d M Y, h:i A') : '—' }}
                @endif
            </p>

            @if ($request->status === 'pending' && $request->bids->where('status', 'pending')->isNotEmpty())
                <hr class="my-3">
                <p class="small fw-medium mb-2">Offers from caregivers</p>
                <div class="row g-2">
                    @foreach ($request->bids->where('status', 'pending')->sortBy('proposed_price') as $bid)
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 p-3 rounded-3 bg-light">
                                <div>
                                    @if($bid->caregiver && $bid->caregiver->id)
                                        <a href="{{ route('patient.caregiver.show', $bid->caregiver) }}" class="fw-medium text-decoration-none">{{ optional($bid->caregiver->user)->name ?? 'Caregiver' }}</a>
                                    @else
                                        <span class="fw-medium">{{ optional($bid->caregiver->user)->name ?? 'Caregiver' }}</span>
                                    @endif
                                    <span class="text-primary fw-bold ms-2">Rs {{ number_format($bid->proposed_price, 0) }}</span>
                                    @if($bid->message)
                                        <p class="text-muted small mb-0 mt-1">{{ Str::limit($bid->message, 60) }}</p>
                                    @endif
                                </div>
                                <div class="d-flex gap-1">
                                    <form action="{{ route('patient.bids.accept', $bid) }}" method="POST" class="d-inline" onsubmit="return confirm('Accept this offer?');">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Accept</button>
                                    </form>
                                    <form action="{{ route('patient.bids.reject', $bid) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this offer?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Reject</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($request->status === 'accepted' && $request->bookings->isNotEmpty())
                @php $booking = $request->bookings->first(); @endphp
                <hr class="my-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <span class="text-muted small">Caregiver</span>
                        @if($booking->caregiver && $booking->caregiver->id)
                            <span class="d-block fw-medium"><a href="{{ route('patient.caregiver.show', $booking->caregiver) }}" class="text-decoration-none">{{ optional($booking->caregiver->user)->name ?? 'Caregiver' }}</a></span>
                        @else
                            <span class="d-block fw-medium">{{ optional($booking->caregiver->user)->name ?? 'Caregiver' }}</span>
                        @endif
                        <span class="text-muted small">Agreed fee</span>
                        <span class="d-block fw-bold text-success">Rs {{ number_format($booking->price ?? 0, 0) }}</span>
                    </div>
                    @php $bookingId = $booking->getKey(); @endphp
                    @if($bookingId)
                        <a href="{{ route('patient.bookings.show', ['id' => $bookingId]) }}" class="btn btn-primary btn-sm">View booking</a>
                    @else
                        <a href="{{ route('patient.bookings.index') }}" class="btn btn-outline-secondary btn-sm">View booking</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@empty
    <div class="card border-0 shadow-sm text-center py-5">
        <i class="bi bi-clipboard2-pulse text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
        <p class="text-muted mt-3 mb-3">No requests yet</p>
        <a href="{{ route('patient.services.index') }}" class="btn btn-primary btn-sm">Browse services</a>
    </div>
@endforelse
@endsection
