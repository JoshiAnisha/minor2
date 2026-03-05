@extends('backend.layouts.dashboard.app')

@section('title', 'Services')

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">Services</h1>
    <p class="text-muted small mb-0">Choose a service to book. Caregivers will respond with an offer or bid.</p>
</div>

@if ($services->isEmpty())
    <div class="card border-0 shadow-sm text-center py-5">
        <i class="bi bi-heart-pulse text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
        <p class="text-muted mt-3 mb-3">No services available</p>
        <a href="{{ route('patient.dashboard') }}" class="btn btn-primary btn-sm">Back to dashboard</a>
    </div>
@else
    <div class="row g-4">
        @foreach ($services as $service)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 service-card" style="border-radius: 16px; transition: transform 0.2s, box-shadow 0.2s;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0 fw-bold">{{ $service->name }}</h5>
                            <span class="badge bg-light text-dark" style="font-size: 0.7rem;">{{ ucfirst($service->service_type ?? 'regular') }}</span>
                        </div>
                        @if(!empty($service->details))
                            <p class="text-muted small mb-3">{{ Str::limit($service->details, 90) }}</p>
                        @endif
                        <p class="fw-bold text-primary mb-3 mb-0">Rs {{ number_format((float) ($service->base_price ?? 0), 0) }}</p>
                        <a href="{{ route('patient.bookings.create', ['service' => $service->slug]) }}" class="btn btn-primary btn-sm w-100 mt-2" style="border-radius: 10px;">Book now</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<style>
    .service-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(14, 165, 233, 0.12) !important; }
</style>
@endsection
