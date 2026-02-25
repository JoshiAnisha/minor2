<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequestRejection extends Model
{
    protected $fillable = [
        'caregiver_id',
        'service_request_id',
    ];

    public function caregiver()
    {
        return $this->belongsTo(Caregiver::class);
    }

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }
}
