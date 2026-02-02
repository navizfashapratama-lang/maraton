<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $events = [];
        
        // Coba ambil events dari database
        if (class_exists('App\Models\Lomba')) {
            try {
                $events = \App\Models\Lomba::where('status', 'mendatang')
                    ->orderBy('tanggal', 'asc')
                    ->limit(3)
                    ->get();
            } catch (\Exception $e) {
                // Jika error, gunakan data dummy
                $events = $this->getDummyEvents();
            }
        } else {
            $events = $this->getDummyEvents();
        }
        
        // ===== TAMBAHKAN KODE INI UNTUK STATISTIK =====
        // Data statistik pendapatan
        $stats = [];
        
        if (class_exists('App\Models\Pembayaran') && class_exists('App\Models\Pendaftaran')) {
            try {
                // 1. Total pendapatan bulan ini (hanya yang sudah terverifikasi)
                $totalPendapatanBulanIni = \App\Models\Pembayaran::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->where('status', 'terverifikasi')
                    ->sum('jumlah');
                
                // 2. Total pendapatan bulan lalu (hanya yang sudah terverifikasi)
                $totalPendapatanBulanLalu = \App\Models\Pembayaran::whereMonth('created_at', now()->subMonth()->month)
                    ->whereYear('created_at', now()->subMonth()->year)
                    ->where('status', 'terverifikasi')
                    ->sum('jumlah');
                
                // 3. Hitung pertumbuhan pendapatan (%)
                $pertumbuhanPendapatan = 0;
                if ($totalPendapatanBulanLalu > 0) {
                    $pertumbuhanPendapatan = (($totalPendapatanBulanIni - $totalPendapatanBulanLalu) / $totalPendapatanBulanLalu) * 100;
                }
                
                // 4. Total peserta bulan ini
                $totalPesertaBulanIni = \App\Models\Pendaftaran::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->where('status_pendaftaran', 'disetujui')
                    ->count();
                
                // 5. Total peserta bulan lalu
                $totalPesertaBulanLalu = \App\Models\Pendaftaran::whereMonth('created_at', now()->subMonth()->month)
                    ->whereYear('created_at', now()->subMonth()->year)
                    ->where('status_pendaftaran', 'disetujui')
                    ->count();
                
                // 6. Hitung pertumbuhan peserta (%)
                $pertumbuhanPeserta = 0;
                if ($totalPesertaBulanLalu > 0) {
                    $pertumbuhanPeserta = (($totalPesertaBulanIni - $totalPesertaBulanLalu) / $totalPesertaBulanLalu) * 100;
                }
                
                // Format pendapatan
                $pendapatanFormatted = 'Rp ' . number_format($totalPendapatanBulanIni, 0, ',', '.');
                
                // Format pertumbuhan pendapatan
                $pertumbuhanPendapatanFormatted = round($pertumbuhanPendapatan, 1) . '%';
                if ($pertumbuhanPendapatan > 0) {
                    $pertumbuhanPendapatanFormatted = '+' . $pertumbuhanPendapatanFormatted;
                } elseif ($pertumbuhanPendapatan < 0) {
                    $pertumbuhanPendapatanFormatted = $pertumbuhanPendapatanFormatted;
                }
                
                // Format pertumbuhan peserta
                $pertumbuhanPesertaFormatted = round($pertumbuhanPeserta, 1) . '%';
                if ($pertumbuhanPeserta > 0) {
                    $pertumbuhanPesertaFormatted = '+' . $pertumbuhanPesertaFormatted;
                } elseif ($pertumbuhanPeserta < 0) {
                    $pertumbuhanPesertaFormatted = $pertumbuhanPesertaFormatted;
                }
                
                // Jika belum ada data pendapatan bulan ini
                if ($totalPendapatanBulanIni == 0) {
                    $totalSemuaPendapatan = \App\Models\Pembayaran::where('status', 'terverifikasi')->sum('jumlah');
                    $pendapatanFormatted = 'Rp ' . number_format($totalSemuaPendapatan, 0, ',', '.');
                    $pertumbuhanPendapatanFormatted = '+0%';
                }
                
                // Jika belum ada data peserta bulan ini
                if ($totalPesertaBulanIni == 0) {
                    $totalSemuaPeserta = \App\Models\Pendaftaran::where('status_pendaftaran', 'disetujui')->count();
                    $totalPesertaBulanIni = $totalSemuaPeserta;
                    $pertumbuhanPesertaFormatted = '+0%';
                }
                
                // Array statistik
                $stats = [
                    [
                        'title' => 'TOTAL PENDAPATAN',
                        'value' => $pendapatanFormatted,
                        'description' => 'Total pendapatan bulan ini',
                        'growth' => $pertumbuhanPendapatanFormatted,
                        'border' => 'border-l-4 border-blue-500'
                    ],
                    [
                        'title' => 'TOTAL PESERTA',
                        'value' => number_format($totalPesertaBulanIni, 0, ',', '.'),
                        'description' => 'Peserta terdaftar bulan ini',
                        'growth' => $pertumbuhanPesertaFormatted,
                        'border' => 'border-l-4 border-green-500'
                    ]
                ];
                
            } catch (\Exception $e) {
                // Jika error, gunakan data dummy untuk statistik
                $stats = $this->getDummyStats();
            }
        } else {
            $stats = $this->getDummyStats();
        }
        // ===== END TAMBAHAN =====
        
        return view('welcome', compact('events', 'stats'));
    }
    
    private function getDummyEvents()
    {
        return [
            (object) [
                'id' => 1,
                'nama' => 'Fun Run 5K',
                'kategori' => '5K',
                'tanggal' => '2024-03-15',
                'lokasi' => 'Stadion Kota',
                'harga_reguler' => 50000,
            ],
            (object) [
                'id' => 2,
                'nama' => 'Trail Run 10K',
                'kategori' => '10K',
                'tanggal' => '2024-04-20',
                'lokasi' => 'Gunung Hijau',
                'harga_reguler' => 75000,
            ],
            (object) [
                'id' => 3,
                'nama' => 'Charity Run',
                'kategori' => '3K',
                'tanggal' => '2024-05-01',
                'lokasi' => 'Alun-Alun Kota',
                'harga_reguler' => 30000,
            ],
        ];
    }
    
    // ===== TAMBAHKAN FUNCTION INI =====
    private function getDummyStats()
    {
        return [
            [
                'title' => 'TOTAL PENDAPATAN',
                'value' => 'Rp 9.640.816',
                'description' => 'Total pendapatan bulan ini',
                'growth' => '+9%',
                'border' => 'border-l-4 border-blue-500'
            ],
            [
                'title' => 'TOTAL PESERTA',
                'value' => '156',
                'description' => 'Peserta terdaftar bulan ini',
                'growth' => '+15%',
                'border' => 'border-l-4 border-green-500'
            ]
        ];
    }
    // ===== END TAMBAHAN =====
}