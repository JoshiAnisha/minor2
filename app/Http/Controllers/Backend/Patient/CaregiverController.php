<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use App\Models\Caregiver;
use App\Models\Bid;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Support\Facades\Storage;

class CaregiverController extends Controller
{
    /**
     * Show a caregiver's profile. Patient can only view caregivers who have
     * placed a bid on their request or have a booking with them.
     */
    public function show(Caregiver $caregiver)
    {
        $patient = auth()->user()->patient;
        if (!$patient) {
            abort(403, 'Patient profile not found.');
        }

        $hasBid = Bid::where('caregivers_id', $caregiver->id)
            ->whereHas('serviceRequest', fn ($q) => $q->where('patient_id', $patient->id))
            ->exists();

        $hasBooking = Booking::where('caregivers_id', $caregiver->id)
            ->where('patients_id', $patient->id)
            ->exists();

        if (!$hasBid && !$hasBooking) {
            abort(403, 'You can only view profiles of caregivers who have responded to your requests or have a booking with you.');
        }

        $caregiver->load('user');

        // Reviews about this caregiver (reviews left by patients for bookings with this caregiver)
        $reviews = Review::with(['user', 'booking.patient.user'])
            ->whereHas('booking', fn ($q) => $q->where('caregivers_id', $caregiver->id))
            ->latest()
            ->get();

        return view('backend.patient.caregiver.show', compact('caregiver', 'reviews'));
    }

    /**
     * Show caregiver's certificate. Same authorization as show().
     */
    public function certificate(Caregiver $caregiver)
    {
        $patient = auth()->user()->patient;
        if (!$patient) {
            abort(403, 'Patient profile not found.');
        }

        $hasBid = Bid::where('caregivers_id', $caregiver->id)
            ->whereHas('serviceRequest', fn ($q) => $q->where('patient_id', $patient->id))
            ->exists();

        $hasBooking = Booking::where('caregivers_id', $caregiver->id)
            ->where('patients_id', $patient->id)
            ->exists();

        if (!$hasBid && !$hasBooking) {
            abort(403, 'You can only view certificates of caregivers who have responded to your requests or have a booking with you.');
        }

        if (!$caregiver->certificate_path || !Storage::disk('public')->exists($caregiver->certificate_path)) {
            abort(404, 'Certificate not found.');
        }

        return response(
            Storage::disk('public')->get($caregiver->certificate_path),
            200,
            [
                'Content-Type' => mime_content_type(Storage::disk('public')->path($caregiver->certificate_path)),
                'Content-Disposition' => 'inline',
            ]
        );
    }

    /**
     * Serve caregiver's profile photo. Same authorization as show().
     * Returns the uploaded photo or a default image so the profile picture always displays.
     */
    public function photo(Caregiver $caregiver)
    {
        $patient = auth()->user()->patient;
        if (!$patient) {
            abort(403, 'Patient profile not found.');
        }

        $hasBid = Bid::where('caregivers_id', $caregiver->id)
            ->whereHas('serviceRequest', fn ($q) => $q->where('patient_id', $patient->id))
            ->exists();

        $hasBooking = Booking::where('caregivers_id', $caregiver->id)
            ->where('patients_id', $patient->id)
            ->exists();

        if (!$hasBid && !$hasBooking) {
            abort(403, 'You can only view profiles of caregivers who have responded to your requests or have a booking with you.');
        }

        if ($caregiver->profile_photo_path && Storage::disk('public')->exists($caregiver->profile_photo_path)) {
            return response(
                Storage::disk('public')->get($caregiver->profile_photo_path),
                200,
                [
                    'Content-Type' => mime_content_type(Storage::disk('public')->path($caregiver->profile_photo_path)),
                    'Content-Disposition' => 'inline',
                ]
            );
        }

        // No photo or file missing: serve default placeholder from public path
        $defaultPath = public_path('Images/default-profile.png');
        if (file_exists($defaultPath)) {
            return response()->file($defaultPath, ['Content-Type' => 'image/png']);
        }

        abort(404, 'Profile photo not found.');
    }
}
