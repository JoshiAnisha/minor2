@extends('backend.layouts.dashboard.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="main-content">
        <h3 class="page-title">Edit Patient Profile</h3>

        <form action="{{ route('patient.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="profile-card">
                <div class="row g-5">
                    <div class="col-md-4 text-center border-end">
                        <div class="profile-photo-wrapper mb-3">
                            <input type="file" name="profile_photo" id="fileInput" accept="image/*" style="display:none"
                                onchange="document.getElementById('profilePreview').src = window.URL.createObjectURL(this.files[0])">

                            <label for="fileInput" class="profile-photo-box" title="Click to change photo">
                                <img id="profilePreview"
                                    src="{{ $patient && $patient->profile_photo ? asset('storage/' . $patient->profile_photo) : 'https://ui-avatars.com/api/?name=' . $user->name }}"
                                    style="width:140px;height:140px;object-fit:cover;border-radius:50%;"
                                    alt="Profile Photo">
                            </label>
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
                        <h5 class="section-title">Personal Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name', $user->name) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="contact_number"
                                    value="{{ old('contact_number', $patient->contact_number ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address"
                                    value="{{ old('address', $patient->address ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" name="date_of_birth"
                                    value="{{ old('date_of_birth', $patient->date_of_birth?->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <select class="form-select" name="gender">
                                    <option value="male"
                                        {{ old('gender', $patient->gender ?? '') == 'male' ? 'selected' : '' }}>Male
                                    </option>
                                    <option value="female"
                                        {{ old('gender', $patient->gender ?? '') == 'female' ? 'selected' : '' }}>Female
                                    </option>
                                    <option value="other"
                                        {{ old('gender', $patient->gender ?? '') == 'other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Blood Group</label>
                                <input type="text" class="form-control" name="blood_group"
                                    value="{{ old('blood_group', $patient->blood_group ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Emergency Contact Name</label>
                                <input type="text" class="form-control" name="emergency_contact_name"
                                    value="{{ old('emergency_contact_name', $patient->emergency_contact_name ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Emergency Contact Number</label>
                                <input type="text" class="form-control" name="emergency_contact_number"
                                    value="{{ old('emergency_contact_number', $patient->emergency_contact_number ?? '') }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Medical Notes</label>
                                <textarea class="form-control" name="medical_history" rows="3">{{ old('medical_history', $patient->medical_history ?? '') }}</textarea>
                            </div>

                            <div class="col-12 text-end mt-2">
                                <button type="submit" class="btn btn-save">Update Profile</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
