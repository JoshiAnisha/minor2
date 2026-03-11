@extends('admin.layouts.app')

@section('title', 'Invoices - Admin | SewaCare')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-12">
            <h2 class="mb-4">Invoices</h2>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2 small" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Summary cards --}}
            <div class="row g-4 mb-4">
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #0dcaf0 !important;">
                        <h6 class="text-muted mb-1">Total invoices</h6>
                        <h4 class="mb-0">{{ $total }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #198754 !important;">
                        <h6 class="text-muted mb-1">Paid</h6>
                        <h4 class="mb-0">{{ $paid }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #ffc107 !important;">
                        <h6 class="text-muted mb-1">Pending</h6>
                        <h4 class="mb-0">{{ $pending }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #0d6efd !important;">
                        <h6 class="text-muted mb-1">Total amount</h6>
                        <h4 class="mb-0">Rs {{ number_format($totalAmount, 0) }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid #20c997 !important;">
                        <h6 class="text-muted mb-1">Paid amount</h6>
                        <h4 class="mb-0">Rs {{ number_format($paidAmount, 0) }}</h4>
                    </div>
                </div>
            </div>

            <div class="card border shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All invoices</h5>
                    <span class="badge bg-secondary">{{ $invoices->count() }} invoice(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>SN</th>
                                    <th>Invoice #</th>
                                    <th>Patient</th>
                                    <th>Service</th>
                                    <th>Caregiver</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Due date</th>
                                    <th>Paid date</th>
                                    <th>Issued on</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($invoices as $index => $invoice)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $invoice->invoice_number ?? '—' }}</strong></td>
                                        <td>
                                            @if($invoice->booking && $invoice->booking->patient)
                                                <a href="{{ route('admin.patients.show', $invoice->booking->patient) }}" class="text-decoration-none">{{ $invoice->user->name ?? optional($invoice->booking->patient->user)->name ?? '—' }}</a>
                                                <br><small class="text-muted">{{ $invoice->user->email ?? optional($invoice->booking->patient->user)->email ?? '' }}</small>
                                            @elseif($invoice->user)
                                                {{ $invoice->user->name ?? '—' }}
                                                <br><small class="text-muted">{{ $invoice->user->email ?? '' }}</small>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ optional(optional($invoice->booking)->service)->name ?? '—' }}</td>
                                        <td>
                                            @if($invoice->booking && $invoice->booking->caregiver && $invoice->booking->caregiver->user)
                                                <a href="{{ route('admin.caregivers.show', $invoice->booking->caregiver) }}" class="text-decoration-none">{{ $invoice->booking->caregiver->user->name ?? '—' }}</a>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="fw-semibold">Rs {{ number_format((float) $invoice->amount, 0) }}</td>
                                        <td>
                                            @if(($invoice->status ?? '') === 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif(($invoice->status ?? '') === 'overdue')
                                                <span class="badge bg-danger">Overdue</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '—' }}</td>
                                        <td>{{ $invoice->paid_date ? $invoice->paid_date->format('d M Y') : '—' }}</td>
                                        <td>{{ $invoice->created_at ? $invoice->created_at->format('d M Y') : '—' }}</td>
                                        <td>
                                            <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-4 text-muted">No invoices yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
