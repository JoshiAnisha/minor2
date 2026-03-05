<?php

namespace App\Notifications;

use App\Models\AssignedServiceBid;
use Illuminate\Notifications\Notification;

class PatientAcceptedAssignedBidNotification extends Notification
{

    public function __construct(
        public AssignedServiceBid $bid,
        public string $patientName
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'patient_accepted_assigned_bid',
            'message' => "{$this->patientName} accepted your bid (Rs " . number_format($this->bid->proposed_price, 2) . ") on the assigned service. You have been assigned.",
            'assigned_service_id' => $this->bid->assigned_service_id,
            'link' => route('caregiver.assigned-services.my-bids'),
        ];
    }
}
