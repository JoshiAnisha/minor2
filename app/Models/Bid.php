<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'caregivers_id',
        'service_request_id',
        'proposed_price',
        'message',
        'status',
    ];

     public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

     public function caregiver()
    {
        return $this->belongsTo(Caregiver::class, 'caregivers_id');
    }
}
