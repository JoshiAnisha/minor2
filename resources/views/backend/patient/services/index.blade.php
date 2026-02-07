@extends('backend.layouts.dashboard.app')

@section('title', 'Services')

@section('content')
    <div class="row">
        <h2>Available Services</h2>
        <div class="services-grid">
            @foreach ($services as $service)
                <div class="card">
                    <h3>{{ $service->name }}</h3>
                    <p>Price: Rs {{ $service->base_price }}</p>
                    <a href="{{ route('patient.services.show', $service->slug) }}">View Details</a>
                </div>
            @endforeach
        </div>


    </div>


    </div>
@endsection
