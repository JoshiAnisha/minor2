@extends('Caregiver.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 p-4">
                <h2 class="mb-4">Welcome, {{ Auth::user()->name }}!</h2>

                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="card shadow-sm p-3 border-start border-info border-5 text-center">
                            <h6>Upcoming Visits</h6>
                            <h4>{{ $upcomingVisits }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm p-3 border-start border-warning border-5 text-center">
                            <h6>Tasks to Log</h6>
                            <h4>{{ $tasksToLog }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm p-3 border-start border-success border-5 text-center">
                            <h6>Completed Bookings</h6>
                            <h4>{{ $completedBookings }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm p-3 border-start border-primary border-5 text-center">
                            <h6>My Rating</h6>
                            <h4>{{ $averageRating }} ★</h4>
                        </div>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <a href="{{ route('caregiver.bookings') }}"
                            class="card shadow-sm p-3 text-center text-decoration-none">
                            <i class="bi bi-calendar-check fs-2"></i>
                            <h6 class="mt-2">My Bookings</h6>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('caregiver.services') }}"
                            class="card shadow-sm p-3 text-center text-decoration-none">
                            <i class="bi bi-gear fs-2"></i>
                            <h6 class="mt-2">My Services</h6>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('caregiver.profile') }}"
                            class="card shadow-sm p-3 text-center text-decoration-none">
                            <i class="bi bi-person-circle fs-2"></i>
                            <h6 class="mt-2">Profile</h6>
                        </a>
                    </div>
                </div>

                <h5 class="mb-3">Today's Assigned Bookings</h5>
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>SN</th>
                            <th>Patient</th>
                            <th>Service</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todaysBookings as $index => $booking)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ optional(optional($booking->patient)->user)->name ?? 'N/A' }}</td>
                                <td>{{ optional($booking->service)->service_name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->date_time)->format('h:i A') }}</td>
                                <td>
                                    @if ($booking->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($booking->status === 'accepted')
                                        <span class="badge bg-info">In Progress</span>
                                    @elseif($booking->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No bookings for today.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('bookingsChart').getContext('2d');
        const bookingsChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'In Progress', 'Completed'],
                datasets: [{
                    label: 'Bookings',
                    data: [
                        {{ $pendingCount }},
                        {{ $inProgressCount }},
                        {{ $completedCount }}
                    ],
                    backgroundColor: ['#ffc107', '#0dcaf0', '#198754'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
@endsection
