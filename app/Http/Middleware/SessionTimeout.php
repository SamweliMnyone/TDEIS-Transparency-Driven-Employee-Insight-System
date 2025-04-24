<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        // Set session timeout value
        $timeout = config('session.lifetime') * 60;  // session.lifetime is in minutes, so we convert to seconds

        if (Auth::check() && session('last_activity') && (time() - session('last_activity') > $timeout)) {
            Auth::logout();
            return redirect()->route('login'); // Logout and redirect to login if session expired
        }

        session(['last_activity' => time()]); // Update session activity time
        return $next($request);
    }
}
