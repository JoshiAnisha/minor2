<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Notifications\Notification;

/** Sent to the patient when they submit a service request (confirmation). */
class ServiceRequestSubmittedNotification extends Notification
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

        return [
            'type' => 'service_request_submitted',
            'message' => "Your request for \"{$serviceName}\" was submitted. Caregivers can now accept or place bids.",
            'service_request_id' => $this->serviceRequest->id,
            'link' => route('patient.service-requests.index'),
        ];
    }
}
