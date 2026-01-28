<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['user', 'booking'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();
        return view('backend.patient.invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = Invoice::with(['user', 'booking'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        return view('backend.patient.invoices.show', compact('invoice'));
    }
}
