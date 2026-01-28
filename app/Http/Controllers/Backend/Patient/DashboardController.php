<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function index()
    {
        // Ensure user is authenticated - enforce authentication
        if (!Auth::check() || !Auth::user()) {
            return redirect()->route('backend.auth.login')
                ->with('error', 'Please login to access the patient dashboard.');
        }

        $user = Auth::user();

        // Check if user exists
        if (!$user) {
            Auth::logout();
            return redirect()->route('backend.auth.login')->withErrors(['error' => 'User not found.']);
        }

        // Numeric counts - handle potential errors
        try {
            $totalBookings = Booking::where('user_id', $user->id)->count();
            $upcomingServices = Booking::where('user_id', $user->id)
                                       ->where('status', 'upcoming')
                                       ->count();
            $totalInvoices = Invoice::where('user_id', $user->id)->count();

            // Latest 5 bookings with service info
            $latestBookings = Booking::where('user_id', $user->id)
                                     ->with('service') // eager load service relation
                                     ->orderByDesc('created_at')
                                     ->take(5)
                                     ->get();
        } catch (\Exception $e) {
            // If there's an error, set defaults
            $totalBookings = 0;
            $upcomingServices = 0;
            $totalInvoices = 0;
            $latestBookings = collect([]);
        }

        return view('backend.patient.dashboard.index', compact(
            'totalBookings',
            'upcomingServices',
            'totalInvoices',
            'latestBookings'
        ));
    }
}
