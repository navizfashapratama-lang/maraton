<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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

        $events = DB::table('lomba')
            ->orderBy('tanggal', 'asc')
            ->paginate(10);
            
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    /**
 * Show the form for creating a new event.
 */
public function create()
{
    // Check if user is admin
    if (!in_array(session('user_peran'), ['admin', 'superadmin'])) {
        return redirect()->route('login')->with('error', 'Akses ditolak!');
    }

    try {
        // Coba ambil kategori dari database
        $kategoris = DB::table('kategori_event')
            ->orderBy('nama_kategori', 'asc')
            ->get();
            
        \Log::info('Data kategori ditemukan: ' . ($kategoris ? $kategoris->count() : 0));
        
        // Jika tidak ada data, tambahkan data default
        if (!$kategoris || $kategoris->isEmpty()) {
            \Log::info('Tabel kategori_event kosong, menambahkan data default...');
            
            // Tambahkan data default
            $defaultCategories = [
                ['nama_kategori' => 'Marathon', 'deskripsi' => 'Lomba lari jarak jauh dengan rute yang menantang', 'ikon' => 'fa-running', 'warna' => '#FF6B6B'],
                ['nama_kategori' => 'Fun Run', 'deskripsi' => 'Lomba lari santai untuk semua usia', 'ikon' => 'fa-walking', 'warna' => '#4ECDC4'],
                ['nama_kategori' => 'Trail Run', 'deskripsi' => 'Lomba lari di jalur alam dan pegunungan', 'ikon' => 'fa-mountain', 'warna' => '#1A535C'],
                ['nama_kategori' => 'Charity Run', 'deskripsi' => 'Lomba lari untuk amal dan kegiatan sosial', 'ikon' => 'fa-heart', 'warna' => '#FF6B6B'],
                ['nama_kategori' => 'Corporate Run', 'deskripsi' => 'Lomba lari untuk perusahaan dan komunitas', 'ikon' => 'fa-building', 'warna' => '#118AB2'],
            ];
            
            foreach ($defaultCategories as $category) {
                DB::table('kategori_event')->insert([
                    'nama_kategori' => $category['nama_kategori'],
                    'deskripsi' => $category['deskripsi'],
                    'ikon' => $category['ikon'],
                    'warna' => $category['warna'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Ambil data lagi setelah insert
            $kategoris = DB::table('kategori_event')
                ->orderBy('nama_kategori', 'asc')
                ->get();
        }
        
    } catch (\Exception $e) {
        \Log::error('Error mengambil kategori: ' . $e->getMessage());
        
        // Fallback: array statis
        $kategoris = collect([
            (object) ['id' => 1, 'nama_kategori' => 'Marathon', 'ikon' => 'fa-running', 'warna' => '#FF6B6B'],
            (object) ['id' => 2, 'nama_kategori' => 'Fun Run', 'ikon' => 'fa-walking', 'warna' => '#4ECDC4'],
            (object) ['id' => 3, 'nama_kategori' => 'Trail Run', 'ikon' => 'fa-mountain', 'warna' => '#1A535C'],
        ]);
    }

    // Pastikan $kategoris adalah collection
    if (!$kategoris instanceof \Illuminate\Support\Collection) {
        $kategoris = collect($kategoris);
    }

    return view('admin.events.create', compact('kategoris'));
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

    // === PERBAIKAN: Atur status otomatis berdasarkan tanggal ===
    $today = now()->startOfDay();
    $eventDate = \Carbon\Carbon::parse($request->tanggal)->startOfDay();
    
    // Tentukan status secara otomatis
    if ($eventDate < $today) {
        $status = 'selesai'; // Jika tanggal sudah lewat
    } elseif ($eventDate->isToday()) {
        $status = 'berlangsung'; // Jika tanggal hari ini
    } else {
        $status = 'mendatang'; // Jika tanggal akan datang
    }

    // === Validasi: Event tidak boleh di masa lalu (kecuali untuk event khusus) ===
    if ($eventDate < $today && $request->input('allow_past_event') !== 'yes') {
        return redirect()->back()
            ->with('error', 'Tanggal event tidak boleh di masa lalu! Untuk event khusus, hubungi superadmin.')
            ->withInput();
    }

    // Tentukan rules (tanpa validasi status karena otomatis)
    $rules = [
        'nama' => 'required|string|max:255',
        'kategori' => 'required|string|max:50',
        'tanggal' => 'required|date',
        'lokasi' => 'required|string|max:255',
        'kuota_peserta' => 'nullable|integer|min:1',
        'deskripsi' => 'required|string|max:2000',
        // 'status' dihapus karena otomatis
        'poster_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
    ];

    $customMessages = [
        'nama.required' => 'Nama event wajib diisi',
        'kategori.required' => 'Kategori wajib dipilih',
        'tanggal.required' => 'Tanggal event wajib diisi',
        'tanggal.date' => 'Format tanggal tidak valid',
        'lokasi.required' => 'Lokasi event wajib diisi',
        'deskripsi.required' => 'Deskripsi event wajib diisi',
        'poster_url.image' => 'File harus berupa gambar',
        'poster_url.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
        'poster_url.max' => 'Ukuran gambar maksimal 5MB',
    ];

    // ... (validasi harga dan lainnya tetap sama) ...

    $validator = Validator::make($request->all(), $rules, $customMessages);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // ... (kode validasi harga tetap) ...

    // Handle image upload
    $posterPath = null;
    if ($request->hasFile('poster_url') && $request->file('poster_url')->isValid()) {
        // ... (kode upload file tetap) ...
    }

    DB::beginTransaction();

    try {
        // Siapkan data untuk insert
        $eventData = [
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'tanggal' => $request->tanggal,
            'lokasi' => $request->lokasi,
            'kuota_peserta' => $request->kuota_peserta,
            'deskripsi' => $request->deskripsi,
            'status' => $status, // STATUS OTOMATIS
            'poster_url' => $posterPath,
            'created_by' => session('user_id'),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Tambahkan kategori_id jika ada
        if ($request->has('kategori_id') && $request->kategori_id) {
            $eventData['kategori_id'] = $request->kategori_id;
        }

        // Tambahkan field tambahan jika ada
        $dbColumns = [
            'map_link', 'waktu_mulai', 'durasi', 'deskripsi_lokasi',
            'rute_lomba', 'syarat_ketentuan', 'fasilitas', 'pendaftaran_ditutup'
        ];
        
        foreach ($dbColumns as $column) {
            if ($request->has($column) && !is_null($request->$column)) {
                $eventData[$column] = $request->$column;
            }
        }

        // Handle harga
        $eventType = $request->input('event_type', 'paid');
        if ($eventType === 'free') {
            $eventData['harga_reguler'] = 0;
            $eventData['harga_premium'] = 0;
        } else {
            $eventData['harga_reguler'] = $request->regular_price ?: 0;
            $eventData['harga_premium'] = $request->premium_price ?: 0;
        }

        // Insert event
        $eventId = DB::table('lomba')->insertGetId($eventData);

        // ... (kode buat paket tetap) ...

        // Log aktivitas dengan info status
        DB::table('log_aktivitas')->insert([
            'user_id' => session('user_id'),
            'aksi' => 'tambah_event',
            'deskripsi' => "Menambahkan event baru: {$request->nama} (Status: {$status})",
            'tabel_terkait' => 'lomba',
            'id_terkait' => $eventId,
            'created_at' => now(),
        ]);

        DB::commit();

        return redirect()->route('admin.events.index')
            ->with('success', "Event berhasil ditambahkan dengan status: {$status}!");

    } catch (\Exception $e) {
        DB::rollBack();
        
        if ($posterPath && Storage::disk('public')->exists($posterPath)) {
            Storage::disk('public')->delete($posterPath);
        }

        \Log::error('Event creation failed: ' . $e->getMessage());

        return redirect()->back()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Display the specified event.
     */
    public function show($id)
    {
        // Check if user is admin
        if (!in_array(session('user_peran'), ['admin', 'superadmin'])) {
            return redirect()->route('login')->with('error', 'Akses ditolak!');
        }

        $event = DB::table('lomba')
            ->leftJoin('kategori_event', 'lomba.kategori_id', '=', 'kategori_event.id')
            ->select('lomba.*', 'kategori_event.nama_kategori as kategori_nama')
            ->where('lomba.id', $id)
            ->first();
        
        if (!$event) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Event tidak ditemukan');
        }

        $packages = DB::table('paket')->where('lomba_id', $id)->get();
        
        $registrations = DB::table('pendaftaran')
            ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
            ->where('pendaftaran.id_lomba', $id)
            ->select('pendaftaran.*', 'pengguna.nama as peserta_nama', 'pengguna.email')
            ->orderBy('pendaftaran.created_at', 'desc')
            ->get();

        $registrationCount = $registrations->count();
        $approvedCount = $registrations->where('status', 'disetujui')->count();
        $pendingCount = $registrations->where('status', 'menunggu')->count();

        return view('admin.events.show', compact(
            'event', 
            'packages', 
            'registrations',
            'registrationCount',
            'approvedCount',
            'pendingCount'
        ));
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

        // PERBAIKAN: Ambil data kategori dari database untuk form edit
        $kategoris = DB::table('kategori_event')
            ->where('is_active', 1)
            ->orderBy('urutan', 'asc')
            ->orderBy('nama_kategori', 'asc')
            ->get();
        
        // Jika belum ada data kategori, berikan array kosong
        if (!$kategoris) {
            $kategoris = [];
        }

        return view('admin.events.edit', compact('event', 'kategoris'));
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

        // === PERBAIKAN: Validasi berdasarkan event type ===
        $rules = [
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:50',
            'kategori_id' => 'nullable|exists:kategori_event,id', // PERBAIKAN: Tambahkan validasi kategori_id
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'kuota_peserta' => 'nullable|integer|min:1',
            'deskripsi' => 'required|string|max:2000',
            'status' => 'required|in:mendatang,selesai,dibatalkan',
            'poster_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ];

        $customMessages = [
            'nama.required' => 'Nama event wajib diisi',
            'kategori.required' => 'Kategori wajib dipilih',
            'kategori_id.exists' => 'Kategori event tidak valid',
            'tanggal.required' => 'Tanggal event wajib diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'lokasi.required' => 'Lokasi event wajib diisi',
            'deskripsi.required' => 'Deskripsi event wajib diisi',
            'poster_url.image' => 'File harus berupa gambar',
            'poster_url.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'poster_url.max' => 'Ukuran gambar maksimal 5MB',
        ];

        $eventType = $request->input('event_type', 'paid');
        
        if ($eventType === 'paid') {
            $rules['regular_price'] = 'required|numeric|min:10000';
            $rules['premium_price'] = 'nullable|numeric|min:0';
            $customMessages['regular_price.required'] = 'Harga reguler wajib diisi untuk event berbayar';
            $customMessages['regular_price.min'] = 'Harga reguler minimal Rp 10,000';
            
            $packageType = $request->input('package_type', 'regular');
            if ($packageType === 'premium') {
                $rules['premium_price'] = 'required|numeric|min:10000';
                $customMessages['premium_price.required'] = 'Harga premium wajib diisi untuk paket premium';
                $customMessages['premium_price.min'] = 'Harga premium minimal Rp 10,000';
            }
        } else {
            $rules['regular_price'] = 'nullable|numeric';
            $rules['premium_price'] = 'nullable|numeric';
            
            // Set harga ke 0 untuk event gratis
            $request->merge([
                'regular_price' => 0,
                'premium_price' => 0
            ]);
        }

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validasi harga premium > harga reguler HANYA untuk event berbayar
        if ($eventType === 'paid') {
            $hargaReguler = $request->regular_price;
            $hargaPremium = $request->premium_price ?: 0;
            
            if ($hargaPremium > 0 && $hargaPremium <= $hargaReguler) {
                return redirect()->back()
                    ->with('error', 'Harga premium harus lebih tinggi dari harga reguler!')
                    ->withInput();
            }
        }

        // Handle image upload
        $posterPath = $event->poster_url;
        if ($request->hasFile('poster_url') && $request->file('poster_url')->isValid()) {
            try {
                // Hapus gambar lama jika ada
                if ($posterPath && Storage::disk('public')->exists($posterPath)) {
                    Storage::disk('public')->delete($posterPath);
                }
                
                $file = $request->file('poster_url');
                
                // Validasi tipe file
                $validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!in_array($file->getMimeType(), $validTypes)) {
                    return redirect()->back()
                        ->with('error', 'Format file tidak didukung. Gunakan JPG, PNG, atau GIF.')
                        ->withInput();
                }
                
                // Validasi ukuran file (max 5MB)
                if ($file->getSize() > 5 * 1024 * 1024) {
                    return redirect()->back()
                        ->with('error', 'Ukuran file terlalu besar. Maksimal 5MB.')
                        ->withInput();
                }
                
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

        DB::beginTransaction();

        try {
            // Siapkan data untuk update
            $eventData = [
                'nama' => $request->nama,
                'kategori' => $request->kategori,
                'kategori_id' => $request->kategori_id, // PERBAIKAN: Tambahkan kategori_id
                'tanggal' => $request->tanggal,
                'lokasi' => $request->lokasi,
                'kuota_peserta' => $request->kuota_peserta,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
                'poster_url' => $posterPath,
                'updated_at' => now(),
            ];

            // Tambahkan field tambahan jika ada di database
            $dbColumns = [
                'map_link', 'waktu_mulai', 'durasi', 'deskripsi_lokasi',
                'rute_lomba', 'syarat_ketentuan', 'fasilitas', 'pendaftaran_ditutup'
            ];
            
            foreach ($dbColumns as $column) {
                if ($request->has($column) && !is_null($request->$column)) {
                    $eventData[$column] = $request->$column;
                }
            }

            // === PERBAIKAN: Handle harga berdasarkan jenis event ===
            if ($eventType === 'free') {
                // Event gratis: harga = 0
                $eventData['harga_reguler'] = 0;
                $eventData['harga_premium'] = 0;
                if (DB::getSchemaBuilder()->hasColumn('lomba', 'is_free')) {
                    $eventData['is_free'] = 1;
                }
            } else {
                // Event berbayar: gunakan harga dari input
                $eventData['harga_reguler'] = $request->regular_price ?: 0;
                $eventData['harga_premium'] = $request->premium_price ?: 0;
                if (DB::getSchemaBuilder()->hasColumn('lomba', 'is_free')) {
                    $eventData['is_free'] = 0;
                }
            }

            // Update event di database
            DB::table('lomba')->where('id', $id)->update($eventData);

            // Update atau buat paket
            if ($eventType === 'paid') {
                // Update paket reguler
                $regularPackage = DB::table('paket')
                    ->where('lomba_id', $id)
                    ->where('nama', 'like', '%Reguler%')
                    ->first();
                    
                if ($regularPackage && $request->regular_price > 0) {
                    DB::table('paket')->where('id', $regularPackage->id)->update([
                        'harga' => $request->regular_price,
                        'updated_at' => now(),
                    ]);
                } elseif ($request->regular_price > 0) {
                    // Buat paket reguler baru jika tidak ada
                    DB::table('paket')->insert([
                        'lomba_id' => $id,
                        'nama' => 'Paket Reguler',
                        'termasuk_race_kit' => 1,
                        'termasuk_medali' => 1,
                        'termasuk_kaos' => 1,
                        'termasuk_sertifikat' => 1,
                        'termasuk_snack' => 1,
                        'harga' => $request->regular_price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Update paket premium
                if ($request->premium_price > 0) {
                    $premiumPackage = DB::table('paket')
                        ->where('lomba_id', $id)
                        ->where('nama', 'like', '%Premium%')
                        ->first();
                        
                    if ($premiumPackage) {
                        DB::table('paket')->where('id', $premiumPackage->id)->update([
                            'harga' => $request->premium_price,
                            'updated_at' => now(),
                        ]);
                    } else {
                        // Buat paket premium baru jika tidak ada
                        DB::table('paket')->insert([
                            'lomba_id' => $id,
                            'nama' => 'Paket Premium',
                            'termasuk_race_kit' => 1,
                            'termasuk_medali' => 1,
                            'termasuk_kaos' => 1,
                            'termasuk_sertifikat' => 1,
                            'termasuk_snack' => 1,
                            'harga' => $request->premium_price,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            } else {
                // Event gratis: update atau buat paket gratis
                $freePackage = DB::table('paket')
                    ->where('lomba_id', $id)
                    ->where('nama', 'like', '%Gratis%')
                    ->first();
                    
                if ($freePackage) {
                    DB::table('paket')->where('id', $freePackage->id)->update([
                        'harga' => 0,
                        'updated_at' => now(),
                    ]);
                } else {
                    // Buat paket gratis baru
                    DB::table('paket')->insert([
                        'lomba_id' => $id,
                        'nama' => 'Paket Gratis',
                        'harga' => 0,
                        'termasuk_race_kit' => 0,
                        'termasuk_medali' => 0,
                        'termasuk_kaos' => 0,
                        'termasuk_sertifikat' => 1,
                        'termasuk_snack' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Log aktivitas
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'edit_event',
                'deskripsi' => 'Mengedit event: ' . $request->nama . 
                               ($eventType === 'free' ? ' (Gratis)' : ' (Berbayar)'),
                'tabel_terkait' => 'lomba',
                'id_terkait' => $id,
                'created_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.events.index')
                ->with('success', 'Event berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Event update failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

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
                ->with('error', 'Tidak dapat menghapus event yang sudah memiliki pendaftaran!');
        }

        DB::beginTransaction();

        try {
            // Hapus gambar event jika ada
            if ($event->poster_url && Storage::disk('public')->exists($event->poster_url)) {
                Storage::disk('public')->delete($event->poster_url);
            }

            // Hapus paket untuk event ini
            DB::table('paket')->where('lomba_id', $id)->delete();

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
            
            return redirect()->route('admin.events.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * AJAX: Get kategori dropdown options
     */
    public function getKategoriDropdown()
    {
        // Check if user is admin
        if (!in_array(session('user_peran'), ['admin', 'superadmin'])) {
            return response()->json(['error' => 'Akses ditolak!'], 403);
        }

        $kategoris = DB::table('kategori_event')
            ->where('is_active', 1)
            ->orderBy('urutan', 'asc')
            ->orderBy('nama_kategori', 'asc')
            ->get(['id', 'nama_kategori', 'ikon', 'warna', 'deskripsi']);
        
        return response()->json([
            'success' => true,
            'data' => $kategoris
        ]);
    }

    /**
     * Toggle event status
     */
    public function toggleStatus($id)
    {
        // Check if user is admin
        if (!in_array(session('user_peran'), ['admin', 'superadmin'])) {
            return redirect()->route('login')->with('error', 'Akses ditolak!');
        }

        $event = DB::table('lomba')->where('id', $id)->first();
        
        if (!$event) {
            return redirect()->back()->with('error', 'Event tidak ditemukan');
        }

        // Determine new status
        $newStatus = $event->status === 'mendatang' ? 'selesai' : 'mendatang';
        
        DB::table('lomba')->where('id', $id)->update([
            'status' => $newStatus,
            'updated_at' => now()
        ]);

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'user_id' => session('user_id'),
            'aksi' => 'toggle_status_event',
            'deskripsi' => 'Mengubah status event ' . $event->nama . ' menjadi ' . $newStatus,
            'tabel_terkait' => 'lomba',
            'id_terkait' => $id,
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status event berhasil diubah!');
    }

    /**
     * Export events to CSV
     */
    public function export()
    {
        // Check if user is admin
        if (!in_array(session('user_peran'), ['admin', 'superadmin'])) {
            return redirect()->route('login')->with('error', 'Akses ditolak!');
        }

        $events = DB::table('lomba')
            ->leftJoin('kategori_event', 'lomba.kategori_id', '=', 'kategori_event.id')
            ->select('lomba.*', 'kategori_event.nama_kategori as kategori_event_nama')
            ->orderBy('tanggal', 'desc')
            ->get();

        $filename = 'events_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($events) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'Nama Event', 'Kategori', 'Kategori Event', 'Tanggal', 
                'Lokasi', 'Harga Reguler', 'Harga Premium', 'Status',
                'Kuota Peserta', 'Deskripsi', 'Created At'
            ]);
            
            // Add data rows
            foreach ($events as $event) {
                fputcsv($file, [
                    $event->id,
                    $event->nama,
                    $event->kategori,
                    $event->kategori_event_nama ?? 'Tidak ada kategori',
                    $event->tanggal,
                    $event->lokasi,
                    $event->harga_reguler,
                    $event->harga_premium,
                    $event->status,
                    $event->kuota_peserta,
                    substr($event->deskripsi, 0, 100) . '...',
                    $event->created_at
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get event statistics
     */
    public function getStats()
    {
        // Check if user is admin
        if (!in_array(session('user_peran'), ['admin', 'superadmin'])) {
            return response()->json(['error' => 'Akses ditolak!'], 403);
        }

        $stats = [
            'total' => DB::table('lomba')->count(),
            'mendatang' => DB::table('lomba')->where('status', 'mendatang')->count(),
            'selesai' => DB::table('lomba')->where('status', 'selesai')->count(),
            'dibatalkan' => DB::table('lomba')->where('status', 'dibatalkan')->count(),
            'berbayar' => DB::table('lomba')->where('harga_reguler', '>', 0)->count(),
            'gratis' => DB::table('lomba')->where('harga_reguler', 0)->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]); 
    }
}