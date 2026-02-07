@extends('backend.layouts.dashboard.app')

@section('title', 'My Bookings')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>Service</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <h2>My Bookings</h2>
            @foreach ($bookings as $booking)
                <div>
                    <a href="{{ route('patient.bookings.show', $booking->id) }}">
                        {{ $booking->service->name }} | Status: {{ $booking->status }} | Start: {{ $booking->start_date }}
                    </a>
                </div>
            @endforeach




        </tbody>
    </table>
@endsection
