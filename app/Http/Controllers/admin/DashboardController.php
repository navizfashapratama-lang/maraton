<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lomba;
use App\Models\Pengguna;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_users' => Pengguna::count(),
            'total_events' => Lomba::count(),
            'total_registrations' => Pendaftaran::count(),
            'total_revenue' => Pembayaran::where('status', 'terverifikasi')->sum('jumlah'),
            
            'recent_users' => Pengguna::latest()->take(5)->get(),
            'recent_registrations' => Pendaftaran::with(['pengguna', 'lomba'])
                ->latest()
                ->take(5)
                ->get(),
        ];
        
        return view('admin.dashboard', $data);
    }
}