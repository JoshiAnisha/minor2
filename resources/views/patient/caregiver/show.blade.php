@extends('patient.layouts.app')

@section('title', 'Caregiver Profile')

@section('content')
<div class="mb-4">
    <a href="{{ route('patient.service-requests.index') }}" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left me-1"></i> Back to My Requests</a>
</div>

{{-- Profile header with photo and basic info --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <div class="row align-items-center g-4">
            <div class="col-auto">
                <img src="{{ route('patient.caregiver.photo', $caregiver) }}" alt="Profile" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
            </div>
            <div class="col">
                <h4 class="fw-bold mb-1">{{ optional($caregiver->user)->name ?? 'N/A' }}</h4>
                <p class="text-muted mb-1">{{ optional($caregiver->user)->email ?? '-' }}</p>
                <p class="mb-0"><strong>Contact:</strong> {{ optional($caregiver->user)->contact_number ?? ($caregiver->contact_number ?? '-') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Full profile details --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4"><i class="bi bi-person-vcard me-2"></i>Profile Details</h5>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="text-primary mb-3">Basic Info</h6>
                    <p class="mb-2"><strong>Name:</strong> {{ optional($caregiver->user)->name ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Email:</strong> {{ optional($caregiver->user)->email ?? '-' }}</p>
                    <p class="mb-2"><strong>Contact:</strong> {{ optional($caregiver->user)->contact_number ?? ($caregiver->contact_number ?? '-') }}</p>
                    <p class="mb-2"><strong>Address:</strong> {{ $caregiver->address ?? '-' }}</p>
                    <p class="mb-2"><strong>Type:</strong> {{ $caregiver->caregiver_type ? ucfirst(str_replace('_', ' ', $caregiver->caregiver_type)) : '-' }}</p>
                    <p class="mb-0"><strong>Available for service:</strong> {{ !empty($caregiver->availability_status) ? 'Yes' : 'No' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="text-success mb-3">Professional Info</h6>
                    <p class="mb-2"><strong>Qualification:</strong> {{ $caregiver->qualification ?? '-' }}</p>
                    <p class="mb-2"><strong>Experience:</strong> {{ $caregiver->experience ?? '-' }}</p>
                    <p class="mb-2"><strong>Skills:</strong> {{ $caregiver->skills ?? '-' }}</p>
                    <p class="mb-2"><strong>Field:</strong> {{ $caregiver->field ?? '-' }}</p>
                    <p class="mb-0"><strong>Bio:</strong> {{ $caregiver->bio ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Certificate --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-award me-2"></i>Certificate</h5>
        @if($caregiver->certificate_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($caregiver->certificate_path))
            <a href="{{ route('patient.caregiver.certificate', $caregiver) }}" target="_blank" class="btn btn-outline-primary">
                <i class="bi bi-file-earmark-pdf me-1"></i> View Certificate
            </a>
            @php $ext = pathinfo($caregiver->certificate_path, PATHINFO_EXTENSION); @endphp
            @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                <div class="mt-3">
                    <img src="{{ asset('storage/' . $caregiver->certificate_path) }}" alt="Certificate" class="img-fluid rounded border" style="max-width: 400px;">
                </div>
            @endif
        @else
            <p class="text-muted mb-0">No certificate uploaded.</p>
        @endif
    </div>
</div>

{{-- Reviews from patients --}}
<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4"><i class="bi bi-star me-2"></i>Reviews</h5>
        @if($reviews->isEmpty())
            <p class="text-muted mb-0">No reviews yet.</p>
        @else
            <div class="row g-3">
                @foreach($reviews as $review)
                    <div class="col-12">
                        <div class="border rounded p-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= (int) $review->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </span>
                                <small class="text-muted">
                                    {{ optional($review->booking->patient->user)->name ?? 'Patient' }}
                                </small>
                            </div>
                            @if(!empty($review->comments))
                                <p class="mb-0">{{ $review->comments }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
