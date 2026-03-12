@extends('patient.layouts.app')

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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $review->service->name ?? '-' }}</td>
                    <td>
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= (int) $review->rating ? '-fill text-warning' : '' }}"></i>
                        @endfor
                    </td>
                    <td>{{ $review->comment ?? $review->comments ?? '-' }}</td>
                    <td>{{ $review->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('patient.reviews.edit', $review) }}" class="btn btn-outline-info btn-sm">Edit</a>
                        <form action="{{ route('patient.reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this review?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No reviews found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('patient.reviews.create') }}" class="btn btn-info btn-sm mt-3">Write a Review</a>
@endsection
