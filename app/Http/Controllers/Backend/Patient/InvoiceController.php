<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['booking.service', 'booking.caregiver.user'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();
        return view('backend.patient.invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = Invoice::with(['booking.service', 'booking.caregiver.user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        return view('backend.patient.invoices.show', compact('invoice'));
    }
}
