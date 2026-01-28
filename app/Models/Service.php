<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    
    public $timestamps = false; // Disable timestamps since table doesn't have created_at/updated_at columns
    
    protected $fillable = ['name','slug','details','base_price','service_type'];

    public function bookings(){ return $this->hasMany(Booking::class); }
    public function reviews(){ return $this->hasMany(Review::class); }
    public function serviceRequests(){ return $this->hasMany(Service_Request::class); }
}
