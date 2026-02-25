<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Show all feedback
     * Displays feedback from both patients and caregivers
     */
    public function index()
    {
        // Verify user is authenticated admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $reviews = Review::with(['user', 'service', 'booking.patient.user', 'booking.caregiver.user'])
            ->latest()
            ->get();

        $reviews = $reviews->map(function ($review) {
            $review->reviewed_party = null;
            $review->reviewed_party_type = null;
            $reviewer = $review->user;
            if ($reviewer) {
                if ($reviewer->role === 'caregiver' && $review->booking && $review->booking->patient) {
                    $review->reviewed_party = $review->booking->patient->user;
                    $review->reviewed_party_type = 'patient';
                } elseif ($reviewer->role === 'patient' && $review->booking && $review->booking->caregiver) {
                    $review->reviewed_party = $review->booking->caregiver;
                    $review->reviewed_party_type = 'caregiver';
                }
            }
            return $review;
        });

        return view('admin.feedback', compact('reviews'));
    }

    /**
     * Delete feedback
     * Requires confirmation before deletion
     */
    public function destroy($id)
    {
        // Verify user is authenticated admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()
            ->route('admin.feedback')
            ->with('success', 'Feedback deleted successfully');
    }
}
