@extends('backend.layouts.dashboard.app')

@section('title', 'Invoice Details')

@section('content')
    <h3 class="mb-4">Invoice Details</h3>

    <div class="card p-4 shadow-sm">
        <p><strong>Invoice ID:</strong> #INV-{{ $invoice->id }}</p>
        <p><strong>Amount:</strong> ${{ $invoice->amount }}</p>
        <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
        <p><strong>Issued On:</strong> {{ $invoice->created_at->format('d M Y') }}</p>
    </div>
@endsection
