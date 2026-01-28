<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        // Fetch all patients with related user
        $patients = Patient::with('user')->orderBy('id', 'desc')->get();

        // Counts
        $totalPatients = $patients->count();
        $activePatients = $patients->where('user.role', 'patient')->count();
        $pendingPatients = $totalPatients - $activePatients;

        return view('admin.patient', compact(
            'patients',
            'totalPatients',
            'activePatients',
            'pendingPatients'
        ));
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

        return redirect()->route('admin.patient')
            ->with('success', 'Patient updated successfully');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('admin.patient')
            ->with('success', 'Patient deleted successfully');
    }
}
