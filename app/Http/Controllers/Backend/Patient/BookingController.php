<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('service')
            ->where('patients_id', auth()->id())
            ->orderBy('start_date', 'desc')
            ->get();

        return view('backend.patient.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with('service', 'caregiver')
            ->where('patients_id', auth()->id())
            ->findOrFail($id);

        return view('backend.patient.bookings.show', compact('booking'));
    }
}
