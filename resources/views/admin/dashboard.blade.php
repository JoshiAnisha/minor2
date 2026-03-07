@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid px-0">
        <div class="row g-0">
            <div class="col-12">
                <h2 class="mb-4">Welcome, {{ Auth::user()->name }}!</h2>

                {{-- Key Metrics Section --}}
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="card shadow-sm p-4 border-start border-primary border-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Patients</h6>
                                    <h2 class="mb-0">{{ $totalPatients }}</h2>
                                </div>
                                <div class="text-primary" style="font-size: 3rem; opacity: 0.3;">
                                    <i class="bi bi-people"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm p-4 border-start border-info border-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Caregivers</h6>
                                    <h2 class="mb-0">{{ $totalCaregivers }}</h2>
                                </div>
                                <div class="text-info" style="font-size: 3rem; opacity: 0.3;">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm p-4 border-start border-success border-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Services</h6>
                                    <h2 class="mb-0">{{ $totalServices }}</h2>
                                </div>
                                <div class="text-success" style="font-size: 3rem; opacity: 0.3;">
                                    <i class="bi bi-heart-pulse"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Charts Section --}}
                <div class="row g-4 mb-5">
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="mb-0 fw-semibold text-dark">Bookings by Status</h6>
                                <small class="text-muted">Distribution overview</small>
                            </div>
                            <div class="card-body p-4">
                                <div class="chart-container" style="position: relative; height: 280px;">
                                    <canvas id="bookingsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="mb-0 fw-semibold text-dark">Bookings Count</h6>
                                <small class="text-muted">By status</small>
                            </div>
                            <div class="card-body p-4">
                                <div class="chart-container" style="position: relative; height: 280px;">
                                    <canvas id="bookingsBarChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bookings Summary Table --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm p-4">
                            <h5 class="mb-4">Bookings Summary</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Status</th>
                                            <th>Count</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalBookings = array_sum($bookingsByStatus);
                                        @endphp
                                        <tr>
                                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                                            <td><strong>{{ $bookingsByStatus['pending'] }}</strong></td>
                                            <td>{{ $totalBookings > 0 ? number_format(($bookingsByStatus['pending'] / $totalBookings) * 100, 1) : 0 }}%
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-info">In Process</span></td>
                                            <td><strong>{{ $bookingsByStatus['in_process'] }}</strong></td>
                                            <td>{{ $totalBookings > 0 ? number_format(($bookingsByStatus['in_process'] / $totalBookings) * 100, 1) : 0 }}%
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-success">Completed</span></td>
                                            <td><strong>{{ $bookingsByStatus['completed'] }}</strong></td>
                                            <td>{{ $totalBookings > 0 ? number_format(($bookingsByStatus['completed'] / $totalBookings) * 100, 1) : 0 }}%
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-danger">Cancelled</span></td>
                                            <td><strong>{{ $bookingsByStatus['cancelled'] }}</strong></td>
                                            <td>{{ $totalBookings > 0 ? number_format(($bookingsByStatus['cancelled'] / $totalBookings) * 100, 1) : 0 }}%
                                            </td>
                                        </tr>
                                        <tr class="table-secondary">
                                            <td><strong>Total</strong></td>
                                            <td><strong>{{ $totalBookings }}</strong></td>
                                            <td><strong>100%</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js CDN --}}
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

            // Doughnut Chart - Bookings by Status
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
                                labels: {
                                    padding: 16,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: { size: 12, family: "'Inter', sans-serif" }
                                }
                            }
                        }
                    }
                });
            }

            // Bar Chart - Bookings by Status
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
                                ticks: {
                                    stepSize: 1,
                                    font: { size: 11 },
                                    padding: 8
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: {
                                    font: { size: 11 },
                                    padding: 8,
                                    maxRotation: 0
                                }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(30, 41, 59, 0.95)',
                                padding: 10,
                                titleFont: { size: 12 },
                                bodyFont: { size: 12 }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
