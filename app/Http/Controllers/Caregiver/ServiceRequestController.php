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
     * Show all pending service requests to every caregiver.
     * Caregivers see every new patient request and can accept at base price, place a bid, or decline.
     */
    public function serviceRequest()
    {
        $user = Auth::user();
        $caregiver = $user->caregiver;
        if (!$caregiver && $user->role === 'caregiver') {
            $caregiver = \App\Models\Caregiver::create(['user_id' => $user->id, 'users_id' => $user->id, 'availability_status' => true]);
        }
        $rejectedIds = $caregiver
            ? ServiceRequestRejection::where('caregiver_id', $caregiver->id)->pluck('service_request_id')
            : collect();

        $requests = ServiceRequest::with('user', 'service', 'bids')
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
        $serviceRequest = ServiceRequest::with(['service', 'user'])->findOrFail($id);

        if ($serviceRequest->status !== 'pending') {
            return back()->with('error', 'Request already accepted.');
        }
        if ($serviceRequest->bids()->exists()) {
            return back()->with('error', 'Bidding is closed for this request. Patient will choose from existing offers.');
        }

        $caregiver = Auth::user()->caregiver;
        if (!$caregiver) {
            return back()->with('error', 'Caregiver profile not found.');
        }
        if (!Auth::user()->isProfileComplete()) {
            return redirect()->route('caregiver.profile.edit')
                ->with('error', 'Please complete your profile (name, email, contact number, and address) before accepting a request.');
        }

        // Bookings use patients_id = patients.id; service_requests.patient_id references patients.id
        $patient = $serviceRequest->patient;
        if (!$patient) {
            return back()->with('error', 'Patient record not found for this request.');
        }
        $patientUser = $patient->user;

        $basePrice = (float) ($serviceRequest->effective_base_price ?? 0);

        try {
            \DB::transaction(function () use ($serviceRequest, $patient, $caregiver, $basePrice) {
                $serviceRequest->update(['status' => 'accepted']);

                $preferredTime = $serviceRequest->preferred_time
                    ? \Carbon\Carbon::parse($serviceRequest->preferred_time)
                    : now();

                [$bookingStart, $bookingEnd] = $serviceRequest->isLongTerm()
                    ? [$serviceRequest->start_date->toDateString(), $serviceRequest->end_date->toDateString()]
                    : [$preferredTime->toDateString(), $preferredTime->copy()->addDay()->toDateString()];

                Booking::create([
                    'service_request_id' => $serviceRequest->id,
                    'patients_id'        => $patient->id,
                    'caregivers_id'      => $caregiver->id,
                    'services_id'        => $serviceRequest->service_id,
                    'status'             => 'accepted',
                    'price'              => $basePrice,
                    'location'           => $serviceRequest->location ?? 'N/A',
                    'date_time'          => $preferredTime,
                    'start_date'         => $bookingStart,
                    'end_date'           => $bookingEnd,
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
            'proposed_price'     => 'required|numeric|min:0',
        ]);

        $serviceRequest = ServiceRequest::with('service')->findOrFail($request->service_request_id);
        if ($serviceRequest->bids()->exists()) {
            return back()->with('error', 'Bidding is closed for this request. Patient will choose from existing offers.');
        }
        $proposedPrice = (float) $request->proposed_price;

        $caregiver = Auth::user()->caregiver;
        if (!$caregiver) {
            return back()->with('error', 'Caregiver profile not found.');
        }
        if (!Auth::user()->isProfileComplete()) {
            return redirect()->route('caregiver.profile.edit')
                ->with('error', 'Please complete your profile (name, email, contact number, and address) before placing a bid.');
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
