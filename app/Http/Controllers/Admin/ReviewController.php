<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Reviews;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Reviews::with(['patient', 'caregiver'])
            ->latest()
            ->get();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy($id)
    {
        Reviews::findOrFail($id)->delete();
        return back()->with('success', 'Review deleted successfully');
    }
}
