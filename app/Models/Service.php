<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name', 'slug', 'details', 'base_price', 'service_type'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'services_id');
    }

    public function assignedServices()
    {
        return $this->hasMany(AssignedService::class);
    }
}

