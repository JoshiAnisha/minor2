<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Service;

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

        $patient = $user->patient;

        try {
            $totalBookings = $patient
                ? Booking::where('patients_id', $patient->id)->count()
                : 0;
            $upcomingServices = $patient
                ? Booking::where('patients_id', $patient->id)->whereIn('status', ['pending', 'accepted'])->whereDate('date_time', '>=', now())->count()
                : 0;
            $totalInvoices = Invoice::where('user_id', $user->id)->count();

            $latestBookings = $patient
                ? Booking::where('patients_id', $patient->id)->with('service', 'caregiver.user')->orderByDesc('created_at')->take(5)->get()
                : collect([]);

            // Services opened by admin (visible to patients for booking)
            $availableServices = Service::orderBy('name')->get();
        } catch (\Exception $e) {
            // If there's an error, set defaults
            $totalBookings = 0;
            $upcomingServices = 0;
            $totalInvoices = 0;
            $latestBookings = collect([]);
            $availableServices = collect([]);
        }

        return view('backend.patient.dashboard.index', compact(
            'totalBookings',
            'upcomingServices',
            'totalInvoices',
            'latestBookings',
            'availableServices'
        ));
    }
}
