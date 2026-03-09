<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Service;

class BookingController extends Controller
{
    /**
     * Show the form to book a service (request it). Service can be pre-selected via ?service=slug.
     */
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

        return view('backend.patient.bookings.create', compact('service'));
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
            ->whereIn('bookings_id', $bookings->pluck('booking_id'))
            ->pluck('bookings_id')
            ->toArray();

        return view('backend.patient.bookings.index', compact('bookings', 'reviewedBookingIds'));
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
            ->where('bookings_id', $booking->getKey())
            ->first();

        return view('backend.patient.bookings.show', compact('booking', 'hasReview'));
    }
}
