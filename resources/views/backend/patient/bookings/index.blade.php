@extends('backend.layouts.dashboard.app')

@section('title', 'My Bookings')

@section('content')
    <h3 class="mb-4">My Bookings</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('patient.bookings.create') }}" class="btn btn-info btn-sm mb-3">
        New Booking
    </a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Service</th>
                <th>Date</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $booking->service->name ?? '-' }}</td>
                    <td>{{ $booking->date?->format('Y-m-d') }}</td>
                    <td>
                        <span
                            class="badge bg-{{ $booking->status == 'upcoming' ? 'warning' : ($booking->status == 'completed' ? 'success' : 'danger') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('patient.bookings.show', $booking->id) }}" class="btn btn-sm btn-primary">
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No bookings found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
