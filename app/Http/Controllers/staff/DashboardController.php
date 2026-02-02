<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Lomba;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show staff dashboard
     */
    public function index()
    {
        // Staff hanya bisa lihat event yang aktif
        $events = Lomba::where('status', 'mendatang')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Pendaftaran untuk event aktif
        $eventIds = $events->pluck('id')->toArray();
        
        $registrations = Pendaftaran::with(['lomba', 'user'])
            ->whereIn('id_lomba', $eventIds)
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        // Pembayaran pending untuk event aktif
        $payments = Pembayaran::with(['pendaftaran.lomba'])
            ->where('status', 'menunggu')
            ->whereHas('pendaftaran', function ($query) use ($eventIds) {
                $query->whereIn('id_lomba', $eventIds);
            })
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        $stats = [
            'total_pendaftaran' => Pendaftaran::whereIn('id_lomba', $eventIds)->count(),
            'pendaftaran_disetujui' => Pendaftaran::whereIn('id_lomba', $eventIds)
                ->where('status_pendaftaran', 'disetujui')
                ->where('status_pembayaran', 'lunas')
                ->count(),
            'pembayaran_pending' => $payments->count(),
            'pendaftaran_hari_ini' => Pendaftaran::whereIn('id_lomba', $eventIds)
                ->whereDate('created_at', today())
                ->count(),
        ];

        return view('staff.dashboard', compact(
            'events', 'registrations', 'payments', 'stats'
        ));
    }
}