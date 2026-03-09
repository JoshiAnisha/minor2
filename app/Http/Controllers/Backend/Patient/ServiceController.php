<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Patient;
use App\Models\User;
use App\Notifications\NewServiceRequestNotification;
use App\Notifications\ServiceRequestSubmittedNotification;

class ServiceController extends Controller
{
    /**
     * List available services (catalog; exclude "Other" which is only for custom request).
     */
    public function index()
    {
        $today = now()->toDateString();
        $services = Service::where('slug', '!=', 'other-custom-request')
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->orderBy('category')
            ->orderBy('name')
            ->get();
        return view('backend.patient.services.index', compact('services'));
    }

    public function show($slug)
    {
        $today = now()->toDateString();
        $service = Service::where('slug', $slug)
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->firstOrFail();
        return view('backend.patient.services.show', compact('service'));
    }

    /**
     * Patient requests a service (creates ServiceRequest).
     * Short-term (one-day) services: single preferred date only.
     * Long-term services: start date and end date required.
     */
    public function book(Request $request)
    {
        if (!auth()->user()->isProfileComplete()) {
            return redirect()->route('patient.profile.edit')
                ->with('error', 'Please complete your profile (name, email, contact number, and address) before making a service request.');
        }

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'location'   => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $service = Service::findOrFail($request->service_id);

        if ($service->slug === 'other-custom-request') {
            $request->validate(['description' => 'required|string|max:1000']);
        }

        $isLongTerm = $this->resolveIsLongTerm($request, $service);

        if ($isLongTerm) {
            $request->validate([
                'start_date' => 'required|date|after_or_equal:today',
                'end_date'   => 'required|date|after_or_equal:today|after_or_equal:start_date',
            ]);
            $preferredTime = \Carbon\Carbon::parse($request->start_date . ' 09:00');
            $startDate = $request->start_date;
            $endDate = $request->end_date;
        } else {
            $request->validate([
                'preferred_date' => 'required|date|after_or_equal:today',
            ]);
            $preferredTime = \Carbon\Carbon::parse($request->preferred_date . ' 09:00');
            $startDate = null;
            $endDate = null;
        }

        $user = auth()->user();
        $patient = $user->patient ?? Patient::firstOrCreate(
            ['user_id' => $user->id],
            ['email' => $user->email]
        );

        $basePricePerDay = (float) ($service->base_price ?? 0);
        if ($isLongTerm && $startDate && $endDate) {
            $days = \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1;
            $totalBasePrice = $days * $basePricePerDay;
        } else {
            $totalBasePrice = $basePricePerDay;
        }

        $serviceRequest = ServiceRequest::create([
            'patient_id'        => $patient->id,
            'service_id'        => $service->id,
            'location'          => $request->location,
            'preferred_time'    => $preferredTime,
            'start_date'        => $startDate,
            'end_date'          => $endDate,
            'total_base_price'  => $totalBasePrice,
            'description'       => $request->description,
            'status'            => 'pending',
        ]);

        $serviceRequest->load('service', 'user');

        // Notify all caregivers so it appears in their notifications
        User::where('role', 'caregiver')->get()->each(function (User $u) use ($serviceRequest) {
            $u->notify(new NewServiceRequestNotification($serviceRequest));
        });

        // Notify the patient (confirmation in their notifications list)
        $user->notify(new ServiceRequestSubmittedNotification($serviceRequest));

        return redirect()->route('patient.service-requests.index')
            ->with('success', 'Service request submitted. All caregivers will see your request and can accept at base price or place a bid. Once one accepts, it will appear under My Bookings.');
    }

    /** For catalog services use is_long_term; for custom use request duration. */
    private function resolveIsLongTerm(Request $request, Service $service): bool
    {
        if ($service->slug !== 'other-custom-request') {
            return (bool) ($service->is_long_term ?? false);
        }
        return $request->input('duration') === 'long_term';
    }
}
