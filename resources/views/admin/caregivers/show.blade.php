@extends('admin.layouts.app')

@section('title', 'Caregiver Profile | Admin | SewaCare')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Caregiver Profile</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.caregivers.edit', $caregiver) }}" class="btn btn-outline-primary btn-sm">Edit</a>
            <a href="{{ route('admin.caregivers.index') }}" class="btn btn-outline-secondary btn-sm">Back to list</a>
        </div>
    </div>

    <div class="row">
        {{-- Profile photo & quick info --}}
        <div class="col-lg-4 col-md-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($caregiver->profile_photo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($caregiver->profile_photo_path))
                            <img src="{{ asset('storage/' . $caregiver->profile_photo_path) }}" alt="Profile" class="rounded-circle img-thumbnail" style="width: 160px; height: 160px; object-fit: cover;">
                        @else
                            <img src="{{ $caregiver->user ? 'https://ui-avatars.com/api/?name=' . urlencode($caregiver->user->name) . '&size=160&background=0ea5e9&color=fff' : 'https://ui-avatars.com/api/?name=Caregiver&size=160&background=0ea5e9&color=fff' }}" alt="Profile" class="rounded-circle img-thumbnail" style="width: 160px; height: 160px; object-fit: cover;">
                        @endif
                    </div>
                    <h5 class="mb-1">{{ $caregiver->user?->name ?? 'N/A' }}</h5>
                    <p class="text-muted small mb-0">{{ $caregiver->user?->email ?? '—' }}</p>
                    <p class="mt-2 mb-0">
                        @if ($caregiver->availability_status)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending / Inactive</span>
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
                            <p class="mb-0 fw-semibold">{{ $caregiver->user?->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Email</p>
                            <p class="mb-0">{{ $caregiver->user?->email ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Contact</p>
                            <p class="mb-0">{{ $caregiver->user?->contact_number ?? $caregiver->contact_number ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Type</p>
                            <p class="mb-0">{{ $caregiver->caregiver_type ? ucfirst($caregiver->caregiver_type) : '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Qualification</p>
                            <p class="mb-0">{{ $caregiver->qualification ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Experience</p>
                            <p class="mb-0">{{ $caregiver->experience ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Rating</p>
                            <p class="mb-0">{{ $caregiver->rating ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Background check</p>
                            <p class="mb-0">{{ $caregiver->background_check_status ? 'Passed' : 'Pending' }}</p>
                        </div>
                        <div class="col-12">
                            <p class="text-muted small mb-1">Address</p>
                            <p class="mb-0">{{ $caregiver->address ?? '—' }}</p>
                        </div>
                        <div class="col-12">
                            <p class="text-muted small mb-1">Field</p>
                            <p class="mb-0">{{ $caregiver->field ?? '—' }}</p>
                        </div>
                        <div class="col-12">
                            <p class="text-muted small mb-1">Skills</p>
                            <p class="mb-0">{{ $caregiver->skills ?? '—' }}</p>
                        </div>
                        <div class="col-12">
                            <p class="text-muted small mb-1">Bio</p>
                            <p class="mb-0">{{ $caregiver->bio ?: '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Certificate / Documents --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light py-2">
                    <h5 class="mb-0">Certificate / Documents</h5>
                </div>
                <div class="card-body">
                    @if($caregiver->certificate_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($caregiver->certificate_path))
                        @php $certExt = strtolower(pathinfo($caregiver->certificate_path, PATHINFO_EXTENSION)); @endphp
                        <a href="{{ asset('storage/' . $caregiver->certificate_path) }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm mb-3">
                            <i class="bi bi-download me-1"></i> Open / Download
                        </a>
                        @if(in_array($certExt, ['jpg', 'jpeg', 'png']))
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $caregiver->certificate_path) }}" alt="Certificate" class="img-fluid rounded border" style="max-width: 100%; max-height: 500px; object-fit: contain;">
                            </div>
                        @elseif($certExt == 'pdf')
                            <div class="mt-2 border rounded overflow-hidden" style="height: 500px;">
                                <iframe src="{{ asset('storage/' . $caregiver->certificate_path) }}" class="w-100 h-100" title="Certificate"></iframe>
                            </div>
                        @else
                            <p class="text-muted small mb-0">Document uploaded ({{ strtoupper($certExt) }}). Use the button above to open or download.</p>
                        @endif
                    @else
                        <p class="text-muted mb-0">No certificate or document uploaded.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
