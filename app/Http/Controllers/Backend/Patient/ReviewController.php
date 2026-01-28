<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Service;

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
            'comment' => $request->comment,
        ]);

        return redirect()->route('patient.reviews.index')->with('success', 'Review submitted successfully.');
    }
}
