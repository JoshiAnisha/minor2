<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Caregiver;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    // List all services
    public function index()
    {
        $services = Service::all();
        return view('backend.patient.services.index', compact('services'));
    }

    // Show a single service details
    public function show($slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();
        return view('backend.patient.services.show', compact('service'));
    }

    // Patient books a service
    public function book(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'location'   => 'required|string|max:255',
        ]);

        $service = Service::findOrFail($request->service_id);
        $patient = Auth::user()->patient;

        // Choose an available caregiver (basic logic)
        $caregiver = Caregiver::first(); // improve logic later
        if (!$caregiver) {
            return back()->with('error', 'No caregiver available for booking.');
        }

        $booking = Booking::create([
            'patients_id'   => $patient->id,
            'caregivers_id' => $caregiver->id,
            'services_id'   => $service->id,
            'status'        => 'pending',
            'location'      => $request->location,
            'price'         => $service->base_price,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'payment_status'=> 'pending',
            'duration_type' => 'one-time',
            'date_time'     => now(),
        ]);

        return redirect()->route('patient.bookings.show', $booking->id)
                         ->with('success', 'Booking created successfully!');
    }

    // Show patient bookings
    public function bookings()
    {
        $patient = Auth::user()->patient;
        $bookings = Booking::with('caregiver.user', 'service')
            ->where('patients_id', $patient->id)
            ->get();

        return view('backend.patient.bookings.index', compact('bookings'));
    }

    // Show single booking
    public function showBooking(Booking $booking)
    {
        $this->authorize('view', $booking); // optional: gate for security
        $booking->load('caregiver.user', 'service');
        return view('backend.patient.bookings.show', compact('booking'));
    }
}
