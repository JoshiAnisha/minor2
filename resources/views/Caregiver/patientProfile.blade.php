@extends('caregiver.layouts.app')

@section('content')
    <div class="card shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Patient Profile</h2>
            <a href="{{ route('caregiver.bookings') }}" class="btn btn-outline-secondary btn-sm">Back to Bookings</a>
        </div>

        <div class="row g-4">
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
        </div>
    </div>
@endsection
