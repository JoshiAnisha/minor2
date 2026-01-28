@extends('backend.layouts.dashboard.app')

@section('title', 'Invoices')

@section('content')
    <h3 class="mb-4">My Invoices</h3>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Invoice ID</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>#INV-{{ $invoice->id }}</td>
                    <td>${{ $invoice->amount }}</td>
                    <td>{{ ucfirst($invoice->status) }}</td>
                    <td>{{ $invoice->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('patient.invoices.show', $invoice->id) }}" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No invoices found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
