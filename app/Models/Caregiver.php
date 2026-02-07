<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caregiver extends Model
{
    use HasFactory;

    protected $fillable = [
         'user_id',
        'address',
        'skills',
        'field',
        'bio',
        'certificate_path',
        'preferred_shift',
        'available_time',
        'available_day',
        'available_service',
        'available_date',
        'availability_status',
 ];

    public function user()
    {
        return $this->belongsTo(User::class);
     }
}
