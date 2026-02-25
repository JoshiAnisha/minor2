<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignedService extends Model
{
    protected $fillable = [
        'patient_id',
        'service_id',
        'admin_id',
        'title',
        'description',
        'preferred_date',
        'budget',
        'location',
        'assigned_caregiver_id',
        'status',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'budget' => 'decimal:2',
    ];

    /**
     * Patient (User) this service is assigned to.
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Service type from catalog.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Admin who assigned this service.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Caregiver assigned after a bid is accepted (null until then).
     */
    public function assignedCaregiver()
    {
        return $this->belongsTo(Caregiver::class, 'assigned_caregiver_id');
    }

    /**
     * All bids on this assigned service.
     */
    public function bids()
    {
        return $this->hasMany(AssignedServiceBid::class);
    }
}
