<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Notifications\Notification;

class PatientPaidInvoiceNotification extends Notification
{

    public function __construct(
        public Invoice $invoice,
        public string $patientName
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $amount = $this->invoice->amount ? number_format((float) $this->invoice->amount, 0) : '0';
        return [
            'type'    => 'patient_paid_invoice',
            'message' => "{$this->patientName} paid Rs {$amount} for the completed booking.",
            'invoice_id' => $this->invoice->id,
            'booking_id' => $this->invoice->booking_id,
            'link'    => route('caregiver.bookings'),
        ];
    }
}
