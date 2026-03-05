<?php

namespace App\Http\Controllers\Backend\Patient;

use App\Http\Controllers\Controller;
use App\Models\AssignedService;
use App\Models\AssignedServiceBid;
use App\Notifications\PatientAcceptedAssignedBidNotification;
use Illuminate\Http\Request;

class AssignedServiceController extends Controller
{
    /**
     * List assigned services for the logged-in patient.
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role !== 'patient') {
            abort(403, 'Unauthorized. Patient access only.');
        }
        $patient = $user->patient;
        if (!$patient) {
            return redirect()->route('patient.dashboard')->with('error', 'Patient profile not found.');
        }

        // Only show assigned services for THIS patient (patient_id = user_id in assigned_services table)
        $assignedServices = AssignedService::with('service', 'bids.caregiver.user', 'assignedCaregiver.user')
            ->where('patient_id', (int) $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.patient.assigned-services.index', compact('assignedServices'));
    }

    /**
     * Accept a bid.
     */
    public function acceptBid(Request $request, AssignedServiceBid $assigned_service_bid)
    {
        $user = auth()->user();
        if ($user->role !== 'patient') {
            abort(403, 'Unauthorized.');
        }
        $bid = $assigned_service_bid;
        $assignedService = $bid->assignedService;

        if ($assignedService->patient_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }
        if ($assignedService->status !== 'pending') {
            return back()->with('error', 'This service is no longer accepting bids.');
        }
        if ($bid->status !== 'pending') {
            return back()->with('error', 'This bid is no longer available.');
        }

        \DB::transaction(function () use ($bid, $assignedService) {
            $bid->update(['status' => 'accepted']);
            $assignedService->update([
                'status'                => 'accepted',
                'assigned_caregiver_id' => $bid->caregiver_id,
            ]);
            AssignedServiceBid::where('assigned_service_id', $assignedService->id)
                ->where('id', '!=', $bid->id)
                ->update(['status' => 'rejected']);
        });

        // Notify the caregiver that their bid was accepted
        $caregiverUser = $bid->caregiver?->user;
        if ($caregiverUser) {
            $caregiverUser->notify(new PatientAcceptedAssignedBidNotification(
                $bid->fresh(),
                auth()->user()->name ?? 'The patient'
            ));
        }

        return redirect()->route('patient.assigned-services.index')
            ->with('success', 'Bid accepted. Caregiver has been notified.');
    }

    /**
     * Reject a bid.
     */
    public function rejectBid(Request $request, AssignedServiceBid $assigned_service_bid)
    {
        $user = auth()->user();
        if ($user->role !== 'patient') {
            abort(403, 'Unauthorized.');
        }
        $bid = $assigned_service_bid;
        $assignedService = $bid->assignedService;

        if ($assignedService->patient_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }
        if ($bid->status !== 'pending') {
            return back()->with('error', 'This bid is no longer available.');
        }

        $bid->update(['status' => 'rejected']);

        return redirect()->route('patient.assigned-services.index')
            ->with('success', 'Bid rejected.');
    }
}
