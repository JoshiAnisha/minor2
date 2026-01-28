<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'service',
        'full_name',
        'location',
        'date',
        'time',
        'status'
    ];

    // Relationship
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}