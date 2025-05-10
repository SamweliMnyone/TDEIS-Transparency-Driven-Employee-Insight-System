<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!in_array($user->role, $roles)) {
            // Instead of aborting, redirect to their proper dashboard
            switch ($user->role) {
                case 'ADMIN':
                    return redirect()->route('admin.dashboard');
                case 'HR':
                    return redirect()->route('hr.dashboard');
                case 'PM':
                    return redirect()->route('pm.dashboard');
                case 'Employee':
                    return redirect()->route('employee.dashboard');
                default:
                    Auth::logout();
                    return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
