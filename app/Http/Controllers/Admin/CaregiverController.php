<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caregiver;
use Illuminate\Http\Request;

class CaregiverController extends Controller
{
    public function index()
{
    $totalCaregivers = Caregiver::count();
    $activeCaregivers = Caregiver::where('availability_status', 1)->count();
    $pendingCaregivers = Caregiver::where('availability_status', 0)->count();

    $caregivers = Caregiver::with('user')->latest()->get(); // already fetching all fields

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

        return redirect()->route('admin.caregiver')
            ->with('success', 'Caregiver updated successfully');
    }

    // 🔹 Delete caregiver
    public function destroy(Caregiver $caregiver)
    {
        $caregiver->delete();

        return redirect()->route('admin.caregiver')
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
