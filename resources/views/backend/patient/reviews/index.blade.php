@extends('backend.layouts.dashboard.app')

@section('title', 'Reviews')

@section('content')
    <h3 class="mb-4">My Reviews</h3>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Service</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $review->service->name ?? '-' }}</td>
                    <td>{{ $review->rating }}/5</td>
                    <td>{{ $review->comment }}</td>
                    <td>{{ $review->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No reviews found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('patient.reviews.create') }}" class="btn btn-info btn-sm mt-3">Write a Review</a>
@endsection
