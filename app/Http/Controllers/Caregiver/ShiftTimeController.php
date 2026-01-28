<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CaregiverShiftTime;

class ShiftTimeController extends Controller
{
    /**
     * Show shift-time form
     */
    public function index()
    {
        return view('caregiver.shiftTime');
    }

    /**
     * Store caregiver availability
     */
    public function store(Request $request)
    {
        $request->validate([
            'shift' => 'required|in:Day,Night,Both',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'day' => 'required|string',
            'service' => 'required|string',
            'available_date' => 'required|date|after_or_equal:today',
        ]);

        CaregiverShiftTime::create([
            'caregiver_id'   => Auth::id(),
            'shift'           => $request->shift,
            'start_time'      => $request->start_time,
            'end_time'        => $request->end_time,
            'day'             => $request->day,
            'service'         => $request->service,
            'available_date'  => $request->available_date,
        ]);

        return back()->with('success', 'Availability saved successfully!');
    }
}
