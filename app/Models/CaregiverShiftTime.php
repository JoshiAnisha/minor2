<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CaregiverShiftTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'caregiver_id',
        'shift',
        'start_time',
        'end_time',
        'day',
        'service',
        'available_date',
    ];

    public function caregiver()
    {
        return $this->belongsTo(User::class, 'caregiver_id');
    }
}
