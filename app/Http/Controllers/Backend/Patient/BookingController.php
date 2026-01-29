<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Service;

class BookingController extends Controller
{
    public function index()
    {
        // Fetch bookings for the logged-in patient
        $bookings = Booking::with('service')
            ->where('patients_id', Auth::id())  // use patients_id, not user_id
            ->orderBy('date_time', 'asc')      // use date_time column
            ->get();

        return view('backend.patient.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $services = Service::all();
        return view('backend.patient.bookings.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
        ], [
            'date.after_or_equal' => 'Booking date must be today or a future date.',
        ]);

        // Make sure patient is logged in
        if (!Auth::check()) {
            return redirect()->route('backend.auth.login')
                ->withErrors(['error' => 'Please login to book a service.']);
        }

        // Find or create service
        $service = Service::firstOrCreate(
            ['name' => $request->service_name],
            [
                'slug' => Str::slug($request->service_name),
                'details' => 'Home visit service',
                'base_price' => 0,
                'service_type' => 'home_visit',
            ]
        );

        $bookingDate = Carbon::parse($request->date);

        Booking::create([
            'patients_id' => Auth::id(),       // correct column
            'services_id' => $service->id,     // correct column
            'date_time'   => $bookingDate,     // correct column
            'status'      => 'pending',        // match your enum values
            'location'    => 'Not set',        // add defaults if required
            'price'       => $service->base_price,
            'duration_type' => 'one-time',
            'start_date'    => $bookingDate->format('Y-m-d'),
            'end_date'      => $bookingDate->format('Y-m-d'),
            'payment_status'=> 'pending',
        ]);

        return redirect()->route('patient.bookings.index')
            ->with('success', 'Booking confirmed for ' . $service->name . ' on ' . $bookingDate->format('F d, Y'));
    }

    public function show($id)
    {
        $booking = Booking::with('service')
            ->where('patients_id', Auth::id()) // use patients_id
            ->findOrFail($id);

        return view('backend.patient.bookings.show', compact('booking'));
    }
}
