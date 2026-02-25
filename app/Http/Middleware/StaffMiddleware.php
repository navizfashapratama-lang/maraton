<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('is_logged_in')) {
            return redirect()->route('login');
        }

        $userRole = session('user_peran');
        if (!in_array($userRole, ['staff', 'admin', 'superadmin'])) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}