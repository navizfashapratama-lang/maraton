<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek jika user tidak login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek jika user bukan staff atau admin
        $user = Auth::user();
        if (!$user->isStaff() && !$user->isAdmin()) {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman staff.');
        }

        return $next($request);
    }
}