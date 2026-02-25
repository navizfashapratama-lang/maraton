<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicEventController extends Controller
{
    /**
     * Menampilkan halaman daftar events (public)
     */
    public function index(Request $request)
    {
        $query = DB::table('lomba');
        
        // Filter kategori
        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }
        
        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter search
        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        
        $events = $query->orderBy('tanggal', 'asc')
                       ->paginate(12);
        
        // Ambil harga untuk setiap event
        foreach($events as $event) {
            $packages = DB::table('paket')
                ->where('lomba_id', $event->id)
                ->select('nama', 'harga')
                ->orderBy('harga', 'asc')
                ->get();
            
            $standardPackage = collect($packages)->first(function($package) {
                return stripos($package->nama, 'standar') !== false || 
                       stripos($package->nama, 'standard') !== false;
            });
            
            $premiumPackage = collect($packages)->first(function($package) {
                return stripos($package->nama, 'premium') !== false;
            });
            
            $event->harga_standar = $standardPackage->harga ?? 0;
            $event->harga_premium = $premiumPackage->harga ?? 0;
            $event->harga_min = $packages->min('harga') ?? 0;
            $event->has_packages = $packages->isNotEmpty();
        }
        
        $categories = DB::table('lomba')
            ->select('kategori')
            ->distinct()
            ->get();
        
        // ✅ INI YANG DIUBAH - pakai 'event.index' karena file Anda ada di folder event/
        return view('event.index', compact('events', 'categories'));
    }

    /**
     * Menampilkan detail event
     */
    public function show($id)
    {
        $event = DB::table('lomba')->where('id', $id)->first();
        
        if (!$event) {
            return redirect()->route('home')
                ->with('error', 'Event tidak ditemukan.');
        }
        
        // Get packages for this event
        $packages = DB::table('paket')->where('lomba_id', $id)->get();
        
        // Check if user is logged in and already registered
        $alreadyRegistered = false;
        if (session('is_logged_in')) {
            $alreadyRegistered = DB::table('pendaftaran')
                ->where('id_lomba', $id)
                ->where('id_pengguna', session('user_id'))
                ->exists();
        }
        
        // Calculate available slots
        $totalPendaftar = DB::table('pendaftaran')
            ->where('id_lomba', $id)
            ->where('status_pendaftaran', 'disetujui')
            ->count();
        $kuotaTersedia = $event->kuota_peserta - $totalPendaftar;
        
        // ✅ INI JUGA SESUAIKAN - pakai 'event.detail'
        return view('event.detail', compact('event', 'packages', 'alreadyRegistered', 'kuotaTersedia'));
    }
}