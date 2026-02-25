<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('is_logged_in')) {
            return redirect('/login')->with('error', 'Harap login terlebih dahulu!');
        }
        return $next($request);
    }
}

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('is_logged_in') || session('user_peran') != 'admin') {
            return redirect('/login')->with('error', 'Akses ditolak. Hanya untuk admin!');
        }
        return $next($request);
    }
}

class StaffAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('is_logged_in') || session('user_peran') != 'staff') {
            return redirect('/login')->with('error', 'Akses ditolak. Hanya untuk staff!');
        }
        return $next($request);
    }
}

class PesertaAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('is_logged_in') || session('user_peran') != 'peserta') {
            return redirect('/login')->with('error', 'Akses ditolak. Hanya untuk peserta!');
        }
        return $next($request);
    }
}