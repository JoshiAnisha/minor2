@extends('admin.layouts.app')

@section('title', 'Invoice #' . ($invoice->invoice_number ?? '') . ' - Admin | SewaCare')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-3">
        <a href="{{ route('admin.invoices.index') }}" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Back to invoices</a>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
        <div class="card-header px-4 py-3" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: #fff;">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 fw-bold">Invoice #{{ $invoice->invoice_number ?? '—' }}</h5>
                    <span class="badge {{ ($invoice->status ?? '') === 'paid' ? 'bg-success' : 'bg-warning text-dark' }} mt-2">
                        {{ ucfirst($invoice->status ?? 'pending') }}
                    </span>
                </div>
                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    <span class="d-block small opacity-90">Total amount</span>
                    <span class="fw-bold fs-4">Rs {{ number_format((float) $invoice->amount, 0) }}</span>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-md-4">
                    <div class="p-3 rounded-3 bg-light">
                        <p class="text-muted small mb-1">Patient</p>
                        <p class="mb-0 fw-medium">
                            @if($invoice->booking && $invoice->booking->patient)
                                <a href="{{ route('admin.patients.show', $invoice->booking->patient) }}" class="text-decoration-none">{{ $invoice->user->name ?? optional($invoice->booking->patient->user)->name ?? '—' }}</a>
                            @else
                                {{ $invoice->user->name ?? '—' }}
                            @endif
                        </p>
                        <small class="text-muted">{{ $invoice->user->email ?? '' }}</small>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="p-3 rounded-3 bg-light">
                        <p class="text-muted small mb-1">Caregiver</p>
                        <p class="mb-0">
                            @if($invoice->booking && $invoice->booking->caregiver)
                                <a href="{{ route('admin.caregivers.show', $invoice->booking->caregiver) }}" class="text-decoration-none">{{ optional($invoice->booking->caregiver->user)->name ?? '—' }}</a>
                            @else
                                —
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="p-3 rounded-3 bg-light">
                        <p class="text-muted small mb-1">Service</p>
                        <p class="mb-0">{{ optional(optional($invoice->booking)->service)->name ?? '—' }}</p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="p-3 rounded-3 bg-light">
                        <p class="text-muted small mb-1">Issued on</p>
                        <p class="mb-0">{{ $invoice->created_at ? $invoice->created_at->format('d M Y') : '—' }}</p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="p-3 rounded-3 bg-light">
                        <p class="text-muted small mb-1">Due date</p>
                        <p class="mb-0">{{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '—' }}</p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="p-3 rounded-3 bg-light">
                        <p class="text-muted small mb-1">Paid date</p>
                        <p class="mb-0">{{ $invoice->paid_date ? $invoice->paid_date->format('d M Y') : '—' }}</p>
                    </div>
                </div>
            </div>
            @if($invoice->notes)
                <div class="p-3 rounded-3 bg-light">
                    <p class="text-muted small mb-1">Notes</p>
                    <p class="mb-0">{{ $invoice->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
