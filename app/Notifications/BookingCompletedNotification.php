<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Notification;

class BookingCompletedNotification extends Notification
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
        return [
            'type' => 'booking_completed',
            'message' => "{$this->caregiverName} marked your booking as completed. You can pay from your Invoices page.",
            'booking_id' => $this->booking->getKey(),
            'link' => route('patient.invoices.index'),
        ];
    }
}
