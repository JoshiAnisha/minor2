<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\Bid;
use App\Models\Booking;
use App\Models\Patient;
use App\Models\ServiceRequestRejection;
use App\Notifications\CaregiverAcceptedRequestNotification;
use App\Notifications\CaregiverPlacedBidNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceRequestController extends Controller
{
    /**
     * Show all pending service requests (excluding those this caregiver has rejected)
     */
    public function serviceRequest()
    {
        $user = Auth::user();
        $caregiver = $user->caregiver;
        if (!$caregiver && $user->role === 'caregiver') {
            $caregiver = \App\Models\Caregiver::create(['user_id' => $user->id, 'users_id' => $user->id]);
        }
        $rejectedIds = $caregiver
            ? ServiceRequestRejection::where('caregiver_id', $caregiver->id)->pluck('service_request_id')
            : collect();

        $requests = ServiceRequest::with('user', 'service')
            ->where('status', 'pending')
            ->whereNotIn('id', $rejectedIds)
            ->latest()
            ->get();

        return view('Caregiver.serviceRequest', compact('requests'));
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

        $patient = $serviceRequest->patient ?? $serviceRequest->user?->patient;
        if (!$patient && $serviceRequest->user) {
            $patient = Patient::create([
                'user_id' => $serviceRequest->patient_id,
                'email' => $serviceRequest->user->email,
            ]);
        }
        if (!$patient) {
            return back()->with('error', 'Patient record not found.');
        }

        $serviceRequest->update(['status' => 'accepted']);

        $preferredTime = $serviceRequest->preferred_time
            ? \Carbon\Carbon::parse($serviceRequest->preferred_time)
            : now();

        $booking = Booking::create([
            'service_request_id' => $serviceRequest->id,
            'patients_id'        => $patient->id,
            'caregivers_id'      => $caregiver->id,
            'services_id'        => $serviceRequest->service_id,
            'status'             => 'accepted',
            'price'              => $serviceRequest->service->base_price ?? 0,
            'location'           => $serviceRequest->location,
            'date_time'          => $preferredTime,
            'start_date'         => $preferredTime->toDateString(),
            'end_date'           => $preferredTime->copy()->addDay()->toDateString(),
            'duration_type'      => 'one-time',
            'payment_status'     => 'pending',
        ]);

        // Notify patient
        $patientUser = $serviceRequest->user;
        if ($patientUser) {
            $patientUser->notify(new CaregiverAcceptedRequestNotification(
                $booking,
                $caregiver->user?->name ?? 'A caregiver'
            ));
        }

        return back()->with('success', 'You accepted the base price. Booking created. Patient has been notified.');
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

        $existingBid = Bid::where('caregivers_id', $caregiver->id)
            ->where('service_request_id', $request->service_request_id)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();

        if ($existingBid) {
            return back()->with('error', 'You have already placed a bid on this request.');
        }

        $bid = Bid::create([
            'caregivers_id'      => $caregiver->id,
            'service_request_id' => $request->service_request_id,
            'proposed_price'     => $request->proposed_price,
            'message'            => $request->message,
            'status'             => 'pending',
        ]);

        // Notify patient
        $serviceRequest = \App\Models\ServiceRequest::find($request->service_request_id);
        $patientUser = $serviceRequest?->user;
        if ($patientUser) {
            $patientUser->notify(new CaregiverPlacedBidNotification(
                $bid,
                $caregiver->user?->name ?? 'A caregiver',
                $serviceRequest
            ));
        }

        return back()->with('success', 'Your bid was submitted successfully. Patient has been notified.');
    }

    /**
     * Caregiver opts out of a service request (declines to take it).
     * The request stays pending for other caregivers.
     */
    public function reject($id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        $caregiver = Auth::user()->caregiver;

        if (!$caregiver) {
            return back()->with('error', 'Caregiver profile not found.');
        }

        if ($serviceRequest->status !== 'pending') {
            return back()->with('error', 'Request is no longer available.');
        }

        ServiceRequestRejection::firstOrCreate([
            'caregiver_id'       => $caregiver->id,
            'service_request_id' => $serviceRequest->id,
        ]);

        return back()->with('success', 'You declined this request. It will no longer appear in your list.');
    }
}
