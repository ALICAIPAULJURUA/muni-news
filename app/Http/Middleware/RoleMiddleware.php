<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage: ->middleware('role:super_admin|comm_admin|editor')
     *        ->middleware('role:super_admin')
     *        ->middleware('role:super_admin,comm_admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user is active
        if (property_exists($user, 'is_active') || isset($user->is_active)) {
            if (! $user->is_active) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['email' => 'Your account has been deactivated.']);
            }
        }

        // If no roles specified, allow any authenticated active user
        if (empty($roles)) {
            return $next($request);
        }

        // Normalize roles: allow pipe or comma separated
        $allowed = [];
        foreach ($roles as $role) {
            $parts = preg_split('/[|,]/', $role);
            foreach ($parts as $p) {
                $p = trim($p);
                if ($p !== '') {
                    $allowed[] = $p;
                }
            }
        }

        // Use Spatie hasAnyRole if available, otherwise fallback to simple check
        $hasRole = false;
        if (method_exists($user, 'hasAnyRole')) {
            $hasRole = $user->hasAnyRole($allowed);
        } elseif (method_exists($user, 'hasRole')) {
            foreach ($allowed as $role) {
                if ($user->hasRole($role)) {
                    $hasRole = true;
                    break;
                }
            }
        }

        if (! $hasRole) {
            abort(403, 'Unauthorized. Required role: '.implode(', ', $allowed));
        }

        return $next($request);
    }
}
