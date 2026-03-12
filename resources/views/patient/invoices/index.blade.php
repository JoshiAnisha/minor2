@extends('patient.layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">Invoices</h1>
    <p class="text-muted small mb-0">Invoices for care completed by your caregiver.</p>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif
@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        {{ session('info') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

@forelse($invoices as $invoice)
    <div class="card border-0 shadow-sm mb-3 invoice-card" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="row g-0 align-items-stretch">
                <div class="col-lg-8 p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="invoice-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0">
                            <i class="bi bi-receipt-cutoff text-white"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-muted small mb-0">Invoice #{{ $invoice->invoice_number }}</p>
                            <h5 class="fw-bold mb-1 mt-0">{{ optional(optional($invoice->booking)->service)->name ?? 'Care service' }}</h5>
                            @if(optional($invoice->booking)->service)
                                <p class="text-muted small mb-0">
                                    @if(optional($invoice->booking->service)->category)<span class="me-2">{{ $invoice->booking->service->category }}</span>@endif
                                    @if(optional($invoice->booking->service)->service_type)<span class="badge bg-light text-dark">{{ ucfirst($invoice->booking->service->service_type) }}</span>@endif
                                </p>
                            @endif
                            <p class="text-muted small mb-0">
                                <i class="bi bi-person me-1"></i>
                                @if($invoice->booking && $invoice->booking->caregiver)
                                    <a href="{{ route('patient.caregiver.show', $invoice->booking->caregiver) }}" class="text-decoration-none">{{ optional($invoice->booking->caregiver->user)->name ?? 'Caregiver' }}</a>
                                @else
                                    —
                                @endif
                                <span class="ms-2"><i class="bi bi-calendar3 me-1"></i>{{ $invoice->created_at->format('d M Y') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 bg-light bg-opacity-50 p-4 d-flex flex-column justify-content-center align-items-lg-end">
                    <div class="mb-2 mb-lg-0 text-lg-end">
                        <span class="d-block text-muted small">Amount</span>
                        <span class="fw-bold fs-5 text-primary">Rs {{ number_format((float) $invoice->amount, 0) }}</span>
                    </div>
                    @if(($invoice->status === 'paid') || (optional($invoice->booking)->payment_status === 'paid'))
                        <span class="badge bg-success px-3 py-2 mb-2"><i class="bi bi-currency-dollar me-1"></i> Paid</span>
                    @elseif(optional($invoice->booking)->status === 'completed')
                        <span class="badge bg-info px-3 py-2 mb-2"><i class="bi bi-check-lg me-1"></i> Completed by caregiver</span>
                    @endif
                    <a href="{{ route('patient.invoices.show', $invoice->id) }}" class="btn btn-outline-primary btn-sm mt-1">View details</a>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="card border-0 shadow-sm text-center py-5 px-4 empty-invoices">
        <div class="invoice-empty-icon mx-auto mb-3">
            <i class="bi bi-receipt"></i>
        </div>
        <h5 class="text-muted mb-2">No invoices yet</h5>
        <p class="text-muted small mb-4" style="max-width: 320px; margin-left: auto; margin-right: auto;">Invoices appear here after a caregiver marks your booking as completed.</p>
        <a href="{{ route('patient.bookings.index') }}" class="btn btn-outline-primary btn-sm">View my bookings</a>
    </div>
@endforelse

<style>
    .invoice-card { transition: box-shadow 0.2s ease; }
    .invoice-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08) !important; }
    .invoice-icon { width: 48px; height: 48px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .empty-invoices { border-radius: 16px; }
    .invoice-empty-icon { width: 80px; height: 80px; border-radius: 50%; background: #f0f9ff; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: #0ea5e9; opacity: 0.7; }
</style>
@endsection

