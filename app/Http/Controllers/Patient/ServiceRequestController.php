<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Bid;
use App\Models\Booking;
use App\Notifications\PatientAcceptedBidNotification;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function createCustom()
    {
        $service = Service::firstOrCreate(
            ['slug' => 'other-custom-request'],
            [
                'name' => 'Other (describe in request)',
                'details' => 'Describe your need; caregivers can respond.',
                'base_price' => 0,
                'service_type' => 'regular',
            ]
        );
        return view('patient.service-requests.create-custom', compact('service'));
    }

    public function index()
    {
        $patient = auth()->user()->patient;

        $serviceRequests = $patient
            ? ServiceRequest::with('service', 'bids.caregiver.user', 'bookings.caregiver.user')
                ->where('patient_id', $patient->id)
                ->orderBy('created_at', 'desc')
                ->get()
            : collect();

        if ($serviceRequests->isNotEmpty()) {
            $priority = function ($r) {
                if ($r->status === 'pending' && $r->bids->isNotEmpty()) {
                    return 1;
                }
                if ($r->status === 'accepted') {
                    return 2;
                }
                if ($r->status === 'pending') {
                    return 3;
                }
                if ($r->status === 'cancelled') {
                    return 4;
                }
                return 5;
            };
            $serviceRequests = $serviceRequests->sortBy($priority)->values();
        }

        return view('patient.service-requests.index', compact('serviceRequests'));
    }

    public function acceptBid(Request $request, Bid $bid)
    {
        $requestOwner = $bid->serviceRequest->user;
        if (!$requestOwner || auth()->id() !== $requestOwner->id) {
            abort(403, 'Unauthorized');
        }
        if (!auth()->user()->isProfileComplete()) {
            return redirect()->route('patient.profile.edit')
                ->with('error', 'Please complete your profile (name, email, contact number, and address) before accepting an offer.');
        }
        if ($bid->serviceRequest->status !== 'pending') {
            return back()->with('error', 'This request is no longer available.');
        }
        if ($bid->status !== 'pending') {
            return back()->with('error', 'This bid is no longer available.');
        }

        $bid->update(['status' => 'accepted']);
        $bid->serviceRequest->update(['status' => 'accepted']);

        $patientUser = auth()->user();
        $patient = $patientUser->patient;
        if (!$patient) {
            $patient = \App\Models\Patient::firstOrCreate(
                ['user_id' => $patientUser->id],
                ['email' => $patientUser->email]
            );
        }

        $sr = $bid->serviceRequest;
        $preferredTime = $sr->preferred_time
            ? \Carbon\Carbon::parse($sr->preferred_time)
            : now();

        [$bookingStart, $bookingEnd] = $sr->isLongTerm()
            ? [$sr->start_date->toDateString(), $sr->end_date->toDateString()]
            : [$preferredTime->toDateString(), $preferredTime->copy()->addDay()->toDateString()];

        $booking = Booking::create([
            'service_request_id' => $bid->service_request_id,
            'patients_id'        => $patient->id,
            'caregivers_id'      => $bid->caregivers_id,
            'services_id'        => $sr->service_id,
            'status'             => 'accepted',
            'price'              => $bid->proposed_price,
            'location'           => $sr->location ?? 'N/A',
            'date_time'          => $preferredTime,
            'start_date'         => $bookingStart,
            'end_date'           => $bookingEnd,
            'duration_type'      => 'one-time',
            'payment_status'     => 'pending',
        ]);

        $caregiverUser = $bid->caregiver?->user;
        if ($caregiverUser) {
            $caregiverUser->notify(new PatientAcceptedBidNotification(
                $bid,
                auth()->user()->name ?? 'The patient',
                $booking
            ));
        }

        Bid::where('service_request_id', $bid->service_request_id)
            ->where('id', '!=', $bid->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        $bookingId = $booking->getKey();
        if ($bookingId) {
            return redirect()->route('patient.bookings.show', ['id' => $bookingId])
                ->with('success', 'Bid accepted! Booking created successfully.');
        }
        return redirect()->route('patient.bookings.index')
            ->with('success', 'Bid accepted! Booking created successfully.');
    }

    public function rejectBid(Request $request, Bid $bid)
    {
        $requestOwner = $bid->serviceRequest->user;
        if (!$requestOwner || auth()->id() !== $requestOwner->id) {
            abort(403, 'Unauthorized');
        }
        if ($bid->status !== 'pending') {
            return back()->with('error', 'This bid is no longer available.');
        }

        $bid->update(['status' => 'rejected']);

        $caregiverUser = $bid->caregiver?->user;
        if ($caregiverUser) {
            $caregiverUser->notify(new \App\Notifications\PatientRejectedBidNotification(
                $bid,
                auth()->user()->name ?? 'Patient'
            ));
        }

        return back()->with('success', 'Bid rejected. The caregiver has been removed from this request.');
    }

    public function cancel(ServiceRequest $service_request)
    {
        $patient = auth()->user()->patient;
        if (!$patient || $service_request->patient_id !== $patient->id) {
            abort(403, 'You can only cancel your own requests.');
        }
        if ($service_request->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be cancelled.');
        }

        $service_request->update(['status' => 'cancelled']);
        $service_request->bids()->where('status', 'pending')->update(['status' => 'rejected']);

        return redirect()->route('patient.service-requests.index')
            ->with('success', 'Request cancelled successfully.');
    }
}
