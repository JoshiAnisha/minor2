<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'details',
        'base_price',
        'service_type',
    ];

    public function requests()
    {
        return $this->hasMany(ServiceRequest::class);
    }
}
