@extends('Caregiver.layouts.app')

@section('content')
    <style>
        .card {
            border-left: 5px solid #0dcaf0;
        }

        .badge {
            font-size: 0.9rem;
        }
    </style>

    <div class="card shadow-sm p-4">
        <h2 class="mb-4">My Bookings</h2>

        {{-- Pending Requests (Bids & Pending Service Requests) --}}
        @forelse ($pendingBookings as $index => $item)
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $isBid = $item instanceof \App\Models\Bids;
                            $sr = $isBid ? $item->serviceRequest : $item;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ optional($sr->patient)->name ?? 'N/A' }}</td>
                            <td>{{ optional($sr->service)->name ?? 'N/A' }}</td>
                            <td>{{ $sr->preferred_time ? \Carbon\Carbon::parse($sr->preferred_time)->format('Y-m-d') : '-' }}
                            </td>
                            <td>{{ $sr->preferred_time ? \Carbon\Carbon::parse($sr->preferred_time)->format('h:i A') : '-' }}
                            </td>
                            <td>{{ $sr->location ?? '-' }}</td>
                            <td>Rs. {{ $isBid ? $item->proposed_price : $sr->service->base_price ?? 0 }}</td>
                            <td>
                                @if ($isBid)
                                    <form action="{{ route('caregiver.bookings.acceptBid', $item->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Accept</button>
                                    </form>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @empty
            <p class="text-muted">No pending requests.</p>
        @endforelse

        {{-- Accepted / In Progress --}}
        <h5 class="mt-5 text-info">Accepted / In Progress</h5>
        @forelse ($acceptedBookings as $index => $booking)
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-hover bg-light">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ optional($booking->patient->user)->name ?? 'N/A' }}</td>
                            <td>{{ optional($booking->service)->name ?? 'N/A' }}</td>
                            <td>{{ $booking->date_time ? \Carbon\Carbon::parse($booking->date_time)->format('Y-m-d') : '-' }}
                            </td>
                            <td>{{ $booking->date_time ? \Carbon\Carbon::parse($booking->date_time)->format('h:i A') : '-' }}
                            </td>
                            <td>Rs. {{ $booking->price ?? 0 }}</td>
                            <td><span class="badge bg-info">In Progress</span></td>
                            <td>
                                <form action="{{ route('caregiver.bookings.complete', $booking->booking_id) }}"
                                    method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Mark Completed</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @empty
            <p class="text-muted">No accepted bookings.</p>
        @endforelse

        {{-- Completed --}}
        <h5 class="mt-5 text-success">Completed Bookings</h5>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @forelse ($completedBookings as $index => $booking)
            <div class="row mb-4">
                {{-- Left: Booking Details --}}
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Patient</th>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if (!empty($booking->patients_id))
                                            <a href="{{ route('caregiver.patients.show', $booking->patients_id) }}">
                                                {{ optional($booking->patient->user)->name ?? 'N/A' }}
                                            </a>
                                        @else
                                            {{ optional($booking->patient->user)->name ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td>{{ optional($booking->service)->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->date_time ? \Carbon\Carbon::parse($booking->date_time)->format('Y-m-d') : '-' }}
                                    </td>
                                    <td>{{ $booking->date_time ? \Carbon\Carbon::parse($booking->date_time)->format('h:i A') : '-' }}
                                    </td>
                                    <td>Rs. {{ $booking->price ?? 0 }}</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Right: Leave Review Button --}}
                <div class="col-md-4 d-flex align-items-center justify-content-center">
                    @if (!empty($booking->patients_id))
                        <a href="{{ route('caregiver.reviews.create', $booking->patients_id) }}"
                            class="btn btn-primary btn-lg">
                            Leave Review
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted">No completed bookings.</p>
        @endforelse

    </div>
@endsection
