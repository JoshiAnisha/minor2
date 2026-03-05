<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use App\Models\CaregiverShiftTime;
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
     * Show pending service requests that match this caregiver's schedule (shift + date).
     * Caregiver can only accept or reject requests that match their My Schedule.
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

        // Get this caregiver's schedule (caregiver_id in table = user id)
        $shiftTimes = CaregiverShiftTime::where('caregiver_id', $user->id)->get();
        $hasNoSchedule = $shiftTimes->isEmpty();

        $caregiverShifts = $shiftTimes->pluck('shift')->map(fn ($s) => strtolower(trim($s)))->filter()->unique()->values()->all();

        $requests = ServiceRequest::with('user', 'service')
            ->where('status', 'pending')
            ->whereNotIn('id', $rejectedIds)
            ->latest()
            ->get();

        // When caregiver has no schedule, show all pending requests so they can accept/bid (and add schedule later)
        // When they have a schedule, match by shift only (so they see all requests for their shift types)
        if (!$hasNoSchedule) {
            $requests = $requests->filter(function ($req) use ($caregiverShifts) {
                $reqShift = strtolower(trim($req->shift_type ?? 'day'));
                return in_array($reqShift, $caregiverShifts, true) || in_array('both', $caregiverShifts, true);
            })->values();
        }

        return view('Caregiver.serviceRequest', compact('requests', 'hasNoSchedule'));
    }

    /**
     * Accept the base price of a service request
     */
    public function acceptBasePrice($id)
    {
        $serviceRequest = ServiceRequest::with(['service', 'user'])->findOrFail($id);

        if ($serviceRequest->status !== 'pending') {
            return back()->with('error', 'Request already accepted.');
        }

        $caregiver = Auth::user()->caregiver;
        if (!$caregiver) {
            return back()->with('error', 'Caregiver profile not found.');
        }

        // Get or create Patient record (bookings use patients_id = patients.id)
        $patientUser = $serviceRequest->user;
        if (!$patientUser) {
            return back()->with('error', 'Patient user not found for this request.');
        }

        $patient = $patientUser->patient;
        if (!$patient) {
            $patient = Patient::firstOrCreate(
                ['user_id' => $serviceRequest->patient_id],
                ['email' => $patientUser->email]
            );
        }

        $basePrice = $serviceRequest->service ? (float) $serviceRequest->service->base_price : 0;

        try {
            \DB::transaction(function () use ($serviceRequest, $patient, $caregiver, $basePrice) {
                $serviceRequest->update(['status' => 'accepted']);

                $preferredTime = $serviceRequest->preferred_time
                    ? \Carbon\Carbon::parse($serviceRequest->preferred_time)
                    : now();

                Booking::create([
                    'service_request_id' => $serviceRequest->id,
                    'patients_id'        => $patient->id,
                    'caregivers_id'      => $caregiver->id,
                    'services_id'        => $serviceRequest->service_id,
                    'status'             => 'accepted',
                    'price'              => $basePrice,
                    'location'           => $serviceRequest->location ?? 'N/A',
                    'date_time'          => $preferredTime,
                    'start_date'         => $preferredTime->toDateString(),
                    'end_date'           => $preferredTime->copy()->addDay()->toDateString(),
                    'duration_type'      => 'one-time',
                    'payment_status'     => 'pending',
                ]);
            });
        } catch (\Throwable $e) {
            \Log::error('Caregiver accept base price failed: ' . $e->getMessage(), ['request_id' => $serviceRequest->id]);
            return back()->with('error', 'Could not create booking. Please try again or contact support.');
        }

        $booking = Booking::where('service_request_id', $serviceRequest->id)->where('caregivers_id', $caregiver->id)->first();
        if ($booking && $patientUser) {
            $patientUser->notify(new CaregiverAcceptedRequestNotification(
                $booking,
                $caregiver->user?->name ?? 'A caregiver'
            ));
        }

        return redirect()->route('caregiver.bookings')
            ->with('success', 'You accepted at base price (Rs ' . number_format($basePrice, 0) . '). Your new booking appears in In Progress below.');
    }

    /**
     * Place a bid on a service request
     */
    public function placeBid(Request $request)
    {
        $request->validate([
            'service_request_id' => 'required|exists:service_requests,id',
            'add_to_base'        => 'required|numeric|min:0',
        ]);

        $serviceRequest = ServiceRequest::with('service')->findOrFail($request->service_request_id);
        $basePrice = (float) ($serviceRequest->service->base_price ?? 0);
        $addToBase = (float) $request->add_to_base;
        $proposedPrice = $basePrice + $addToBase;

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
            'proposed_price'     => $proposedPrice,
            'message'            => $request->message,
            'status'             => 'pending',
        ]);

        // Notify patient
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
