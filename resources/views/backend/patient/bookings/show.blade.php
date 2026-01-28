@extends('backend.layouts.dashboard.app')

@section('title', 'Booking Details')

@section('content')
    <h3 class="mb-4">Booking Details</h3>

    <div class="card p-4 shadow-sm">
        <p><strong>Service:</strong> {{ $booking->service->name ?? '-' }}</p>
        <p><strong>Date:</strong> {{ $booking->date ?? '-' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
        <p><strong>Created At:</strong> {{ $booking->created_at->format('d M Y') }}</p>
    </div>
@endsection
