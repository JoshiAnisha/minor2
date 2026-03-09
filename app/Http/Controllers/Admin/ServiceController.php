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
        $categoriesWithTermTypes = $this->getCategoriesWithTermTypes();
        return view('admin.services.index', compact('services', 'categoriesWithTermTypes'));
    }

    /**
     * Show the form for creating a new service.
     * Optional query params: category, is_long_term (0 or 1) to pre-fill the form.
     */
    public function create(Request $request)
    {
        $this->authorizeAdmin();
        $existingCategories = $this->getExistingCategories();
        $presetCategory = $request->query('category', '');
        $presetLongTerm = $request->has('is_long_term') ? (bool) $request->query('is_long_term') : null;
        return view('admin.services.create', compact('existingCategories', 'presetCategory', 'presetLongTerm'));
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
     * For each category, analyze whether it can be short-term, long-term, or both (by nature of the category).
     * Returns: [ 'Category Name' => ['short' => bool, 'long' => bool], ... ]
     */
    private function getCategoriesWithTermTypes(): array
    {
        $categories = $this->getExistingCategories();

        // By nature: which categories typically support short-term, long-term, or both
        $termTypeByCategory = [
            'Doctor & Consultation' => ['short' => true, 'long' => true],   // one-time visit or ongoing
            'Nursing & Care'       => ['short' => true, 'long' => true],   // single procedure or ongoing care
            'Therapy'              => ['short' => true, 'long' => true],   // single session or ongoing
            'Elderly & Caregiver'  => ['short' => false, 'long' => true],  // typically ongoing care
            'Lab & Pharmacy'       => ['short' => true, 'long' => false],  // delivery, sample collection
            'Equipment'            => ['short' => true, 'long' => true],   // short or long rental
            'Specialized Care'     => ['short' => false, 'long' => true],  // e.g. palliative, ongoing
        ];

        $result = [];
        foreach ($categories as $category) {
            $result[$category] = $termTypeByCategory[$category] ?? ['short' => true, 'long' => true];
        }
        return $result;
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
