<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Notifications\PatientPaidInvoiceNotification;
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

    /**
     * Patient marks invoice as paid (records payment transaction, updates caregiver).
     */
    public function markPaid(Invoice $invoice)
    {
        if ($invoice->user_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }
        if ($invoice->status === 'paid') {
            return back()->with('info', 'This invoice is already marked as paid.');
        }

        $invoice->update([
            'status'    => 'paid',
            'paid_date' => now(),
        ]);

        if ($invoice->booking_id) {
            $invoice->booking->update(['payment_status' => 'paid']);
        }

        $caregiverUser = $invoice->booking?->caregiver?->user;
        if ($caregiverUser) {
            $caregiverUser->notify(new PatientPaidInvoiceNotification(
                $invoice,
                Auth::user()->name ?? 'Patient'
            ));
        }

        return back()->with('success', 'Payment recorded. The caregiver has been notified.');
    }
}
