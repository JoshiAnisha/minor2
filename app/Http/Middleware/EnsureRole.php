<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Restrict route access to users with the given role (patient, caregiver, admin).
     * Use in routes: ->middleware('role:admin')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user()) {
            return redirect()->route('backend.auth.login');
        }

        $userRole = $request->user()->role;

        if ($userRole !== $role) {
            abort(403, 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
