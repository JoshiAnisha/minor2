@extends('backend.layouts.dashboard.app')

@section('title', 'Custom Request')

@section('content')
<div class="mb-3">
    <a href="{{ route('patient.services.index') }}" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Back to Services</a>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h2 class="h5 fw-bold mb-1">Custom service request</h2>
        <p class="text-muted small mb-0">Describe what you need. Caregivers can respond with an offer.</p>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        @if (session('error'))
            <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger py-2 small">
                @foreach ($errors->all() as $e)
                    <span class="d-block">{{ $e }}</span>
                @endforeach
            </div>
        @endif

        <form action="{{ route('patient.services.book') }}" method="POST">
            @csrf
            <input type="hidden" name="service_id" value="{{ $service->id }}">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-medium">What do you need?</label>
                    <textarea name="description" class="form-control form-control-sm @error('description') is-invalid @enderror" rows="3" required placeholder="e.g. Post-surgery care, 2 hours daily for one week">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-medium">Start date</label>
                    <input type="date" name="start_date" class="form-control form-control-sm" required min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-medium">End date</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" required min="{{ date('Y-m-d') }}" value="{{ old('end_date') }}">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-medium">Address</label>
                    <input type="text" name="location" class="form-control form-control-sm" required placeholder="Where care is needed" value="{{ old('location') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-medium">Shift</label>
                    <select name="shift_type" class="form-select form-select-sm" required>
                        <option value="" disabled selected>Select</option>
                        <option value="morning" {{ old('shift_type') == 'morning' ? 'selected' : '' }}>Morning</option>
                        <option value="day" {{ old('shift_type') == 'day' ? 'selected' : '' }}>Day</option>
                        <option value="night" {{ old('shift_type') == 'night' ? 'selected' : '' }}>Night</option>
                        <option value="both" {{ old('shift_type') == 'both' ? 'selected' : '' }}>Both</option>
                    </select>
                </div>
                <div class="col-12 pt-2">
                    <button type="submit" class="btn btn-primary">Submit request</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
