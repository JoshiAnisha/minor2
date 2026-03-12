@extends('patient.layouts.app')

@section('title', 'Review this booking')

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
        <a href="{{ route('patient.bookings.show', ['id' => $booking->getKey()]) }}" class="text-decoration-none text-muted small d-inline-flex align-items-center">
            <i class="bi bi-arrow-left me-1"></i> Back to booking
        </a>
    </div>

    <h3 class="mb-4">Leave a review</h3>
    <p class="text-muted mb-3">You're reviewing: <strong>{{ optional($booking->service)->name ?? 'Service' }}</strong> (completed booking)</p>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php $oldRating = old('rating'); @endphp
    <form action="{{ route('patient.bookings.review.store', $booking->getKey()) }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="form-label">Rating <span class="text-danger">*</span></label>
            <div class="star-rating">
                <input type="radio" id="star5" name="rating" value="5" {{ (int) $oldRating === 5 ? 'checked' : '' }} required>
                <label for="star5">★</label>
                <input type="radio" id="star4" name="rating" value="4" {{ (int) $oldRating === 4 ? 'checked' : '' }}>
                <label for="star4">★</label>
                <input type="radio" id="star3" name="rating" value="3" {{ (int) $oldRating === 3 ? 'checked' : '' }}>
                <label for="star3">★</label>
                <input type="radio" id="star2" name="rating" value="2" {{ (int) $oldRating === 2 ? 'checked' : '' }}>
                <label for="star2">★</label>
                <input type="radio" id="star1" name="rating" value="1" {{ (int) $oldRating === 1 ? 'checked' : '' }}>
                <label for="star1">★</label>
            </div>
            <small class="text-muted">Select 1-5 stars (5 being the highest)</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Comment <span class="text-danger">*</span></label>
            <textarea name="comment" class="form-control" rows="4" required placeholder="Share your experience...">{{ old('comment') }}</textarea>
        </div>
        <button type="submit" class="btn btn-info">Submit review</button>
        <a href="{{ route('patient.bookings.show', ['id' => $booking->getKey()]) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </form>
@endsection
