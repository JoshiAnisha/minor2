<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignedServiceBid extends Model
{
    protected $fillable = [
        'assigned_service_id',
        'caregiver_id',
        'proposed_price',
        'message',
        'status',
    ];

    protected $casts = [
        'proposed_price' => 'decimal:2',
    ];

    /**
     * Assigned service this bid is for.
     */
    public function assignedService()
    {
        return $this->belongsTo(AssignedService::class);
    }

    /**
     * Caregiver who placed the bid.
     */
    public function caregiver()
    {
        return $this->belongsTo(Caregiver::class);
    }
}
