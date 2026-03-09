<?php

namespace App\Notifications;

use App\Models\Bid;
use App\Models\Booking;
use Illuminate\Notifications\Notification;

class PatientAcceptedBidNotification extends Notification
{

    public function __construct(
        public Bid $bid,
        public string $patientName,
        public Booking $booking
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'patient_accepted_bid',
            'message' => "{$this->patientName} accepted your bid. Booking created for Rs " . number_format($this->bid->proposed_price, 2) . ".",
            'booking_id' => $this->booking->getKey(),
            'link' => route('caregiver.bookings'),
        ];
    }
}
