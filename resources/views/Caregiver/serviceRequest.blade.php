@extends('Caregiver.layouts.app')

@section('content')
<style>
    :root {
        --sewa-primary: #0ea5e9;
        --sewa-primary-light: #7dd3fc;
        --sewa-bg: #e0f2fe;
    }
    .request-card {
        border-radius: 10px;
        border: none;
        transition: all 0.2s ease;
    }
    .request-card:hover {
        box-shadow: 0 6px 16px rgba(13, 148, 136, 0.12);
        transform: translateY(-1px);
    }
    .request-card .card-header {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 0.5rem 0.75rem;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .request-card .card-body { padding: 0.65rem 0.75rem; }
    .info-row { display: flex; gap: 0.35rem; margin-bottom: 0.35rem; font-size: 0.85rem; }
    .info-row i { color: var(--sewa-primary); min-width: 16px; font-size: 0.8rem; }
    .btn-accept { background: #059669; border-color: #059669; }
    .btn-accept:hover { background: #047857; border-color: #047857; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold">Service Requests</h2>
            <p class="text-muted mb-0">All new patient requests appear here. Accept at base price, place a bid, or decline — requests you decline will be hidden from your list.</p>
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

    @if ($requests->isEmpty())
        <div class="card request-card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                <h5 class="text-muted">No pending service requests</h5>
                <p class="text-muted mb-0">When patients request a service, it will appear here. You can accept at base price, place a bid, or decline. Check back later for new requests.</p>
            </div>
        </div>
    @else
        @php
            $longTermRequests = $requests->filter(fn($r) => $r->isLongTerm());
            $shortTermRequests = $requests->filter(fn($r) => !$r->isLongTerm());
        @endphp

        @if($shortTermRequests->isNotEmpty())
            <h5 class="fw-bold mb-3 text-success"><i class="bi bi-calendar-day me-2"></i>Short-term (one day)</h5>
            <div class="row g-3 mb-5">
                @foreach ($shortTermRequests as $serviceRequest)
                    @include('Caregiver.partials.service-request-card', ['serviceRequest' => $serviceRequest])
                @endforeach
            </div>
        @endif

        @if($longTermRequests->isNotEmpty())
            <h5 class="fw-bold mb-3 text-info"><i class="bi bi-calendar-range me-2"></i>Long-term</h5>
            <div class="row g-3">
                @foreach ($longTermRequests as $serviceRequest)
                    @include('Caregiver.partials.service-request-card', ['serviceRequest' => $serviceRequest])
                @endforeach
            </div>
        @endif
    @endif
</div>
@endsection
