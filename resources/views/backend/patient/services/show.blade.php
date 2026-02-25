@extends('backend.layouts.dashboard.app')

@section('title', $service->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('patient.services.index') }}" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Back to services</a>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h2 class="mb-3">{{ $service->name }}</h2>
        <p class="text-muted">{{ $service->details }}</p>
        <p class="fw-bold text-primary fs-4 mb-2">Base price: Rs {{ number_format((float) ($service->base_price ?? 0), 2) }}</p>
        <span class="badge bg-light text-dark">{{ ucfirst($service->service_type ?? 'regular') }}</span>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h5 class="mb-3">Request this service</h5>
        <p class="text-muted small mb-4">Caregivers will see your request and can accept at base price or submit a bid.</p>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('patient.services.book') }}" method="POST">
            @csrf
            <input type="hidden" name="service_id" value="{{ $service->id }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Start date</label>
                    <input type="date" name="start_date" class="form-control" required min="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">End date</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" required placeholder="Your address">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Shift type</label>
                    <select name="shift_type" class="form-select">
                        <option value="day">Day</option>
                        <option value="night">Night</option>
                        <option value="both">Both</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Additional details (optional)</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Special requirements..."></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Submit request</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
