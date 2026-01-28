<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// IMPORTANT: model imports
use App\Models\User;
use App\Models\Booking;

class Patient extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'user_id',
        'profile_photo',
        'email',
        'date_of_birth',
        'gender',
        'blood_group',
        'contact_number',
        'address',
        'city',
        'state',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_number',
        'insurance_provider',
        'insurance_number',
        'medical_history',
        'prescriptions',
        'health_condition',
        'allergies',
        'disabilities',
        'verified_status',
        'rating',
        'notes',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'date_of_birth' => 'date',
        'verified_status' => 'boolean',
        'rating' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Patient → User (One to One)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Patient → Bookings
     * (linked through user_id)
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id', 'user_id');
    }

    public function getProfilePhotoUrlAttribute()
     {
    if ($this->profile_photo) {
        return asset('storage/' . $this->profile_photo);
    }
    return "https://ui-avatars.com/api/?name=" . urlencode($this->user->name) . "&background=0dcaf0&color=fff&size=128";
  }
}
