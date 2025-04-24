<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestUser
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $role = auth()->user()->role; // assuming 'role' is a string like 'ADMIN', 'HR', etc.
    
            switch ($role) {
                case 'ADMIN':
                    return redirect()->route('admin.dashboard');
                case 'HR':
                    return redirect()->route('hr.dashboard');
                case 'PM':
                    return redirect()->route('pm.dashboard');
                case 'Employee':
                    return redirect()->route('employee.dashboard');
                default:
                    auth()->logout();
                    return redirect()->route('login')->withErrors('Invalid role');
            }
        }
    
        return $next($request);
    }
}
