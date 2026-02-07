@extends('backend.layouts.dashboard.app')

@section('content')
    <div class="container-fluid">
        <h4>Booking Details</h4>

        <div class="card">
            <div class="card-body">
                <h2>{{ $booking->service->name }}</h2>
                <p>Status: {{ $booking->status }}</p>
                <p>Location: {{ $booking->location }}</p>
                <p>Price: Rs {{ $booking->price }}</p>
                <p>Start Date: {{ $booking->start_date }}</p>
                <p>End Date: {{ $booking->end_date }}</p>
                <p>Caregiver: {{ $booking->caregiver->name ?? 'Not assigned' }}</p>



            </div>
        </div>
    </div>
@endsection
