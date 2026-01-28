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
        $bookings = Booking::with('service')
            ->where('user_id', Auth::id())
            ->orderBy('date', 'asc')
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

        if (!Auth::check()) {
            return redirect()->route('backend.auth.login')
                ->withErrors(['error' => 'Please login to book a service.']);
        }

        // Find or create service
        $service = Service::where('name', $request->service_name)->first();

        if (!$service) {
            $baseSlug = Str::slug($request->service_name);
            $slug = $baseSlug;
            $count = 1;

            while (Service::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }

            $service = Service::create([
                'name' => $request->service_name,
                'slug' => $slug,
                'details' => 'Home visit service',
                'base_price' => 0,
                'service_type' => 'home_visit',
            ]);
        }

        $bookingDate = Carbon::parse($request->date);

        Booking::create([
            'user_id'    => Auth::id(),
            'service_id' => $service->id,
            'date'       => $bookingDate,
            'status'     => 'upcoming',
        ]);

        return redirect()->route('patient.bookings.index')
            ->with('success', 'Booking confirmed for ' . $service->name . ' on ' . $bookingDate->format('F d, Y'));
    }

    public function show($id)
    {
        $booking = Booking::with('service')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('backend.patient.bookings.show', compact('booking'));
    }
}
