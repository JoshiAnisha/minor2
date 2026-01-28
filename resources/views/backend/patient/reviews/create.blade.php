@extends('backend.layouts.dashboard.app')

@section('title', 'New Review')

@section('content')
    <h3 class="mb-4">Write a Review</h3>

    <form action="{{ route('patient.reviews.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Select Service</label>
            <select name="service_id" class="form-select" required>
                <option value="">-- Choose Service --</option>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Rating</label>
            <select name="rating" class="form-select" required>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Comment</label>
            <textarea name="comment" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-info">Submit Review</button>
    </form>
@endsection
