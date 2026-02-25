<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        $services = Service::orderBy('name')->get();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        return view('admin.services.create');
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug',
            'details' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'service_type' => 'required|string|in:medical,regular',
        ]);

        $slug = isset($validated['slug']) ? trim($validated['slug']) : '';
        if ($slug === '') {
            $validated['slug'] = Str::slug($validated['name']);
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Service::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        } else {
            $validated['slug'] = Str::slug($slug);
            if ($validated['slug'] === '') {
                $validated['slug'] = Str::slug($validated['name']);
            }
        }

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully! It is now visible to patients and caregivers.');
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service)
    {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Service $service)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug,' . $service->id,
            'details' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'service_type' => 'required|string|in:medical,regular',
        ]);

        $slug = isset($validated['slug']) ? trim($validated['slug']) : '';
        if ($slug === '') {
            $validated['slug'] = Str::slug($validated['name']);
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Service::where('slug', $validated['slug'])->where('id', '!=', $service->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        } else {
            $validated['slug'] = Str::slug($slug);
            if ($validated['slug'] === '') {
                $validated['slug'] = Str::slug($validated['name']);
            }
        }

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        $service->delete();
        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
