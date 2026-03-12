@extends(auth()->user()->role === 'patient' ? 'patient.layouts.app' : 'Caregiver.layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Notifications</h1>
    <p class="text-muted mb-0">Your recent notifications</p>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@php
    $routePrefix = auth()->user()->role === 'patient' ? 'patient' : 'caregiver';
@endphp
<div class="d-flex justify-content-end mb-3">
    <form action="{{ route($routePrefix . '.notifications.mark-all-read') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-outline-secondary btn-sm">Mark all as read</button>
    </form>
</div>

@forelse ($notifications as $notification)
    <div class="card border-0 shadow-sm mb-2 {{ $notification->read_at ? '' : 'border-start border-3 border-primary' }}" style="border-radius: 12px;">
        <div class="card-body py-3 d-flex justify-content-between align-items-center">
            <div>
                <p class="mb-0">{{ $notification->data['message'] ?? 'Notification' }}</p>
                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
            </div>
            @if (($notification->data['link'] ?? null) && !$notification->read_at)
                <form action="{{ route($routePrefix . '.notifications.mark-read', $notification->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="redirect" value="1">
                    <button type="submit" class="btn btn-sm btn-primary">View</button>
                </form>
            @endif
        </div>
    </div>
@empty
    <div class="card border-0 shadow-sm text-center py-5">
        <i class="bi bi-bell text-muted display-4"></i>
        <p class="text-muted mt-3 mb-0">No notifications yet</p>
    </div>
@endforelse

{{ $notifications->links() }}
@endsection
