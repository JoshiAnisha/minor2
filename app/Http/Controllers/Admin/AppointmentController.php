<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;

class AppointmentController extends Controller
{
    /**
     * Show all service requests (appointments) in a categorized way.
     * Groups by service category and provides status counts.
     */
    public function index()
    {
        $serviceRequests = ServiceRequest::with(['patient.user', 'service'])
            ->latest()
            ->get();

        // Counts by status
        $total = $serviceRequests->count();
        $pending = $serviceRequests->where('status', 'pending')->count();
        $accepted = $serviceRequests->where('status', 'accepted')->count();
        $completed = $serviceRequests->where('status', 'completed')->count();
        $rejected = $serviceRequests->where('status', 'rejected')->count();

        // Group by service category (service->category)
        $byCategory = $serviceRequests->groupBy(function ($sr) {
            $category = $sr->relationLoaded('service') && $sr->service
                ? ($sr->service->category ?: 'Uncategorized')
                : 'Uncategorized';
            return $category;
        })->sortKeys();

        return view('admin.appointment', compact(
            'serviceRequests',
            'byCategory',
            'total',
            'pending',
            'accepted',
            'completed',
            'rejected'
        ));
    }
}
