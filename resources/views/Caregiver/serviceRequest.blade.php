@extends('Caregiver.layouts.app')

@section('content')
<style>
    :root {
        --sewa-primary: #0ea5e9;
        --sewa-primary-light: #7dd3fc;
        --sewa-bg: #e0f2fe;
    }
    .request-card {
        border-radius: 12px;
        border: none;
        transition: all 0.2s ease;
    }
    .request-card:hover {
        box-shadow: 0 8px 24px rgba(13, 148, 136, 0.12);
        transform: translateY(-2px);
    }
    .request-card .card-header {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 1rem 1.25rem;
        font-weight: 600;
    }
    .info-row { display: flex; gap: 0.5rem; margin-bottom: 0.5rem; }
    .info-row i { color: var(--sewa-primary); min-width: 20px; }
    .btn-accept { background: #059669; border-color: #059669; }
    .btn-accept:hover { background: #047857; border-color: #047857; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold">Service Requests</h2>
            <p class="text-muted mb-0">Respond to patient requests — accept at base price or place a bid</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show">{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (isset($hasNoSchedule) && $hasNoSchedule)
        <div class="alert alert-warning mb-4">
            <strong>No schedule set.</strong> Add your availability in <a href="{{ route('caregiver.shift.index') }}">My Schedule</a>. Only requests that match your shift (Morning/Day/Night) and date will appear here for you to accept or reject.
        </div>
    @endif

    @if ($requests->isEmpty())
        <div class="card request-card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                <h5 class="text-muted">No pending service requests</h5>
                <p class="text-muted mb-0">
                    @if (isset($hasNoSchedule) && $hasNoSchedule)
                        Add your shift and dates in <a href="{{ route('caregiver.shift.index') }}">My Schedule</a> to see matching requests.
                    @else
                        No requests match your schedule right now, or no new requests from patients. Check back later.
                    @endif
                </p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach ($requests as $serviceRequest)
                <div class="col-lg-6">
                    <div class="card request-card shadow-sm h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>{{ $serviceRequest->service->name ?? 'Service' }}</span>
                            <span class="badge bg-light text-dark">Rs {{ number_format($serviceRequest->service->base_price ?? 0, 0) }}</span>
                        </div>
                        <div class="card-body">
                            <div class="info-row">
                                <i class="bi bi-person"></i>
                                <span><strong>Patient:</strong> {{ optional($serviceRequest->user)->name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <i class="bi bi-geo-alt"></i>
                                <span>{{ $serviceRequest->location }}</span>
                            </div>
                            <div class="info-row">
                                <i class="bi bi-calendar-event"></i>
                                <span>{{ $serviceRequest->preferred_time ? $serviceRequest->preferred_time->format('d M Y, h:i A') : '-' }}</span>
                            </div>
                            <div class="info-row">
                                <i class="bi bi-clock"></i>
                                <span>Shift: {{ ucfirst($serviceRequest->shift_type ?? 'day') }}</span>
                            </div>
                            @if ($serviceRequest->description)
                                <div class="info-row">
                                    <i class="bi bi-chat-text"></i>
                                    <span class="small text-muted">{{ Str::limit($serviceRequest->description, 120) }}</span>
                                </div>
                            @endif

                            <hr class="my-3">

                            <p class="small text-muted mb-3">Choose one:</p>
                            <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                                <form method="POST" action="{{ route('caregiver.service.acceptBase', $serviceRequest->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-accept btn-sm">Accept at base price</button>
                                </form>
                                <span class="text-muted">or</span>
                                @php $basePrice = (float) ($serviceRequest->service->base_price ?? 0); @endphp
                                <form method="POST" action="{{ route('caregiver.service.placeBid') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="service_request_id" value="{{ $serviceRequest->id }}">
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <small class="text-muted">Base: Rs {{ number_format($basePrice, 0) }}</small>
                                        <div class="input-group input-group-sm" style="max-width: 220px;">
                                            <span class="input-group-text text-muted small">+ Rs</span>
                                            <input type="number" name="add_to_base" class="form-control" required placeholder="Add amount" step="0.01" min="0" title="Amount to add to base price. Total = base + this.">
                                            <button type="submit" class="btn btn-warning">Bid</button>
                                        </div>
                                        <small class="text-muted">= your total bid (base + amount)</small>
                                    </div>
                                </form>
                            </div>
                            <form method="POST" action="{{ route('caregiver.service.reject', $serviceRequest->id) }}" class="d-inline" onsubmit="return confirm('Decline this request?');">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm text-muted p-0">Not interested</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
