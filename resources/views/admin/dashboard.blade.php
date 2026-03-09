@extends('admin.layouts.app')

@section('title', 'Admin Dashboard | SewaCare')

@section('content')
<style>
    .admin-dashboard .dashboard-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 12px;
        padding: 1.5rem 1.75rem;
        margin-bottom: 1.75rem;
        color: #fff;
    }
    .admin-dashboard .dashboard-header .admin-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        background: rgba(255,255,255,0.2);
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .admin-dashboard .section-title {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 1rem;
    }
    .admin-dashboard .metric-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        transition: box-shadow 0.2s, transform 0.15s;
    }
    .admin-dashboard .metric-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .admin-dashboard .card-activity {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
    }
    .admin-dashboard .card-activity .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-weight: 600;
        padding: 0.75rem 1rem;
    }
</style>

<div class="admin-dashboard container-fluid px-0">
    {{-- Admin-centered header --}}
    <div class="dashboard-header d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <span class="admin-badge mb-2 d-inline-block">Admin</span>
            <h1 class="h4 mb-1 fw-semibold">Dashboard</h1>
            <p class="mb-0 small opacity-85">Platform overview and quick access</p>
        </div>
        <div class="text-end">
            <p class="mb-0 small opacity-75">Signed in as</p>
            <p class="mb-0 fw-semibold">{{ Auth::user()->name }}</p>
        </div>
    </div>

    {{-- Key metrics: Platform at a glance --}}
    <p class="section-title mb-0">Platform at a glance</p>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="metric-card card shadow-sm p-3 h-100 border-start border-primary border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Patients</p>
                        <h3 class="mb-0 fw-bold">{{ $totalPatients }}</h3>
                    </div>
                    <i class="bi bi-people-fill text-primary opacity-50" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="metric-card card shadow-sm p-3 h-100 border-start border-info border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Caregivers</p>
                        <h3 class="mb-0 fw-bold">{{ $totalCaregivers }}</h3>
                    </div>
                    <i class="bi bi-person-badge-fill text-info opacity-50" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="metric-card card shadow-sm p-3 h-100 border-start border-success border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Services</p>
                        <h3 class="mb-0 fw-bold">{{ $totalServices }}</h3>
                    </div>
                    <i class="bi bi-heart-pulse-fill text-success opacity-50" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="metric-card card shadow-sm p-3 h-100 border-start border-warning border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Total bookings</p>
                        <h3 class="mb-0 fw-bold">{{ $totalBookings }}</h3>
                    </div>
                    <i class="bi bi-calendar-check-fill text-warning opacity-50" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Platform activity: charts --}}
    <p class="section-title">Booking activity</p>
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card card-activity shadow-sm overflow-hidden">
                <div class="card-header">Bookings by status</div>
                <div class="card-body p-4">
                    <div class="chart-container" style="position: relative; height: 260px;">
                        <canvas id="bookingsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-activity shadow-sm overflow-hidden">
                <div class="card-header">Count by status</div>
                <div class="card-body p-4">
                    <div class="chart-container" style="position: relative; height: 260px;">
                        <canvas id="bookingsBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bookings breakdown table --}}
    <div class="card card-activity shadow-sm">
        <div class="card-header">Booking status breakdown</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Status</th>
                            <th>Count</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalBookingsTable = array_sum($bookingsByStatus); @endphp
                        <tr>
                            <td class="ps-4"><span class="badge bg-warning text-dark">Pending</span></td>
                            <td><strong>{{ $bookingsByStatus['pending'] }}</strong></td>
                            <td>{{ $totalBookingsTable > 0 ? number_format(($bookingsByStatus['pending'] / $totalBookingsTable) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="ps-4"><span class="badge bg-info">In process</span></td>
                            <td><strong>{{ $bookingsByStatus['in_process'] }}</strong></td>
                            <td>{{ $totalBookingsTable > 0 ? number_format(($bookingsByStatus['in_process'] / $totalBookingsTable) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="ps-4"><span class="badge bg-success">Completed</span></td>
                            <td><strong>{{ $bookingsByStatus['completed'] }}</strong></td>
                            <td>{{ $totalBookingsTable > 0 ? number_format(($bookingsByStatus['completed'] / $totalBookingsTable) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="ps-4"><span class="badge bg-danger">Cancelled</span></td>
                            <td><strong>{{ $bookingsByStatus['cancelled'] }}</strong></td>
                            <td>{{ $totalBookingsTable > 0 ? number_format(($bookingsByStatus['cancelled'] / $totalBookingsTable) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr class="table-light">
                            <td class="ps-4 fw-semibold">Total</td>
                            <td class="fw-semibold">{{ $totalBookingsTable }}</td>
                            <td class="fw-semibold">100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartLabels = @json($chartLabels);
        const chartData = @json($chartData);
        const colors = {
            pending: 'rgba(245, 158, 11, 0.85)',
            inProcess: 'rgba(14, 165, 233, 0.85)',
            completed: 'rgba(22, 163, 74, 0.85)',
            cancelled: 'rgba(225, 29, 72, 0.85)'
        };
        const bgColors = [colors.pending, colors.inProcess, colors.completed, colors.cancelled];
        const borderColors = ['#d97706', '#0284c7', '#16a34a', '#dc2626'];

        const pieCtx = document.getElementById('bookingsChart');
        if (pieCtx) {
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        data: chartData,
                        backgroundColor: bgColors,
                        borderColor: '#fff',
                        borderWidth: 2,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '55%',
                    layout: { padding: 12 },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 16, usePointStyle: true, pointStyle: 'circle', font: { size: 12 } }
                        }
                    }
                }
            });
        }

        const barCtx = document.getElementById('bookingsBarChart');
        if (barCtx) {
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Bookings',
                        data: chartData,
                        backgroundColor: bgColors,
                        borderColor: borderColors,
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: { padding: 12 },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.06)' },
                            ticks: { stepSize: 1, font: { size: 11 }, padding: 8 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 }, padding: 8, maxRotation: 0 }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: 'rgba(30, 41, 59, 0.95)', padding: 10 }
                    }
                }
            });
        }
    });
</script>
@endsection
