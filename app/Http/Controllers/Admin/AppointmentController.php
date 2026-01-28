<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    // Show all appointments
    public function index()
{
    $appointments = Appointment::latest()->get();
    return view('admin.appointment', compact('appointments'));
}


    // Show form to create new appointment
    public function create()
    {
        return view('admin.appointments.create');
    }

    // Store new appointment
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string',
            'email' => 'required|email',
            'date'  => 'required|date',
            'time'  => 'required'
        ]);

        Appointment::create($request->all());

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', 'Appointment created successfully!');
    }

    // Show form to edit appointment
    public function edit(Appointment $appointment)
    {
        return view('admin.appointments.edit', compact('appointment'));
    }

    // Update appointment
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'name'  => 'required|string',
            'email' => 'required|email',
            'date'  => 'required|date',
            'time'  => 'required'
        ]);

        $appointment->update($request->all());

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', 'Appointment updated successfully!');
    }

    // Delete appointment
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', 'Appointment deleted successfully!');
    }
}
