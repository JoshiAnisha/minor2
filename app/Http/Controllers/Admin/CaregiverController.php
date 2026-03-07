<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caregiver;
use App\Models\User;
use Illuminate\Http\Request;

class CaregiverController extends Controller
{
    public function index()
    {
        // Ensure every user with role=caregiver has a caregiver profile row (DB: caregivers.users_id -> users.id)
        User::where('role', 'caregiver')->get()->each(function (User $user) {
            if (Caregiver::where('users_id', $user->id)->doesntExist()) {
                Caregiver::create(['users_id' => $user->id, 'availability_status' => true]);
            }
        });

        // Only list caregivers whose user has role = 'caregiver' (exclude admin, patient, etc.)
        $caregivers = Caregiver::with('user')
            ->whereHas('user', fn ($q) => $q->where('role', 'caregiver'))
            ->orderByDesc('id')
            ->get();

        $totalCaregivers = $caregivers->count();
        $activeCaregivers = $caregivers->where('availability_status', true)->count();
        $pendingCaregivers = $totalCaregivers - $activeCaregivers;

        return view('admin.caregiver', compact(
            'totalCaregivers',
            'activeCaregivers',
            'pendingCaregivers',
            'caregivers'
        ));
    }


    // 🔹 Edit page
    public function edit(Caregiver $caregiver)
    {
        return view('admin.caregiver-edit', compact('caregiver'));
    }

    // 🔹 Update caregiver
    public function update(Request $request, Caregiver $caregiver)
    {
        $request->validate([
            'skills' => 'nullable|string',
            'field' => 'nullable|string',
            'address' => 'nullable|string',
            'bio' => 'nullable|string',
        ]);

        $caregiver->update($request->only([
            'skills', 'field', 'address', 'bio'
        ]));

        return redirect()->route('admin.caregivers.index')
            ->with('success', 'Caregiver updated successfully');
    }

    // 🔹 Delete caregiver
    public function destroy(Caregiver $caregiver)
    {
        $caregiver->delete();

        return redirect()->route('admin.caregivers.index')
            ->with('success', 'Caregiver deleted successfully');
    }

    // 🔹 Toggle Active / Pending
    public function toggleStatus(Caregiver $caregiver)
    {
        $caregiver->availability_status = !$caregiver->availability_status;
        $caregiver->save();

        return back()->with('success', 'Caregiver status updated');
    }
}
