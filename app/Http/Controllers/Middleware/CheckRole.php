<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Cek session manual
        if (!session('is_logged_in')) {
            return redirect('/login')->with('error', 'Harap login terlebih dahulu!');
        }
        
        // Cek role dari session
        $userRole = session('user_peran');
        
        // Jika role tidak sesuai
        if ($userRole != $role) {
            return redirect('/')->with('error', 'Akses ditolak! Anda tidak memiliki izin.');
        }
        
        return $next($request);
    }
}