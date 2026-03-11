<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// IMPORTANT: model imports
use App\Models\User;
use App\Models\Booking;
use App\Models\ServiceRequest;

class Patient extends Model
{
    use HasFactory;

    /**
     * The patients table may not have created_at/updated_at columns (legacy schema).
     * Disable timestamps so inserts/updates do not require them.
     */
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'user_id',
        'is_active',
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
        'is_active' => 'boolean',
        'verified_status' => 'boolean',
        'rating' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Default attributes when creating a new patient.
     * Only includes columns that exist and may be NOT NULL (no default) in the DB.
     * Omit columns that don't exist in your patients table (e.g. allergies, disabilities).
     */
    public static function defaultAttributesForCreate(int $userId): array
    {
        return [
            'user_id' => $userId,
            'is_active' => true,
            'medical_history' => '',
            'prescriptions' => '',
            'health_condition' => '',
        ];
    }

    /**
     * Patient → User (One to One)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Patient → Bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'patients_id');
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'patient_id');
    }

    /**
     * Patient → Health reports (uploaded documents)
     */
    public function healthReports()
    {
        return $this->hasMany(PatientHealthReport::class);
    }

    public function getProfilePhotoUrlAttribute()
     {
    if ($this->profile_photo) {
        return asset('storage/' . $this->profile_photo);
    }
    return "https://ui-avatars.com/api/?name=" . urlencode($this->user->name) . "&background=0dcaf0&color=fff&size=128";
  }
}
