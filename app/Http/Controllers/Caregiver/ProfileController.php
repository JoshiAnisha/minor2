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
    public function edit()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        // ✅ FIXED: users_id
        $caregiver = Caregiver::firstOrNew(['users_id' => $user->id]);

        return view('Caregiver.edit', compact('user', 'caregiver'));
    }
 
    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => "required|email|unique:users,email,{$user->id}",
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'skills' => 'nullable|string|max:255',
            'field' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'qualification' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:255',
            'caregiver_type' => 'nullable|in:medical,regular,home_nurse',
            'availability_status' => 'nullable|boolean',
            'certificate' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update user
        User::where('id', $user->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
        ]);
        
        $user = Auth::user();

        // ✅ FIXED: users_id
        $caregiver = Caregiver::firstOrNew(['users_id' => $user->id]);

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

        // Certificate upload
         if ($request->hasFile('certificate')) {
            if ($caregiver->certificate_path) {
                Storage::disk('public')->delete($caregiver->certificate_path);
            }

            $filename = Str::uuid() . '.' . $request->file('certificate')->getClientOriginalExtension();
            $caregiver->certificate_path =
                $request->file('certificate')->storeAs('certificates', $filename, 'public');
        }

        // Profile photo upload
        if ($request->hasFile('profile_photo')) {
            if ($caregiver->profile_photo_path) {
                Storage::disk('public')->delete($caregiver->profile_photo_path);
            }

            $photoName = Str::uuid() . '.' . $request->file('profile_photo')->getClientOriginalExtension();
            $caregiver->profile_photo_path =
                $request->file('profile_photo')->storeAs('profile_photos', $photoName, 'public');
        }

        $caregiver->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function viewCertificate()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        // ✅ FIXED: users_id
        $caregiver = Caregiver::where('users_id', $user->id)->first();

        if (
            !$caregiver ||
            !$caregiver->certificate_path ||
            !Storage::disk('public')->exists($caregiver->certificate_path)
        ) {
            abort(404, 'Certificate not found.');
        }

        return response(
            Storage::disk('public')->get($caregiver->certificate_path),
            200,
            [
                'Content-Type' => mime_content_type(Storage::disk('public')->path($caregiver->certificate_path)),
                'Content-Disposition' => 'inline',
            ]
        );
    }
}
