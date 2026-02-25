<?php

namespace App\Notifications;

use App\Models\Bid;
use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CaregiverPlacedBidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Bid $bid,
        public string $caregiverName,
        public ServiceRequest $serviceRequest
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'caregiver_bid',
            'message' => "{$this->caregiverName} placed a bid of Rs " . number_format($this->bid->proposed_price, 2) . " on your service request.",
            'service_request_id' => $this->serviceRequest->id,
            'bid_id' => $this->bid->id,
            'link' => route('patient.service-requests.index'),
        ];
    }
}
