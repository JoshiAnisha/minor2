<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Caregiver;
use App\Models\Service;
use App\Models\Booking;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard with metrics and charts
     */
    public function index()
    {
        // Verify user is authenticated admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        // Key metrics: only count by role so admin/other users are excluded
        $totalPatients = Patient::whereHas('user', fn ($q) => $q->where('role', 'patient'))->count();
        $totalCaregivers = Caregiver::whereHas('user', fn ($q) => $q->where('role', 'caregiver'))->count();
        $totalServices = Service::count();
        $pendingServiceRequests = ServiceRequest::where('status', 'pending')->count();

        // Bookings by status (bookings.status enum: pending, accepted, completed, cancelled)
        $bookingsByStatus = [
            'pending'   => Booking::where('status', 'pending')->count(),
            'in_process' => Booking::where('status', 'accepted')->count(),
            'completed'  => Booking::where('status', 'completed')->count(),
            'cancelled'  => Booking::where('status', 'cancelled')->count(),
        ];
        $totalBookings = array_sum($bookingsByStatus);

        // Prepare data for Chart.js
        $chartLabels = ['Pending', 'In Process', 'Completed', 'Cancelled'];
        $chartData = [
            $bookingsByStatus['pending'],
            $bookingsByStatus['in_process'],
            $bookingsByStatus['completed'],
            $bookingsByStatus['cancelled'],
        ];
        $chartColors = ['#ffc107', '#0dcaf0', '#198754', '#dc3545'];

        return view('admin.dashboard', compact(
            'totalPatients',
            'totalCaregivers',
            'totalServices',
            'pendingServiceRequests',
            'totalBookings',
            'bookingsByStatus',
            'chartLabels',
            'chartData',
            'chartColors'
        ));
    }

    /**
     * Show admin profile
     */
    public function profile()
    {
        // Verify user is authenticated admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.profile');
    }
}
