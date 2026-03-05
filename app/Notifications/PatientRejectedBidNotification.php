<?php

namespace App\Notifications;

use App\Models\Bid;
use Illuminate\Notifications\Notification;

class PatientRejectedBidNotification extends Notification
{
    public function __construct(
        public Bid $bid,
        public string $patientName
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'patient_rejected_bid',
            'message' => "{$this->patientName} declined your bid of Rs " . number_format($this->bid->proposed_price, 2) . ".",
            'service_request_id' => $this->bid->service_request_id,
            'link'    => route('caregiver.service.requests'),
        ];
    }
}
