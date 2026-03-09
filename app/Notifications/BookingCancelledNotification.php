<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Notification;

class BookingCancelledNotification extends Notification
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
        $bookingId = $this->booking->getKey();
        return [
            'type' => 'booking_cancelled',
            'message' => "{$this->caregiverName} cancelled your booking for " . (optional($this->booking->service)->name ?? 'service') . ". You can see it in My Bookings under Cancelled requests.",
            'booking_id' => $bookingId,
            'link' => $bookingId ? route('patient.bookings.show', ['id' => $bookingId]) : route('patient.bookings.index'),
        ];
    }
}
