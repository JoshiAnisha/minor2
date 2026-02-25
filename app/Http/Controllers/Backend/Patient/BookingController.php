<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;
        if (!$patient) {
            return redirect()->route('patient.dashboard')->with('error', 'Patient profile not found.');
        }

        $bookings = Booking::with('service', 'caregiver.user')
            ->where('patients_id', $patient->id)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('backend.patient.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $patient = auth()->user()->patient;
        if (!$patient) {
            return redirect()->route('patient.dashboard')->with('error', 'Patient profile not found.');
        }

        $booking = Booking::with('service', 'caregiver.user')
            ->where('patients_id', $patient->id)
            ->findOrFail($id);

        return view('backend.patient.bookings.show', compact('booking'));
    }
}
