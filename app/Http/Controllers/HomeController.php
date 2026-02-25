<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama
     */
    public function index()
    {
        // Get stats
        $totalEvents = DB::table('lomba')->count();
        $totalUsers = DB::table('pengguna')->count();
        $upcomingEvents = DB::table('lomba')
            ->where('status', 'mendatang')
            ->where('tanggal', '>=', now())
            ->count();
        
        // Get upcoming events
        $events = DB::table('lomba')
            ->where('status', 'mendatang')
            ->where('tanggal', '>=', now())
            ->orderBy('tanggal', 'asc')
            ->limit(6)
            ->get();
        
        // Calculate available slots for each event
        $eventsWithSlots = [];
        foreach ($events as $event) {
            $totalPendaftar = DB::table('pendaftaran')
                ->where('id_lomba', $event->id)
                ->where('status_pendaftaran', 'disetujui')
                ->count();
            
            $event->kuota_tersedia = $event->kuota_peserta - $totalPendaftar;
            $eventsWithSlots[] = $event;
        }
        
        return view('home', compact('totalEvents', 'totalUsers', 'upcomingEvents', 'eventsWithSlots'));
    }

    /**
     * Menampilkan halaman profil user
     */
    public function profile()
    {
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $user = DB::table('pengguna')->where('id', session('user_id'))->first();
        
        // Get user registrations
        $registrations = DB::table('pendaftaran')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->where('pendaftaran.id_pengguna', session('user_id'))
            ->select('pendaftaran.*', 'lomba.nama as event_nama', 'lomba.tanggal')
            ->orderBy('pendaftaran.created_at', 'desc')
            ->limit(5)
            ->get();
            
        return view('profile', compact('user', 'registrations'));
    }
}