<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $fillable = [
        'patient_id',
        'service_id',
        'location',
        'preferred_time',
        'description',
        'status',
        'shift_type',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'service_request_id');
    }
}
