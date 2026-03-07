@extends('backend.layouts.dashboard.app')

@section('title', 'Profile')

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">My Profile</h1>
    <p class="text-muted small mb-0">View and manage your profile details</p>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm profile-card" style="border-radius: 20px; overflow: hidden;">
    <div class="profile-cover"></div>
    <div class="card-body p-0">
        <div class="profile-header px-4 pb-4">
            <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start gap-3">
                <div class="profile-avatar flex-shrink-0">
                    <img src="{{ $patient && $patient->profile_photo ? asset('storage/' . $patient->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=128&background=0ea5e9&color=fff' }}"
                         alt="Profile">
                </div>
                <div class="text-center text-sm-start">
                    <h2 class="h5 fw-bold mb-1">{{ $user->name }}</h2>
                    <p class="text-muted small mb-0">#SC-{{ $user->id }}</p>
                    @if ($patient && $patient->verified_status)
                        <span class="badge bg-success mt-2"><i class="bi bi-patch-check-fill me-1"></i> Verified</span>
                    @endif
                    <a href="{{ route('patient.profile.edit') }}" class="btn btn-primary btn-sm mt-3">
                        <i class="bi bi-pencil me-1"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <div class="px-4 pb-4">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="profile-section rounded-3 p-4 h-100">
                        <h5 class="profile-section-title mb-3"><i class="bi bi-person-circle me-2"></i>Personal</h5>
                        <div class="profile-detail"><span class="label">Name</span><span class="value">{{ $user->name ?? '—' }}</span></div>
                        <div class="profile-detail"><span class="label">Email</span><span class="value">{{ $user->email ?? '—' }}</span></div>
                        <div class="profile-detail"><span class="label">Phone</span><span class="value">{{ $user->contact_number ?? optional($patient)->contact_number ?? '—' }}</span></div>
                        <div class="profile-detail"><span class="label">Date of birth</span><span class="value">{{ optional(optional($patient)->date_of_birth)->format('d M Y') ?? '—' }}</span></div>
                        <div class="profile-detail"><span class="label">Gender</span><span class="value">{{ $patient ? ucfirst($patient->gender ?? '—') : '—' }}</span></div>
                        <div class="profile-detail mb-0"><span class="label">Blood group</span><span class="value">{{ optional($patient)->blood_group ?? '—' }}</span></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="profile-section rounded-3 p-4 h-100">
                        <h5 class="profile-section-title mb-3"><i class="bi bi-geo-alt me-2"></i>Address</h5>
                        <div class="profile-detail"><span class="label">Address</span><span class="value">{{ optional($patient)->address ?? '—' }}</span></div>
                        <div class="profile-detail"><span class="label">City</span><span class="value">{{ optional($patient)->city ?? '—' }}</span></div>
                        <div class="profile-detail"><span class="label">State</span><span class="value">{{ optional($patient)->state ?? '—' }}</span></div>
                        <div class="profile-detail mb-0"><span class="label">Postal code</span><span class="value">{{ optional($patient)->postal_code ?? '—' }}</span></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="profile-section rounded-3 p-4 h-100">
                        <h5 class="profile-section-title mb-3"><i class="bi bi-telephone me-2"></i>Emergency contact</h5>
                        <div class="profile-detail"><span class="label">Name</span><span class="value">{{ optional($patient)->emergency_contact_name ?? '—' }}</span></div>
                        <div class="profile-detail mb-0"><span class="label">Number</span><span class="value">{{ optional($patient)->emergency_contact_number ?? '—' }}</span></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="profile-section rounded-3 p-4 h-100">
                        <h5 class="profile-section-title mb-3"><i class="bi bi-heart-pulse me-2"></i>Medical</h5>
                        <div class="profile-detail"><span class="label">Medical notes</span><span class="value">{{ optional($patient)->medical_history ?: '—' }}</span></div>
                        <div class="profile-detail mb-0"><span class="label">Allergies</span><span class="value">{{ optional($patient)->allergies ?: '—' }}</span></div>
                    </div>
                </div>
            </div>

            {{-- Reviews section --}}
            <div class="mt-4">
                <div class="profile-section rounded-3 p-4">
                    <h5 class="profile-section-title mb-3"><i class="bi bi-star me-2"></i>My Reviews</h5>
                    @if(isset($reviews) && $reviews->isNotEmpty())
                        <div class="list-group list-group-flush">
                            @foreach($reviews as $review)
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                    <div>
                                        <span class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                            @endfor
                                        </span>
                                        <span class="fw-medium ms-2">{{ $review->service->name ?? 'Service' }}</span>
                                        <p class="mb-0 mt-1 small text-muted">{{ $review->comment }}</p>
                                    </div>
                                    <small class="text-muted">{{ optional($review->created_at)->format('d M Y') }}</small>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('patient.reviews.index') }}" class="btn btn-outline-primary btn-sm mt-3">View all reviews</a>
                    @else
                        <p class="text-muted mb-0">You haven’t written any reviews yet.</p>
                        <a href="{{ route('patient.reviews.create') }}" class="btn btn-primary btn-sm mt-2">Write a review</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-cover { height: 80px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .profile-header { margin-top: -48px; position: relative; z-index: 1; }
    .profile-avatar { width: 96px; height: 96px; border-radius: 50%; overflow: hidden; border: 4px solid #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .profile-section { background: #f8fafc; border: 1px solid #f1f5f9; }
    .profile-section-title { font-size: 0.95rem; font-weight: 600; color: #1e293b; }
    .profile-detail { display: flex; flex-direction: column; gap: 0.15rem; padding-bottom: 0.75rem; }
    .profile-detail:last-child { padding-bottom: 0; }
    .profile-detail .label { font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.02em; }
    .profile-detail .value { font-size: 0.9rem; font-weight: 500; color: #1e293b; }
</style>
@endsection
