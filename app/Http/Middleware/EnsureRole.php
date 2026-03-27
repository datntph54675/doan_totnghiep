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
            if ($request->is('admin/*') || $request->is('admin')) {
                return redirect()->route('admin.login');
            }
            return redirect()->route('login');
        }

        $roles = array_map('trim', explode('|', $role));
        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
