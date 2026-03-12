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
        'booking_id',
        'rating',
        'comment',
    ];

    /** DB column is 'comment'; alias for backward compatibility in views. */
    public function getCommentAttribute()
    {
        return $this->attributes['comment'] ?? '';
    }

    /** Alias so views using $review->comments still work. */
    public function getCommentsAttribute()
    {
        return $this->comment;
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

    /** reviews.booking_id references bookings.id */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }
}
