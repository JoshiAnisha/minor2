<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        // Verify user is authenticated admin
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.service-create');
    }

    /**
     * Store a newly created service in storage.
     *
     * Validates inputs according to column types:
     * - name: string (required, max 255)
     * - slug: string (nullable, unique, max 255)
     * - details: text (nullable)
     * - base_price: float (required, numeric, min 0)
     * - service_type: string (required, must be 'medical' or 'regular')
     */
    public function store(Request $request)
    {
        // Verify user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        // Validate inputs according to column types
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:services,slug',
            'details' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'service_type' => 'required|string|in:medical,regular',
        ]);

        // Auto-generate slug from name if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);

            // Ensure slug is unique
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Service::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Save service using Service::create() which respects $fillable
        Service::create($validated);

        // Redirect back to form with success message
        return redirect()->route('admin.services.create')
            ->with('success', 'Service created successfully!');
    }
}
