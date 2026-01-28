@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 col-lg-10 p-4">
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
                                    <h6 class="text-muted mb-2">Active Caregivers</h6>
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
                    <div class="col-md-12">
                        <div class="card shadow-sm p-4">
                            <h5 class="mb-4">Bookings by Status</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <canvas id="bookingsChart" style="max-height: 300px;"></canvas>
                                </div>
                                <div class="col-md-6">
                                    <canvas id="bookingsBarChart" style="max-height: 300px;"></canvas>
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
        // Pie Chart - Bookings by Status
        const pieCtx = document.getElementById('bookingsChart');
        if (pieCtx) {
            new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Bookings',
                        data: @json($chartData),
                        backgroundColor: @json($chartColors),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'Bookings Distribution (Pie Chart)'
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
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Number of Bookings',
                        data: @json($chartData),
                        backgroundColor: @json($chartColors),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Bookings Distribution (Bar Chart)'
                        }
                    }
                }
            });
        }
    </script>
@endsection
