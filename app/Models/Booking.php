<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

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

    protected $casts = [
        'date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($booking) {
            if (Carbon::parse($booking->date)->isPast()) {
                throw new \Exception('Past dates are not allowed.');
            }
        });
    }

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