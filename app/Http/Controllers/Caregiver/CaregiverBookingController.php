<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Bid;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Review;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaregiverBookingController extends Controller
{
    public function bookings()
    {
        $user = Auth::user();
        $caregiver = $user->caregiver;
        if (!$caregiver && $user->role === 'caregiver') {
            $caregiver = \App\Models\Caregiver::create(['users_id' => $user->id, 'availability_status' => true]);
        }
        if (!$caregiver) {
            return redirect()->route('caregiver.dashboard')->with('error', 'Caregiver profile not found.');
        }
        $caregiverId = $caregiver->id;

        // Pending bids placed by this caregiver (waiting for patient to accept)
        $pendingBids = Bid::with('serviceRequest.user', 'serviceRequest.service')
            ->where('caregivers_id', $caregiverId)
            ->where('status', 'pending')
            ->get()
            ->filter(fn($bid) => $bid->serviceRequest !== null);

        // Pending service requests (caregiver can accept or bid from service-requests page)
        $pendingRequests = ServiceRequest::with('user', 'service')
            ->where('status', 'pending')
            ->get();

        // Merge bids and requests for "pending" section
        $pendingBookings = $pendingBids->merge($pendingRequests);

        // Accepted / In Progress bookings
        $acceptedBookings = Booking::with('patient.user', 'service', 'serviceRequest')
            ->where('caregivers_id', $caregiverId)
            ->where('status', 'accepted')
            ->get();

        // Completed bookings
        $completedBookings = Booking::with('patient.user', 'service', 'serviceRequest')
            ->where('caregivers_id', $caregiverId)
            ->where('status', 'completed')
            ->get();

        return view('Caregiver.caregiverBooking', compact(
            'pendingBookings',
            'acceptedBookings',
            'completedBookings'
        ));
    }

    /**
     * Legacy: Patient accepts bids. Caregiver does not accept their own bid.
     * Redirect caregiver to bookings - they see new booking when patient accepts.
     */
    public function acceptBid(Bid $bid)
    {
        return back()->with('info', 'The patient must accept your bid. Check back after they respond.');
    }

    // Mark booking as completed (starts payment: invoice is created for patient)
    public function complete(Booking $booking)
    {
        $booking->load('patient', 'service');
        if ($booking->status === 'completed') {
            return back()->with('info', 'Booking is already completed.');
        }

        $booking->update(['status' => 'completed']);

        // Create invoice for patient so they can pay; payment flow starts here
        $patientUserId = $booking->patient?->user_id;
        if ($patientUserId && !Invoice::where('booking_id', $booking->id)->exists()) {
            $invoiceNumber = 'INV-' . str_pad((string) (Invoice::max('id') ?? 0) + 1, 5, '0', STR_PAD_LEFT);
            Invoice::create([
                'user_id'        => $patientUserId,
                'booking_id'     => $booking->id,
                'invoice_number' => $invoiceNumber,
                'amount'         => $booking->price ?? 0,
                'status'        => 'pending',
                'due_date'      => now()->addDays(7),
                'notes'         => 'Service: ' . ($booking->service?->name ?? 'Care'),
            ]);
        }

        return back()->with('success', 'Booking completed. Patient can pay from their Invoices page.');
    }

    // Caregiver marks payment as received (updates booking + invoice so both sides show paid)
    public function markPaid(Booking $booking)
    {
        $caregiver = Auth::user()->caregiver;
        if (!$caregiver || $booking->caregivers_id != $caregiver->id) {
            abort(403, 'Unauthorized.');
        }
        if ($booking->status !== 'completed') {
            return back()->with('error', 'Only completed bookings can be marked as paid.');
        }
        if (($booking->payment_status ?? '') === 'paid') {
            return back()->with('info', 'Payment already recorded.');
        }

        $booking->update(['payment_status' => 'paid']);
        Invoice::where('booking_id', $booking->id)->update([
            'status'    => 'paid',
            'paid_date' => now(),
        ]);

        $patientUser = $booking->patient?->user;
        if ($patientUser) {
            $patientUser->notify(new \App\Notifications\CaregiverMarkedPaymentReceivedNotification(
                $booking,
                Auth::user()->name ?? 'Caregiver'
            ));
        }

        return back()->with('success', 'Payment marked as received. Invoice updated for patient.');
    }

    // Show patient profile (only if this caregiver has a booking with that patient)
    public function showPatient(Patient $patient)
    {
        $caregiver = Auth::user()->caregiver;
        if (!$caregiver) {
            abort(403, 'Caregiver profile not found.');
        }

        $hasRelationship = Booking::where('caregivers_id', $caregiver->id)
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

        $booking = Booking::where('caregivers_id', $caregiver->id)
            ->where('patients_id', $patientId)
            ->where('status', 'completed')
            ->first();

        Review::create([
            'user_id'     => $caregiverUserId,
            'service_id'  => $booking?->services_id,
            'booking_id'  => $booking?->id,
            'rating'      => $request->rating,
            'comment'     => $request->comment ?? '',
        ]);

        return redirect()->route('caregiver.bookings')
            ->with('success', 'Review submitted successfully!');
    }
}
