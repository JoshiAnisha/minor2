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

    protected $casts = [
        'preferred_time' => 'datetime',
    ];

    /**
     * patient_id stores user_id (FK to users table).
     * Use patient() to get the Patient model via User.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'patient_id');
    }

    public function patient()
    {
        return $this->hasOneThrough(
            Patient::class,
            \App\Models\User::class,
            'id',       // FK on users
            'user_id',  // FK on patients
            'patient_id',
            'id'
        );
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'service_request_id');
    }

    public function rejections()
    {
        return $this->belongsToMany(Caregiver::class, 'service_request_rejections', 'service_request_id', 'caregiver_id')
            ->withTimestamps();
    }
}
