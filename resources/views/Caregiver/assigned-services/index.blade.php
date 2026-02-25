@extends('Caregiver.layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Assigned Services</h1>
    <p class="text-muted mb-0">Place bids on services assigned by admin. Patients will review and accept one caregiver.</p>
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

<div class="mb-4">
    <a href="{{ route('caregiver.assigned-services.my-bids') }}" class="btn btn-outline-primary">View my bids</a>
</div>

@if ($assignedServices->isEmpty())
    <div class="card border-0 shadow-sm text-center py-5">
        <i class="bi bi-clipboard-plus text-muted display-4"></i>
        <p class="text-muted mt-3 mb-0">No services to bid on at the moment</p>
    </div>
@else
    <div class="row g-4">
        @foreach ($assignedServices as $as)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $as->title }}</h5>
                        <p class="text-muted small mb-2">{{ $as->service->name ?? 'Service' }}</p>
                        <p class="small mb-2"><i class="bi bi-geo-alt me-1"></i> {{ $as->location ?? '-' }}</p>
                        <p class="small mb-2"><i class="bi bi-calendar me-1"></i> {{ optional($as->preferred_date)?->format('d M Y') ?? '-' }}</p>
                        @if ($as->budget)
                            <p class="small mb-2"><i class="bi bi-currency-dollar me-1"></i> Rs {{ number_format($as->budget, 2) }}</p>
                        @endif
                        @if ($as->description)
                            <p class="small text-muted mb-3">{{ \Str::limit($as->description, 120) }}</p>
                        @endif

                        <form action="{{ route('caregiver.assigned-services.place-bid') }}" method="POST">
                            @csrf
                            <input type="hidden" name="assigned_service_id" value="{{ $as->id }}">
                            <div class="mb-2">
                                <label class="form-label small">Your price (Rs) <span class="text-danger">*</span></label>
                                <input type="number" name="proposed_price" class="form-control" step="0.01" min="0.01" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">Message (optional)</label>
                                <textarea name="message" class="form-control" rows="2" placeholder="Brief note"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Place bid</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
