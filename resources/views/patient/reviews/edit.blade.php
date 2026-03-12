@extends('patient.layouts.app')

@section('title', 'Edit Review')

@section('content')
    <style>
        .star-rating {
            font-size: 2rem;
            cursor: pointer;
            unicode-bidi: bidi-override;
            direction: rtl;
            text-align: left;
        }
        .star-rating input[type="radio"] { display: none; }
        .star-rating label {
            color: #ddd;
            transition: color 0.2s;
            cursor: pointer;
            display: inline-block;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label { color: #ffc107; }
        .star-rating input[type="radio"]:checked ~ label { color: #ffc107; }
    </style>

    <div class="mb-3">
        <a href="{{ route('patient.profile.show') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center">
            <i class="bi bi-arrow-left me-1"></i> Back to profile
        </a>
    </div>

    <h3 class="mb-4">Edit your review</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php $currentRating = (int) old('rating', $review->rating); @endphp
    <form action="{{ route('patient.reviews.update', $review) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Service</label>
            <select name="service_id" class="form-select" required>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" {{ (int) old('service_id', $review->service_id) === (int) $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label">Rating <span class="text-danger">*</span></label>
            <div class="star-rating">
                <input type="radio" id="star5" name="rating" value="5" {{ $currentRating === 5 ? 'checked' : '' }} required>
                <label for="star5">★</label>
                <input type="radio" id="star4" name="rating" value="4" {{ $currentRating === 4 ? 'checked' : '' }}>
                <label for="star4">★</label>
                <input type="radio" id="star3" name="rating" value="3" {{ $currentRating === 3 ? 'checked' : '' }}>
                <label for="star3">★</label>
                <input type="radio" id="star2" name="rating" value="2" {{ $currentRating === 2 ? 'checked' : '' }}>
                <label for="star2">★</label>
                <input type="radio" id="star1" name="rating" value="1" {{ $currentRating === 1 ? 'checked' : '' }}>
                <label for="star1">★</label>
            </div>
            <small class="text-muted">Select 1-5 stars (5 being the highest)</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Comment</label>
            <textarea name="comment" class="form-control" rows="4" required>{{ old('comment', $review->comment ?? $review->comments ?? '') }}</textarea>
        </div>
        <button type="submit" class="btn btn-info">Update review</button>
        <a href="{{ route('patient.profile.show') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </form>
@endsection
