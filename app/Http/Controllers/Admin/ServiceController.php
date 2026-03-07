<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use App\Notifications\NewServiceOpenedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();
        try {
            $services = Service::orderBy('category')->orderBy('name')->get();
        } catch (\Throwable $e) {
            \Log::warning('Admin services index: ' . $e->getMessage());
            $services = collect([]);
        }
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        $this->authorizeAdmin();
        $existingCategories = $this->getExistingCategories();
        return view('admin.services.create', compact('existingCategories'));
    }

    /**
     * Ensure the current user is an admin.
     */
    private function authorizeAdmin(): void
    {
        if (!Auth::check()) {
            abort(403, 'You must be logged in to access this page.');
        }
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized. Admin access required.');
        }
    }

    /**
     * Get existing categories from services plus default options (for empty DB).
     */
    private function getExistingCategories(): array
    {
        $fromDb = Service::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values()
            ->all();
        $defaults = [
            'Doctor & Consultation',
            'Nursing & Care',
            'Therapy',
            'Elderly & Caregiver',
            'Lab & Pharmacy',
            'Equipment',
            'Specialized Care',
        ];
        return array_values(array_unique(array_merge($defaults, $fromDb)));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug',
            'details' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'service_type' => 'required|string|in:medical,regular',
            'category' => 'nullable|string|max:100',
            'is_long_term' => 'nullable|boolean',
        ]);
        $validated['is_long_term'] = $request->boolean('is_long_term');

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

        $service = Service::create($validated);

        // Notify all patients and caregivers that a new service is available
        User::whereIn('role', ['patient', 'caregiver'])->get()->each(function (User $user) use ($service) {
            $user->notify(new NewServiceOpenedNotification($service));
        });

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully! Patients and caregivers have been notified.');
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service)
    {
        $this->authorizeAdmin();
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service)
    {
        $this->authorizeAdmin();
        $existingCategories = $this->getExistingCategories();
        return view('admin.services.edit', compact('service', 'existingCategories'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Service $service)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug,' . $service->id,
            'details' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'service_type' => 'required|string|in:medical,regular',
            'category' => 'nullable|string|max:100',
            'is_long_term' => 'nullable|boolean',
        ]);
        $validated['is_long_term'] = $request->boolean('is_long_term');

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
        $this->authorizeAdmin();
        $service->delete();
        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
