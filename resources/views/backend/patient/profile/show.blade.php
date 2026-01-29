@extends('backend.layouts.dashboard.app')

@section('title', 'Profile')

@section('content')
    <h3 class="mb-4">My Profile</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-4 shadow-sm">
        <div class="row g-5">
            <div class="col-md-4 text-center border-end">
                <div class="profile-photo-wrapper mb-3">
                    <div class="profile-photo-box"
                        style="width:140px;height:140px;margin:auto;border-radius:50%;overflow:hidden;">
                        <img src="{{ $patient && $patient->profile_photo ? asset('storage/' . $patient->profile_photo) : 'https://ui-avatars.com/api/?name=' . $user->name }}"
                            alt="Profile Photo" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    </div>
                </div>
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted small">ID: #SC-{{ $user->id }}</p>
                @if ($patient && $patient->verified_status)
                    <div class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill mt-2">
                        <i class="bi bi-patch-check-fill me-1"></i> Verified Patient
                    </div>
                @endif
            </div>

            <div class="col-md-8">
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Phone:</strong> {{ $patient->contact_number ?? '-' }}</p>
                <p><strong>Date of Birth:</strong>
                    {{ optional($patient?->date_of_birth)->format('Y-m-d') ?? '-' }}
                </p>
                <p><strong>Gender:</strong> {{ $patient->gender ?? '-' }}</p>
                <p><strong>Blood Group:</strong> {{ $patient->blood_group ?? '-' }}</p>
                <p><strong>Address:</strong> {{ $patient->address ?? '-' }}</p>
                <p><strong>City:</strong> {{ $patient->city ?? '-' }}</p>
                <p><strong>State:</strong> {{ $patient->state ?? '-' }}</p>
                <p><strong>Postal Code:</strong> {{ $patient->postal_code ?? '-' }}</p>
                <p><strong>Emergency Contact Name:</strong> {{ $patient->emergency_contact_name ?? '-' }}</p>
                <p><strong>Emergency Contact Number:</strong> {{ $patient->emergency_contact_number ?? '-' }}</p>
                <p><strong>Medical History:</strong> {{ $patient->medical_history ?? '-' }}</p>
                <p><strong>Allergies:</strong> {{ $patient->allergies ?? '-' }}</p>

                <a href="{{ route('patient.profile.edit') }}" class="btn btn-info mt-3">Edit Profile</a>
            </div>
        </div>
    </div>
@endsection
