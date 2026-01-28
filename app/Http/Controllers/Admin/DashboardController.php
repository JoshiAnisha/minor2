<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Caregiver;
use App\Models\Service;
use App\Models\Booking;
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

        // Key Metrics
        $totalPatients = Patient::count();
        // Count active caregivers (availability_status = 1 or true, not null)
        $totalCaregivers = Caregiver::where(function($query) {
            $query->where('availability_status', 1)
                  ->orWhere('availability_status', true);
        })->count();
        $totalServices = Service::count();

        // Bookings by status for chart
        $bookingsByStatus = [
            'pending' => Booking::where('status', 'pending')->count(),
            'in_process' => Booking::where('status', 'accepted')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

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
