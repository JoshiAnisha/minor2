<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_Request extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id','service_id','location','preferred_time','description','status'];

    public function patient(){ return $this->belongsTo(User::class,'patient_id'); }
    public function service(){ return $this->belongsTo(Service::class,'service_id'); }
}

