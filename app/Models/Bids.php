<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bids extends Model
{
    use HasFactory;

    protected $fillable = [
        'caregivers_id',
        'service_request_id',
        'proposed_price',
        'message',
        'status',
    ];

    // Relationship to ServiceRequest
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    // Optional: Relationship to Caregiver
    public function caregiver()
    {
        return $this->belongsTo(Caregiver::class, 'caregivers_id');
    }
}
