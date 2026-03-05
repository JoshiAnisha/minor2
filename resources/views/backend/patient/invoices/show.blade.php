@extends('backend.layouts.dashboard.app')

@section('title', 'Invoice #' . $invoice->invoice_number)

@section('content')
<div class="mb-3">
    <a href="{{ route('patient.invoices.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center">
        <i class="bi bi-arrow-left me-1"></i> Back to invoices
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
    {{-- Invoice header --}}
    <div class="invoice-header px-4 py-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="h5 fw-bold mb-1 text-white">Invoice #{{ $invoice->invoice_number }}</h2>
                <span class="badge
                    @if($invoice->status === 'paid') bg-white text-success
                    @elseif($invoice->status === 'overdue') bg-danger
                    @else bg-warning text-dark @endif">
                    {{ ucfirst($invoice->status) }}
                </span>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                <span class="d-block text-white-50 small">Total amount</span>
                <span class="fw-bold fs-4 text-white">Rs {{ number_format((float) $invoice->amount, 0) }}</span>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        {{-- Details grid --}}
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-md-3">
                <div class="invoice-detail-block p-3 rounded-3 bg-light">
                    <p class="text-muted small mb-1">Service</p>
                    <p class="mb-0 fw-medium">{{ optional(optional($invoice->booking)->service)->name ?? 'Care service' }}</p>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="invoice-detail-block p-3 rounded-3 bg-light">
                    <p class="text-muted small mb-1">Caregiver</p>
                    <p class="mb-0">{{ optional(optional(optional($invoice->booking)->caregiver)->user)->name ?? '—' }}</p>
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

        @if($invoice->status === 'paid')
            {{-- Payment confirmation --}}
            <div class="rounded-3 border border-success border-2 p-4 bg-success bg-opacity-10">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-25 p-3">
                        <i class="bi bi-check2-circle text-success fs-4"></i>
                    </div>
                    <div>
                        <p class="fw-bold text-success mb-0">Payment completed</p>
                        <p class="text-muted small mb-0">Paid on {{ $invoice->paid_date ? $invoice->paid_date->format('d F Y') : '—' }}</p>
                    </div>
                </div>
            </div>
        @else
            {{-- Mark as paid --}}
            <div class="rounded-3 border border-primary border-2 p-4 bg-primary bg-opacity-10">
                <p class="fw-medium mb-2">Have you sent the payment?</p>
                <p class="text-muted small mb-3">Click below to record this invoice as paid. The caregiver will be notified.</p>
                <form action="{{ route('patient.invoices.mark-paid', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('Mark this invoice as paid?');">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i> Mark as paid
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

<style>
    .invoice-header { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .invoice-detail-block { transition: background 0.15s; }
    .invoice-detail-block:hover { background: #e0f2fe !important; }
</style>
@endsection
