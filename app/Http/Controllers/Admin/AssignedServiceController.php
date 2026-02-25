<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssignedService;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Http\Request;

class AssignedServiceController extends Controller
{
    private function ensureAdmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * List all assigned services.
     */
    public function index()
    {
        $this->ensureAdmin();
        $assignedServices = AssignedService::with(['patient', 'service', 'admin', 'assignedCaregiver.user', 'bids'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.assigned-services.index', compact('assignedServices'));
    }

    /**
     * Show form to assign a new service to a patient.
     */
    public function create()
    {
        $this->ensureAdmin();
        $patients = Patient::whereHas('user')->with('user')->get();
        $services = Service::all();

        return view('admin.assigned-services.create', compact('patients', 'services'));
    }

    /**
     * Store a newly assigned service.
     */
    public function store(Request $request)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'patient_id'      => 'required|exists:patients,id',
            'service_id'      => 'required|exists:services,id',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string|max:5000',
            'preferred_date'  => 'required|date|after_or_equal:today',
            'budget'          => 'nullable|numeric|min:0',
            'location'        => 'nullable|string|max:255',
        ]);

        $patient = Patient::with('user')->findOrFail($validated['patient_id']);
        if (!$patient->user_id) {
            return back()->withErrors(['patient_id' => 'Selected patient has no linked user account.'])->withInput();
        }

        AssignedService::create([
            'patient_id'     => $patient->user_id,
            'service_id'     => $validated['service_id'],
            'admin_id'       => auth()->id(),
            'title'          => $validated['title'],
            'description'    => $validated['description'] ?? null,
            'preferred_date' => $validated['preferred_date'],
            'budget'         => $validated['budget'] ?? null,
            'location'       => $validated['location'] ?? null,
            'status'         => 'pending',
        ]);

        return redirect()->route('admin.assigned-services.index')
            ->with('success', 'Service assigned to patient successfully.');
    }
}
