<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FrontEventController extends Controller
{
    /**
     * Display a listing of the events for public view.
     */
    public function index(Request $request)
    {
        // Query untuk mengambil data event
        $query = DB::table('lomba');
        
        // Filter berdasarkan kategori
        if ($request->has('kategori') && $request->kategori) {
            $query->where('kategori', $request->kategori);
        }
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        } else {
            // Default: tampilkan yang mendatang dulu
            $query->orderByRaw("
                CASE 
                    WHEN status = 'mendatang' AND tanggal >= CURDATE() THEN 1
                    WHEN status = 'mendatang' AND tanggal < CURDATE() THEN 2
                    WHEN status = 'selesai' THEN 3
                    WHEN status = 'dibatalkan' THEN 4
                    ELSE 5
                END
            ");
        }
        
        // Filter search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'LIKE', '%' . $request->search . '%');
            });
        }
        
        // Order by date (mendatang dulu)
        $query->orderBy('tanggal', 'asc');
        
        // Paginate dengan 9 item per halaman
        $events = $query->paginate(9);
        
        // Tambahkan atribut tambahan untuk view
        foreach ($events as $event) {
            $event->has_packages = ($event->harga_reguler > 0 || $event->harga_premium > 0);
            $event->harga_min = $this->calculateHargaMin($event);
            $event->harga_standar = $event->harga_reguler;
            
            // Format tanggal
            $event->tanggal_formatted = Carbon::parse($event->tanggal)->translatedFormat('d F Y');
        }
        
        // Ambil kategori unik untuk filter
        $categories = DB::table('lomba')
            ->select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->get();
        
        return view('events', compact('events', 'categories'));
    }

    /**
     * Calculate minimum price
     */
    private function calculateHargaMin($event)
    {
        if ($event->harga_reguler > 0 && $event->harga_premium > 0) {
            return min($event->harga_reguler, $event->harga_premium);
        } elseif ($event->harga_reguler > 0) {
            return $event->harga_reguler;
        } elseif ($event->harga_premium > 0) {
            return $event->harga_premium;
        }
        return 0;
    }

    /**
     * Display the specified event.
     */
    public function show($id)
    {
        $event = DB::table('lomba')->where('id', $id)->first();
        
        if (!$event) {
            abort(404, 'Event tidak ditemukan');
        }
        
        // Tambahkan atribut untuk view
        $event->has_packages = ($event->harga_reguler > 0 || $event->harga_premium > 0);
        $event->harga_min = $this->calculateHargaMin($event);
        $event->harga_standar = $event->harga_reguler;
        
        // Format tanggal
        $event->tanggal_formatted = Carbon::parse($event->tanggal)->translatedFormat('d F Y');
        
        // Ambil paket yang tersedia
        $paket = DB::table('paket')->where('lomba_id', $id)->get();
        
        // Cek apakah event masih terbuka untuk pendaftaran
        $pendaftaran_dibuka = false;
        if ($event->status == 'mendatang') {
            if ($event->pendaftaran_ditutup) {
                $pendaftaran_dibuka = now()->lt(Carbon::parse($event->pendaftaran_ditutup));
            } else {
                $pendaftaran_dibuka = now()->lt(Carbon::parse($event->tanggal));
            }
        }
        
        // Ambil data peserta terdaftar (hitung saja)
        $peserta_terdaftar = DB::table('pendaftaran')
            ->where('id_lomba', $id)
            ->where('status', 'disetujui')
            ->count();
            
        $event->peserta_terdaftar = $peserta_terdaftar;
        $event->pendaftaran_dibuka = $pendaftaran_dibuka;
        
        // Ambil event lainnya untuk rekomendasi
        $upcoming_events = DB::table('lomba')
            ->where('status', 'mendatang')
            ->where('id', '!=', $id)
            ->orderBy('tanggal', 'asc')
            ->limit(3)
            ->get();
            
        // Tambahkan atribut untuk event lainnya
        foreach ($upcoming_events as $up_event) {
            $up_event->harga_min = $this->calculateHargaMin($up_event);
        }
        
        return view('event.detail', compact('event', 'paket', 'upcoming_events'));
    }

    /**
     * Show registration form for event.
     */
    public function register($id)
    {
        // Cek apakah user sudah login
        if (!session('user_id')) {
            session(['intended_url' => url()->current()]);
            return redirect()->route('login')
                ->with('warning', 'Silakan login terlebih dahulu untuk mendaftar.');
        }
        
        $event = DB::table('lomba')->where('id', $id)->first();
        
        if (!$event) {
            abort(404, 'Event tidak ditemukan');
        }
        
        // Validasi apakah event bisa didaftar
        if ($event->status != 'mendatang') {
            return redirect()->route('event.detail', $id)
                ->with('error', 'Pendaftaran untuk event ini sudah ditutup.');
        }
        
        // Cek tanggal pendaftaran
        if ($event->pendaftaran_ditutup && now()->gt(Carbon::parse($event->pendaftaran_ditutup))) {
            return redirect()->route('event.detail', $id)
                ->with('error', 'Masa pendaftaran sudah berakhir.');
        }
        
        // Cek kuota
        if ($event->kuota_peserta) {
            $peserta_terdaftar = DB::table('pendaftaran')
                ->where('id_lomba', $id)
                ->where('status', 'disetujui')
                ->count();
                
            if ($peserta_terdaftar >= $event->kuota_peserta) {
                return redirect()->route('event.detail', $id)
                    ->with('error', 'Maaf, kuota peserta sudah penuh.');
            }
        }
        
        // Cek apakah user sudah mendaftar
        $sudah_daftar = DB::table('pendaftaran')
            ->where('id_lomba', $id)
            ->where('id_pengguna', session('user_id'))
            ->exists();
            
        if ($sudah_daftar) {
            return redirect()->route('event.detail', $id)
                ->with('info', 'Anda sudah terdaftar pada event ini.');
        }
        
        // Ambil paket yang tersedia
        $paket = DB::table('paket')->where('lomba_id', $id)->get();
        
        if ($paket->isEmpty()) {
            // Jika tidak ada paket, buat paket default
            $paket = collect([(object) [
                'id' => 0,
                'nama' => 'Paket Standar',
                'harga' => $event->harga_reguler > 0 ? $event->harga_reguler : 0,
                'termasuk_race_kit' => 1,
                'termasuk_medali' => 1,
                'termasuk_kaos' => 1,
                'termasuk_sertifikat' => 1,
                'termasuk_snack' => 1,
            ]]);
        }
        
        // Ambil data user
        $user = DB::table('pengguna')->where('id', session('user_id'))->first();
        
        return view('event.register', compact('event', 'paket', 'user'));
    }

    /**
     * Get events for homepage (upcoming events)
     */
    public function upcomingEvents()
    {
        $events = DB::table('lomba')
            ->where('status', 'mendatang')
            ->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->limit(6)
            ->get();
            
        // Tambahkan atribut untuk view
        foreach ($events as $event) {
            $event->harga_min = $this->calculateHargaMin($event);
            $event->tanggal_formatted = Carbon::parse($event->tanggal)->translatedFormat('d F Y');
        }
        
        return response()->json($events);
    }

    /**
     * Count total events
     */
    public function countEvents()
    {
        $counts = [
            'total' => DB::table('lomba')->count(),
            'mendatang' => DB::table('lomba')->where('status', 'mendatang')->count(),
            'selesai' => DB::table('lomba')->where('status', 'selesai')->count(),
        ];
        
        return response()->json($counts);
    }
    
    /**
     * Search events (ajax)
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        
        $events = DB::table('lomba')
            ->where('nama', 'LIKE', '%' . $query . '%')
            ->orWhere('lokasi', 'LIKE', '%' . $query . '%')
            ->orWhere('deskripsi', 'LIKE', '%' . $query . '%')
            ->where('status', 'mendatang')
            ->orderBy('tanggal', 'asc')
            ->limit(5)
            ->get();
            
        // Tambahkan atribut untuk view
        foreach ($events as $event) {
            $event->harga_min = $this->calculateHargaMin($event);
        }
        
        return response()->json($events);
    }
}