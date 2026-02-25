@extends('admin.layouts.app')

@section('title', 'Assigned Services')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="bi bi-journal-plus me-2"></i>Assigned Services</h2>
                <p class="text-muted small mb-0">Manage services assigned to patients</p>
            </div>
            <a href="{{ route('admin.assigned-services.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Assign Service to Patient
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                @if ($assignedServices->isEmpty())
                    <div class="text-center py-5 px-4">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-2">No assigned services yet.</p>
                        <p class="text-muted small mb-4">Assign a service to a patient to get started.</p>
                        <a href="{{ route('admin.assigned-services.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> Assign First Service
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 py-3">Patient</th>
                                    <th class="border-0 py-3">Service</th>
                                    <th class="border-0 py-3">Title</th>
                                    <th class="border-0 py-3">Date</th>
                                    <th class="border-0 py-3">Budget</th>
                                    <th class="border-0 py-3 text-center">Bids</th>
                                    <th class="border-0 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignedServices as $as)
                                    <tr>
                                        <td>
                                            <span class="fw-medium">{{ optional($as->patient)->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ $as->service->name ?? '-' }}</td>
                                        <td>{{ Str::limit($as->title, 40) }}</td>
                                        <td>{{ optional($as->preferred_date)?->format('d M Y') ?? '-' }}</td>
                                        <td>{{ $as->budget ? 'Rs ' . number_format($as->budget, 2) : '-' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">{{ $as->bids->count() }}</span>
                                        </td>
                                        <td>
                                            @if ($as->status === 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif ($as->status === 'accepted')
                                                <span class="badge bg-success">Accepted</span>
                                                @if ($as->assignedCaregiver)
                                                    <br><small class="text-muted">{{ optional($as->assignedCaregiver?->user)->name ?? '' }}</small>
                                                @endif
                                            @elseif ($as->status === 'completed')
                                                <span class="badge bg-secondary">Completed</span>
                                            @else
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
