<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('is_logged_in')) {
            // Save the intended URL for redirect after login
            if ($request->isMethod('get')) {
                session(['url.intended' => $request->fullUrl()]);
            }
            
            return redirect()->route('login')
                ->with('info', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }
        
        return $next($request);
    }
}