<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Caregiver;
use App\Models\User;

class ProfileController extends Controller
{
    // Show edit form (not changed)
    public function edit()
    {
        $user = Auth::user();
        $caregiver = Caregiver::where('user_id', $user->id)->first();
        return view('Caregiver.edit', compact('caregiver'));
    }

    // Update caregiver profile
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'contact_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'skills' => 'nullable|string',
            'field' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'qualification' => 'nullable|string',
            'experience' => 'nullable|string',
            'caregiver_type' => 'nullable|in:medical,regular',
            'certificate' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        // Correct column name: use user_id (not users_id)
        $caregiver = Caregiver::firstOrNew(['user_id' => $user->id]);

        // Ensure association exists
        $caregiver->user_id = $user->id;

        $caregiver->fill([
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'skills' => $request->skills,
            'field' => $request->field,
            'bio' => $request->bio,
            'qualification' => $request->qualification,
            'experience' => $request->experience,
            'caregiver_type' => $request->caregiver_type,
            'availability_status' => $request->has('availability_status'),
        ]);

        // Certificate upload (store in public disk)
        if ($request->hasFile('certificate')) {
            // delete previous file if present
            if ($caregiver->certificate_path) {
                Storage::disk('public')->delete($caregiver->certificate_path);
            }

            $filename = (string) Str::uuid() . '.' . $request->file('certificate')->getClientOriginalExtension();
            $path = $request->file('certificate')->storeAs('caregiver_certificates', $filename, 'public');
            $caregiver->certificate_path = $path;
        }

        $caregiver->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    // Other methods...
}