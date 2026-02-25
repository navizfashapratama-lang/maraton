<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->isAdmin() || $user->isSuperAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isStaff()) {
                return redirect()->route('staff.dashboard');
            } else {
                return redirect()->route('peserta.dashboard');
            }
        }

        return $next($request);
    }
}