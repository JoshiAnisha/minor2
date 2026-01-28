<?php

namespace App\Models;

use App\Models\Service;
use App\Models\Caregiver;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Model;


class Booking extends Model
{
    protected $primaryKey = 'booking_id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'service_request_id',
        'patients_id',
        'caregivers_id',
        'services_id',
        'status',
        'price',
        'location',
        'date_time',
        'duration_type',
        'start_date',
        'end_date',
        'payment_status',
    ];

    // Patient relationship (go through patients table to user)
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patients_id');
    }

    // Caregiver relationship (optional, if you want the name)
    public function caregiver()
    {
        return $this->belongsTo(Caregiver::class, 'caregivers_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'services_id');
    }

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }
}
