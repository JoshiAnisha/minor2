@extends('admin.layouts.app')
@section('content')

    <div class="container-fluid px-0">
        <div class="row g-0">
            <div class="col-12">
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
                                        @php
                                            $reviewerPatient = $review->user && $review->user->role === 'patient' ? \App\Models\Patient::where('user_id', $review->user->id)->first() : null;
                                            $reviewerCaregiver = $review->user && $review->user->role === 'caregiver' ? \App\Models\Caregiver::where('users_id', $review->user->id)->first() : null;
                                        @endphp
                                        @if($reviewerPatient)
                                            <a href="{{ route('admin.patients.show', $reviewerPatient) }}" class="text-decoration-none fw-semibold">{{ $review->user->name ?? 'Unknown' }}</a>
                                        @elseif($reviewerCaregiver)
                                            <a href="{{ route('admin.caregivers.show', $reviewerCaregiver) }}" class="text-decoration-none fw-semibold">{{ $review->user->name ?? 'Unknown' }}</a>
                                        @else
                                            <strong>{{ optional($review->user)->name ?? 'Unknown' }}</strong>
                                        @endif
                                        <br>
                                        <small class="text-muted">
                                            @if ($review->user)
                                                @if ($review->user->role === 'caregiver')
                                                    <span class="badge bg-info">Caregiver</span>
                                                @elseif($review->user->role === 'patient')
                                                    <span class="badge bg-primary">Patient</span>
                                                @endif
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if ($review->reviewed_party_type === 'patient')
                                            @php $revPatient = $review->reviewed_party ? \App\Models\Patient::where('user_id', $review->reviewed_party->id)->first() : null; @endphp
                                            @if($revPatient)
                                                <a href="{{ route('admin.patients.show', $revPatient) }}" class="text-decoration-none fw-semibold">{{ $review->reviewed_party->name ?? 'Unknown Patient' }}</a>
                                            @else
                                                <strong>{{ optional($review->reviewed_party)->name ?? 'Unknown Patient' }}</strong>
                                            @endif
                                            <br>
                                            <small class="text-muted"><span class="badge bg-primary">Patient</span></small>
                                        @elseif($review->reviewed_party_type === 'caregiver')
                                            <a href="{{ route('admin.caregivers.show', $review->reviewed_party) }}" class="text-decoration-none fw-semibold">{{ optional($review->reviewed_party->user)->name ?? 'Unknown Caregiver' }}</a>
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
                                    <td>{{ $review->comment ?? 'No comment' }}</td>
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
