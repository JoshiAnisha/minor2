<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CaregiverAcceptedRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
            'type' => 'caregiver_accepted',
            'message' => "{$this->caregiverName} accepted your service request at base price. Booking created.",
            'booking_id' => $this->booking->id,
            'link' => route('patient.bookings.show', $this->booking->id),
        ];
    }
}
