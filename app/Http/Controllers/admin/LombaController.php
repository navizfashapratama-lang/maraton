<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lomba;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LombaController extends Controller
{
    /**
     * Menampilkan daftar semua lomba
     */
    public function index()
    {
        $lombas = Lomba::with('paket')->latest()->paginate(10);
        return view('admin.lomba.index', compact('lombas'));
    }

    /**
     * Menampilkan form untuk membuat lomba baru
     */
    public function create()
    {
        return view('admin.lomba.create');
    }

    /**
     * Menyimpan lomba baru ke database
     */
    public function store(Request $request)
    {
        // Validasi data - HAPUS "DONASI" dari pilihan
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'lokasi' => 'nullable|string|max:255',
            'jenis_event' => 'required|in:berbayar,gratis', // HAPUS: ,donasi
            'jenis_paket' => 'required_if:jenis_event,berbayar|in:reguler,premium',
            'harga_reguler' => 'required_if:jenis_paket,reguler|integer|min:0',
            'harga_premium' => 'required_if:jenis_paket,premium|integer|min:0',
            'kuota_peserta' => 'nullable|integer|min:0',
            'fasilitas' => 'array',
            'fasilitas.*' => 'in:racekit,sertifikat,kaos,medali,akses_event',
            'pendaftaran_ditutup' => 'nullable|date',
            'rute_lomba' => 'nullable|string',
            'syarat_ketentuan' => 'nullable|string',
            'poster_url' => 'nullable|url',
        ]);

        // Mulai transaction
        DB::beginTransaction();

        try {
            // Konversi fasilitas array ke JSON
            $fasilitasJson = $request->fasilitas ? json_encode($request->fasilitas) : null;
            
            // Set harga berdasarkan jenis event
            $hargaStandar = 0;
            $hargaPremium = 0;
            
            if ($request->jenis_event === 'berbayar') {
                $hargaStandar = $request->harga_reguler ?? 0;
                $hargaPremium = $request->harga_premium ?? 0;
            }
            
            // Simpan data lomba
            $lomba = Lomba::create([
                'nama' => $request->nama,
                'kategori' => $request->kategori,
                'deskripsi' => $request->deskripsi,
                'tanggal' => $request->tanggal,
                'lokasi' => $request->lokasi,
                'harga_standar' => $hargaStandar,
                'harga_premium' => $hargaPremium,
                'kuota_peserta' => $request->kuota_peserta,
                'status' => 'mendatang',
                'jenis_event' => $request->jenis_event,
                'pendaftaran_ditutup' => $request->pendaftaran_ditutup,
                'rute_lomba' => $request->rute_lomba,
                'syarat_ketentuan' => $request->syarat_ketentuan,
                'poster_url' => $request->poster_url,
                'fasilitas' => $fasilitasJson,
                'created_by' => auth()->id(),
            ]);

            // Buat paket hanya untuk event berbayar
            if ($request->jenis_event === 'berbayar') {
                $this->createPaket($lomba, $request);
            }
            
            DB::commit();

            return redirect()->route('admin.lomba.index')
                ->with('success', 'Event ' . ($request->jenis_event === 'berbayar' ? 'Berbayar' : 'Gratis') . ' berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat event: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Fungsi untuk membuat paket berdasarkan pilihan
     */
    private function createPaket(Lomba $lomba, Request $request)
    {
        $fasilitas = $request->fasilitas ?? [];
        
        // Selalu buat paket reguler untuk event berbayar
        Paket::create([
            'lomba_id' => $lomba->id,
            'nama' => 'Reguler',
            'termasuk_race_kit' => in_array('racekit', $fasilitas),
            'termasuk_medali' => in_array('medali', $fasilitas),
            'termasuk_kaos' => in_array('kaos', $fasilitas),
            'harga' => $request->harga_reguler,
        ]);

        // Buat paket premium jika ada harga premium atau dipilih premium
        if ($request->jenis_paket == 'premium' || $request->harga_premium) {
            Paket::create([
                'lomba_id' => $lomba->id,
                'nama' => 'Premium',
                'termasuk_race_kit' => in_array('racekit', $fasilitas),
                'termasuk_medali' => in_array('medali', $fasilitas),
                'termasuk_kaos' => in_array('kaos', $fasilitas),
                'harga' => $request->harga_premium ?? $request->harga_reguler,
            ]);
        }
    }

    /**
     * Menampilkan detail lomba
     */
    public function show(Lomba $lomba)
    {
        $lomba->load('paket', 'pendaftarans');
        
        // Decode fasilitas untuk tampilan
        $lomba->fasilitas_array = $lomba->fasilitas ? json_decode($lomba->fasilitas, true) : [];
        
        return view('admin.lomba.show', compact('lomba'));
    }

    /**
     * Menampilkan form untuk edit lomba
     */
    public function edit(Lomba $lomba)
    {
        $lomba->load('paket');
        
        // Ambil data paket untuk pre-fill form
        $reguler = $lomba->paket->firstWhere('nama', 'Reguler');
        $premium = $lomba->paket->firstWhere('nama', 'Premium');
        
        // Decode fasilitas dari JSON
        $lomba->fasilitas_array = $lomba->fasilitas ? json_decode($lomba->fasilitas, true) : [];
        
        return view('admin.lomba.edit', compact('lomba', 'reguler', 'premium'));
    }

    /**
     * Update lomba di database
     */
    public function update(Request $request, Lomba $lomba)
    {
        // Validasi update - HAPUS "DONASI"
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'lokasi' => 'nullable|string|max:255',
            'jenis_event' => 'required|in:berbayar,gratis', // HAPUS: ,donasi
            'jenis_paket' => 'required_if:jenis_event,berbayar|in:reguler,premium',
            'harga_reguler' => 'required_if:jenis_paket,reguler|integer|min:0',
            'harga_premium' => 'required_if:jenis_paket,premium|integer|min:0',
            'kuota_peserta' => 'nullable|integer|min:0',
            'status' => 'required|in:mendatang,selesai,dibatalkan',
            'fasilitas' => 'array',
            'pendaftaran_ditutup' => 'nullable|date',
            'rute_lomba' => 'nullable|string',
            'syarat_ketentuan' => 'nullable|string',
            'poster_url' => 'nullable|url',
        ]);

        DB::beginTransaction();

        try {
            // Konversi fasilitas array ke JSON
            $fasilitasJson = $request->fasilitas ? json_encode($request->fasilitas) : null;
            
            // Set harga berdasarkan jenis event
            $hargaStandar = 0;
            $hargaPremium = 0;
            
            if ($request->jenis_event === 'berbayar') {
                $hargaStandar = $request->harga_reguler ?? 0;
                $hargaPremium = $request->harga_premium ?? 0;
            }
            
            // Update data lomba
            $lomba->update([
                'nama' => $request->nama,
                'kategori' => $request->kategori,
                'deskripsi' => $request->deskripsi,
                'tanggal' => $request->tanggal,
                'lokasi' => $request->lokasi,
                'harga_standar' => $hargaStandar,
                'harga_premium' => $hargaPremium,
                'kuota_peserta' => $request->kuota_peserta,
                'status' => $request->status,
                'jenis_event' => $request->jenis_event,
                'pendaftaran_ditutup' => $request->pendaftaran_ditutup,
                'rute_lomba' => $request->rute_lomba,
                'syarat_ketentuan' => $request->syarat_ketentuan,
                'poster_url' => $request->poster_url,
                'fasilitas' => $fasilitasJson,
            ]);

            // Hapus paket lama
            $lomba->paket()->delete();
            
            // Buat paket baru hanya untuk event berbayar
            if ($request->jenis_event === 'berbayar') {
                $this->createPaket($lomba, $request);
            }
            
            DB::commit();

            return redirect()->route('admin.lomba.index')
                ->with('success', 'Event berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update event: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Hapus lomba
     */
    public function destroy(Lomba $lomba)
    {
        DB::beginTransaction();
        
        try {
            // Hapus paket terlebih dahulu
            $lomba->paket()->delete();
            // Hapus lomba
            $lomba->delete();
            
            DB::commit();
            
            return redirect()->route('admin.lomba.index')
                ->with('success', 'Event berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus event: ' . $e->getMessage());
        }
    }

    /**
     * Update status lomba
     */
    public function updateStatus(Request $request, Lomba $lomba)
    {
        $request->validate([
            'status' => 'required|in:mendatang,selesai,dibatalkan'
        ]);
        
        $lomba->update(['status' => $request->status]);
        
        return back()->with('success', 'Status event berhasil diubah!');
    }

    /**
     * Export data lomba ke Excel
     */
    public function export()
    {
        // Implementasi export ke Excel jika diperlukan
        return response()->json(['message' => 'Export feature coming soon']);
    }

    /**
     * Fungsi tambahan untuk mendapatkan label jenis event
     */
    public static function getJenisEventLabel($jenis)
    {
        $labels = [
            'berbayar' => '<span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200">Berbayar</span>',
            'gratis' => '<span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 border border-green-200">Gratis</span>',
        ];
        
        return $labels[$jenis] ?? '<span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800">Unknown</span>';
    }

    /**
     * Fungsi untuk mendapatkan label status event
     */
    public static function getStatusLabel($status)
    {
        $labels = [
            'mendatang' => '<span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Mendatang</span>',
            'selesai' => '<span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 border border-green-200">Selesai</span>',
            'dibatalkan' => '<span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 border border-red-200">Dibatalkan</span>',
        ];
        
        return $labels[$status] ?? '<span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800">Unknown</span>';
    }
}