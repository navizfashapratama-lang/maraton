<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StaffProfileController extends Controller
{
    /**
     * Menampilkan halaman profil staff
     */
    public function index()
    {
        // Cek apakah user sudah login
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Harap login terlebih dahulu!');
        }
        
        // Cek apakah user adalah staff
        if (!in_array(session('user_peran'), ['staff', 'admin', 'superadmin'])) {
            return redirect()->route('home')->with('error', 'Akses ditolak! Hanya untuk staff.');
        }
        
        // Ambil data user
        $user = DB::table('pengguna')
            ->where('id', session('user_id'))
            ->first();
        
        if (!$user) {
            session()->flush();
            return redirect()->route('login')->with('error', 'Sesi tidak valid. Silakan login kembali.');
        }
        
        // Get staff statistics
        $stats = [
            'total_verifications' => DB::table('pembayaran')
                ->where('diverifikasi_oleh', session('user_id'))
                ->where('status', 'terverifikasi')
                ->count(),
            'pending_tasks' => DB::table('pendaftaran')
                ->where('status_pendaftaran', 'menunggu')
                ->count() + DB::table('pembayaran')
                ->where('status', 'menunggu')
                ->count(),
            'total_registrations_processed' => DB::table('pendaftaran')
                ->where('status_pendaftaran', 'disetujui')
                ->count(),
            'monthly_verifications' => DB::table('pembayaran')
                ->where('diverifikasi_oleh', session('user_id'))
                ->where('status', 'terverifikasi')
                ->whereMonth('tanggal_verifikasi', date('m'))
                ->whereYear('tanggal_verifikasi', date('Y'))
                ->count(),
        ];
        
        // Ambil aktivitas terbaru
        $activities = DB::table('log_aktivitas')
            ->where('user_id', session('user_id'))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Ambil verifikasi terbaru
        $recent_verifications = DB::table('pembayaran')
            ->join('pendaftaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->where('pembayaran.diverifikasi_oleh', session('user_id'))
            ->where('pembayaran.status', 'terverifikasi')
            ->select('pembayaran.*', 'pendaftaran.nama_lengkap', 'lomba.nama as event_nama')
            ->orderBy('pembayaran.tanggal_verifikasi', 'desc')
            ->limit(5)
            ->get();
        
        return view('staff.profile.index', compact('user', 'stats', 'activities', 'recent_verifications'));
    }

    /**
     * Menampilkan form edit profil
     */
    public function edit()
    {
        // Cek apakah user sudah login
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Harap login terlebih dahulu!');
        }
        
        // Cek apakah user adalah staff
        if (!in_array(session('user_peran'), ['staff', 'admin', 'superadmin'])) {
            return redirect()->route('home')->with('error', 'Akses ditolak! Hanya untuk staff.');
        }
        
        // Ambil data user
        $user = DB::table('pengguna')
            ->where('id', session('user_id'))
            ->first();
        
        if (!$user) {
            session()->flush();
            return redirect()->route('login')->with('error', 'Sesi tidak valid. Silakan login kembali.');
        }
        
        return view('staff.profile.edit', compact('user'));
    }

    /**
     * Update profil staff (dari form edit biasa)
     */
    public function update(Request $request)
    {
        // Cek apakah user sudah login
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Harap login terlebih dahulu!');
        }
        
        // Cek apakah user adalah staff
        if (!in_array(session('user_peran'), ['staff', 'admin', 'superadmin'])) {
            return redirect()->route('home')->with('error', 'Akses ditolak! Hanya untuk staff.');
        }
        
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'current_password' => 'nullable|required_with:new_password|string|min:6',
            'new_password' => 'nullable|string|min:6|confirmed',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'telepon.max' => 'Nomor telepon maksimal 20 karakter',
            'alamat.max' => 'Alamat maksimal 500 karakter',
            'current_password.required_with' => 'Password saat ini diperlukan untuk mengubah password',
            'new_password.min' => 'Password baru minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Ambil data user
        $user = DB::table('pengguna')
            ->where('id', session('user_id'))
            ->first();
        
        if (!$user) {
            session()->flush();
            return redirect()->route('login')->with('error', 'Sesi tidak valid. Silakan login kembali.');
        }
        
        // Validasi password saat ini jika ingin mengganti password
        if ($request->new_password) {
            if (!$request->current_password || !Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->with('error', 'Password saat ini salah!')
                    ->withInput();
            }
        }
        
        try {
            DB::beginTransaction();
            
            // Siapkan data untuk update
            $data = [
                'nama' => $request->nama,
                'telepon' => $request->telepon ?? null,
                'alamat' => $request->alamat ?? null,
                'updated_at' => now(),
            ];
            
            // Update password jika diisi
            if ($request->new_password) {
                $data['password'] = Hash::make($request->new_password);
            }
            
            // Handle hapus foto (jika checkbox di centang)
            if ($request->has('remove_photo') && $request->remove_photo == '1') {
                // Hapus foto dari storage
                if ($user->foto_profil && Storage::exists('public/' . $user->foto_profil)) {
                    Storage::delete('public/' . $user->foto_profil);
                }
                $data['foto_profil'] = null;
                session(['user_foto' => null]);
            }
            
            // Update data di database
            DB::table('pengguna')
                ->where('id', session('user_id'))
                ->update($data);
            
            // Update session
            session(['user_nama' => $request->nama]);
            session(['user_telp' => $request->telepon]);
            session(['user_alamat' => $request->alamat]);
            
            // Log aktivitas
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'update_staff_profile',
                'deskripsi' => 'Staff memperbarui profil: ' . $request->nama,
                'tabel_terkait' => 'pengguna',
                'id_terkait' => session('user_id'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'method' => $request->method(),
                'endpoint' => $request->path(),
                'created_at' => now()
            ]);
            
            DB::commit();
            
            return redirect()->route('staff.profile.index')
                ->with('success', 'Profil berhasil diperbarui!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan halaman pengaturan foto profil
     */
    public function photo()
    {
        // Cek apakah user sudah login
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Harap login terlebih dahulu!');
        }
        
        // Cek apakah user adalah staff
        if (!in_array(session('user_peran'), ['staff', 'admin', 'superadmin'])) {
            return redirect()->route('home')->with('error', 'Akses ditolak! Hanya untuk staff.');
        }
        
        // Ambil data user
        $user = DB::table('pengguna')
            ->where('id', session('user_id'))
            ->first();
        
        return view('staff.profile.photo', compact('user'));
    }

    /**
     * Upload foto profil
     */
    public function uploadPhoto(Request $request)
    {
        // Cek apakah user sudah login
        if (!session('is_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Harap login terlebih dahulu!'], 401);
        }
        
        // Cek apakah user adalah staff
        if (!in_array(session('user_peran'), ['staff', 'admin', 'superadmin'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak!'], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'crop_x' => 'nullable|numeric',
            'crop_y' => 'nullable|numeric',
            'crop_width' => 'nullable|numeric',
            'crop_height' => 'nullable|numeric',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $user = DB::table('pengguna')->where('id', session('user_id'))->first();
            
            // Hapus foto lama jika ada
            if ($user->foto_profil && Storage::exists('public/' . $user->foto_profil)) {
                Storage::delete('public/' . $user->foto_profil);
            }
            
            $file = $request->file('photo');
            $filename = 'staff_' . session('user_id') . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('staff/profiles', $filename, 'public');
            
            // Update database
            DB::table('pengguna')
                ->where('id', session('user_id'))
                ->update([
                    'foto_profil' => $path,
                    'updated_at' => now()
                ]);
            
            // Update session
            session(['user_foto' => $path]);
            
            // Log aktivitas
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'upload_profile_photo',
                'deskripsi' => 'Mengupload foto profil baru',
                'tabel_terkait' => 'pengguna',
                'id_terkait' => session('user_id'),
                'created_at' => now()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diupload!',
                'photo_url' => Storage::url($path)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate avatar otomatis
     */
    public function generateAvatar(Request $request)
    {
        // Cek apakah user sudah login
        if (!session('is_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Harap login terlebih dahulu!'], 401);
        }
        
        // Cek apakah user adalah staff
        if (!in_array(session('user_peran'), ['staff', 'admin', 'superadmin'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak!'], 403);
        }
        
        $request->validate([
            'color' => 'nullable|string',
            'background' => 'nullable|string'
        ]);
        
        try {
            DB::beginTransaction();
            
            $user = DB::table('pengguna')->where('id', session('user_id'))->first();
            
            // Hapus foto lama jika ada
            if ($user->foto_profil && Storage::exists('public/' . $user->foto_profil)) {
                Storage::delete('public/' . $user->foto_profil);
            }
            
            $initials = strtoupper(substr($user->nama, 0, 2));
            $color = $request->color ?? '#4a6cf7';
            $background = $request->background ?? '#f8f9fa';
            
            // Simpan metadata avatar
            $avatarData = [
                'type' => 'generated',
                'initials' => $initials,
                'color' => $color,
                'background' => $background,
                'generated_at' => now()->toDateTimeString()
            ];
            
            // Buat placeholder (nanti bisa diganti dengan GD library jika diinginkan)
            $path = 'staff/avatars/avatar_' . session('user_id') . '_' . time() . '.txt';
            Storage::disk('public')->put($path, json_encode($avatarData));
            
            // Update database
            DB::table('pengguna')
                ->where('id', session('user_id'))
                ->update([
                    'foto_profil' => $path,
                    'updated_at' => now()
                ]);
            
            // Update session
            session(['user_foto' => $path]);
            
            // Log aktivitas
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'generate_avatar',
                'deskripsi' => 'Membuat avatar otomatis: ' . $initials,
                'tabel_terkait' => 'pengguna',
                'id_terkait' => session('user_id'),
                'created_at' => now()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Avatar berhasil dibuat!',
                'photo_url' => asset('images/default-avatar.png'), // Gunakan default avatar
                'initials' => $initials
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat avatar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus foto profil
     */
    public function deletePhoto()
    {
        // Cek apakah user sudah login
        if (!session('is_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Harap login terlebih dahulu!'], 401);
        }
        
        // Cek apakah user adalah staff
        if (!in_array(session('user_peran'), ['staff', 'admin', 'superadmin'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak!'], 403);
        }
        
        try {
            DB::beginTransaction();
            
            $user = DB::table('pengguna')->where('id', session('user_id'))->first();
            
            // Hapus file dari storage jika ada
            if ($user->foto_profil && Storage::exists('public/' . $user->foto_profil)) {
                Storage::delete('public/' . $user->foto_profil);
            }
            
            // Update database
            DB::table('pengguna')
                ->where('id', session('user_id'))
                ->update([
                    'foto_profil' => null,
                    'updated_at' => now()
                ]);
            
            // Update session
            session(['user_foto' => null]);
            
            // Log aktivitas
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'delete_profile_photo',
                'deskripsi' => 'Menghapus foto profil',
                'tabel_terkait' => 'pengguna',
                'id_terkait' => session('user_id'),
                'created_at' => now()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil dihapus!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan aktivitas staff
     */
    public function activities(Request $request)
    {
        // Cek apakah user sudah login
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Harap login terlebih dahulu!');
        }
        
        // Cek apakah user adalah staff
        if (!in_array(session('user_peran'), ['staff', 'admin', 'superadmin'])) {
            return redirect()->route('home')->with('error', 'Akses ditolak! Hanya untuk staff.');
        }
        
        // Filter berdasarkan tanggal
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $search = $request->input('search');
        
        $activities = DB::table('log_aktivitas')
            ->where('user_id', session('user_id'))
            ->when($start_date, function ($query) use ($start_date) {
                return $query->whereDate('created_at', '>=', $start_date);
            })
            ->when($end_date, function ($query) use ($end_date) {
                return $query->whereDate('created_at', '<=', $end_date);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('aksi', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('tabel_terkait', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Statistik aktivitas
        $activity_stats = [
            'total' => DB::table('log_aktivitas')
                ->where('user_id', session('user_id'))
                ->count(),
            'today' => DB::table('log_aktivitas')
                ->where('user_id', session('user_id'))
                ->whereDate('created_at', today())
                ->count(),
            'this_week' => DB::table('log_aktivitas')
                ->where('user_id', session('user_id'))
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'this_month' => DB::table('log_aktivitas')
                ->where('user_id', session('user_id'))
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->count(),
        ];
        
        return view('staff.profile.activities', compact('activities', 'activity_stats'));
    }

    /**
     * Menampilkan verifikasi yang dilakukan staff
     */
    public function verifications(Request $request)
    {
        // Cek apakah user sudah login
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Harap login terlebih dahulu!');
        }
        
        // Cek apakah user adalah staff
        if (!in_array(session('user_peran'), ['staff', 'admin', 'superadmin'])) {
            return redirect()->route('home')->with('error', 'Akses ditolak! Hanya untuk staff.');
        }
        
        // Filter berdasarkan status dan tanggal
        $status = $request->query('status');
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        $search = $request->query('search');
        
        $verifications = DB::table('pembayaran')
            ->join('pendaftaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
            ->where('pembayaran.diverifikasi_oleh', session('user_id'))
            ->when($status, function ($query) use ($status) {
                return $query->where('pembayaran.status', $status);
            })
            ->when($start_date, function ($query) use ($start_date) {
                return $query->whereDate('pembayaran.tanggal_verifikasi', '>=', $start_date);
            })
            ->when($end_date, function ($query) use ($end_date) {
                return $query->whereDate('pembayaran.tanggal_verifikasi', '<=', $end_date);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('pembayaran.kode_pembayaran', 'like', "%{$search}%")
                      ->orWhere('pendaftaran.nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('pendaftaran.kode_pendaftaran', 'like', "%{$search}%")
                      ->orWhere('lomba.nama', 'like', "%{$search}%");
                });
            })
            ->select(
                'pembayaran.*',
                'pendaftaran.nama_lengkap',
                'pendaftaran.kode_pendaftaran',
                'pendaftaran.telepon as peserta_telepon',
                'pendaftaran.email as peserta_email',
                'lomba.nama as event_nama',
                'pengguna.nama as peserta_nama'
            )
            ->orderBy('pembayaran.tanggal_verifikasi', 'desc')
            ->paginate(20);
        
        $stats = [
            'total' => DB::table('pembayaran')
                ->where('diverifikasi_oleh', session('user_id'))
                ->count(),
            'verified' => DB::table('pembayaran')
                ->where('diverifikasi_oleh', session('user_id'))
                ->where('status', 'terverifikasi')
                ->count(),
            'rejected' => DB::table('pembayaran')
                ->where('diverifikasi_oleh', session('user_id'))
                ->where('status', 'ditolak')
                ->count(),
            'pending' => DB::table('pembayaran')
                ->where('diverifikasi_oleh', session('user_id'))
                ->where('status', 'menunggu')
                ->count(),
            'total_amount' => DB::table('pembayaran')
                ->where('diverifikasi_oleh', session('user_id'))
                ->where('status', 'terverifikasi')
                ->sum('jumlah'),
        ];
        
        return view('staff.profile.verifications', compact('verifications', 'stats'));
    }

    /**
     * Menampilkan statistik kinerja staff
     */
    public function performance(Request $request)
    {
        // Cek apakah user sudah login
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Harap login terlebih dahulu!');
        }
        
        // Cek apakah user adalah staff
        if (!in_array(session('user_peran'), ['staff', 'admin', 'superadmin'])) {
            return redirect()->route('home')->with('error', 'Akses ditolak! Hanya untuk staff.');
        }
        
        // Periode filter
        $period = $request->query('period', 'monthly');
        $year = $request->query('year', date('Y'));
        
        // Statistik harian
        $dailyStats = [
            'verifications' => DB::table('pembayaran')
                ->where('diverifikasi_oleh', session('user_id'))
                ->where('status', 'terverifikasi')
                ->whereDate('tanggal_verifikasi', today())
                ->count(),
            'registrations_processed' => DB::table('pendaftaran')
                ->where('status_pendaftaran', 'disetujui')
                ->whereDate('updated_at', today())
                ->count(),
            'rejected_payments' => DB::table('pembayaran')
                ->where('diverifikasi_oleh', session('user_id'))
                ->where('status', 'ditolak')
                ->whereDate('tanggal_verifikasi', today())
                ->count(),
        ];
        
        // Statistik mingguan
        $weeklyStats = [
            'verifications' => DB::table('pembayaran')
                ->where('diverifikasi_oleh', session('user_id'))
                ->where('status', 'terverifikasi')
                ->whereBetween('tanggal_verifikasi', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'registrations_processed' => DB::table('pendaftaran')
                ->where('status_pendaftaran', 'disetujui')
                ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'weekly_revenue' => DB::table('pembayaran')
                ->where('diverifikasi_oleh', session('user_id'))
                ->where('status', 'terverifikasi')
                ->whereBetween('tanggal_verifikasi', [now()->startOfWeek(), now()->endOfWeek()])
                ->sum('jumlah'),
        ];
        
        // Statistik bulanan
        $monthlyStats = [
            'verifications' => DB::table('pembayaran')
                ->where('diverifikasi_oleh', session('user_id'))
                ->where('status', 'terverifikasi')
                ->whereMonth('tanggal_verifikasi', date('m'))
                ->whereYear('tanggal_verifikasi', date('Y'))
                ->count(),
            'registrations_processed' => DB::table('pendaftaran')
                ->where('status_pendaftaran', 'disetujui')
                ->whereMonth('updated_at', date('m'))
                ->whereYear('updated_at', date('Y'))
                ->count(),
            'monthly_revenue' => DB::table('pembayaran')
                ->where('diverifikasi_oleh', session('user_id'))
                ->where('status', 'terverifikasi')
                ->whereMonth('tanggal_verifikasi', date('m'))
                ->whereYear('tanggal_verifikasi', date('Y'))
                ->sum('jumlah'),
        ];
        
        // Grafik verifikasi bulanan (12 bulan terakhir)
        $monthlyVerifications = DB::table('pembayaran')
            ->select(
                DB::raw('MONTH(tanggal_verifikasi) as month'),
                DB::raw('YEAR(tanggal_verifikasi) as year'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(jumlah) as total_amount')
            )
            ->where('diverifikasi_oleh', session('user_id'))
            ->where('status', 'terverifikasi')
            ->whereYear('tanggal_verifikasi', '>=', now()->subYear()->year)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Event yang paling banyak diverifikasi
        $topEvents = DB::table('pembayaran')
            ->join('pendaftaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->select(
                'lomba.id',
                'lomba.nama',
                DB::raw('COUNT(*) as verification_count'),
                DB::raw('SUM(pembayaran.jumlah) as total_revenue')
            )
            ->where('pembayaran.diverifikasi_oleh', session('user_id'))
            ->where('pembayaran.status', 'terverifikasi')
            ->groupBy('lomba.id', 'lomba.nama')
            ->orderBy('verification_count', 'desc')
            ->limit(5)
            ->get();
        
        // Performa berdasarkan hari dalam seminggu
        $weeklyPerformance = DB::table('pembayaran')
            ->select(
                DB::raw('DAYOFWEEK(tanggal_verifikasi) as day_of_week'),
                DB::raw('COUNT(*) as count')
            )
            ->where('diverifikasi_oleh', session('user_id'))
            ->where('status', 'terverifikasi')
            ->whereBetween('tanggal_verifikasi', [now()->subDays(30), now()])
            ->groupBy('day_of_week')
            ->orderBy('day_of_week')
            ->get();
        
        // Tahun yang tersedia untuk filter
        $availableYears = DB::table('pembayaran')
            ->select(DB::raw('YEAR(tanggal_verifikasi) as year'))
            ->where('diverifikasi_oleh', session('user_id'))
            ->whereNotNull('tanggal_verifikasi')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        return view('staff.profile.performance', compact(
            'dailyStats',
            'weeklyStats',
            'monthlyStats',
            'monthlyVerifications',
            'topEvents',
            'weeklyPerformance',
            'availableYears',
            'period',
            'year'
        ));
    }

    /**
     * API untuk mendapatkan data statistik staff (JSON)
     */
    public function getStats(Request $request)
    {
        // Cek apakah user sudah login
        if (!session('is_logged_in') || !in_array(session('user_peran'), ['staff', 'admin', 'superadmin'])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $period = $request->query('period', 'monthly');
        
        $stats = [];
        
        switch ($period) {
            case 'daily':
                $stats = [
                    'verifications' => DB::table('pembayaran')
                        ->where('diverifikasi_oleh', session('user_id'))
                        ->where('status', 'terverifikasi')
                        ->whereDate('tanggal_verifikasi', today())
                        ->count(),
                    'registrations_processed' => DB::table('pendaftaran')
                        ->where('status_pendaftaran', 'disetujui')
                        ->whereDate('updated_at', today())
                        ->count(),
                    'rejected_payments' => DB::table('pembayaran')
                        ->where('diverifikasi_oleh', session('user_id'))
                        ->where('status', 'ditolak')
                        ->whereDate('tanggal_verifikasi', today())
                        ->count(),
                ];
                break;
                
            case 'weekly':
                $stats = [
                    'verifications' => DB::table('pembayaran')
                        ->where('diverifikasi_oleh', session('user_id'))
                        ->where('status', 'terverifikasi')
                        ->whereBetween('tanggal_verifikasi', [now()->startOfWeek(), now()->endOfWeek()])
                        ->count(),
                    'registrations_processed' => DB::table('pendaftaran')
                        ->where('status_pendaftaran', 'disetujui')
                        ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
                        ->count(),
                    'weekly_revenue' => DB::table('pembayaran')
                        ->where('diverifikasi_oleh', session('user_id'))
                        ->where('status', 'terverifikasi')
                        ->whereBetween('tanggal_verifikasi', [now()->startOfWeek(), now()->endOfWeek()])
                        ->sum('jumlah'),
                ];
                break;
                
            case 'monthly':
                $stats = [
                    'verifications' => DB::table('pembayaran')
                        ->where('diverifikasi_oleh', session('user_id'))
                        ->where('status', 'terverifikasi')
                        ->whereMonth('tanggal_verifikasi', date('m'))
                        ->whereYear('tanggal_verifikasi', date('Y'))
                        ->count(),
                    'registrations_processed' => DB::table('pendaftaran')
                        ->where('status_pendaftaran', 'disetujui')
                        ->whereMonth('updated_at', date('m'))
                        ->whereYear('updated_at', date('Y'))
                        ->count(),
                    'monthly_revenue' => DB::table('pembayaran')
                        ->where('diverifikasi_oleh', session('user_id'))
                        ->where('status', 'terverifikasi')
                        ->whereMonth('tanggal_verifikasi', date('m'))
                        ->whereYear('tanggal_verifikasi', date('Y'))
                        ->sum('jumlah'),
                ];
                break;
        }
        
        return response()->json([
            'success' => true,
            'data' => $stats,
            'period' => $period
        ]);
    }
}