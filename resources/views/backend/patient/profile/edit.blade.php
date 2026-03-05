@extends('backend.layouts.dashboard.app')

@section('title', 'Edit Profile')

@section('content')
<div class="mb-3">
    <a href="{{ route('patient.profile.show') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center">
        <i class="bi bi-arrow-left me-1"></i> Back to profile
    </a>
</div>

<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">Edit Profile</h1>
    <p class="text-muted small mb-0">Update your personal and medical details</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0 small">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('patient.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <input type="file" name="profile_photo" id="fileInput" accept="image/*" class="d-none" onchange="document.getElementById('profilePreview').src = window.URL.createObjectURL(this.files[0])">
                    <label for="fileInput" class="profile-edit-avatar mb-0 cursor-pointer">
                        <img id="profilePreview"
                             src="{{ $patient && $patient->profile_photo ? asset('storage/' . $patient->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=96&background=0ea5e9&color=fff' }}"
                             alt="Profile">
                        <span class="profile-edit-avatar-badge"><i class="bi bi-camera"></i></span>
                    </label>
                </div>
                <div class="col">
                    <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                    <p class="text-muted small mb-0">#SC-{{ $user->id }}</p>
                    <p class="text-muted small mb-0 mt-1">Click photo to change</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-person me-2 text-primary"></i>Personal</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-medium">Full name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" placeholder="Your name">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-medium">Email</label>
                    <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-medium">Phone</label>
                    <input type="text" class="form-control" name="contact_number" value="{{ old('contact_number', $user->contact_number ?? optional($patient)->contact_number ?? '') }}" placeholder="Contact number">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-medium">Date of birth</label>
                    <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth', optional($patient)->date_of_birth?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-medium">Gender</label>
                    <select class="form-select" name="gender">
                        <option value="">Select</option>
                        <option value="male" {{ old('gender', optional($patient)->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', optional($patient)->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', optional($patient)->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-medium">Blood group</label>
                    <input type="text" class="form-control" name="blood_group" value="{{ old('blood_group', optional($patient)->blood_group ?? '') }}" placeholder="e.g. O+">
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i>Address</h5>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-medium">Address</label>
                    <input type="text" class="form-control" name="address" value="{{ old('address', optional($patient)->address ?? '') }}" placeholder="Street address">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-medium">City</label>
                    <input type="text" class="form-control" name="city" value="{{ old('city', optional($patient)->city ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-medium">State</label>
                    <input type="text" class="form-control" name="state" value="{{ old('state', optional($patient)->state ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-medium">Postal code</label>
                    <input type="text" class="form-control" name="postal_code" value="{{ old('postal_code', optional($patient)->postal_code ?? '') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-telephone me-2 text-primary"></i>Emergency contact</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-medium">Name</label>
                    <input type="text" class="form-control" name="emergency_contact_name" value="{{ old('emergency_contact_name', optional($patient)->emergency_contact_name ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-medium">Number</label>
                    <input type="text" class="form-control" name="emergency_contact_number" value="{{ old('emergency_contact_number', optional($patient)->emergency_contact_number ?? '') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-heart-pulse me-2 text-primary"></i>Medical</h5>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-medium">Medical notes</label>
                    <textarea class="form-control" name="medical_history" rows="3" placeholder="Relevant medical history">{{ old('medical_history', optional($patient)->medical_history ?? '') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-medium">Allergies</label>
                    <input type="text" class="form-control" name="allergies" value="{{ old('allergies', optional($patient)->allergies ?? '') }}" placeholder="Known allergies, if any">
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-2"></i>Save changes</button>
        <a href="{{ route('patient.profile.show') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<style>
    .profile-edit-avatar { position: relative; display: inline-block; }
    .profile-edit-avatar img { width: 96px; height: 96px; border-radius: 50%; object-fit: cover; border: 3px solid #e0f2fe; }
    .profile-edit-avatar-badge { position: absolute; bottom: 0; right: 0; width: 32px; height: 32px; background: #0ea5e9; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; border: 2px solid #fff; }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection
