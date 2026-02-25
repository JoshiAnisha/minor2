@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="mb-3">
            <a href="{{ route('admin.services.index') }}" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Back to services</a>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0">{{ $service->name }}</h3>
                <div>
                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-light btn-sm">Edit</a>
                </div>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">Slug</dt>
                    <dd class="col-sm-9"><code>{{ $service->slug }}</code></dd>

                    <dt class="col-sm-3">Service type</dt>
                    <dd class="col-sm-9"><span class="badge bg-secondary">{{ ucfirst($service->service_type) }}</span></dd>

                    <dt class="col-sm-3">Base price</dt>
                    <dd class="col-sm-9">{{ number_format($service->base_price, 2) }}</dd>

                    <dt class="col-sm-3">Details</dt>
                    <dd class="col-sm-9">{{ $service->details ?: '—' }}</dd>
                </dl>
                <p class="text-muted small mt-3 mb-0">This service is visible to patients (Services) and to caregivers when assigned or requested.</p>
            </div>
        </div>
    </div>
@endsection
