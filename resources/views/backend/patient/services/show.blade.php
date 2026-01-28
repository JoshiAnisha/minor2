@extends('backend.layouts.dashboard.app')

@section('title', 'Service Details')

@section('content')
    <h3 class="mb-4">Service Details</h3>

    <div class="card p-4 shadow-sm">
        <h5>{{ $service->name }}</h5>
        <p>{{ $service->description }}</p>
        <p><strong>Price:</strong> ${{ $service->price }}</p>
    </div>
@endsection
