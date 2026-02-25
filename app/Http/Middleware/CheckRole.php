<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = session('user_peran');
        
        if (!in_array($userRole, $roles)) {
            return redirect()->route('home')->with('error', 'Akses ditolak!');
        }

        return $next($request);
    }
}