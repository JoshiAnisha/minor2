<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    protected $fillable = [
        'rating',
        'comments',
        'user_id',
        'bookings_id',
    ];

    /**
     * Get the reviewer (user who wrote the review)
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the caregiver profile (bookings_id points to caregivers table)
     */
    public function caregiverProfile()
    {
        return $this->belongsTo(\App\Models\Caregiver::class, 'bookings_id');
    }

    /**
     * Get the caregiver user (through caregiver profile)
     */
    public function caregiverUser()
    {
        return $this->hasOneThrough(
            User::class,
            Caregiver::class,
            'id', // Foreign key on caregivers table
            'id', // Foreign key on users table
            'bookings_id', // Local key on reviews table
            'users_id' // Local key on caregivers table
        );
    }

    /**
     * Get patient from completed bookings
     * This finds the patient that was reviewed by this caregiver
     */
    public function getReviewedPatient()
    {
        if (!$this->caregiverProfile) {
            return null;
        }

        // Find a completed booking for this caregiver and get the patient
        $booking = \App\Models\Booking::where('caregivers_id', $this->bookings_id)
            ->where('status', 'completed')
            ->with('patient.user')
            ->latest()
            ->first();

        return $booking ? $booking->patient : null;
    }
}

