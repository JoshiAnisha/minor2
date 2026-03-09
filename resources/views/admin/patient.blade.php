@extends('admin.layouts.app')

@section('title', 'Patients - Admin | SewaCare')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-12">
            <h2 class="mb-4">Manage Patients</h2>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2 small" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show py-2 small" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Summary cards (same style as Appointments / other admin pages) --}}
            <div class="row g-4 mb-4">
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #0dcaf0 !important;">
                        <h6 class="text-muted mb-1">Total</h6>
                        <h4 class="mb-0">{{ $totalPatients }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #198754 !important;">
                        <h6 class="text-muted mb-1">Active</h6>
                        <h4 class="mb-0">{{ $activePatients }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #ffc107 !important;">
                        <h6 class="text-muted mb-1">Pending</h6>
                        <h4 class="mb-0">{{ $pendingPatients }}</h4>
                    </div>
                </div>
            </div>

            <div class="card border shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Patients</h5>
                    <span class="badge bg-secondary">{{ $patients->count() }} patient(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($patients as $index => $patient)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.patients.show', $patient) }}" class="text-decoration-none fw-semibold">{{ $patient->user?->name ?? 'No user assigned' }}</a>
                                        </td>
                                        <td>{{ $patient->user?->email ?? '-' }}</td>
                                        <td>
                                            @if ($patient->is_active ?? true)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Deactivated</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                @if ($patient->is_active ?? true)
                                                    <form action="{{ route('admin.patients.toggle-active', $patient) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-warning"
                                                            onclick="return confirm('Deactivate this patient?')">Deactivate</button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.patients.toggle-active', $patient) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            onclick="return confirm('Activate this patient?')">Activate</button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('admin.patients.edit', $patient) }}"
                                                    class="btn btn-sm btn-info">Edit</a>
                                                <form action="{{ route('admin.patients.destroy', $patient) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No patients found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
