@extends('Caregiver.layouts.app')

@section('content')
    <div class="card shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Patient Profile</h2>
            <a href="{{ route('caregiver.bookings') }}" class="btn btn-outline-secondary btn-sm">Back to Bookings</a>
        </div>

        <div class="row g-4">
            {{-- Profile picture and name --}}
            <div class="col-12 mb-3">
                <div class="d-flex align-items-center gap-3 p-3 rounded bg-light">
                    <div class="flex-shrink-0">
                        @if($patient->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($patient->profile_photo))
                            <img src="{{ asset('storage/' . $patient->profile_photo) }}" alt="Profile picture" class="rounded-circle border" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <img src="{{ $patient->user ? 'https://ui-avatars.com/api/?name=' . urlencode($patient->user->name) . '&size=80&background=0ea5e9&color=fff' : 'https://ui-avatars.com/api/?name=Patient&size=80&background=0ea5e9&color=fff' }}" alt="Profile" class="rounded-circle border" style="width: 80px; height: 80px; object-fit: cover;">
                        @endif
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ optional($patient->user)->name ?? 'N/A' }}</h5>
                        <p class="text-muted small mb-0">Patient</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h5 class="text-info mb-3">Basic Info</h5>
                    <p class="mb-2"><strong>Name:</strong> {{ optional($patient->user)->name ?? 'N/A' }}</p>
                    <p class="mb-2">
                        <strong>Age:</strong>
                        @if (!empty($patient->date_of_birth))
                            {{ \Carbon\Carbon::parse($patient->date_of_birth)->age }}
                        @else
                            -
                        @endif
                    </p>
                    <p class="mb-2"><strong>Gender:</strong> {{ $patient->gender ?? '-' }}</p>
                    <p class="mb-2"><strong>Contact:</strong>
                        {{ optional($patient->user)->contact_number ?? ($patient->contact_number ?? '-') }}</p>
                    <p class="mb-0"><strong>Address:</strong>
                        {{ $patient->address ?? (optional($patient->user)->address ?? '-') }}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <h5 class="text-success mb-3">Medical Info</h5>
                    <p class="mb-2"><strong>Health Condition:</strong> {{ $patient->health_condition ?? '-' }}</p>
                    <p class="mb-2"><strong>Medical History:</strong> {{ $patient->medical_history ?? '-' }}</p>
                    <p class="mb-0"><strong>Prescriptions:</strong> {{ $patient->prescriptions ?? '-' }}</p>
                </div>
            </div>
            @if($patient->healthReports && $patient->healthReports->isNotEmpty())
            <div class="col-12">
                <div class="border rounded p-3">
                    <h5 class="text-success mb-3"><i class="bi bi-file-earmark-medical me-2"></i>Health reports</h5>
                    <div class="row g-3">
                        @foreach($patient->healthReports as $report)
                            @php $ext = strtolower(pathinfo($report->file_path, PATHINFO_EXTENSION)); @endphp
                            <div class="col-12">
                                <p class="small fw-semibold text-muted mb-2">{{ $report->original_name ?: 'Health report' }}</p>
                                @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                    <img src="{{ route('caregiver.patient.health-report.view', [$patient, $report]) }}" alt="Health report" class="img-fluid rounded border" style="max-width: 100%; max-height: 500px; object-fit: contain;">
                                @else
                                    <iframe src="{{ route('caregiver.patient.health-report.view', [$patient, $report]) }}" class="w-100 rounded border" style="height: 500px;" title="{{ $report->original_name ?: 'Health report' }}"></iframe>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
