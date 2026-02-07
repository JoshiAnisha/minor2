@extends('backend.layouts.dashboard.app')

@section('title', 'Service Details')

@section('content')
    <div class="card p-4">
        <h3>{{ $service->name }}</h3>
        <p>{{ $service->description }}</p>
        <p class="fw-bold">Rs. {{ $service->price }}</p>

        <h2>{{ $service->name }}</h2>
        <p>{{ $service->details }}</p>
        <p>Price: Rs {{ $service->base_price }}</p>

        @if (session('error'))
            <p style="color:red">{{ session('error') }}</p>
        @endif

        <form action="{{ route('patient.services.book') }}" method="POST">
            @csrf
            <input type="hidden" name="service_id" value="{{ $service->id }}">
            <label>Start Date:</label>
            <input type="date" name="start_date" required>
            <label>End Date:</label>
            <input type="date" name="end_date" required>
            <label>Location:</label>
            <input type="text" name="location" required>
            <button type="submit">Confirm Booking</button>
        </form>


    </div>
@endsection
