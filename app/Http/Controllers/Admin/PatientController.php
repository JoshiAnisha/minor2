<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientHealthReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PatientController extends Controller
{
    public function index()
    {
        // Ensure every user with role=patient has a patient profile row (DB: patients.user_id -> users.id)
        User::where('role', 'patient')->get()->each(function (User $user) {
            if (Patient::where('user_id', $user->id)->doesntExist()) {
                Patient::create(Patient::defaultAttributesForCreate($user->id));
            }
        });

        // Only list patients whose user has role = 'patient' (exclude admin, caregiver, etc.)
        $patients = Patient::with('user')
            ->whereHas('user', fn ($q) => $q->where('role', 'patient'))
            ->orderByDesc('id')
            ->get();

        $totalPatients = $patients->count();
        $activePatients = $patients->where('is_active', true)->count();
        $pendingPatients = 0;

        return view('admin.patient', compact(
            'patients',
            'totalPatients',
            'activePatients',
            'pendingPatients'
        ));
    }

    public function show(Patient $patient)
    {
        $patient->load(['user', 'healthReports']);
        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Delete a patient's health report (admin only).
     */
    public function destroyHealthReport(Patient $patient, PatientHealthReport $healthReport)
    {
        if ($healthReport->patient_id !== $patient->id) {
            abort(404);
        }
        if (Storage::disk('public')->exists($healthReport->file_path)) {
            Storage::disk('public')->delete($healthReport->file_path);
        }
        $healthReport->delete();
        return redirect()->route('admin.patients.show', $patient)->with('success', 'Health report removed.');
    }

    /**
     * View a patient's health report inline (admin only).
     */
    public function viewHealthReport(Patient $patient, PatientHealthReport $healthReport): Response
    {
        if ($healthReport->patient_id !== $patient->id) {
            abort(404);
        }
        if (!Storage::disk('public')->exists($healthReport->file_path)) {
            abort(404, 'File not found.');
        }
        $path = Storage::disk('public')->path($healthReport->file_path);
        return response(Storage::disk('public')->get($healthReport->file_path), 200, [
            'Content-Type'        => mime_content_type($path),
            'Content-Disposition' => 'inline',
        ]);
    }

    public function edit(Patient $patient)
    {
        return view('admin.patient-edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'medical_history' => 'nullable|string',
            'prescriptions' => 'nullable|string',
            'health_condition' => 'nullable|string',
        ]);

        $patient->update($request->only([
            'medical_history',
            'prescriptions',
            'health_condition'
        ]));

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient updated successfully');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient deleted successfully');
    }

    /**
     * Toggle patient active status (activate / deactivate).
     */
    public function toggleActive(Patient $patient)
    {
        $patient->update(['is_active' => !$patient->is_active]);
        $status = $patient->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.patients.index')
            ->with('success', "Patient {$status} successfully.");
    }
}
