@extends('Caregiver.layouts.app')

@section('content')
    <style>
        .star-rating {
            font-size: 2rem;
            cursor: pointer;
            unicode-bidi: bidi-override;
            direction: rtl;
            text-align: left;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            color: #ddd;
            transition: color 0.2s;
            cursor: pointer;
            display: inline-block;
        }

        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffc107;
        }

        .star-rating input[type="radio"]:checked~label {
            color: #ffc107;
        }
    </style>

    <div class="card shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Leave a Review</h2>
            <a href="{{ route('caregiver.bookings') }}" class="btn btn-outline-secondary btn-sm">Back to Bookings</a>
        </div>

        <div class="mb-3">
            <p class="text-muted mb-0">Reviewing: <strong>{{ optional($patient->user)->name ?? 'N/A' }}</strong></p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('caregiver.review.store') }}" method="POST">
            @csrf
            <input type="hidden" name="patient_id" value="{{ $patient->id }}">

            <div class="card border-primary mb-4">
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label fs-5">Rating <span class="text-danger">*</span></label>
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
                        <label for="comment" class="form-label fs-5">Comment (Optional)</label>
                        <textarea class="form-control" id="comment" name="comment" rows="5" maxlength="1000"
                            placeholder="Share your experience with this patient..."></textarea>
                        <small class="text-muted">Maximum 1000 characters</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Submit Review</button>
                        <a href="{{ route('caregiver.bookings') }}" class="btn btn-outline-secondary btn-lg">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
