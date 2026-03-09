@extends('Caregiver.layouts.app')

@section('content')
<style>
    .dashboard-header { margin-bottom: 1.75rem; }
    .dashboard-header h1 { font-size: 1.4rem; font-weight: 600; color: #0f172a; }
    .dashboard-header p { color: #64748b; font-size: 0.9rem; }
    .stat-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 1.25rem;
        transition: box-shadow 0.2s;
        background: #fff;
    }
    .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
    .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; }
    .quick-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        color: #334155;
        padding: 1.25rem;
        transition: all 0.2s;
        display: block;
        background: #fff;
    }
    .quick-card:hover { border-color: #0ea5e9; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.1); color: #0ea5e9; }
    .section-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        background: #fff;
        overflow: hidden;
    }
    .section-card .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: #0f172a;
    }
    .section-card .card-title { font-size: 0.95rem; font-weight: 600; color: #0f172a; }
    .chart-container { position: relative; height: 240px; }
    .booking-legend { max-width: 160px; }
</style>

<div class="dashboard-header">
    <h1 class="h3 fw-bold mb-1">Welcome, {{ Auth::user()->name }}!</h1>
    <p class="text-muted mb-0">Here’s your caregiving overview</p>
</div>

@if(isset($profileComplete) && !$profileComplete)
    <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="border-radius: 12px; border-left: 4px solid #eab308;">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-warning bg-opacity-25 p-2"><i class="bi bi-exclamation-triangle-fill text-warning"></i></div>
            <div>
                <h6 class="fw-bold mb-1">Complete your profile</h6>
                <p class="mb-0 small text-muted">You must complete your profile (name, email, contact number, and address) before accepting a service request or placing a bid.</p>
            </div>
        </div>
        <a href="{{ route('caregiver.profile.edit') }}" class="btn btn-warning">Complete profile</a>
    </div>
@endif

{{-- Stats Row --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="card stat-card shadow-sm">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Upcoming Visits</p>
                    <h3 class="fw-bold mb-0">{{ $upcomingVisits ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-calendar-event"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card stat-card shadow-sm">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Tasks to Log</p>
                    <h3 class="fw-bold mb-0">{{ $tasksToLog ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-list-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card stat-card shadow-sm">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Completed</p>
                    <h3 class="fw-bold mb-0">{{ $completedBookings ?? 0 }}</h3>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Left column --}}
    <div class="col-lg-8">
        {{-- Quick Actions – primary actions first --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <a href="{{ route('caregiver.bookings') }}" class="card quick-card shadow-sm text-center">
                    <i class="bi bi-calendar-check text-info fs-2"></i>
                    <p class="mb-0 mt-2 fw-semibold small">My Bookings</p>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('caregiver.service.requests') }}" class="card quick-card shadow-sm text-center">
                    <i class="bi bi-clipboard2-pulse text-warning fs-2"></i>
                    <p class="mb-0 mt-2 fw-semibold small">Service Requests</p>
                    @if(isset($pendingServiceRequests) && $pendingServiceRequests->isNotEmpty())
                        <span class="badge bg-warning text-dark mt-1">{{ $pendingServiceRequests->count() }} new</span>
                    @endif
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('caregiver.notifications.index') }}" class="card quick-card shadow-sm text-center">
                    <i class="bi bi-bell text-primary fs-2"></i>
                    <p class="mb-0 mt-2 fw-semibold small">Notifications</p>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge bg-danger mt-1">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('caregiver.profile.edit') }}" class="card quick-card shadow-sm text-center">
                    <i class="bi bi-person-circle text-secondary fs-2"></i>
                    <p class="mb-0 mt-2 fw-semibold small">Profile</p>
                </a>
            </div>
        </div>

        {{-- Booking Overview – summary --}}
        <div class="card section-card mb-4">
            <div class="card-header">Booking Overview</div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <div class="chart-container">
                            <canvas id="bookingsPieChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex flex-column gap-2 booking-legend">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle" style="width:10px;height:10px;background:#3b82f6;"></span>
                                    <span class="small">Pending</span>
                                </span>
                                <strong class="small">{{ $pendingCount ?? 0 }}</strong>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle" style="width:10px;height:10px;background:#06b6d4;"></span>
                                    <span class="small">In Progress</span>
                                </span>
                                <strong class="small">{{ $inProgressCount ?? 0 }}</strong>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="d-flex align-items-center gap-2">
                                    <span class="rounded-circle" style="width:10px;height:10px;background:#22c55e;"></span>
                                    <span class="small">Completed</span>
                                </span>
                                <strong class="small">{{ $completedCount ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- New Service Requests --}}
        <div class="card section-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>New Service Requests</span>
                <a href="{{ route('caregiver.service.requests') }}" class="btn btn-sm btn-outline-primary py-0">View all</a>
            </div>
            <div class="card-body p-0">
                @if(isset($pendingServiceRequests) && $pendingServiceRequests->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Patient</th>
                                    <th>Service</th>
                                    <th>Location</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingServiceRequests as $req)
                                    <tr>
                                        <td>
                                            @if($req->patient)
                                                <a href="{{ route('caregiver.patient.show', $req->patient) }}" class="text-decoration-none">{{ optional($req->patient->user)->name ?? optional($req->user)->name ?? 'N/A' }}</a>
                                            @else
                                                {{ optional($req->user)->name ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td>{{ $req->service->name ?? '-' }}</td>
                                        <td>{{ Str::limit($req->location ?? '-', 20) }}</td>
                                        <td>
                                            @if ($req->isLongTerm() && $req->start_date && $req->end_date)
                                                {{ $req->start_date->format('d M') }} – {{ $req->end_date->format('d M') }}
                                            @else
                                                {{ $req->preferred_time ? $req->preferred_time->format('d M') : '-' }}
                                            @endif
                                        </td>
                                        <td><a href="{{ route('caregiver.service.requests') }}" class="btn btn-sm btn-primary">Respond</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox display-4"></i>
                        <p class="mb-0 mt-2">No new service requests</p>
                        <small>Patient requests will appear here</small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right column: Today first, then context --}}
    <div class="col-lg-4">
        {{-- Today's Bookings – top priority --}}
        <div class="card section-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Today’s Bookings</span>
                <a href="{{ route('caregiver.bookings') }}" class="btn btn-sm btn-link text-decoration-none p-0 text-primary">View all</a>
            </div>
            <div class="card-body p-0">
                @if(isset($todaysBookings) && $todaysBookings->isNotEmpty())
                    <ul class="list-group list-group-flush">
                        @foreach($todaysBookings as $booking)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                <div>
                                    @if($booking->patient)
                                        <a href="{{ route('caregiver.patient.show', $booking->patient) }}" class="text-decoration-none small fw-semibold">{{ optional($booking->patient->user)->name ?? 'N/A' }}</a>
                                    @else
                                        <strong class="small">{{ optional(optional($booking->patient)->user)->name ?? 'N/A' }}</strong>
                                    @endif
                                    <br><small class="text-muted">{{ optional($booking->service)->name ?? '-' }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="small">{{ $booking->date_time ? \Carbon\Carbon::parse($booking->date_time)->format('h:i A') : '-' }}</span>
                                    <br>
                                    @if ($booking->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($booking->status === 'accepted')
                                        <span class="badge bg-info">In Progress</span>
                                    @else
                                        <span class="badge bg-success">Done</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-calendar-x d-block fs-4 mb-2"></i>
                        <p class="mb-0">No bookings today</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick tips – suits dashboard --}}
        <div class="card section-card">
            <div class="card-header">Quick tips</div>
            <div class="card-body py-3">
                <ul class="list-unstyled mb-0 small text-muted">
                    <li class="d-flex align-items-start mb-2">
                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                        <span>Log tasks after each visit to keep records up to date.</span>
                    </li>
                    <li class="d-flex align-items-start mb-2">
                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                        <span>Respond to service requests to get matched with more bookings.</span>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                        <span>Check Service Requests regularly — all new patient requests appear there for you to accept or bid.</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pending = {{ $pendingCount ?? 0 }};
    const inProgress = {{ $inProgressCount ?? 0 }};
    const completed = {{ $completedCount ?? 0 }};
    const total = pending + inProgress + completed;

    const pieCtx = document.getElementById('bookingsPieChart');
    if (pieCtx) {
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: total > 0 ? ['Pending', 'In Progress', 'Completed'] : ['No bookings yet'],
                datasets: [{
                    data: total > 0 ? [pending, inProgress, completed] : [1],
                    backgroundColor: total > 0 ? ['#3b82f6', '#06b6d4', '#22c55e'] : ['#e2e8f0'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '65%'
            }
        });
    }
});
</script>
@endpush
@endsection
