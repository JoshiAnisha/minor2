<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Notification;

class CaregiverMarkedPaymentReceivedNotification extends Notification
{
    public function __construct(
        public Booking $booking,
        public string $caregiverName
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $amount = $this->booking->price ? number_format((float) $this->booking->price, 0) : '0';
        return [
            'type'    => 'caregiver_marked_paid',
            'message' => "{$this->caregiverName} marked payment of Rs {$amount} as received for your completed booking.",
            'booking_id' => $this->booking->id,
            'link'    => route('patient.bookings.show', $this->booking->id),
        ];
    }
}
