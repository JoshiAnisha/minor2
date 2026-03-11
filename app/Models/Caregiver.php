<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caregiver extends Model
{
    use HasFactory;

    /** New caregivers are active by default (not pending). */
    protected $attributes = [
        'availability_status' => true,
    ];

    protected $fillable = [
        'users_id',
        'contact_number',
        'address',
        'skills',
        'field',
        'bio',
        'qualification',
        'experience',
        'caregiver_type',
        'certificate_path',
        'profile_photo_path',
        'preferred_shift',
        'available_time',
        'available_day',
        'available_service',
        'available_date',
        'availability_status',
 ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function rejectedServiceRequests()
    {
        return $this->belongsToMany(ServiceRequest::class, 'service_request_rejections', 'caregiver_id', 'service_request_id')
            ->withTimestamps();
    }
}
