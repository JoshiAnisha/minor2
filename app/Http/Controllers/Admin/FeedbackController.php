<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reviews;
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

        // Fetch all reviews with reviewer information
        $reviews = Review::with(['reviewer', 'caregiverProfile.user'])
            ->latest()
            ->get();

        // Process reviews to get reviewed party information
        $reviews = $reviews->map(function ($review) {
            $review->reviewed_party = null;
            $review->reviewed_party_type = null;

            // Get reviewer info
            $reviewer = $review->reviewer;
            
            if ($reviewer) {
                // If reviewer is a caregiver, they reviewed a patient
                if ($reviewer->role === 'caregiver') {
                    $review->reviewed_party = $review->getReviewedPatient();
                    $review->reviewed_party_type = 'patient';
                }
                // If reviewer is a patient, they reviewed a caregiver/service
                elseif ($reviewer->role === 'patient') {
                    $review->reviewed_party = $review->caregiverProfile;
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

        $review = Reviews::findOrFail($id);
        $review->delete();

        return redirect()
            ->route('admin.feedback.index')
            ->with('success', 'Feedback deleted successfully');
    }
}
