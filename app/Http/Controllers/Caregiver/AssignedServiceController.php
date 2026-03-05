<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use App\Models\AssignedService;
use App\Models\AssignedServiceBid;
use App\Notifications\CaregiverPlacedAssignedBidNotification;
use Illuminate\Http\Request;

class AssignedServiceController extends Controller
{
    /**
     * List available assigned services (pending, not yet accepted).
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role !== 'caregiver') {
            abort(403, 'Unauthorized. Caregiver access only.');
        }
        $caregiver = $user->caregiver;
        if (!$caregiver) {
            return redirect()->route('caregiver.dashboard')->with('error', 'Caregiver profile not found.');
        }

        $serviceIdsWithMyBid = AssignedServiceBid::where('caregiver_id', $caregiver->id)->pluck('assigned_service_id');

        $query = AssignedService::with('patient', 'service', 'bids')
            ->where('status', 'pending');
        if ($serviceIdsWithMyBid->isNotEmpty()) {
            $query->whereNotIn('id', $serviceIdsWithMyBid);
        }
        $assignedServices = $query->orderBy('preferred_date')->get();

        return view('Caregiver.assigned-services.index', compact('assignedServices'));
    }

    /**
     * List services the caregiver has bid on.
     */
    public function myBids()
    {
        $user = auth()->user();
        if ($user->role !== 'caregiver') {
            abort(403, 'Unauthorized.');
        }
        $caregiver = $user->caregiver;
        if (!$caregiver) {
            return redirect()->route('caregiver.dashboard')->with('error', 'Caregiver profile not found.');
        }

        $bids = AssignedServiceBid::with('assignedService.service', 'assignedService.patient')
            ->where('caregiver_id', $caregiver->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Caregiver.assigned-services.my-bids', compact('bids'));
    }

    /**
     * Place a bid on an assigned service.
     */
    public function placeBid(Request $request)
    {
        $validated = $request->validate([
            'assigned_service_id' => 'required|exists:assigned_services,id',
            'proposed_price'      => 'required|numeric|min:0.01',
            'message'             => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        if ($user->role !== 'caregiver') {
            abort(403, 'Unauthorized.');
        }
        $caregiver = $user->caregiver;
        if (!$caregiver) {
            return back()->with('error', 'Caregiver profile not found.');
        }

        $assignedService = AssignedService::findOrFail($validated['assigned_service_id']);
        if ($assignedService->status !== 'pending') {
            return back()->with('error', 'This service is no longer accepting bids.');
        }

        $exists = AssignedServiceBid::where('assigned_service_id', $assignedService->id)
            ->where('caregiver_id', $caregiver->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'You have already placed a bid on this service.');
        }

        $bid = AssignedServiceBid::create([
            'assigned_service_id' => $assignedService->id,
            'caregiver_id'        => $caregiver->id,
            'proposed_price'      => $validated['proposed_price'],
            'message'             => $validated['message'] ?? null,
            'status'              => 'pending',
        ]);

        // Notify the patient that a caregiver placed a bid
        $patientUser = $assignedService->patient;
        if ($patientUser) {
            $patientUser->notify(new CaregiverPlacedAssignedBidNotification(
                $bid->load('assignedService'),
                $caregiver->user?->name ?? 'A caregiver'
            ));
        }

        return redirect()->route('caregiver.assigned-services.my-bids')
            ->with('success', 'Your bid was submitted successfully. Patient has been notified.');
    }
}
