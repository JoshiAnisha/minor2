<?php

namespace App\Notifications;

use App\Models\AssignedServiceBid;
use Illuminate\Notifications\Notification;

class CaregiverPlacedAssignedBidNotification extends Notification
{

    public function __construct(
        public AssignedServiceBid $bid,
        public string $caregiverName
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'caregiver_placed_assigned_bid',
            'message' => "{$this->caregiverName} placed a bid of Rs " . number_format($this->bid->proposed_price, 2) . " on your assigned service.",
            'assigned_service_id' => $this->bid->assigned_service_id,
            'link' => route('patient.assigned-services.index'),
        ];
    }
}
