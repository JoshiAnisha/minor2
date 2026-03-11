<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Caregiver;
use App\Models\User;
use App\Models\Review;

class ProfileController extends Controller
 {
    public function edit()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        $caregiver = Caregiver::firstOrNew(['users_id' => $user->id]);
        $reviews = Review::where('user_id', $user->id)->with(['booking.patient.user', 'booking.service'])->latest()->take(15)->get();

        return view('Caregiver.edit', compact('user', 'caregiver', 'reviews'));
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
        ]);
        if ($request->hasFile('profile_photo')) {
            $request->validate(['profile_photo' => 'file|mimes:jpg,jpeg,png|max:5120']);
        }

        // Update user
        User::where('id', $user->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
        ]);

        $user = Auth::user();

        $caregiver = Caregiver::where('users_id', $user->id)->first();

        $data = [
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'skills' => $request->skills,
            'field' => $request->field,
            'bio' => $request->bio,
            'qualification' => $request->qualification,
            'experience' => $request->experience,
            'caregiver_type' => $request->caregiver_type,
            'availability_status' => $request->has('availability_status'),
        ];

        // Certificate upload
        if ($request->hasFile('certificate')) {
            if ($caregiver && $caregiver->certificate_path) {
                Storage::disk('public')->delete($caregiver->certificate_path);
            }
            $filename = Str::uuid() . '.' . $request->file('certificate')->getClientOriginalExtension();
            $data['certificate_path'] = $request->file('certificate')->storeAs('certificates', $filename, 'public');
        }

        // Persist caregiver row first (so we always have a row to update)
        Caregiver::updateOrCreate(
            ['users_id' => $user->id],
            $data
        );

        // Profile photo: handle separately and force-write to DB so it always saves
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $caregiver = Caregiver::where('users_id', $user->id)->first();
            if ($caregiver && $caregiver->profile_photo_path) {
                Storage::disk('public')->delete($caregiver->profile_photo_path);
            }
            $photoName = Str::uuid() . '.' . $request->file('profile_photo')->getClientOriginalExtension();
            $profilePhotoPath = $request->file('profile_photo')->storeAs('profile_photos', $photoName, 'public');

            DB::table('caregivers')->where('users_id', $user->id)->update(['profile_photo_path' => $profilePhotoPath]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Serve the logged-in caregiver's profile photo. Same pattern as viewCertificate().
     */
    public function viewProfilePhoto()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        $caregiver = Caregiver::where('users_id', $user->id)->first();

        if ($caregiver && $caregiver->profile_photo_path && Storage::disk('public')->exists($caregiver->profile_photo_path)) {
            return response(
                Storage::disk('public')->get($caregiver->profile_photo_path),
                200,
                [
                    'Content-Type' => mime_content_type(Storage::disk('public')->path($caregiver->profile_photo_path)),
                    'Content-Disposition' => 'inline',
                ]
            );
        }

        $defaultPath = public_path('Images/default-profile.png');
        if (file_exists($defaultPath)) {
            return response()->file($defaultPath, ['Content-Type' => 'image/png']);
        }

        // Fallback: 1x1 transparent GIF so img never breaks (same idea as certificate returning 404 when missing)
        $gif = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        return response($gif, 200, ['Content-Type' => 'image/gif']);
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
