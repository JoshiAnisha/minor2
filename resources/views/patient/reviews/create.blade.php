@extends('patient.layouts.app')

@section('title', 'New Review')

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
        <div class="mb-4">
            <label class="form-label">Rating <span class="text-danger">*</span></label>
            <div class="star-rating">
                <input type="radio" id="star5" name="rating" value="5" required>
                <label for="star5">★</label>
                <input type="radio" id="star4" name="rating" value="4">
                <label for="star4">★</label>
                <input type="radio" id="star3" name="rating" value="3">
                <label for="star3">★</label>
                <input type="radio" id="star2" name="rating" value="2">
                <label for="star2">★</label>
                <input type="radio" id="star1" name="rating" value="1">
                <label for="star1">★</label>
            </div>
            <small class="text-muted">Select 1-5 stars (5 being the highest)</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Comment</label>
            <textarea name="comment" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-info">Submit Review</button>
    </form>
@endsection
