@extends('backend.layouts.dashboard.app')

@section('title', 'Services')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Available Services</h1>
    <p class="text-muted mb-0">Browse and book home care services. Caregivers can accept or bid on your request.</p>
</div>

@if ($services->isEmpty())
    <div class="card border-0 shadow-sm text-center py-5">
        <i class="bi bi-heart-pulse text-muted display-4"></i>
        <p class="text-muted mt-3 mb-0">No services available at the moment.</p>
    </div>
@else
    <div class="row g-4">
        @foreach ($services as $service)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 service-card" style="border-radius: 16px; transition: transform 0.2s, box-shadow 0.2s;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">{{ $service->name }}</h5>
                            <span class="badge bg-light text-dark">{{ ucfirst($service->service_type ?? 'regular') }}</span>
                        </div>
                        <p class="text-muted small mb-3">{{ Str::limit($service->details ?? '', 100) }}</p>
                        <p class="fw-bold text-primary mb-3">Base price: Rs {{ number_format((float) ($service->base_price ?? 0), 2) }}</p>
                        <a href="{{ route('patient.services.show', $service->slug) }}" class="btn btn-primary btn-sm w-100">View & Request</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<style>
    .service-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(14, 165, 233, 0.15) !important; }
</style>
@endsection
