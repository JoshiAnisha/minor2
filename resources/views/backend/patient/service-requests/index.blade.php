@extends('backend.layouts.dashboard.app')

@section('title', 'My Service Requests')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">My Service Requests</h1>
    <p class="text-muted mb-0">Caregivers can accept at base price or place bids. Accept a bid to create your booking.</p>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@forelse ($serviceRequests as $request)
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h5 class="mb-1">{{ $request->service->name ?? 'Service' }}</h5>
                    <span class="badge 
                        @if($request->status === 'pending') bg-warning text-dark
                        @elseif($request->status === 'accepted') bg-success
                        @elseif($request->status === 'rejected') bg-danger
                        @else bg-secondary @endif">
                        {{ ucfirst($request->status) }}
                    </span>
                </div>
                <span class="text-muted">Rs {{ number_format($request->service->base_price ?? 0, 0) }}</span>
            </div>
            <p class="text-muted small mb-0 mt-2">
                <i class="bi bi-geo-alt me-1"></i> {{ $request->location }} · 
                <i class="bi bi-calendar me-1"></i> {{ $request->preferred_time ? $request->preferred_time->format('d M Y, h:i A') : '-' }}
            </p>

            @if ($request->status === 'pending' && $request->bids->where('status', 'pending')->isNotEmpty())
                <hr>
                <h6 class="mb-2">Caregiver bids</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Caregiver</th>
                                <th>Price</th>
                                <th>Message</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($request->bids->where('status', 'pending')->sortBy('proposed_price') as $bid)
                                <tr>
                                    <td>{{ optional($bid->caregiver->user)->name ?? 'N/A' }}</td>
                                    <td class="fw-bold">Rs {{ number_format($bid->proposed_price, 2) }}</td>
                                    <td class="text-muted small">{{ $bid->message ? \Str::limit($bid->message, 50) : '—' }}</td>
                                    <td>
                                        <form action="{{ route('patient.bids.accept', $bid) }}" method="POST" class="d-inline" onsubmit="return confirm('Accept this bid?');">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Accept</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if ($request->status === 'accepted' && $request->bookings->isNotEmpty())
                <hr>
                <a href="{{ route('patient.bookings.index') }}" class="btn btn-primary btn-sm">View Bookings</a>
            @endif
        </div>
    </div>
@empty
    <div class="card border-0 shadow-sm text-center py-5">
        <i class="bi bi-clipboard2-pulse text-muted display-4"></i>
        <p class="text-muted mt-3 mb-2">No service requests yet</p>
        <a href="{{ route('patient.services.index') }}" class="btn btn-primary">Browse services</a>
    </div>
@endforelse
@endsection
