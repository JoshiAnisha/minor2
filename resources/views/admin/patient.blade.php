@extends('admin.layouts.app')

@section('content')
    <div class="p-4">
        <h2 class="mb-4">Manage Patients</h2>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card card-summary shadow-sm p-3">
                    <h6>Total Patients</h6>
                    <h4>{{ $totalPatients }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-summary shadow-sm p-3">
                    <h6>Active Patients</h6>
                    <h4>{{ $activePatients }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-summary shadow-sm p-3">
                    <h6>Pending Patients</h6>
                    <h4>{{ $pendingPatients }}</h4>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>SN</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Medical History</th>
                        <th>Prescriptions</th>
                        <th>Health Condition</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patients as $index => $patient)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $patient->user?->name ?? 'No user assigned' }}</td>
                            <td>{{ $patient->user?->email ?? '-' }}</td>
                            <td>{{ $patient->user?->contact_number ?? '-' }}</td>
                            <td>
                                @if ($patient->user?->role === 'patient')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-warning">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $patient->medical_history ?? '-' }}</td>
                            <td>{{ $patient->prescriptions ?? '-' }}</td>
                            <td>{{ $patient->health_condition ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.patient.edit', $patient->id) }}"
                                        class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('admin.patient.destroy', $patient->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
