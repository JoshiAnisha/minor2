<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use Illuminate\Support\Facades\Storage;

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

        $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:20',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update user table
        $user->update([
            'name' => $request->name,
            'contact_number' => $request->contact_number,
        ]);

        // Collect patient data
        $data = $request->only([
            'date_of_birth','gender','blood_group','address','city','state','postal_code',
            'emergency_contact_name','emergency_contact_number','medical_history','allergies'
        ]);

        // Handle profile photo
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('patients', 'public');
            $data['profile_photo'] = $path;
        }

        // Update or create patient record
        Patient::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return redirect()->route('patient.profile.show')->with('success', 'Profile updated successfully.');
    }
}
