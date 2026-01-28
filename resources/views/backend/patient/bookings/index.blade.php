@extends('backend.layouts.dashboard.app')

@section('title', 'Bookings')

@section('content')
    <h3 class="mb-4">My Bookings</h3>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('patient.bookings.create') }}" class="btn btn-info btn-sm">New Booking</a>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Service</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $booking->service->name ?? '-' }}</td>
                    <td>{{ $booking->date ? (is_string($booking->date) ? $booking->date : $booking->date->format('Y-m-d')) : '-' }}
                    </td>
                    <td>
                        @if ($booking->status == 'upcoming')
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-clock me-1"></i>Upcoming
                            </span>
                        @elseif($booking->status == 'completed')
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>Completed
                            </span>
                        @elseif($booking->status == 'cancelled')
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle me-1"></i>Cancelled
                            </span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('patient.bookings.show', $booking->id) }}" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No bookings found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
