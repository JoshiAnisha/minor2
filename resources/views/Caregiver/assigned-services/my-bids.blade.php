@extends('Caregiver.layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">My Bids</h1>
    <p class="text-muted mb-0">Track the status of your bids on assigned services</p>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="mb-4">
    <a href="{{ route('caregiver.assigned-services.index') }}" class="btn btn-outline-primary">Browse services</a>
</div>

@if ($bids->isEmpty())
    <div class="card border-0 shadow-sm text-center py-5">
        <i class="bi bi-clipboard-x text-muted display-4"></i>
        <p class="text-muted mt-3 mb-0">No bids placed yet</p>
    </div>
@else
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Service</th>
                            <th>Patient</th>
                            <th>Your price</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bids as $bid)
                            <tr>
                                <td>
                                    <strong>{{ optional($bid->assignedService)->title ?? '-' }}</strong>
                                    <br><small class="text-muted">{{ optional(optional($bid->assignedService)->service)->name ?? '' }}</small>
                                </td>
                                <td>{{ optional(optional($bid->assignedService)->patient)->name ?? 'N/A' }}</td>
                                <td>Rs {{ number_format($bid->proposed_price, 2) }}</td>
                                <td>
                                    @if ($bid->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif ($bid->status === 'accepted')
                                        <span class="badge bg-success">Accepted</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $bid->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection
