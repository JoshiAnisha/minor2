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
            $caregiver = \App\Models\Caregiver::create(['user_id' => $user->id, 'users_id' => $user->id, 'availability_status' => true]);
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

        // Accepted / In Progress bookings (each item has 'booking' and 'booking_id' for the view)
        $acceptedBookings = Booking::with('patient.user', 'service', 'serviceRequest')
            ->where('caregivers_id', $caregiverId)
            ->where('status', 'accepted')
            ->get()
            ->map(function ($booking) {
                // Ensure we have a numeric id for the complete form (model key from DB)
                $id = $booking->getKey();
                return (object) ['booking' => $booking, 'booking_id' => $id];
            });

        // Completed bookings
        $completedBookings = Booking::with('patient.user', 'service', 'serviceRequest')
            ->where('caregivers_id', $caregiverId)
            ->where('status', 'completed')
            ->get();

        // Cancelled bookings (caregiver cancelled in-progress)
        $cancelledBookings = Booking::with('patient.user', 'service')
            ->where('caregivers_id', $caregiverId)
            ->where('status', 'cancelled')
            ->latest('updated_at')
            ->get();

        // Reviews left by this caregiver for these bookings (booking_id => Review)
        $bookingIds = $completedBookings->isEmpty() ? [] : $completedBookings->map(fn ($b) => $b->getKey())->filter()->values()->all();
        $reviewsByBookingId = [];
        if (!empty($bookingIds)) {
            $reviews = Review::where('user_id', $user->id)
                ->whereIn('booking_id', $bookingIds)
                ->get();
            foreach ($reviews as $r) {
                $reviewsByBookingId[$r->booking_id] = $r;
            }
        }

        return view('Caregiver.caregiverBooking', compact(
            'pendingBookings',
            'acceptedBookings',
            'completedBookings',
            'cancelledBookings',
            'reviewsByBookingId'
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
    public function complete($id)
    {
        if (empty($id) || (string) $id === '0') {
            return redirect()->route('caregiver.bookings')->with('error', 'Invalid booking.');
        }
        $booking = Booking::findOrFail($id);
        $caregiver = Auth::user()->caregiver;
        if (!$caregiver || $booking->caregivers_id != $caregiver->id) {
            abort(403, 'Unauthorized.');
        }
        $booking->load('patient', 'service');
        if ($booking->status === 'completed') {
            return back()->with('info', 'Booking is already completed.');
        }

        $booking->update(['status' => 'completed']);

        // Create invoice for patient so they can pay; payment flow starts here
        $patientUser = $booking->patient?->user;
        $patientUserId = $booking->patient?->user_id;
        if ($patientUserId && !Invoice::where('booking_id', $booking->getKey())->exists()) {
            $invoiceNumber = 'INV-' . str_pad((string) (Invoice::max('id') ?? 0) + 1, 5, '0', STR_PAD_LEFT);
            Invoice::create([
                'user_id'        => $patientUserId,
                'booking_id'     => $booking->getKey(),
                'invoice_number' => $invoiceNumber,
                'amount'         => $booking->price ?? 0,
                'status'        => 'pending',
                'due_date'      => now()->addDays(7),
                'notes'         => 'Service: ' . ($booking->service?->name ?? 'Care'),
            ]);
        }

        if ($patientUser) {
            $patientUser->notify(new \App\Notifications\BookingCompletedNotification(
                $booking,
                Auth::user()->name ?? 'Caregiver'
            ));
        }

        return redirect()->route('caregiver.bookings')
            ->with('success', 'Booking completed. It now appears in the Completed section. Patient can pay from their Invoices page.');
    }

    // Cancel an in-progress booking (caregiver backs out; service request reopens for others)
    public function cancel($id)
    {
        if (empty($id) || (string) $id === '0') {
            return redirect()->route('caregiver.bookings')->with('error', 'Invalid booking.');
        }
        $booking = Booking::findOrFail($id);
        $caregiver = Auth::user()->caregiver;
        if (!$caregiver || $booking->caregivers_id != $caregiver->id) {
            abort(403, 'Unauthorized.');
        }
        if ($booking->status !== 'accepted') {
            return redirect()->route('caregiver.bookings')->with('error', 'Only in-progress bookings can be cancelled.');
        }

        $booking->load('patient.user', 'service');
        $booking->update(['status' => 'cancelled']);

        $patientUser = $booking->patient?->user;
        if ($patientUser) {
            $patientUser->notify(new \App\Notifications\BookingCancelledNotification(
                $booking,
                Auth::user()->name ?? 'Caregiver'
            ));
        }

        return redirect()->route('caregiver.bookings')->with('success', 'Booking cancelled. It has been moved to the Cancelled section.');
    }

    // Caregiver marks payment as received (updates booking + invoice so both sides show paid)
    public function markPaid($id)
    {
        $booking = Booking::findOrFail($id);
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
        Invoice::where('booking_id', $booking->getKey())->update([
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

        return redirect()->route('caregiver.bookings')->with('success', 'Payment marked as received. Invoice updated for patient.');
    }

    // Show patient profile (if caregiver has a booking with this patient, or patient has a pending service request the caregiver can see)
    public function showPatient(Patient $patient)
    {
        $caregiver = Auth::user()->caregiver;
        if (!$caregiver) {
            abort(403, 'Caregiver profile not found.');
        }

        $hasBooking = Booking::where('caregivers_id', $caregiver->id)
            ->where('patients_id', $patient->id)
            ->exists();

        $rejectedIds = \App\Models\ServiceRequestRejection::where('caregiver_id', $caregiver->id)->pluck('service_request_id');
        $hasVisibleRequest = ServiceRequest::where('patient_id', $patient->id)
            ->where('status', 'pending')
            ->whereNotIn('id', $rejectedIds)
            ->exists();

        if (!$hasBooking && !$hasVisibleRequest) {
            abort(403, 'You can only view profiles of patients you have a booking with or whose service request you can respond to.');
        }

        $patient->load(['user', 'healthReports']);

        return view('caregiver.patientProfile', compact('patient'));
    }

    /**
     * View a patient's health report inline (caregiver with access to this patient).
     */
    public function viewPatientHealthReport(Patient $patient, \App\Models\PatientHealthReport $healthReport)
    {
        $caregiver = Auth::user()->caregiver;
        if (!$caregiver) {
            abort(403);
        }
        if ($healthReport->patient_id !== $patient->id) {
            abort(404);
        }
        $hasBooking = Booking::where('caregivers_id', $caregiver->id)->where('patients_id', $patient->id)->exists();
        $rejectedIds = \App\Models\ServiceRequestRejection::where('caregiver_id', $caregiver->id)->pluck('service_request_id');
        $hasVisibleRequest = ServiceRequest::where('patient_id', $patient->id)->where('status', 'pending')->whereNotIn('id', $rejectedIds)->exists();
        if (!$hasBooking && !$hasVisibleRequest) {
            abort(403, 'You do not have access to this patient\'s health reports.');
        }
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($healthReport->file_path)) {
            abort(404, 'File not found.');
        }
        $path = \Illuminate\Support\Facades\Storage::disk('public')->path($healthReport->file_path);
        return response(\Illuminate\Support\Facades\Storage::disk('public')->get($healthReport->file_path), 200, [
            'Content-Type'        => mime_content_type($path),
            'Content-Disposition' => 'inline',
        ]);
    }

    // Show review form page
    public function createReview(Patient $patient)
    {
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

        // Already reviewed this patient for a completed booking — don't show form again
        $alreadyReviewed = Review::where('user_id', Auth::id())
            ->whereHas('booking', fn ($q) => $q->where('patients_id', $patient->id))
            ->exists();
        if ($alreadyReviewed) {
            return redirect()->route('caregiver.bookings')
                ->with('info', 'You have already left a review for this patient. It is shown in the Completed section.');
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

        if (!$booking) {
            return back()->with('error', 'No completed booking found with this patient.');
        }

        if (Review::where('user_id', $caregiverUserId)->where('booking_id', $booking->getKey())->exists()) {
            return redirect()->route('caregiver.bookings')
                ->with('info', 'You have already reviewed this patient for this booking.');
        }

        Review::create([
            'user_id'     => $caregiverUserId,
            'service_id'  => $booking?->services_id,
            'booking_id'  => $booking?->getKey(),
            'rating'      => $request->rating,
            'comment'    => $request->comment ?? '',
        ]);

        return redirect()->route('caregiver.bookings')
            ->with('success', 'Review submitted successfully!');
    }
}
