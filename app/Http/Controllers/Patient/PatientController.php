<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientController extends Controller
{


    public function index()
    {
        // Logic for the admin dashboard can be added here
        return view('patient.dashboard');
    }
}
