@extends('Caregiver.layouts.app')

@section('content')
<style>
    .dashboard-header { margin-bottom: 2rem; }
    .stat-card {
        border-radius: 16px;
        border: none;
        padding: 1.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,0.08); }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .quick-card {
        border-radius: 16px;
        border: none;
        text-decoration: none;
        color: inherit;
        padding: 1.5rem;
        transition: all 0.2s;
        display: block;
    }
    .quick-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.1); color: inherit; }
    .section-card { border-radius: 16px; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
    .chart-container { position: relative; height: 280px; }
</style>

<div class="dashboard-header">
    <h1 class="h3 fw-bold mb-1">Welcome, {{ Auth::user()->name }}!</h1>
    <p class="text-muted mb-0">Here’s your caregiving overview</p>
</div>

{{-- Stats Row --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-3">
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
    <div class="col-sm-6 col-lg-3">
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
    <div class="col-sm-6 col-lg-3">
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
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">My Rating</p>
                    <h3 class="fw-bold mb-0">{{ $averageRating ?? '—' }} <i class="bi bi-star-fill text-warning small"></i></h3>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-star"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Left: Charts + Quick Links --}}
    <div class="col-lg-8">
        {{-- Booking Status Pie Chart --}}
        <div class="card section-card mb-4">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-4">Booking Overview</h5>
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <div class="chart-container">
                            <canvas id="bookingsPieChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <span class="rounded-circle" style="width:12px;height:12px;background:#3b82f6;"></span>
                                <span>Pending</span>
                                <strong class="ms-auto">{{ $pendingCount ?? 0 }}</strong>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="rounded-circle" style="width:12px;height:12px;background:#06b6d4;"></span>
                                <span>In Progress</span>
                                <strong class="ms-auto">{{ $inProgressCount ?? 0 }}</strong>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="rounded-circle" style="width:12px;height:12px;background:#22c55e;"></span>
                                <span>Completed</span>
                                <strong class="ms-auto">{{ $completedCount ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
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

        {{-- New Service Requests --}}
        <div class="card section-card">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">New Service Requests</h5>
                <a href="{{ route('caregiver.service.requests') }}" class="btn btn-sm text-decoration-none" style="color: #0ea5e9;">View all</a>
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
                                        <td>{{ optional($req->user)->name ?? 'N/A' }}</td>
                                        <td>{{ $req->service->name ?? '-' }}</td>
                                        <td>{{ Str::limit($req->location ?? '-', 20) }}</td>
                                        <td>{{ $req->preferred_time ? $req->preferred_time->format('d M') : '-' }}</td>
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

    {{-- Right: Today's Bookings + Bar Chart --}}
    <div class="col-lg-4">
        <div class="card section-card mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Today’s Bookings</h5>
            </div>
            <div class="card-body p-0">
                @if(isset($todaysBookings) && $todaysBookings->isNotEmpty())
                    <ul class="list-group list-group-flush">
                        @foreach($todaysBookings as $booking)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ optional(optional($booking->patient)->user)->name ?? 'N/A' }}</strong>
                                    <br><small class="text-muted">{{ optional($booking->service)->name ?? '-' }}</small>
                                </div>
                                <div class="text-end">
                                    <span>{{ $booking->date_time ? \Carbon\Carbon::parse($booking->date_time)->format('h:i A') : '-' }}</span>
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
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x fs-1"></i>
                        <p class="mb-0 mt-2">No bookings today</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Available services (opened by admin – patients request these; you see them in Service Requests) --}}
        <div class="card section-card mb-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Available Services</h5>
                <a href="{{ route('caregiver.service.requests') }}" class="btn btn-sm text-decoration-none" style="color: #0ea5e9;">Respond to requests</a>
            </div>
            <div class="card-body p-0">
                @if(isset($availableServices) && $availableServices->isNotEmpty())
                    <ul class="list-group list-group-flush">
                        @foreach($availableServices->take(5) as $s)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <span><strong>{{ $s->name }}</strong></span>
                                <span class="badge bg-secondary">{{ ucfirst($s->service_type) }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @if($availableServices->count() > 5)
                        <div class="text-center py-2 border-top">
                            <small class="text-muted">+ {{ $availableServices->count() - 5 }} more</small>
                        </div>
                    @endif
                    <p class="small text-muted px-3 pt-2 mb-0">When patients request these services, they appear under <strong>Service Requests</strong>.</p>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-bag fs-1"></i>
                        <p class="mb-0 mt-2 small">No services opened yet</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Bar Chart: Weekly Activity --}}
        <div class="card section-card">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-4">This Week</h5>
                <div class="chart-container">
                    <canvas id="weeklyBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pieCtx = document.getElementById('bookingsPieChart');
    if (pieCtx) {
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'In Progress', 'Completed'],
                datasets: [{
                    data: [{{ $pendingCount ?? 0 }}, {{ $inProgressCount ?? 0 }}, {{ $completedCount ?? 0 }}],
                    backgroundColor: ['#3b82f6', '#06b6d4', '#22c55e'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                cutout: '65%'
            }
        });
    }

    const barCtx = document.getElementById('weeklyBarChart');
    if (barCtx) {
        const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: days,
                datasets: [{
                    label: 'Bookings',
                    data: [{{ implode(',', $weeklyBookings ?? [0,0,0,0,0,0,0]) }}],
                    backgroundColor: 'rgba(14, 165, 233, 0.6)',
                    borderColor: 'rgb(14, 165, 233)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
