<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'slug', 'details', 'base_price', 'service_type', 'category', 'is_long_term', 'start_date', 'end_date'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_long_term' => 'boolean',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'services_id');
    }
}

