@extends('admin.layouts.app')

@section('title', 'Assign Service to Patient')

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.assigned-services.index') }}">Assigned Services</a></li>
                <li class="breadcrumb-item active">Assign New</li>
            </ol>
        </nav>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>Assign Service to Patient</h5>
            </div>
            <div class="card-body p-4">
                @if ($patients->isEmpty())
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        No patients found. Please add patients before assigning services.
                    </div>
                    <a href="{{ route('admin.assigned-services.index') }}" class="btn btn-secondary">Back</a>
                @elseif ($services->isEmpty())
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        No services found. Please add services before assigning.
                    </div>
                    <a href="{{ route('admin.assigned-services.index') }}" class="btn btn-secondary">Back</a>
                @else
                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-start" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                        <div>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.assigned-services.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patient_id" class="form-select" required>
                                <option value="">Select patient</option>
                                @foreach ($patients as $p)
                                    <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->user?->name ?? 'Patient #' . $p->id }} ({{ $p->user?->email ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_id" class="form-label">Service Type <span class="text-danger">*</span></label>
                            <select name="service_id" id="service_id" class="form-select" required>
                                <option value="">Select service</option>
                                @foreach ($services as $s)
                                    <option value="{{ $s->id }}" {{ old('service_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }} (Rs {{ number_format($s->base_price ?? 0, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g. Home Care - Week of March">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Service details, special requirements...">{{ old('description') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="preferred_date" class="form-label">Preferred Date <span class="text-danger">*</span></label>
                            <input type="date" name="preferred_date" id="preferred_date" class="form-control" value="{{ old('preferred_date') }}" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="budget" class="form-label">Budget (Rs)</label>
                            <input type="number" name="budget" id="budget" class="form-control" value="{{ old('budget') }}" step="0.01" min="0" placeholder="Optional">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}" placeholder="Patient address">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Assign Service
                        </button>
                        <a href="{{ route('admin.assigned-services.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
@endsection
