<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caregiver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'caregiver_type',
        'qualification',
        'experience',
        'contact_number',
        'skills',
        'license_number',
        'training_certificate',
        'background_check_status',
        'verified_status',
        'rating',
        'availability_status',
        'address',
        'field',
        'bio',
        'certificate_path'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'caregiver_id');
    }
}