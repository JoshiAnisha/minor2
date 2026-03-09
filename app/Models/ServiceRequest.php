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
        'start_date',
        'end_date',
        'total_base_price',
        'description',
        'status',
        'shift_type',
    ];

    protected $casts = [
        'preferred_time'     => 'datetime',
        'start_date'         => 'date',
        'end_date'           => 'date',
        'total_base_price'   => 'decimal:2',
    ];

    /** True if this request has a date range (long-term). */
    public function isLongTerm(): bool
    {
        return $this->start_date && $this->end_date;
    }

    /** Number of days for this request: long-term = days in range (inclusive), one-day = 1. */
    public function getNumberOfDays(): int
    {
        if ($this->isLongTerm() && $this->start_date && $this->end_date) {
            return (int) $this->start_date->diffInDays($this->end_date) + 1;
        }
        return 1;
    }

    /** Effective base price for this request: stored total (days × per-day price for long-term) or service base price. */
    public function getEffectiveBasePriceAttribute(): ?float
    {
        if ($this->total_base_price !== null && $this->total_base_price !== '') {
            return (float) $this->total_base_price;
        }
        $service = $this->relationLoaded('service') ? $this->service : $this->service()->first();
        return $service ? (float) $service->base_price : null;
    }

    /**
     * patient_id references patients.id.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /**
     * User (patient) via the Patient record.
     */
    public function user()
    {
        return $this->hasOneThrough(
            \App\Models\User::class,
            Patient::class,
            'id',       // Patient.id (key we point to via patient_id)
            'id',      // User.id
            'patient_id',
            'user_id'  // Patient.user_id -> User.id
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
