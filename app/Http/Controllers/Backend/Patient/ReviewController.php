<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Service;
use App\Models\Booking;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('service')->where('user_id', Auth::id())->get();
        return view('backend.patient.reviews.index', compact('reviews'));
    }

    public function create()
    {
        $services = Service::all();
        return view('backend.patient.reviews.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'service_id' => $request->service_id,
            'rating' => $request->rating,
            'comments' => $request->comment,
        ]);

        return redirect()->route('patient.reviews.index')->with('success', 'Review submitted successfully.');
    }

    public function edit(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own reviews.');
        }
        $review->load('service');
        $services = Service::all();
        return view('backend.patient.reviews.edit', compact('review', 'services'));
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own reviews.');
        }
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        $review->update([
            'service_id' => $request->service_id,
            'rating' => $request->rating,
            'comments' => $request->comment,
        ]);

        return redirect()->route('patient.profile.show')->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'You can only delete your own reviews.');
        }
        $review->delete();
        return redirect()->back()->with('success', 'Review deleted successfully.');
    }

    /**
     * Show form to leave a review for a completed booking.
     */
    public function createForBooking($id)
    {
        $patient = Auth::user()->patient;
        if (!$patient) {
            return redirect()->route('patient.dashboard')->with('error', 'Patient profile not found.');
        }
        $booking = Booking::with('service')->where('patients_id', $patient->id)->findOrFail($id);
        if ($booking->status !== 'completed') {
            return redirect()->route('patient.bookings.show', ['id' => $id])
                ->with('info', 'You can review only completed bookings.');
        }
        $existing = Review::where('user_id', Auth::id())->where('bookings_id', $booking->getKey())->first();
        if ($existing) {
            return redirect()->route('patient.reviews.edit', $existing)
                ->with('info', 'You already reviewed this booking. You can edit it below.');
        }
        return view('backend.patient.reviews.create-for-booking', compact('booking'));
    }

    /**
     * Store a review for a completed booking.
     */
    public function storeForBooking(Request $request, $id)
    {
        $patient = Auth::user()->patient;
        if (!$patient) {
            return redirect()->route('patient.dashboard')->with('error', 'Patient profile not found.');
        }
        $booking = Booking::with('service')->where('patients_id', $patient->id)->findOrFail($id);
        if ($booking->status !== 'completed') {
            return redirect()->route('patient.bookings.show', ['id' => $id])
                ->with('error', 'You can review only completed bookings.');
        }
        if (Review::where('user_id', Auth::id())->where('bookings_id', $booking->getKey())->exists()) {
            return redirect()->route('patient.bookings.show', ['id' => $id])
                ->with('info', 'You have already reviewed this booking.');
        }
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        Review::create([
            'user_id'    => Auth::id(),
            'service_id' => $booking->services_id,
            'bookings_id'=> $booking->getKey(),
            'rating'     => $request->rating,
            'comments'  => $request->comment,
        ]);

        return redirect()->route('patient.bookings.show', ['id' => $id])
            ->with('success', 'Thank you! Your review has been submitted.');
    }
}
