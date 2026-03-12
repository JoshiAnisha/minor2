<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Service;

class BookingController extends Controller
{
    public function create(\Illuminate\Http\Request $request)
    {
        $slug = $request->query('service');
        if (!$slug) {
            return redirect()->route('patient.services.index')
                ->with('info', 'Please select a service to book.');
        }

        $today = now()->toDateString();
        $service = Service::where('slug', $slug)
            ->where('slug', '!=', 'other-custom-request')
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->first();

        if (!$service) {
            return redirect()->route('patient.services.index')
                ->with('error', 'Service not found or not available.');
        }

        return view('patient.bookings.create', compact('service'));
    }

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

        $reviewedBookingIds = Review::where('user_id', auth()->id())
            ->whereIn('booking_id', $bookings->pluck('id'))
            ->pluck('booking_id')
            ->toArray();

        return view('patient.bookings.index', compact('bookings', 'reviewedBookingIds'));
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

        $hasReview = Review::where('user_id', auth()->id())
            ->where('booking_id', $booking->getKey())
            ->first();

        return view('patient.bookings.show', compact('booking', 'hasReview'));
    }
}
