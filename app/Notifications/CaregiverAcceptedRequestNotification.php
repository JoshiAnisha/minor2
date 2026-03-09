<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Notification;

class CaregiverAcceptedRequestNotification extends Notification
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
        $link = $bookingId ? route('patient.bookings.show', ['id' => $bookingId]) : route('patient.bookings.index');
        return [
            'type' => 'caregiver_accepted',
            'message' => "{$this->caregiverName} accepted your service request at base price. Booking created.",
            'booking_id' => $bookingId,
            'link' => $link,
        ];
    }
}
