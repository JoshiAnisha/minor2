<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Notifications\Notification;

class NewServiceRequestNotification extends Notification
{
    public function __construct(
        public ServiceRequest $serviceRequest
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $serviceName = $this->serviceRequest->service->name ?? 'Service';
        $patientName = $this->serviceRequest->user->name ?? 'A patient';

        return [
            'type' => 'new_service_request',
            'message' => "{$patientName} requested \"{$serviceName}\". View and respond in Service Requests.",
            'service_request_id' => $this->serviceRequest->id,
            'link' => route('caregiver.service.requests'),
        ];
    }
}
