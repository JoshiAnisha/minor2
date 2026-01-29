<?php

namespace App\Http\Controllers\Backend\Patient;

 use App\Http\Controllers\Controller;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\Storage;
 use App\Models\Patient;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $patient = $user->patient;

        return view('backend.patient.profile.show', compact('user', 'patient'));
    }

    public function edit()
    {
        $user = Auth::user();
        $patient = $user->patient;

        return view('backend.patient.profile.edit', compact('user', 'patient'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // ✅ Validation
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

        // ✅ Update user table
        $user->update([
            'name'           => $validated['name'],
            'contact_number' => $validated['contact_number'] ?? null,
        ]);

        // ✅ Get or create patient record
        $patient = Patient::firstOrNew(['user_id' => $user->id]);

        // ✅ Handle profile photo upload
        if ($request->hasFile('profile_photo')) {

            // Delete old photo if exists
            if ($patient->profile_photo && Storage::disk('public')->exists($patient->profile_photo)) {
                Storage::disk('public')->delete($patient->profile_photo);
            }

            $patient->profile_photo = $request->file('profile_photo')
                ->store('patients', 'public');
        }

        // ✅ Update patient fields
        $patient->fill([
            'date_of_birth'            => $validated['date_of_birth'] ?? null,
            'gender'                   => $validated['gender'] ?? null,
            'blood_group'              => $validated['blood_group'] ?? null,
            'address'                  => $validated['address'] ?? null,
            'city'                     => $validated['city'] ?? null,
            'state'                    => $validated['state'] ?? null,
            'postal_code'              => $validated['postal_code'] ?? null,
            'emergency_contact_name'   => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_number' => $validated['emergency_contact_number'] ?? null,
            'medical_history'          => $validated['medical_history'] ?? null,
            'allergies'                => $validated['allergies'] ?? null,
        ]);

        $patient->user_id = $user->id;
        $patient->save();

        return redirect()
            ->route('patient.profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
