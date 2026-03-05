<?php

namespace App\Notifications;

use App\Models\Service;
use Illuminate\Notifications\Notification;

class NewServiceOpenedNotification extends Notification
{

    public function __construct(
        public Service $service
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $link = $notifiable->role === 'patient'
            ? route('patient.services.index')
            : route('caregiver.service.requests');

        return [
            'type' => 'new_service_opened',
            'message' => "New service \"{$this->service->name}\" is now available. You can request or respond to requests.",
            'service_id' => $this->service->id,
            'link' => $link,
        ];
    }
}
