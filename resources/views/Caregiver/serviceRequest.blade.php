@extends('caregiver.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Available Service Requests</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($requests->isEmpty())
        <div class="alert alert-info">No pending service requests found.</div>
    @else
        <div class="row">
            @foreach($requests as $serviceRequest)
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm rounded">
            <div class="card-body">
                <h5 class="card-title">{{ $serviceRequest->service->name }}</h5>
                <p><strong>Service Type:</strong> {{ ucfirst($serviceRequest->service->service_type) }}</p>
                <p><strong>Patient Name:</strong> {{ $serviceRequest->patient->name }}</p>
                <p><strong>Shift Type:</strong> {{ ucfirst($serviceRequest->shift_type) }}</p>
                <p><strong>Location:</strong> {{ $serviceRequest->location }}</p>
                <p><strong>Preferred Time:</strong> {{ \Carbon\Carbon::parse($serviceRequest->preferred_time)->format('d M Y, h:i A') }}</p>
                <p><strong>Description:</strong> {{ $serviceRequest->description }}</p>
                <p><strong>Base Price:</strong> Rs. {{ number_format($serviceRequest->service->base_price, 2) }}</p>

                <form method="POST" action="{{ route('caregiver.acceptBasePrice', $serviceRequest->id) }}">
                    @csrf
                    <button class="btn btn-success btn-sm">Accept Base Price</button>
                </form>

                <hr>
                <form method="POST" action="{{ route('caregiver.placeBid') }}">
                    @csrf
                    <input type="hidden" name="service_request_id" value="{{ $serviceRequest->id }}">
                    <div class="mb-2">
                        <label for="proposed_price_{{ $serviceRequest->id }}">Propose Your Price:</label>
                        <input type="number" name="proposed_price" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning btn-sm">Submit Bid</button>
                </form>
            </div>
        </div>
    </div>
@endforeach

        </div>
    @endif
</div>
@endsection
