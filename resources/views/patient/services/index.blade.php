@extends('patient.layouts.app')

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
    @php
        $grouped = $services->groupBy(function ($s) { return $s->category ?: 'Other'; });
    @endphp
    @foreach ($grouped as $category => $items)
        <section class="mb-4">
            <h2 class="h6 text-uppercase fw-semibold text-muted mb-3 pb-2 border-bottom">{{ $category }}</h2>
            <div class="row g-3">
                @foreach ($items as $service)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100 service-card" style="border-radius: 16px; transition: transform 0.2s, box-shadow 0.2s;">
                            <div class="card-body p-4">
                                <div class="d-flex flex-wrap gap-1 align-items-center mb-2">
                                    <span class="badge bg-light text-dark" style="font-size: 0.7rem;">{{ ucfirst($service->service_type ?? 'regular') }}</span>
                                    @if ($service->is_long_term ?? false)
                                        <span class="badge bg-info" style="font-size: 0.7rem;">Long-term</span>
                                    @endif
                                </div>
                                <h5 class="card-title mb-2 fw-bold h6">{{ $service->name }}</h5>
                                @if(!empty($service->details))
                                    <p class="text-muted small mb-3 lh-sm" style="min-height: 2.5rem;">{{ Str::limit(strip_tags(preg_replace('/[\r\n]+/', ' ', $service->details)), 100) }}</p>
                                @else
                                    <p class="text-muted small mb-3" style="min-height: 2.5rem;">&nbsp;</p>
                                @endif
                                <div class="d-flex align-items-center justify-content-between mt-auto pt-2">
                                    <span class="fw-bold text-primary">Rs {{ number_format((float) ($service->base_price ?? 0), 0) }}</span>
                                    <a href="{{ route('patient.services.show', $service->slug) }}" class="btn btn-primary btn-sm" style="border-radius: 10px;">Book now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach
@endif

{{-- Self-open / custom service request section --}}
<section class="mt-4 pt-4 border-top">
    <div class="card border-0 shadow-sm" style="border-radius: 16px; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);">
        <div class="card-body p-4">
            <div class="d-flex flex-sm-row flex-column align-items-sm-center justify-content-between gap-3">
                <div>
                    <h2 class="h6 fw-bold mb-1">Need a service that’s not listed?</h2>
                    <p class="text-muted small mb-0">Describe what you need and when. Caregivers can respond with an offer or bid.</p>
                </div>
                <a href="{{ route('patient.service-requests.create-custom') }}" class="btn btn-outline-primary btn-sm text-nowrap" style="border-radius: 10px;">
                    <i class="bi bi-plus-circle me-1"></i> Request custom service
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    .service-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(14, 165, 233, 0.12) !important; }
</style>
@endsection
