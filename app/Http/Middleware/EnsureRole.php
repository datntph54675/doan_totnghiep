<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    /**
     * Handle an incoming request.
     * Expecting role string or array of roles.
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();
        if (! $user) {
            // Redirect to the most relevant login page based on URL prefix
            if ($request->is('admin/*')) {
                return redirect('/admin/login');
            }
            if ($request->is('guide/*')) {
                return redirect('/guide/login');
            }
            return redirect('/login');
        }

        $roles = array_map('trim', explode('|', $role));
        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
