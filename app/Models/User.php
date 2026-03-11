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

    /**
     * Whether the user has completed their profile (required before making/accepting requests).
     * Patient: name, email, contact number, address.
     * Caregiver: name, email, contact number, address.
     */
    public function isProfileComplete(): bool
    {
        $name = trim((string) ($this->name ?? ''));
        $email = trim((string) ($this->email ?? ''));
        if ($name === '' || $email === '') {
            return false;
        }
        if ($this->role === 'patient') {
            $patient = $this->patient;
            if (!$patient) {
                return false;
            }
            $contact = trim((string) ($this->contact_number ?? $patient->contact_number ?? ''));
            $address = trim((string) ($patient->address ?? $this->getAddressAttribute() ?? ''));
            return $contact !== '' && $address !== '';
        }
        if ($this->role === 'caregiver') {
            $caregiver = $this->caregiver;
            if (!$caregiver) {
                return false;
            }
            $contact = trim((string) ($this->contact_number ?? $caregiver->contact_number ?? ''));
            $address = trim((string) ($caregiver->address ?? ''));
            return $contact !== '' && $address !== '';
        }
        return true;
    }

    // Relationships
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function caregiver()
    {
        return $this->hasOne(Caregiver::class, 'users_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }
}
