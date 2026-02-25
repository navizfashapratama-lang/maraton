<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        // Mengambil data dari tabel lomba
        $events = DB::table('lomba')
            ->when($search, function($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('kategori', 'like', "%{$search}%");
            })
            ->when($status, function($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        // Pastikan view ini merujuk ke folder: resources/views/staff/events/index.blade.php
        return view('staff.events.index', compact('events'));
    }
}