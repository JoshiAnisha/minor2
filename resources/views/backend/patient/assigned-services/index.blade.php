@extends('backend.layouts.dashboard.app')

@section('title', 'Assigned Services')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Assigned Services</h1>
    <p class="text-muted mb-0">View services assigned by admin. Accept or reject caregiver bids.</p>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@forelse ($assignedServices as $as)
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <h5 class="mb-1">{{ $as->title }}</h5>
                <span class="badge 
                    @if($as->status === 'pending') bg-warning text-dark
                    @elseif($as->status === 'accepted') bg-success
                    @elseif($as->status === 'completed') bg-secondary
                    @else bg-danger @endif">
                    {{ ucfirst($as->status) }}
                </span>
            </div>
            <p class="text-muted small mb-2">{{ $as->service->name ?? 'Service' }} · {{ optional($as->preferred_date)?->format('d M Y') ?? '-' }} · {{ $as->location ?? '-' }}</p>
            @if ($as->description)
                <p class="small mb-0">{{ $as->description }}</p>
            @endif

            @if ($as->status === 'pending' && $as->bids->where('status', 'pending')->isNotEmpty())
                <hr>
                <h6 class="mb-2">Caregiver bids</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Caregiver</th>
                                <th>Price</th>
                                <th>Message</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($as->bids->where('status', 'pending') as $bid)
                                <tr>
                                    <td><a href="{{ route('patient.caregiver.show', $bid->caregiver) }}" class="text-decoration-none">{{ optional($bid->caregiver->user)->name ?? 'N/A' }}</a></td>
                                    <td class="fw-bold">Rs {{ number_format($bid->proposed_price, 2) }}</td>
                                    <td class="text-muted small">{{ $bid->message ? \Str::limit($bid->message, 50) : '—' }}</td>
                                    <td>
                                        <form action="{{ route('patient.assigned-services.accept-bid', $bid) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Accept</button>
                                        </form>
                                        <form action="{{ route('patient.assigned-services.reject-bid', $bid) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if ($as->status === 'accepted' && $as->assignedCaregiver)
                <p class="mb-0 mt-2 text-success"><i class="bi bi-check-circle me-1"></i> Assigned to <a href="{{ route('patient.caregiver.show', $as->assignedCaregiver) }}" class="text-decoration-none">{{ optional($as->assignedCaregiver->user)->name ?? 'Caregiver' }}</a></p>
            @endif
        </div>
    </div>
@empty
    <div class="card border-0 shadow-sm text-center py-5">
        <i class="bi bi-clipboard-plus text-muted display-4"></i>
        <p class="text-muted mt-3 mb-0">No services assigned yet</p>
    </div>
@endforelse
@endsection
