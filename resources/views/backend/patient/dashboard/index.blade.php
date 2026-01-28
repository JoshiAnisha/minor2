@extends('backend.layouts.dashboard.app')

@section('title', 'Dashboard')

@section('content')
    <div class="welcome-card mb-4">
        <h3 class="text-info fw-bold mb-1">Welcome back, {{ Auth::user()->name ?? 'Patient' }}</h3>
        <p class="text-muted">Here is your health service summary.</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card summary-card p-3">
                <h6>Total Bookings</h6>
                <h4>{{ $totalBookings }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-card p-3">
                <h6>Upcoming Services</h6>
                <h4>{{ $upcomingServices }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-card p-3">
                <h6>Total Invoices</h6>
                <h4>{{ $totalInvoices }}</h4>
            </div>
        </div>
    </div>

    <div class="card p-3">
        <h5>Recent Bookings</h5>
        @if ($latestBookings->isEmpty())
            <p>No bookings yet.</p>
        @else
            <ul class="list-group list-group-flush">
                @foreach ($latestBookings as $booking)
                    <li class="list-group-item">
                        Service: {{ $booking->service->name ?? 'N/A' }} |
                        Date: {{ $booking->date ? $booking->date->format('Y-m-d') : 'N/A' }} |
                        Status: {{ ucfirst($booking->status ?? 'N/A') }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
