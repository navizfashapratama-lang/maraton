<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login (PAKAI SESSION)
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah user adalah admin atau superadmin
        $userRole = session('user_peran');
        if (!in_array($userRole, ['admin', 'superadmin'])) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini!');
        }

        return $next($request);
    }
}