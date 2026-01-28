@extends('backend.layouts.dashboard.app')

@section('title', 'Services')

@section('content')
    <h3 class="mb-4">Available Services</h3>

    <div class="row g-3">
        @foreach ($services as $service)
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <h5>{{ $service->name }}</h5>
                    <p>{{ Str::limit($service->description, 80) }}</p>
                    <a href="{{ route('patient.services.show', $service->id) }}" class="btn btn-sm btn-info">View Details</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
