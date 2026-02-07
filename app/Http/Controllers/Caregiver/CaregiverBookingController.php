<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Bid;
use App\Models\Patient;
use App\Models\Review;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaregiverBookingController extends Controller
{
    public function bookings()
    {
        $caregiverId = Auth::id();

        // Pending bids for this caregiver (only valid serviceRequests)
        $pendingBids = Bid::with('serviceRequest.patient', 'serviceRequest.service')
            ->where('caregivers_id', $caregiverId)
            ->where('status', 'pending')
            ->get()
            ->filter(fn($bid) => $bid->serviceRequest !== null);

        // Pending service requests not yet accepted by this caregiver
        $pendingRequests = ServiceRequest::with('patient', 'service')
            ->where('status', 'pending')
            ->get();

        // Merge bids and requests
        $pendingBookings = $pendingBids->merge($pendingRequests);

        // Accepted / In Progress bookings
        $acceptedBookings = Booking::with('patient', 'service', 'serviceRequest')
            ->where('caregivers_id', $caregiverId)
            ->where('status', 'accepted')
            ->get();

        // Completed bookings
        $completedBookings = Booking::with('patient', 'service', 'serviceRequest')
            ->where('caregivers_id', $caregiverId)
            ->where('status', 'completed')
            ->get();

        return view('Caregiver.caregiverBooking', compact(
            'pendingBookings',
            'acceptedBookings',
            'completedBookings'
        ));
    }

    // Accept a bid and create booking
    public function acceptBid(Bid $bid)
    {
        if (!$bid->serviceRequest) {
            return back()->with('error', 'This bid has no valid service request.');
        }

        $bid->update(['status' => 'accepted']);

        Booking::create([
            'patients_id' => $bid->serviceRequest->patient_id,
            'caregivers_id' => $bid->caregivers_id,
            'services_id' => $bid->serviceRequest->service_id,
            'status' => 'accepted',
            'price' => $bid->proposed_price,
            'location' => $bid->serviceRequest->location ?? 'N/A',
            'date_time' => now(),
            'start_date' => now(),
            'end_date' => now()->addDay(),
        ]);

        return back()->with('success', 'Bid accepted and added to your bookings.');
    }

    // Mark booking as completed
    public function complete(Booking $booking)
    {
        $booking->update(['status' => 'completed']);
        return back()->with('success', 'Booking marked as completed.');
    }

    // Show patient profile (only if this caregiver has a booking with that patient)
    public function showPatient(Patient $patient)
    {
        $caregiverId = Auth::id();

        $hasRelationship = Booking::where('caregivers_id', $caregiverId)
            ->where('patients_id', $patient->id)
            ->exists();

        abort_unless($hasRelationship, 403);

        $patient->load('user');

        return view('caregiver.patientProfile', compact('patient'));
    }

    // Show review form page
    public function createReview(Patient $patient)
    {
        $caregiverUserId = Auth::id();
        
        // Verify caregiver has a completed booking with this patient
        $caregiver = Auth::user()->caregiver;
        if (!$caregiver) {
            return redirect()->route('caregiver.bookings')
                ->with('error', 'Caregiver profile not found.');
        }

        $hasCompletedBooking = Booking::where('caregivers_id', $caregiver->id)
            ->where('patients_id', $patient->id)
            ->where('status', 'completed')
            ->exists();

        if (!$hasCompletedBooking) {
            return redirect()->route('caregiver.bookings')
                ->with('error', 'You can only review patients from your completed bookings.');
        }

        $patient->load('user');

        return view('caregiver.createReview', compact('patient'));
    }

    // Store review for a patient
    public function storeReview(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $caregiverUserId = Auth::id();
        $patientId = $request->patient_id;

        // Verify caregiver has a completed booking with this patient
        $caregiver = Auth::user()->caregiver;
        if (!$caregiver) {
            return back()->with('error', 'Caregiver profile not found.');
        }

        // Verify caregiver has a completed booking with this patient
        $hasCompletedBooking = Booking::where('caregivers_id', $caregiver->id)
            ->where('patients_id', $patientId)
            ->where('status', 'completed')
            ->exists();

        if (!$hasCompletedBooking) {
            return back()->with('error', 'You can only review patients from your completed bookings.');
        }

        // Create review using only existing columns from migration
        // Note: bookings_id is constrained to caregivers table, so it stores caregiver ID
        Review::create([
            'user_id' => $caregiverUserId, // Reviewer (caregiver's user_id from users table)
            'bookings_id' => $caregiver->id, // Caregiver ID (from caregivers table, per migration constraint)
            'rating' => $request->rating,
            'comments' => $request->comment ?? null, // Use 'comments' (plural) as per migration
        ]);

        return redirect()->route('caregiver.bookings')
            ->with('success', 'Review submitted successfully!');
    }
}
