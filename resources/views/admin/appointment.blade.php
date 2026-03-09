@extends('admin.layouts.app')

@section('title', 'Appointments (Service Requests) - Admin | SewaCare')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-12">
            <h2 class="mb-4">Appointments (Service Requests)</h2>

            {{-- Summary cards --}}
            <div class="row g-4 mb-4">
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #0dcaf0 !important;">
                        <h6 class="text-muted mb-1">Total</h6>
                        <h4 class="mb-0">{{ $total }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #ffc107 !important;">
                        <h6 class="text-muted mb-1">Pending</h6>
                        <h4 class="mb-0">{{ $pending }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #0d6efd !important;">
                        <h6 class="text-muted mb-1">Accepted</h6>
                        <h4 class="mb-0">{{ $accepted }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #198754 !important;">
                        <h6 class="text-muted mb-1">Completed</h6>
                        <h4 class="mb-0">{{ $completed }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #dc3545 !important;">
                        <h6 class="text-muted mb-1">Rejected</h6>
                        <h4 class="mb-0">{{ $rejected }}</h4>
                    </div>
                </div>
            </div>

            {{-- Service requests grouped by category --}}
            @forelse ($byCategory as $categoryName => $requests)
            <div class="card border shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $categoryName }}</h5>
                    <span class="badge bg-secondary">{{ $requests->count() }} request(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>SN</th>
                                    <th>Patient</th>
                                    <th>Service</th>
                                    <th>Location</th>
                                    <th>Preferred / Dates</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $sr)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($sr->patient && $sr->patient->user)
                                            <a href="{{ route('admin.patients.show', $sr->patient) }}" class="text-decoration-none fw-semibold">{{ $sr->patient->user->name }}</a>
                                            <br><small class="text-muted">{{ $sr->patient->user->email ?? '-' }}</small>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $sr->service ? $sr->service->name : '—' }}</td>
                                    <td>{{ $sr->location ?? '—' }}</td>
                                    <td>
                                        @if($sr->start_date && $sr->end_date)
                                            {{ $sr->start_date->format('M d, Y') }} – {{ $sr->end_date->format('M d, Y') }}
                                        @elseif($sr->preferred_time)
                                            {{ $sr->preferred_time->format('M d, Y h:i A') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        @switch($sr->status)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                                @break
                                            @case('accepted')
                                                <span class="badge bg-primary">Accepted</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success">Completed</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $sr->status }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-info">No service requests found.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
