@extends('patient.layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <style>
        .patient-profile-page .card {
            border-left: 5px solid #0dcaf0;
        }
        .patient-profile-page .profile-photo-box {
            width: 130px;
            height: 130px;
            background: #e9f7fc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            margin: 0 auto;
            cursor: pointer;
            overflow: hidden;
        }
        .patient-profile-page .profile-photo-box img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 3px solid white;
            border-radius: 50%;
        }
    </style>

    <div class="patient-profile-page">
        <div class="card shadow-sm p-4">
            <h3 class="text-info mb-4">Edit Patient Profile</h3>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
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

                <div class="row g-4">
                    <div class="col-md-4 text-center">
                        <input type="file" name="profile_photo" id="fileInput" accept="image/*" style="display: none;">
                        <label for="fileInput" class="profile-photo-box" title="Click to change photo">
                            <img id="profilePreview"
                                 src="{{ $patient && $patient->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($patient->profile_photo) ? asset('storage/' . $patient->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=96&background=0ea5e9&color=fff' }}"
                                 alt="Profile picture">
                        </label>
                        <h5 class="mt-3 mb-1 fw-semibold">{{ $user->name }}</h5>
                        <p class="text-muted small">#SC-{{ $user->id }}</p>
                        <p class="text-muted small">Click photo to upload (JPG, PNG, max 2MB)</p>
                    </div>

                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="contact_number" value="{{ old('contact_number', $user->contact_number ?? optional($patient)->contact_number ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of birth</label>
                                <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth', optional($patient)->date_of_birth?->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>
                                <select class="form-select" name="gender">
                                    <option value="">Select</option>
                                    <option value="male" {{ old('gender', optional($patient)->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', optional($patient)->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', optional($patient)->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Blood group</label>
                                <input type="text" class="form-control" name="blood_group" value="{{ old('blood_group', optional($patient)->blood_group ?? '') }}" placeholder="e.g. O+">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" value="{{ old('address', optional($patient)->address ?? '') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="city" value="{{ old('city', optional($patient)->city ?? '') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">State</label>
                                <input type="text" class="form-control" name="state" value="{{ old('state', optional($patient)->state ?? '') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Postal code</label>
                                <input type="text" class="form-control" name="postal_code" value="{{ old('postal_code', optional($patient)->postal_code ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Emergency contact name</label>
                                <input type="text" class="form-control" name="emergency_contact_name" value="{{ old('emergency_contact_name', optional($patient)->emergency_contact_name ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Emergency contact number</label>
                                <input type="text" class="form-control" name="emergency_contact_number" value="{{ old('emergency_contact_number', optional($patient)->emergency_contact_number ?? '') }}">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Medical notes</label>
                                <textarea class="form-control" name="medical_history" rows="3">{{ old('medical_history', optional($patient)->medical_history ?? '') }}</textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Allergies</label>
                                <input type="text" class="form-control" name="allergies" value="{{ old('allergies', optional($patient)->allergies ?? '') }}">
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-info px-4">Save Profile</button>
                                <a href="{{ route('patient.profile.show') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Health reports upload & list --}}
            @if ($patient)
            <div class="mt-4 pt-4 border-top">
                <h5 class="text-info mb-3"><i class="bi bi-file-earmark-medical me-2"></i>Health reports</h5>
                <p class="text-muted small mb-3">Upload PDF or image (JPG, PNG). Max 10MB per file. These will be visible to caregivers and admin when they view your profile.</p>
                <form action="{{ route('patient.profile.health-reports.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                    @csrf
                    <div class="d-flex flex-wrap gap-2 align-items-end">
                        <div class="flex-grow-1" style="min-width: 200px;">
                            <input type="file" name="health_report" id="health_report" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png" required>
                            @error('health_report')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-info btn-sm">Upload</button>
                    </div>
                </form>
                @if ($patient->healthReports && $patient->healthReports->isNotEmpty())
                    <div class="row g-3">
                        @foreach ($patient->healthReports as $report)
                            @php $ext = strtolower(pathinfo($report->file_path, PATHINFO_EXTENSION)); @endphp
                            <div class="col-12">
                                <div class="border rounded p-3">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                                        <span class="small fw-semibold text-muted">{{ $report->original_name ?: 'Health report' }}</span>
                                        <form action="{{ route('patient.profile.health-reports.destroy', $report) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this health report?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Remove</button>
                                        </form>
                                    </div>
                                    @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                        <img src="{{ route('patient.profile.health-reports.view', $report) }}" alt="Health report" class="img-fluid rounded border" style="max-width: 100%; max-height: 400px; object-fit: contain;">
                                    @else
                                        <iframe src="{{ route('patient.profile.health-reports.view', $report) }}" class="w-100 rounded border" style="height: 400px;" title="{{ $report->original_name ?: 'Health report' }}"></iframe>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small mb-0">No health reports uploaded yet.</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('fileInput').addEventListener('change', function(e) {
            if (e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('profilePreview').src = event.target.result;
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
@endsection
