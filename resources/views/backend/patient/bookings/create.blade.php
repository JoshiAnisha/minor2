@extends('backend.layouts.dashboard.app')

@section('title', 'Book ' . $service->name)

@section('content')
<div class="mb-3">
    <a href="{{ route('patient.services.index') }}" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Services</a>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h2 class="h5 fw-bold mb-2">{{ $service->name }}</h2>
        @if(!empty($service->details))
            <p class="text-muted small mb-2">{{ Str::limit($service->details, 200) }}</p>
        @endif
        <p class="fw-bold text-primary mb-0">Rs {{ number_format((float) ($service->base_price ?? 0), 0) }}</p>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Request details</h5>

        @if (session('error'))
            <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success py-2 small">{{ session('success') }}</div>
        @endif

        <form action="{{ route('patient.services.book') }}" method="POST">
            @csrf
            <input type="hidden" name="service_id" value="{{ $service->id }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-medium">Start date</label>
                    <input type="date" name="start_date" class="form-control form-control-sm" required min="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-medium">End date</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" required min="{{ date('Y-m-d') }}">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-medium">Address</label>
                    <input type="text" name="location" class="form-control form-control-sm" required placeholder="Where care is needed">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-medium">Shift</label>
                    <select name="shift_type" class="form-select form-select-sm" required>
                        <option value="" disabled selected>Select</option>
                        <option value="morning">Morning</option>
                        <option value="day">Day</option>
                        <option value="night">Night</option>
                        <option value="both">Both</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-medium">Notes <span class="text-muted">(optional)</span></label>
                    <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Any special requirements"></textarea>
                </div>
                <div class="col-12 pt-2">
                    <button type="submit" class="btn btn-primary">Submit request</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
