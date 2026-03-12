<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Bid;
use App\Models\ServiceRequest;

class CaregiverController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $caregiver = $user->caregiver;
        if (!$caregiver && $user->role === 'caregiver') {
            $caregiver = \App\Models\Caregiver::create(['user_id' => $user->id, 'users_id' => $user->id, 'availability_status' => true]);
        }
        if (!$caregiver) {
            $pendingServiceRequests = ServiceRequest::with('user', 'service')
                ->where('status', 'pending')
                ->latest()
                ->take(3)
                ->get();
            return view('Caregiver.dashboard', [
                'upcomingVisits' => 0,
                'tasksToLog' => 0,
                'completedBookings' => 0,
                'todaysBookings' => collect(),
                'pendingCount' => 0,
                'inProgressCount' => 0,
                'completedCount' => 0,
                'pendingServiceRequests' => $pendingServiceRequests,
                'weeklyBookings' => [0, 0, 0, 0, 0, 0, 0],
                'profileComplete' => false,
            ]);
        }
        $caregiverId = $caregiver->id;
        $today = now()->toDateString();

        // Metrics
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

        // Today's bookings
        $todaysBookings = Booking::with(['patient.user', 'service'])
                                ->where('caregivers_id', $caregiverId)
                                ->whereDate('date_time', $today)
                                ->orderBy('date_time', 'asc')
                                ->get();

        // Booking overview for chart (Pending = pending bookings + pending bids + pending service requests, same as My Bookings Pending section)
        $rejectedRequestIds = \App\Models\ServiceRequestRejection::where('caregiver_id', $caregiverId)->pluck('service_request_id');
        $pendingBookingsCount = Booking::where('caregivers_id', $caregiverId)
            ->where('status', 'pending')
            ->count();
        $pendingBidsCount = Bid::where('caregivers_id', $caregiverId)
            ->where('status', 'pending')
            ->whereHas('serviceRequest')
            ->count();
        $pendingRequestsCount = ServiceRequest::where('status', 'pending')->count();
        $pendingCount = $pendingBookingsCount + $pendingBidsCount + $pendingRequestsCount;
        $inProgressCount = Booking::where('caregivers_id', $caregiverId)
                                ->where('status', 'accepted')
                                ->count();
        $completedCount = $completedBookings;

        $pendingServiceRequests = ServiceRequest::with('user', 'service')
            ->where('status', 'pending')
            ->whereNotIn('id', $rejectedRequestIds)
            ->latest()
            ->take(3)
            ->get();

        $startOfWeek = now()->startOfWeek();
        $weeklyBookings = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $weeklyBookings[] = Booking::where('caregivers_id', $caregiverId)
                ->whereDate('date_time', $day)
                ->count();
        }

        $profileComplete = $user->isProfileComplete();

        return view('Caregiver.dashboard', compact(
            'upcomingVisits',
            'tasksToLog',
            'completedBookings',
            'todaysBookings',
            'pendingCount',
            'inProgressCount',
            'completedCount',
            'pendingServiceRequests',
            'weeklyBookings',
            'profileComplete'
        ));
    }
}
