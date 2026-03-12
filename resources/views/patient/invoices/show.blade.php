@extends('patient.layouts.app')

@section('title', 'Invoice #' . $invoice->invoice_number)

@section('content')
<div class="mb-3">
    <a href="{{ route('patient.invoices.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center">
        <i class="bi bi-arrow-left me-1"></i> Back to invoices
    </a>
</div>

@php
    $booking = $invoice->booking;
    $service = $booking?->service;
    $isPaid = ($invoice->status === 'paid') || ((optional($booking)->payment_status ?? '') === 'paid');
@endphp

<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
    {{-- Invoice header --}}
    <div class="invoice-header px-4 py-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="h5 fw-bold mb-1 text-white">Invoice #{{ $invoice->invoice_number }}</h2>
                @if($isPaid)
                    <span class="badge bg-white text-success"><i class="bi bi-currency-dollar me-1"></i> Paid</span>
                @else
                    @if(optional($booking)->status === 'completed')
                        <span class="badge bg-white text-info"><i class="bi bi-check-lg me-1"></i> Completed by caregiver</span>
                    @else
                        <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                @endif
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                <span class="d-block text-white-50 small">Total amount</span>
                <span class="fw-bold fs-4 text-white">Rs {{ number_format((float) $invoice->amount, 0) }}</span>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        {{-- Service details --}}
        <h6 class="fw-bold mb-3 text-secondary"><i class="bi bi-bag me-1"></i> Service details</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="invoice-detail-block p-3 rounded-3 bg-light">
                    <p class="text-muted small mb-1">Service name</p>
                    <p class="mb-0 fw-medium">{{ optional($service)->name ?? 'Care service' }}</p>
                </div>
            </div>
            @if($service && ($service->details ?? null))
                <div class="col-12">
                    <div class="invoice-detail-block p-3 rounded-3 bg-light">
                        <p class="text-muted small mb-1">Description</p>
                        <p class="mb-0 small">{{ $service->details }}</p>
                    </div>
                </div>
            @endif
            <div class="col-sm-6 col-md-3">
                <div class="invoice-detail-block p-3 rounded-3 bg-light">
                    <p class="text-muted small mb-1">Type</p>
                    <p class="mb-0">{{ $service ? ucfirst(optional($service)->service_type ?? '—') : '—' }}</p>
                </div>
            </div>
            @if($service && ($service->category ?? null))
                <div class="col-sm-6 col-md-3">
                    <div class="invoice-detail-block p-3 rounded-3 bg-light">
                        <p class="text-muted small mb-1">Category</p>
                        <p class="mb-0">{{ optional($service)->category ?? '—' }}</p>
                    </div>
                </div>
            @endif
            <div class="col-sm-6 col-md-3">
                <div class="invoice-detail-block p-3 rounded-3 bg-light">
                    <p class="text-muted small mb-1">Duration</p>
                    <p class="mb-0">
                        @if($booking && optional($booking)->start_date && optional($booking)->end_date)
                            {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} – {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                        @else
                            @if($booking && optional($booking)->date_time)
                                {{ \Carbon\Carbon::parse($booking->date_time)->format('d M Y, h:i A') }}
                            @else
                                —
                            @endif
                        @endif
                    </p>
                </div>
            </div>
            @if($booking && optional($booking)->location)
                <div class="col-sm-6 col-md-3">
                    <div class="invoice-detail-block p-3 rounded-3 bg-light">
                        <p class="text-muted small mb-1">Location</p>
                        <p class="mb-0">{{ $booking->location }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Invoice & caregiver --}}
        <h6 class="fw-bold mb-3 text-secondary"><i class="bi bi-person me-1"></i> Invoice & caregiver</h6>
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-md-3">
                <div class="invoice-detail-block p-3 rounded-3 bg-light">
                    <p class="text-muted small mb-1">Caregiver</p>
                    <p class="mb-0">
                        @php
                            $caregiverName = ($booking && $booking->caregiver) ? (optional($booking->caregiver->user)->name ?? '—') : null;
                        @endphp
                        @if($caregiverName && $booking && $booking->caregiver)
                            <a href="{{ route('patient.caregiver.show', $booking->caregiver) }}" class="text-decoration-none">{{ $caregiverName }}</a>
                        @else
                            {{ $caregiverName ?? '—' }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="invoice-detail-block p-3 rounded-3 bg-light">
                    <p class="text-muted small mb-1">Issued on</p>
                    <p class="mb-0">{{ $invoice->created_at->format('d M Y') }}</p>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="invoice-detail-block p-3 rounded-3 bg-light">
                    <p class="text-muted small mb-1">Due date</p>
                    <p class="mb-0">{{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Payment status (when caregiver marked as paid) --}}
        @if($isPaid)
            <div class="rounded-3 border border-success border-2 p-4 bg-success bg-opacity-10">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-25 p-3">
                        <i class="bi bi-currency-dollar text-success fs-4"></i>
                    </div>
                    <div>
                        <p class="fw-bold text-success mb-0">Paid</p>
                        <p class="text-muted small mb-0">Payment was marked as received by your caregiver{{ $invoice->paid_date ? ' on ' . $invoice->paid_date->format('d F Y') : '' }}.</p>
                    </div>
                </div>
            </div>
        @else
            @if(optional($booking)->status === 'completed')
                <div class="rounded-3 border border-info border-2 p-4 bg-info bg-opacity-10">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-info bg-opacity-25 p-3">
                            <i class="bi bi-check2-circle text-info fs-4"></i>
                        </div>
                        <div>
                            <p class="fw-bold text-info mb-0">Completed by caregiver</p>
                            <p class="text-muted small mb-0">This booking was marked completed. Payment not yet marked as received.</p>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

<style>
    .invoice-header { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .invoice-detail-block { transition: background 0.15s; }
    .invoice-detail-block:hover { background: #e0f2fe !important; }
</style>
@endsection
