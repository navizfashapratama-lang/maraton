<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        // Check if user is admin
        if (!in_array(session('user_peran'), ['admin', 'superadmin'])) {
            return redirect()->route('login')->with('error', 'Akses ditolak!');
        }

        // Perbaikan: ganti 'users' dengan 'pengguna'
        $events = DB::table('lomba')
            ->select('lomba.*', 'pengguna.nama as created_by_name')
            ->leftJoin('pengguna', 'lomba.created_by', '=', 'pengguna.id')
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        // Check if user is admin
        if (!in_array(session('user_peran'), ['admin', 'superadmin'])) {
            return redirect()->route('login')->with('error', 'Akses ditolak!');
        }

        // PERBAIKAN: Tangani jika tabel tidak ada atau kosong
        try {
            $categories = DB::table('kategori_event')
                ->orderBy('nama_kategori', 'asc')
                ->get();
            
            // Pastikan $categories selalu berupa Collection
            if (!$categories instanceof Collection) {
                $categories = collect($categories);
            }
        } catch (\Exception $e) {
            // Jika tabel tidak ada atau error, gunakan collection kosong
            $categories = collect([]);
            \Log::warning('Tabel kategori_event tidak ditemukan atau error: ' . $e->getMessage());
        }

        return view('admin.events.create', compact('categories'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (!in_array(session('user_peran'), ['admin', 'superadmin'])) {
            return redirect()->route('login')->with('error', 'Akses ditolak!');
        }

        // === 1. Validasi sesuai form baru yang disederhanakan ===
        $rules = [
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'kategori_id' => 'nullable|exists:kategori_event,id',
            'tanggal' => 'required|date',
            'pendaftaran_ditutup' => 'nullable|date|after_or_equal:today',
            'lokasi' => 'required|string|max:500',
            'harga_reguler' => 'required|numeric|min:0',
            'harga_premium' => 'nullable|numeric|min:0|gt:harga_reguler',
            'deskripsi' => 'required|string|max:2000',
            'rute_lomba' => 'nullable|string|max:1000',
            'syarat_ketentuan' => 'nullable|string',
            'fasilitas' => 'nullable|string',
            'kuota_peserta' => 'nullable|integer|min:1',
            'poster_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'nullable|in:mendatang,selesai,dibatalkan',
        ];

        $customMessages = [
            'nama.required' => 'Nama event wajib diisi',
            'kategori.required' => 'Kategori lomba wajib dipilih',
            'tanggal.required' => 'Tanggal event wajib diisi',
            'pendaftaran_ditutup.after_or_equal' => 'Tanggal pendaftaran ditutup tidak boleh sebelum hari ini',
            'lokasi.required' => 'Lokasi event wajib diisi',
            'harga_reguler.required' => 'Harga reguler wajib diisi',
            'harga_reguler.min' => 'Harga reguler minimal Rp 0',
            'harga_premium.min' => 'Harga premium minimal Rp 0',
            'harga_premium.gt' => 'Harga premium harus lebih tinggi dari harga reguler',
            'deskripsi.required' => 'Deskripsi event wajib diisi',
            'poster_url.image' => 'File harus berupa gambar',
            'poster_url.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'poster_url.max' => 'Ukuran gambar maksimal 5MB',
            'kuota_peserta.min' => 'Kuota peserta minimal 1 orang',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Periksa kembali data yang Anda input!');
        }

        // === 2. Konversi harga (hapus titik/format ribuan) ===
        $regularPrice = 0;
        $premiumPrice = null; // Set default null untuk premium
        
        if ($request->has('harga_reguler') && !empty($request->harga_reguler)) {
            $regularPrice = (int) preg_replace('/[^0-9]/', '', $request->harga_reguler);
        }
        
        if ($request->has('harga_premium') && !empty($request->harga_premium)) {
            $premiumPrice = (int) preg_replace('/[^0-9]/', '', $request->harga_premium);
            // Validasi manual jika premium <= reguler
            if ($premiumPrice > 0 && $premiumPrice <= $regularPrice) {
                return redirect()->back()
                    ->with('error', 'Harga premium harus lebih tinggi dari harga reguler')
                    ->withInput();
            }
        }

        // === 3. Tentukan status secara otomatis berdasarkan tanggal ===
        $today = Carbon::now()->startOfDay();
        $eventDate = Carbon::parse($request->tanggal)->startOfDay();
        
        $status = $request->status ?? 'mendatang';
        
        // Auto-update status jika tidak diisi
        if (!$request->status) {
            if ($eventDate < $today) {
                $status = 'selesai';
            } else {
                $status = 'mendatang';
            }
        }

        // === 4. Handle upload gambar ===
        $posterPath = null;
        if ($request->hasFile('poster_url') && $request->file('poster_url')->isValid()) {
            try {
                $file = $request->file('poster_url');
                $filename = 'event_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $posterPath = $file->storeAs('events', $filename, 'public');
            } catch (\Exception $e) {
                return redirect()->back()
                    ->with('error', 'Gagal mengupload gambar: ' . $e->getMessage())
                    ->withInput();
            }
        }

        DB::beginTransaction();

        try {
            // === 5. Siapkan data event ===
            $eventData = [
                'nama' => $request->nama,
                'kategori' => $request->kategori,
                'kategori_id' => $request->kategori_id ?: null,
                'deskripsi' => $request->deskripsi,
                'tanggal' => $request->tanggal,
                'pendaftaran_ditutup' => $request->pendaftaran_ditutup ?: null,
                'lokasi' => $request->lokasi,
                'harga_reguler' => $regularPrice,
                'harga_premium' => $premiumPrice ?: null,
                'status' => $status,
                'kuota_peserta' => $request->kuota_peserta ?? 100,
                'rute_lomba' => $request->rute_lomba,
                'syarat_ketentuan' => $request->syarat_ketentuan,
                'fasilitas' => $request->fasilitas,
                'poster_url' => $posterPath,
                'created_by' => session('user_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // === 6. Insert event ke database ===
            $eventId = DB::table('lomba')->insertGetId($eventData);

            // === 7. Buat paket untuk event secara otomatis ===
            // Set default untuk paket gratis
            $isFreeEvent = ($regularPrice == 0 && (!$premiumPrice || $premiumPrice == 0));
            
            if ($isFreeEvent) {
                // Event Gratis
                DB::table('paket')->insert([
                    'lomba_id' => $eventId,
                    'nama' => 'Paket Gratis',
                    'termasuk_race_kit' => 0,
                    'termasuk_medali' => 0,
                    'termasuk_kaos' => 0,
                    'termasuk_sertifikat' => 1,
                    'termasuk_snack' => 0,
                    'harga' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // Event Berbayar - Buat paket Reguler
                if ($regularPrice > 0) {
                    DB::table('paket')->insert([
                        'lomba_id' => $eventId,
                        'nama' => 'Paket Reguler',
                        'termasuk_race_kit' => 1,
                        'termasuk_medali' => 0,
                        'termasuk_kaos' => 0,
                        'termasuk_sertifikat' => 1,
                        'termasuk_snack' => 0,
                        'harga' => $regularPrice,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                
                // Event Berbayar - Buat paket Premium jika ada
                if ($premiumPrice > 0) {
                    DB::table('paket')->insert([
                        'lomba_id' => $eventId,
                        'nama' => 'Paket Premium',
                        'termasuk_race_kit' => 1,
                        'termasuk_medali' => 1,
                        'termasuk_kaos' => 1,
                        'termasuk_sertifikat' => 1,
                        'termasuk_snack' => 1,
                        'harga' => $premiumPrice,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // === 8. Log aktivitas ===
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'tambah_event',
                'deskripsi' => "Menambahkan event baru: {$request->nama} ({$request->kategori})",
                'tabel_terkait' => 'lomba',
                'id_terkait' => $eventId,
                'created_at' => now(),
            ]);

            DB::commit();

            // === 9. Beri feedback sukses dengan detail ===
            $successMessage = 'Event berhasil ditambahkan!';
            if ($regularPrice > 0) {
                $successMessage .= " Paket Reguler: Rp " . number_format($regularPrice, 0, ',', '.');
            }
            if ($premiumPrice > 0) {
                $successMessage .= " Paket Premium: Rp " . number_format($premiumPrice, 0, ',', '.');
            }
            if ($isFreeEvent) {
                $successMessage .= " (Event Gratis)";
            }

            return redirect()->route('admin.events.index')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus gambar jika upload gagal
            if ($posterPath && Storage::disk('public')->exists($posterPath)) {
                Storage::disk('public')->delete($posterPath);
            }

            \Log::error('Gagal menambahkan event: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified event.
     */
    public function show($id)
    {
        // Perbaikan: ganti 'users' dengan 'pengguna'
        $event = DB::table('lomba')
            ->select('lomba.*', 'pengguna.nama as created_by_name')
            ->leftJoin('pengguna', 'lomba.created_by', '=', 'pengguna.id')
            ->where('lomba.id', $id)
            ->first();
            
        if (!$event) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Event tidak ditemukan');
        }

        $packages = DB::table('paket')
            ->where('lomba_id', $id)
            ->get();

        // Perbaikan: tabel pendaftaran menggunakan id_pengguna, bukan user_id
        $participants = DB::table('pendaftaran')
            ->select('pendaftaran.*', 'pengguna.nama as peserta_nama', 'pengguna.email')
            ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
            ->where('pendaftaran.id_lomba', $id)
            ->orderBy('pendaftaran.created_at', 'desc')
            ->paginate(10);

        // PERBAIKAN: Pastikan $packages berupa Collection
        if (!$packages instanceof Collection) {
            $packages = collect($packages);
        }

        return view('admin.events.show', compact('event', 'packages', 'participants'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit($id)
    {
        // Check if user is admin
        if (!in_array(session('user_peran'), ['admin', 'superadmin'])) {
            return redirect()->route('login')->with('error', 'Akses ditolak!');
        }

        $event = DB::table('lomba')->where('id', $id)->first();
        
        if (!$event) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Event tidak ditemukan');
        }

        // PERBAIKAN: Tangani jika tabel kategori_event tidak ada
        try {
            $categories = DB::table('kategori_event')->orderBy('nama_kategori', 'asc')->get();
            if (!$categories instanceof Collection) {
                $categories = collect($categories);
            }
        } catch (\Exception $e) {
            $categories = collect([]);
            \Log::warning('Tabel kategori_event tidak ditemukan saat edit event: ' . $e->getMessage());
        }

        $packages = DB::table('paket')->where('lomba_id', $id)->get();
        
        // PERBAIKAN: Pastikan $packages berupa Collection
        if (!$packages instanceof Collection) {
            $packages = collect($packages);
        }

        return view('admin.events.edit', compact('event', 'categories', 'packages'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, $id)
    {
        // Check if user is admin
        if (!in_array(session('user_peran'), ['admin', 'superadmin'])) {
            return redirect()->route('login')->with('error', 'Akses ditolak!');
        }

        $event = DB::table('lomba')->where('id', $id)->first();
        
        if (!$event) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Event tidak ditemukan');
        }

        // === Validasi sesuai form baru ===
        $rules = [
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'kategori_id' => 'nullable|exists:kategori_event,id',
            'tanggal' => 'required|date',
            'pendaftaran_ditutup' => 'nullable|date',
            'lokasi' => 'required|string|max:500',
            'harga_reguler' => 'required|numeric|min:0',
            'harga_premium' => 'nullable|numeric|min:0',
            'deskripsi' => 'required|string|max:2000',
            'rute_lomba' => 'nullable|string|max:1000',
            'syarat_ketentuan' => 'nullable|string',
            'fasilitas' => 'nullable|string',
            'kuota_peserta' => 'nullable|integer|min:1',
            'poster_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'required|in:mendatang,selesai,dibatalkan',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'nama.required' => 'Nama event wajib diisi',
            'kategori.required' => 'Kategori lomba wajib dipilih',
            'tanggal.required' => 'Tanggal event wajib diisi',
            'lokasi.required' => 'Lokasi event wajib diisi',
            'harga_reguler.required' => 'Harga reguler wajib diisi',
            'harga_reguler.min' => 'Harga reguler minimal Rp 0',
            'harga_premium.min' => 'Harga premium minimal Rp 0',
            'deskripsi.required' => 'Deskripsi event wajib diisi',
            'poster_url.image' => 'File harus berupa gambar',
            'poster_url.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'poster_url.max' => 'Ukuran gambar maksimal 5MB',
            'kuota_peserta.min' => 'Kuota peserta minimal 1 orang',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle upload gambar
        $posterPath = $event->poster_url;
        if ($request->hasFile('poster_url') && $request->file('poster_url')->isValid()) {
            try {
                // Hapus gambar lama jika ada
                if ($posterPath && Storage::disk('public')->exists($posterPath)) {
                    Storage::disk('public')->delete($posterPath);
                }
                
                $file = $request->file('poster_url');
                $filename = 'event_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $posterPath = $file->storeAs('events', $filename, 'public');
                
            } catch (\Exception $e) {
                return redirect()->back()
                    ->with('error', 'Gagal mengupload gambar: ' . $e->getMessage())
                    ->withInput();
            }
        }

        // Cek apakah event sudah memiliki pendaftaran
        $hasRegistrations = DB::table('pendaftaran')->where('id_lomba', $id)->exists();

        // Jika event sudah ada pendaftaran dan status diubah menjadi 'dibatalkan'
        if ($hasRegistrations && $request->status === 'dibatalkan' && $event->status !== 'dibatalkan') {
            return redirect()->back()
                ->with('error', 'Tidak bisa membatalkan event yang sudah memiliki pendaftaran!')
                ->withInput();
        }

        // === Konversi harga ===
        $regularPrice = 0;
        $premiumPrice = null;
        
        if ($request->has('harga_reguler') && !empty($request->harga_reguler)) {
            $regularPrice = (int) preg_replace('/[^0-9]/', '', $request->harga_reguler);
        }
        
        if ($request->has('harga_premium') && !empty($request->harga_premium)) {
            $premiumPrice = (int) preg_replace('/[^0-9]/', '', $request->harga_premium);
            
            // Validasi: harga premium harus lebih tinggi dari reguler
            if ($premiumPrice > 0 && $regularPrice > 0 && $premiumPrice <= $regularPrice) {
                return redirect()->back()
                    ->with('error', 'Harga premium harus lebih tinggi dari harga reguler')
                    ->withInput();
            }
        }

        DB::beginTransaction();

        try {
            // Siapkan data untuk update
            $eventData = [
                'nama' => $request->nama,
                'kategori' => $request->kategori,
                'kategori_id' => $request->kategori_id ?: null,
                'tanggal' => $request->tanggal,
                'pendaftaran_ditutup' => $request->pendaftaran_ditutup ?: null,
                'lokasi' => $request->lokasi,
                'harga_reguler' => $regularPrice,
                'harga_premium' => $premiumPrice ?: null,
                'deskripsi' => $request->deskripsi,
                'rute_lomba' => $request->rute_lomba,
                'syarat_ketentuan' => $request->syarat_ketentuan,
                'fasilitas' => $request->fasilitas,
                'kuota_peserta' => $request->kuota_peserta,
                'status' => $request->status,
                'poster_url' => $posterPath,
                'updated_at' => now(),
            ];

            // Update event
            DB::table('lomba')->where('id', $id)->update($eventData);

            // Update paket berdasarkan harga
            $isFreeEvent = ($regularPrice == 0 && (!$premiumPrice || $premiumPrice == 0));
            
            if ($isFreeEvent) {
                // Hapus semua paket dan buat paket gratis
                DB::table('paket')->where('lomba_id', $id)->delete();
                
                DB::table('paket')->insert([
                    'lomba_id' => $id,
                    'nama' => 'Paket Gratis',
                    'termasuk_race_kit' => 0,
                    'termasuk_medali' => 0,
                    'termasuk_kaos' => 0,
                    'termasuk_sertifikat' => 1,
                    'termasuk_snack' => 0,
                    'harga' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // Hapus paket gratis jika ada
                DB::table('paket')->where('lomba_id', $id)->where('harga', 0)->delete();
                
                // Update atau buat paket reguler
                $regularPackage = DB::table('paket')
                    ->where('lomba_id', $id)
                    ->where('nama', 'like', '%Reguler%')
                    ->first();
                    
                if ($regularPackage) {
                    DB::table('paket')->where('id', $regularPackage->id)->update([
                        'harga' => $regularPrice,
                        'updated_at' => now(),
                    ]);
                } else {
                    DB::table('paket')->insert([
                        'lomba_id' => $id,
                        'nama' => 'Paket Reguler',
                        'termasuk_race_kit' => 1,
                        'termasuk_medali' => 0,
                        'termasuk_kaos' => 0,
                        'termasuk_sertifikat' => 1,
                        'termasuk_snack' => 0,
                        'harga' => $regularPrice,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Update atau buat paket premium
                if ($premiumPrice > 0) {
                    $premiumPackage = DB::table('paket')
                        ->where('lomba_id', $id)
                        ->where('nama', 'like', '%Premium%')
                        ->first();
                        
                    if ($premiumPackage) {
                        DB::table('paket')->where('id', $premiumPackage->id)->update([
                            'harga' => $premiumPrice,
                            'updated_at' => now(),
                        ]);
                    } else {
                        DB::table('paket')->insert([
                            'lomba_id' => $id,
                            'nama' => 'Paket Premium',
                            'termasuk_race_kit' => 1,
                            'termasuk_medali' => 1,
                            'termasuk_kaos' => 1,
                            'termasuk_sertifikat' => 1,
                            'termasuk_snack' => 1,
                            'harga' => $premiumPrice,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    // Hapus paket premium jika harga premium dihapus
                    DB::table('paket')
                        ->where('lomba_id', $id)
                        ->where('nama', 'like', '%Premium%')
                        ->delete();
                }
            }

            // Log aktivitas
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'edit_event',
                'deskripsi' => 'Mengedit event: ' . $request->nama,
                'tabel_terkait' => 'lomba',
                'id_terkait' => $id,
                'created_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.events.index')
                ->with('success', 'Event berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Gagal update event: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy($id)
    {
        // Check if user is admin
        if (!in_array(session('user_peran'), ['admin', 'superadmin'])) {
            return redirect()->route('login')->with('error', 'Akses ditolak!');
        }

        $event = DB::table('lomba')->where('id', $id)->first();
        
        if (!$event) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Event tidak ditemukan');
        }

        // Cek apakah event sudah memiliki pendaftaran
        $hasRegistrations = DB::table('pendaftaran')->where('id_lomba', $id)->exists();
        
        if ($hasRegistrations) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Tidak bisa menghapus event yang sudah memiliki pendaftaran!');
        }

        DB::beginTransaction();

        try {
            // Hapus paket terkait
            DB::table('paket')->where('lomba_id', $id)->delete();
            
            // Hapus gambar poster jika ada
            if ($event->poster_url && Storage::disk('public')->exists($event->poster_url)) {
                Storage::disk('public')->delete($event->poster_url);
            }
            
            // Hapus event
            DB::table('lomba')->where('id', $id)->delete();
            
            // Log aktivitas
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'hapus_event',
                'deskripsi' => 'Menghapus event: ' . $event->nama,
                'tabel_terkait' => 'lomba',
                'id_terkait' => $id,
                'created_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.events.index')
                ->with('success', 'Event berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Gagal menghapus event: ' . $e->getMessage());

            return redirect()->route('admin.events.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get events by category.
     */
    public function byCategory($category)
    {
        $events = DB::table('lomba')
            ->where('kategori', $category)
            ->where('status', 'mendatang')
            ->orderBy('tanggal', 'asc')
            ->paginate(9);

        return view('events.category', compact('events', 'category'));
    }

    /**
     * Get upcoming events.
     */
    public function upcoming()
    {
        $today = Carbon::now()->format('Y-m-d');
        
        $events = DB::table('lomba')
            ->where('status', 'mendatang')
            ->whereDate('tanggal', '>=', $today)
            ->orderBy('tanggal', 'asc')
            ->paginate(12);

        return view('events.upcoming', compact('events'));
    }
}