<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'bookings_id',
        'rating',
        'comments',
    ];

    /** DB column is 'comments'; alias for backward compatibility in views. */
    public function getCommentAttribute()
    {
        return $this->attributes['comments'] ?? '';
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /** reviews table uses bookings_id (FK to bookings.booking_id). */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'bookings_id', 'booking_id');
    }
}
