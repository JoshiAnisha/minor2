@extends('backend.layouts.dashboard.app')

@section('title', 'Profile')

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
        <div class="card shadow-sm p-4 mb-4">
            <h3 class="text-info mb-4">My Profile</h3>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="profile-photo-box">
                        <img src="{{ $patient && $patient->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($patient->profile_photo) ? asset('storage/' . $patient->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=128&background=0ea5e9&color=fff' }}" alt="Profile picture">
                    </div>
                    <h5 class="mt-3 mb-1 fw-semibold">{{ $user->name }}</h5>
                    <p class="text-muted small mb-0">#SC-{{ $user->id }}</p>
                    @if ($patient && $patient->verified_status)
                        <span class="badge bg-success mt-2"><i class="bi bi-patch-check-fill me-1"></i> Verified</span>
                    @endif
                    <a href="{{ route('patient.profile.edit') }}" class="btn btn-info btn-sm mt-3">
                        <i class="bi bi-pencil me-1"></i> Edit Profile
                    </a>
                </div>

                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h5 class="text-info mb-3">Personal</h5>
                                <p class="mb-2"><strong>Name:</strong> {{ $user->name ?? '—' }}</p>
                                <p class="mb-2"><strong>Email:</strong> {{ $user->email ?? '—' }}</p>
                                <p class="mb-2"><strong>Phone:</strong> {{ $user->contact_number ?? optional($patient)->contact_number ?? '—' }}</p>
                                <p class="mb-2"><strong>Date of birth:</strong> {{ optional(optional($patient)->date_of_birth)->format('d M Y') ?? '—' }}</p>
                                <p class="mb-2"><strong>Gender:</strong> {{ $patient ? ucfirst($patient->gender ?? '—') : '—' }}</p>
                                <p class="mb-0"><strong>Blood group:</strong> {{ optional($patient)->blood_group ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h5 class="text-info mb-3">Address</h5>
                                <p class="mb-2"><strong>Address:</strong> {{ optional($patient)->address ?? '—' }}</p>
                                <p class="mb-2"><strong>City:</strong> {{ optional($patient)->city ?? '—' }}</p>
                                <p class="mb-2"><strong>State:</strong> {{ optional($patient)->state ?? '—' }}</p>
                                <p class="mb-0"><strong>Postal code:</strong> {{ optional($patient)->postal_code ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h5 class="text-info mb-3">Emergency contact</h5>
                                <p class="mb-2"><strong>Name:</strong> {{ optional($patient)->emergency_contact_name ?? '—' }}</p>
                                <p class="mb-0"><strong>Number:</strong> {{ optional($patient)->emergency_contact_number ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h5 class="text-info mb-3">Medical</h5>
                                <p class="mb-2"><strong>Medical notes:</strong> {{ optional($patient)->medical_history ?: '—' }}</p>
                                <p class="mb-0"><strong>Allergies:</strong> {{ optional($patient)->allergies ?: '—' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reviews from caregivers (about you / your bookings) --}}
        <div class="card shadow-sm p-4 mb-4">
            <h5 class="fw-bold mb-3 text-info"><i class="bi bi-chat-heart me-2"></i>Reviews from caregivers</h5>
            @if(isset($reviewsFromCaregivers) && $reviewsFromCaregivers->isNotEmpty())
                <div class="list-group list-group-flush">
                    @foreach($reviewsFromCaregivers as $review)
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                            <div>
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </span>
                                <span class="fw-medium ms-2">{{ optional($review->user)->name ?? 'Caregiver' }}</span>
                                @if($review->service)
                                    <span class="text-muted small"> · {{ $review->service->name }}</span>
                                @endif
                                <p class="mb-0 mt-1 small text-muted">{{ $review->comment ?? $review->comments ?? '' }}</p>
                            </div>
                            <small class="text-muted">{{ optional($review->created_at)->format('d M Y') }}</small>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">No reviews from caregivers yet. Complete a booking and caregivers may leave you a review.</p>
            @endif
        </div>

        {{-- My reviews (only reviews given by this patient, with edit/delete) --}}
        <div class="card shadow-sm p-4">
            <h5 class="fw-bold mb-3 text-info"><i class="bi bi-star me-2"></i>My reviews</h5>
            @if(isset($myReviews) && $myReviews->isNotEmpty())
                <div class="list-group list-group-flush">
                    @foreach($myReviews as $review)
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="flex-grow-1">
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </span>
                                <span class="fw-medium ms-2">{{ $review->service->name ?? 'Service' }}</span>
                                <p class="mb-0 mt-1 small text-muted">{{ $review->comment ?? $review->comments ?? '' }}</p>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <small class="text-muted me-2">{{ optional($review->created_at)->format('d M Y') }}</small>
                                <a href="{{ route('patient.reviews.edit', $review) }}" class="btn btn-outline-info btn-sm">Edit</a>
                                <form action="{{ route('patient.reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('patient.reviews.index') }}" class="btn btn-outline-info btn-sm mt-3">View all my reviews</a>
            @else
                <p class="text-muted mb-0">You haven’t written any reviews yet.</p>
            @endif
        </div>
    </div>
@endsection
