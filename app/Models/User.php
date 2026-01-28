<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'contact_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Accessors for backward compatibility with views
    public function getPhoneAttribute()
    {
        return $this->contact_number;
    }

    public function getAddressAttribute()
    {
        if ($this->role === 'patient' && $this->patient) {
            return $this->patient->address;
        }
        return null;
    }

    // Relationships
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function caregiver()
    {
        return $this->hasOne(Caregiver::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }
}
