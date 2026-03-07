@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid px-0">
        <div class="row g-0">
            <div class="col-12">
                <h2 class="mb-4">Edit Patient Details</h2>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
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
                    <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
