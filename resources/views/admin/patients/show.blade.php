@extends('admin.layouts.app')

@section('title', 'Patient Profile | Admin | SewaCare')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Patient Profile</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-outline-primary btn-sm">Edit</a>
            <a href="{{ route('admin.patients.index') }}" class="btn btn-outline-secondary btn-sm">Back to list</a>
        </div>
    </div>

    <div class="row">
        {{-- Profile photo & quick info --}}
        <div class="col-lg-4 col-md-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($patient->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($patient->profile_photo))
                            <img src="{{ asset('storage/' . $patient->profile_photo) }}" alt="Profile" class="rounded-circle img-thumbnail" style="width: 160px; height: 160px; object-fit: cover;">
                        @else
                            <img src="{{ $patient->user ? 'https://ui-avatars.com/api/?name=' . urlencode($patient->user->name) . '&size=160&background=0ea5e9&color=fff' : 'https://ui-avatars.com/api/?name=Patient&size=160&background=0ea5e9&color=fff' }}" alt="Profile" class="rounded-circle img-thumbnail" style="width: 160px; height: 160px; object-fit: cover;">
                        @endif
                    </div>
                    <h5 class="mb-1">{{ $patient->user?->name ?? '—' }}</h5>
                    <p class="text-muted small mb-0">{{ $patient->user?->email ?? $patient->email ?? '—' }}</p>
                    <p class="mt-2 mb-0">
                        @if ($patient->is_active ?? true)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Deactivated</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light py-2">
                    <h5 class="mb-0">Basic information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Name</p>
                            <p class="mb-0 fw-semibold">{{ $patient->user?->name ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Email</p>
                            <p class="mb-0">{{ $patient->user?->email ?? $patient->email ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Phone</p>
                            <p class="mb-0">{{ $patient->user?->contact_number ?? $patient->contact_number ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Date of birth</p>
                            <p class="mb-0">{{ $patient->date_of_birth ? $patient->date_of_birth->format('d M Y') : '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Gender</p>
                            <p class="mb-0">{{ $patient->gender ? ucfirst($patient->gender) : '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Blood group</p>
                            <p class="mb-0">{{ $patient->blood_group ?? '—' }}</p>
                        </div>
                        <div class="col-12">
                            <p class="text-muted small mb-1">Address</p>
                            <p class="mb-0">{{ $patient->address ?? '—' }}</p>
                        </div>
                        @if($patient->city || $patient->state || $patient->postal_code)
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">City</p>
                            <p class="mb-0">{{ $patient->city ?? '—' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">State</p>
                            <p class="mb-0">{{ $patient->state ?? '—' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">Postal code</p>
                            <p class="mb-0">{{ $patient->postal_code ?? '—' }}</p>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Emergency contact</p>
                            <p class="mb-0">{{ $patient->emergency_contact_name ?? '—' }} @if($patient->emergency_contact_number)({{ $patient->emergency_contact_number }})@endif</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Insurance</p>
                            <p class="mb-0">{{ $patient->insurance_provider ?? '—' }} @if($patient->insurance_number)({{ $patient->insurance_number }})@endif</p>
                        </div>
                        @if($patient->allergies || $patient->disabilities || $patient->notes)
                        <div class="col-12">
                            @if($patient->allergies)<p class="mb-1"><span class="text-muted small">Allergies:</span> {{ $patient->allergies }}</p>@endif
                            @if($patient->disabilities)<p class="mb-1"><span class="text-muted small">Disabilities:</span> {{ $patient->disabilities }}</p>@endif
                            @if($patient->notes)<p class="mb-0"><span class="text-muted small">Notes:</span> {{ $patient->notes }}</p>@endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light py-2">
                    <h5 class="mb-0">Medical information / Reports</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Medical history</p>
                        <p class="mb-0">{{ $patient->medical_history ?: '—' }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Prescriptions</p>
                        <p class="mb-0">{{ $patient->prescriptions ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Health condition</p>
                        <p class="mb-0">{{ $patient->health_condition ?: '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
