<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Service;
use App\Models\ServiceRequest;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check() || !Auth::user()) {
            return redirect()->route('backend.auth.login')
                ->with('error', 'Please login to access the patient dashboard.');
        }

        $user = Auth::user();

        if (!$user) {
            Auth::logout();
            return redirect()->route('backend.auth.login')->withErrors(['error' => 'User not found.']);
        }

        if ($user->role === 'patient' && !$user->patient) {
            Patient::create(Patient::defaultAttributesForCreate($user->id));
            $user->load('patient');
        }
        $patient = $user->patient;

        try {
            $totalBookings = $patient
                ? Booking::where('patients_id', $patient->id)->count()
                : 0;
            $myRequestsCount = $patient ? ServiceRequest::where('patient_id', $patient->id)->count() : 0;
            $totalInvoices = Invoice::where('user_id', $user->id)->count();

            $latestBookings = $patient
                ? Booking::where('patients_id', $patient->id)->with('service', 'caregiver.user')->orderByDesc('created_at')->take(5)->get()
                : collect([]);

            $today = now()->toDateString();
            $availableServices = Service::where('slug', '!=', 'other-custom-request')
                ->where(function ($q) use ($today) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
                })
                ->where(function ($q) use ($today) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
                })
                ->orderBy('name')->get();
        } catch (\Exception $e) {
            $totalBookings = 0;
            $myRequestsCount = 0;
            $totalInvoices = 0;
            $latestBookings = collect([]);
            $availableServices = collect([]);
        }

        $profileComplete = $user->isProfileComplete();

        return view('patient.dashboard.index', compact(
            'totalBookings',
            'myRequestsCount',
            'totalInvoices',
            'latestBookings',
            'availableServices',
            'profileComplete'
        ));
    }
}
