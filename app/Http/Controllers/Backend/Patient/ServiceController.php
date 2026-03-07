<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceRequest;

class ServiceController extends Controller
{
    /**
     * List available services (catalog; exclude "Other" which is only for custom request).
     */
    public function index()
    {
        $today = now()->toDateString();
        $services = Service::where('slug', '!=', 'other-custom-request')
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->orderBy('category')
            ->orderBy('name')
            ->get();
        return view('backend.patient.services.index', compact('services'));
    }

    public function show($slug)
    {
        $today = now()->toDateString();
        $service = Service::where('slug', $slug)
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->firstOrFail();
        return view('backend.patient.services.show', compact('service'));
    }

    /**
     * Patient requests a service (creates ServiceRequest).
     * Caregivers will see this and can accept at base price or place a bid.
     */
    public function book(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:today|after_or_equal:start_date',
            'location'   => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'shift_type'  => 'required|string|in:morning,day,night,both',
        ]);

        $service = Service::findOrFail($request->service_id);

        if ($service->slug === 'other-custom-request') {
            $request->validate(['description' => 'required|string|max:1000']);
        }

        $preferredTime = \Carbon\Carbon::parse($request->start_date . ' 09:00');

        ServiceRequest::create([
            'patient_id'     => auth()->id(), // user_id (FK to users)
            'service_id'     => $service->id,
            'location'       => $request->location,
            'preferred_time' => $preferredTime,
            'description'    => $request->description,
            'status'         => 'pending',
            'shift_type'     => $request->shift_type,
        ]);

        return redirect()->route('patient.service-requests.index')
            ->with('success', 'Service request submitted. Caregivers can accept or bid; once accepted, it will appear under My Bookings.');
    }
}
