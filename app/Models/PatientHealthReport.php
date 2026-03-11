<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientHealthReport extends Model
{
    protected $fillable = ['patient_id', 'file_path', 'original_name'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
