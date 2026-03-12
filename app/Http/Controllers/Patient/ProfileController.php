<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientHealthReport;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $patient = $user->patient;

        $myReviews = Review::where('user_id', $user->id)->with('service')->latest()->take(15)->get();

        if ($patient) {
            $patient->load('healthReports');
        }
        $reviewsFromCaregivers = collect([]);
        if ($patient) {
            $reviewsFromCaregivers = Review::whereHas('user', fn ($q) => $q->where('role', 'caregiver'))
                ->whereHas('booking', fn ($q) => $q->where('patients_id', $patient->id))
                ->with(['user', 'service', 'booking.caregiver.user'])
                ->latest()
                ->take(15)
                ->get();
        }

        return view('patient.profile.show', compact('user', 'patient', 'myReviews', 'reviewsFromCaregivers'));
    }

    public function edit()
    {
        $user = Auth::user();
        $patient = $user->patient;
        if ($patient) {
            $patient->load('healthReports');
        }

        return view('patient.profile.edit', compact('user', 'patient'));
    }

    public function storeHealthReport(Request $request)
    {
        $user = Auth::user();
        $patient = $user->patient;
        if (!$patient) {
            return redirect()->route('patient.profile.edit')->with('error', 'Patient profile not found.');
        }

        $request->validate([
            'health_report' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ], [
            'health_report.required' => 'Please select a file to upload.',
            'health_report.mimes'   => 'Allowed formats: PDF, JPG, PNG. Max size 10MB.',
        ]);

        $file = $request->file('health_report');
        $path = $file->store('patient_health_reports', 'public');
        $patient->healthReports()->create([
            'file_path'      => $path,
            'original_name' => $file->getClientOriginalName(),
        ]);

        return redirect()->route('patient.profile.edit')->with('success', 'Health report uploaded successfully.');
    }

    public function destroyHealthReport(PatientHealthReport $health_report)
    {
        $user = Auth::user();
        $patient = $user->patient;
        if (!$patient || $health_report->patient_id !== $patient->id) {
            abort(403, 'You can only delete your own health reports.');
        }

        if (Storage::disk('public')->exists($health_report->file_path)) {
            Storage::disk('public')->delete($health_report->file_path);
        }
        $health_report->delete();

        return redirect()->back()->with('success', 'Health report removed.');
    }

    public function viewHealthReport(PatientHealthReport $health_report): Response
    {
        $user = Auth::user();
        $patient = $user->patient;
        if (!$patient || $health_report->patient_id !== $patient->id) {
            abort(403, 'Unauthorized.');
        }

        if (!Storage::disk('public')->exists($health_report->file_path)) {
            abort(404, 'File not found.');
        }

        $path = Storage::disk('public')->path($health_report->file_path);
        return response(Storage::disk('public')->get($health_report->file_path), 200, [
            'Content-Type'        => mime_content_type($path),
            'Content-Disposition' => 'inline',
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'                     => 'required|string|max:255',
            'contact_number'           => 'nullable|string|max:20',
            'date_of_birth'            => 'nullable|date',
            'gender'                   => 'nullable|in:male,female,other',
            'blood_group'              => 'nullable|string|max:10',
            'address'                  => 'nullable|string|max:255',
            'city'                     => 'nullable|string|max:100',
            'state'                    => 'nullable|string|max:100',
            'postal_code'              => 'nullable|string|max:20',
            'emergency_contact_name'   => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:20',
            'medical_history'          => 'nullable|string',
            'allergies'                => 'nullable|string',
            'profile_photo'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->update([
            'name'           => $validated['name'],
            'contact_number' => $validated['contact_number'] ?? null,
        ]);

        $patient = Patient::firstOrNew(['user_id' => $user->id]);

        if ($request->hasFile('profile_photo')) {
            if ($patient->profile_photo && Storage::disk('public')->exists($patient->profile_photo)) {
                Storage::disk('public')->delete($patient->profile_photo);
            }

            $patient->profile_photo = $request->file('profile_photo')
                ->store('patients', 'public');
        }

        $patient->fill([
            'contact_number'           => $validated['contact_number'] ?? null,
            'date_of_birth'            => $validated['date_of_birth'] ?? null,
            'gender'                   => $validated['gender'] ?? null,
            'blood_group'              => $validated['blood_group'] ?? null,
            'address'                  => $validated['address'] ?: null,
            'city'                     => $validated['city'] ?: null,
            'state'                    => $validated['state'] ?: null,
            'postal_code'              => $validated['postal_code'] ?: null,
            'emergency_contact_name'   => $validated['emergency_contact_name'] ?: null,
            'emergency_contact_number' => $validated['emergency_contact_number'] ?: null,
            'medical_history'          => $validated['medical_history'] !== null && $validated['medical_history'] !== '' ? $validated['medical_history'] : '',
            'allergies'                => $validated['allergies'] !== null && $validated['allergies'] !== '' ? $validated['allergies'] : '',
        ]);

        $patient->user_id = $user->id;
        $patient->save();

        return redirect()
            ->route('patient.profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
