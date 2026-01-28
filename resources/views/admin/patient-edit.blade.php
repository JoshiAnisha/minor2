@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <div class="mb-4">
                    <a href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('frontend/images/logo.png') }}" alt="SewaCare Logo" class="img-fluid logo-img">
                    </a>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a class="nav-link" href="{{ route('admin.appointment') }}">Appointments</a>
                    <a class="nav-link active" href="{{ route('admin.patient') }}">Patients</a>
                    <a class="nav-link" href="{{ route('admin.caregiver') }}">Caregivers</a>
                    <a class="nav-link" href="{{ route('admin.feedback') }}">Feedback</a>
                </nav>
            </div>

            <div class="col-md-9 col-lg-10 p-4">
                <h2 class="mb-4">Edit Patient Details</h2>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.patient.update', $patient->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="medical_history" class="form-label">Medical History</label>
                        <textarea name="medical_history" id="medical_history" class="form-control" rows="3">{{ old('medical_history', $patient->medical_history) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="prescriptions" class="form-label">Prescriptions</label>
                        <textarea name="prescriptions" id="prescriptions" class="form-control" rows="3">{{ old('prescriptions', $patient->prescriptions) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="health_condition" class="form-label">Health Condition</label>
                        <textarea name="health_condition" id="health_condition" class="form-control" rows="3">{{ old('health_condition', $patient->health_condition) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Patient</button>
                    <a href="{{ route('admin.patient') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
