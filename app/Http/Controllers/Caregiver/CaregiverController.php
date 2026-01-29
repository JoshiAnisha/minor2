<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Bids;

class CaregiverController extends Controller
{
    public function index()
{
    $caregiverId = Auth::id();
    $today = now()->toDateString();

    $upcomingVisits = Booking::where('caregivers_id', $caregiverId)
        ->whereIn('status', ['pending', 'accepted'])
        ->whereDate('date_time', '>=', $today)
        ->count();

    $tasksToLog = Booking::where('caregivers_id', $caregiverId)
        ->where('status', 'accepted')
        ->whereDate('date_time', '<=', $today)
        ->count();

    $completedBookings = Booking::where('caregivers_id', $caregiverId)
        ->where('status', 'completed')
        ->count();

    $averageRating = 4.8;

    $todaysBookings = Booking::with(['patient.user', 'service'])
        ->where('caregivers_id', $caregiverId)
        ->whereDate('date_time', $today)
        ->orderBy('date_time', 'asc')
        ->get();

    $pendingCount = Booking::where('caregivers_id', $caregiverId)
        ->where('status', 'pending')
        ->count();

    $inProgressCount = Booking::where('caregivers_id', $caregiverId)
        ->where('status', 'accepted')
        ->count();

    // ✅ FIX
    $completedCount = $completedBookings;

    return view('Caregiver.dashboard', compact(
        'upcomingVisits',
        'tasksToLog',
        'completedBookings',
        'averageRating',
        'todaysBookings',
        'pendingCount',
        'inProgressCount',
        'completedCount'
    ));
}

}
