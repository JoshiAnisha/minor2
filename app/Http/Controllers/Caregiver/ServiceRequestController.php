<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\Bids;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceRequestController extends Controller
{
    /**
     * Show all pending service requests
     */
    public function serviceRequest()
    {
        $requests = ServiceRequest::with('patient', 'service')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('caregiver.serviceRequest', compact('requests'));
    }

    /**
     * Accept the base price of a service request
     */
    public function acceptBasePrice($id)
    {
        $serviceRequest = ServiceRequest::with(['service', 'patient'])->findOrFail($id);

        if ($serviceRequest->status !== 'pending') {
            return back()->with('error', 'Request already accepted.');
        }

        // ✅ Get caregiver profile from logged-in user
        $caregiver = Auth::user()->caregiver;

        if (!$caregiver) {
            return back()->with('error', 'Caregiver profile not found.');
        }

        if (!$serviceRequest->patient) {
            return back()->with('error', 'Patient record not found.');
        }

        $serviceRequest->update(['status' => 'accepted']);

        Booking::create([
            'patients_id'   => $serviceRequest->patient->id,
            'caregivers_id' => $caregiver->id, // ✅ FIXED
            'services_id'   => $serviceRequest->service_id,
            'status'        => 'accepted',
            'price'         => $serviceRequest->service->base_price ?? 0,
            'location'      => $serviceRequest->location,
            'date_time'     => now(),
            'start_date'    => now(),
            'end_date'      => now()->addDay(),
            'duration_type' => 'one-time',
            'payment_status'=> 'pending',
        ]);

        return back()->with('success', 'You accepted the base price. Booking created.');
    }

    /**
     * Place a bid on a service request
     */
    public function placeBid(Request $request)
    {
        $request->validate([
            'service_request_id' => 'required|exists:service_requests,id',
            'proposed_price'     => 'required|numeric|min:1',
        ]);

        // ✅ Get caregiver profile
        $caregiver = Auth::user()->caregiver;

        if (!$caregiver) {
            return back()->with('error', 'Caregiver profile not found.');
        }

        Bids::create([
            'caregivers_id'      => $caregiver->id, // ✅ FIXED
            'service_request_id' => $request->service_request_id,
            'proposed_price'     => $request->proposed_price,
            'status'             => 'pending',
        ]);

        return back()->with('success', 'Your bid was submitted successfully.');
    }
}
