@extends('admin.layouts.app')

@section('title', 'Caregivers - Admin | SewaCare')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-12">
            <h2 class="mb-4">Caregiver Management</h2>

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

            <div class="row g-4 mb-4">
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #0dcaf0 !important;">
                        <h6 class="text-muted mb-1">Total</h6>
                        <h4 class="mb-0">{{ $totalCaregivers }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #198754 !important;">
                        <h6 class="text-muted mb-1">Active</h6>
                        <h4 class="mb-0">{{ $activeCaregivers }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #ffc107 !important;">
                        <h6 class="text-muted mb-1">Pending</h6>
                        <h4 class="mb-0">{{ $pendingCaregivers }}</h4>
                    </div>
                </div>
            </div>

            <div class="card border shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Caregivers</h5>
                    <span class="badge bg-secondary">{{ $caregivers->count() }} caregiver(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($caregivers as $index => $caregiver)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.caregivers.show', $caregiver) }}" class="text-decoration-none fw-semibold">{{ $caregiver->user?->name ?? 'N/A' }}</a>
                                        </td>
                                        <td>{{ $caregiver->user?->contact_number ?? $caregiver->user?->email ?? '—' }}</td>
                                        <td>
                                            @if ($caregiver->availability_status)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ route('admin.caregivers.edit', $caregiver) }}" class="btn btn-sm btn-info">Edit</a>
                                                <form action="{{ route('admin.caregivers.toggle-status', $caregiver) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-warning">
                                                        {{ $caregiver->availability_status ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.caregivers.destroy', $caregiver) }}" method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this caregiver?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No caregivers found</td>
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
