<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaregiverShiftTime extends Model
{
    use HasFactory;

    protected $table = 'caregiver_shift_times';

    protected $fillable = [
        'caregiver_id',
        'shift',
        'start_time',
        'end_time',
        'day',
        'service',
        'available_date',
    ];

    /**
     * Relationship: Availability belongs to a caregiver (user)
     */
    public function caregiver()
    {
        return $this->belongsTo(User::class, 'caregiver_id');
    }
}
