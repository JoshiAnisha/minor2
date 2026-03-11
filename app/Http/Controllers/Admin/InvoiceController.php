<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * List all invoices with related data (patient, booking, service, caregiver).
     */
    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized. Admin access required.');
        }

        $invoices = Invoice::with(['user', 'booking.service', 'booking.caregiver.user', 'booking.patient.user'])
            ->orderByDesc('created_at')
            ->get();

        $total = $invoices->count();
        $paid = $invoices->where('status', 'paid')->count();
        $pending = $invoices->filter(function ($inv) {
            return ($inv->status ?? '') !== 'paid';
        })->count();
        $totalAmount = $invoices->sum('amount');
        $paidAmount = $invoices->where('status', 'paid')->sum('amount');

        return view('admin.invoices.index', compact(
            'invoices',
            'total',
            'paid',
            'pending',
            'totalAmount',
            'paidAmount'
        ));
    }

    /**
     * Show a single invoice (optional detail view).
     */
    public function show($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized. Admin access required.');
        }

        $invoice = Invoice::with(['user', 'booking.service', 'booking.caregiver.user', 'booking.patient.user'])
            ->findOrFail($id);

        return view('admin.invoices.show', compact('invoice'));
    }
}
