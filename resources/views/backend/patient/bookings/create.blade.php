@extends('backend.layouts.dashboard.app')

@section('title', 'New Booking')

@section('content')
    <h3 class="mb-4">Create New Booking</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Error!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('patient.bookings.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Select Service</label>
            <select name="service_name" class="form-select" required>
                <option disabled selected>Choose service</option>
                <option value="Home Nursing">Home Nursing</option>
                <option value="Physiotherapy">Physiotherapy</option>
                <option value="Lab Tests">Lab Tests</option>
                <option value="Child Care Support">Child Care Support</option>
                <option value="Post-Surgery Care">Post-Surgery Care</option>
                <option value="Doctor Home Visit">Doctor Home Visit</option>
                <option value="Wound Dressing">Wound Dressing</option>
                <option value="Vaccination">Vaccination</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Booking Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <button class="btn btn-info">Book Now</button>
    </form>
@endsection
