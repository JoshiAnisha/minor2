@extends('admin.layouts.app')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 col-lg-10 p-4">
                <h2 class="mb-4">Feedback Management</h2>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>SN</th>
                                <th>Reviewer</th>
                                <th>Reviewed</th>
                                <th>Rating</th>
                                <th>Feedback</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $index => $review)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ optional($review->reviewer)->name ?? 'Unknown' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            @if ($review->reviewer)
                                                @if ($review->reviewer->role === 'caregiver')
                                                    <span class="badge bg-info">Caregiver</span>
                                                @elseif($review->reviewer->role === 'patient')
                                                    <span class="badge bg-primary">Patient</span>
                                                @endif
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if ($review->reviewed_party_type === 'patient')
                                            <strong>{{ optional($review->reviewed_party->user)->name ?? 'Unknown Patient' }}</strong>
                                            <br>
                                            <small class="text-muted"><span class="badge bg-primary">Patient</span></small>
                                        @elseif($review->reviewed_party_type === 'caregiver')
                                            <strong>{{ optional($review->reviewed_party->user)->name ?? 'Unknown Caregiver' }}</strong>
                                            <br>
                                            <small class="text-muted"><span class="badge bg-info">Caregiver</span></small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($review->rating)
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <span class="text-warning">★</span>
                                                @else
                                                    <span class="text-muted">★</span>
                                                @endif
                                            @endfor
                                            <br>
                                            <small class="text-muted">({{ $review->rating }}/5)</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $review->comments ?? 'No comment' }}</td>
                                    <td>{{ optional($review->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                    <td>
                                        <form action="{{ route('admin.feedback.delete', $review->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this feedback? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted mb-0">No feedback found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
