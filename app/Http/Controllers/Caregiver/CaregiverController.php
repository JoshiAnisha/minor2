<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Bid;
use App\Models\ServiceRequest;
use App\Models\Service;

class CaregiverController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $caregiver = $user->caregiver;
        if (!$caregiver && $user->role === 'caregiver') {
            $caregiver = \App\Models\Caregiver::create(['user_id' => $user->id, 'users_id' => $user->id]);
        }
        if (!$caregiver) {
            $pendingServiceRequests = ServiceRequest::with('user', 'service')
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();
            $today = now()->toDateString();
            $availableServices = Service::where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })->orderBy('name')->get();
            return view('Caregiver.dashboard', [
                'upcomingVisits' => 0,
                'tasksToLog' => 0,
                'completedBookings' => 0,
                'averageRating' => 0,
                'todaysBookings' => collect(),
                'pendingCount' => 0,
                'inProgressCount' => 0,
                'completedCount' => 0,
                'pendingServiceRequests' => $pendingServiceRequests,
                'weeklyBookings' => [0, 0, 0, 0, 0, 0, 0],
                'availableServices' => $availableServices,
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

        // Average rating (replace with real rating logic later)
        $averageRating = 4.8;

        // Today's bookings
        $todaysBookings = Booking::with(['patient.user', 'service'])
                                ->where('caregivers_id', $caregiverId)
                                ->whereDate('date_time', $today)
                                ->orderBy('date_time', 'asc')
                                ->get();

        // Booking overview for chart
        $pendingCount = Booking::where('caregivers_id', $caregiverId)
                                ->where('status', 'pending')
                                ->count();
        $inProgressCount = Booking::where('caregivers_id', $caregiverId)
                                ->where('status', 'accepted')
                                ->count();
        $completedCount = $completedBookings;

        $pendingServiceRequests = ServiceRequest::with('user', 'service')
            ->where('status', 'pending')
            ->whereNotIn('id', \App\Models\ServiceRequestRejection::where('caregiver_id', $caregiverId)->pluck('service_request_id'))
            ->latest()
            ->take(5)
            ->get();

        $startOfWeek = now()->startOfWeek();
        $weeklyBookings = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $weeklyBookings[] = Booking::where('caregivers_id', $caregiverId)
                ->whereDate('date_time', $day)
                ->count();
        }

        $today = now()->toDateString();
        $availableServices = Service::where(function ($q) use ($today) {
            $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
        })->where(function ($q) use ($today) {
            $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
        })->orderBy('name')->get();

        return view('Caregiver.dashboard', compact(
            'upcomingVisits',
            'tasksToLog',
            'completedBookings',
            'averageRating',
            'todaysBookings',
            'pendingCount',
            'inProgressCount',
            'completedCount',
            'pendingServiceRequests',
            'weeklyBookings',
            'availableServices'
        ));
    }
}
