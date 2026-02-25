<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Constructor - cek akses admin
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!session('is_logged_in')) {
                return redirect()->route('login')->with('error', 'Harap login terlebih dahulu!');
            }
            
            if (!in_array(session('user_peran'), ['admin', 'superadmin'])) {
                return redirect()->route('home')->with('error', 'Akses ditolak! Hanya untuk admin.');
            }
            
            return $next($request);
        });
    }

    /**
     * Dashboard Admin
     */
    public function dashboard()
    {
        $total_users = DB::table('pengguna')->count();
        $total_events = DB::table('lomba')->count();
        $total_registrations = DB::table('pendaftaran')->count();
        
        $recent_events = DB::table('lomba')
            ->where('status', 'mendatang')
            ->orderBy('tanggal', 'asc')
            ->limit(5)
            ->get();
            
        $recent_registrations = DB::table('pendaftaran')
            ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->select('pendaftaran.*', 'pengguna.nama as user_nama', 'lomba.nama as event_nama')
            ->orderBy('pendaftaran.created_at', 'desc')
            ->limit(5)
            ->get();
        
        $pending_payments = DB::table('pembayaran')
            ->where('status', 'menunggu')
            ->count();
        
        $pending_registrations = DB::table('pendaftaran')
            ->where('status', 'menunggu')
            ->count();
            
        $total_revenue = DB::table('pembayaran')
            ->where('status', 'terverifikasi')
            ->sum('jumlah');
        
        return view('admin.dashboard', compact(
            'total_users',
            'total_events',
            'total_registrations',
            'pending_payments',
            'pending_registrations',
            'total_revenue',
            'recent_events',
            'recent_registrations'
        ));
    }

    // ========== USER MANAGEMENT ==========

    /**
     * Users index
     */
    public function usersIndex(Request $request)
    {
        $search = $request->search;
        $role = $request->role;
        
        $users = DB::table('pengguna');
        
        if ($search) {
            $users->where(function($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('telepon', 'like', "%{$search}%");
            });
        }
        
        if ($role && $role !== 'all') {
            $users->where('peran', $role);
        }
        
        $users = $users->orderBy('created_at', 'desc')->paginate(10);
        
        $stats = [
            'total' => DB::table('pengguna')->count(),
            'superadmin' => DB::table('pengguna')->where('peran', 'superadmin')->count(),
            'admin' => DB::table('pengguna')->where('peran', 'admin')->count(),
            'staff' => DB::table('pengguna')->where('peran', 'staff')->count(),
            'kasir' => DB::table('pengguna')->where('peran', 'kasir')->count(),
            'peserta' => DB::table('pengguna')->where('peran', 'peserta')->count(),
            'aktif' => DB::table('pengguna')->where('is_active', 1)->count(),
            'nonaktif' => DB::table('pengguna')->where('is_active', 0)->count(),
        ];
        
        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * User create form
     */
    public function usersCreate()
    {
        return view('admin.users.create');
    }

    /**
     * User store
     */
    public function usersStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna',
            'password' => 'required|string|min:6|confirmed',
            'peran' => 'required|in:peserta,admin,staff,kasir,superadmin',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            DB::table('pengguna')->insert([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'peran' => $request->peran,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Log aktivitas
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'tambah_pengguna',
                'deskripsi' => 'Menambahkan pengguna baru: ' . $request->nama . ' (' . $request->email . ')',
                'tabel_terkait' => 'pengguna',
                'id_terkait' => DB::getPdo()->lastInsertId(),
                'created_at' => now()
            ]);
            
            return redirect('/admin/users')->with('success', 'Pengguna ' . $request->nama . ' berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * User edit form
     */
    public function usersEdit($id)
    {
        $user = DB::table('pengguna')->where('id', $id)->first();
        if (!$user) {
            return redirect('/admin/users')->with('error', 'Pengguna tidak ditemukan');
        }
        return view('admin.users.edit', compact('user'));
    }

    /**
     * User update
     */
    public function usersUpdate(Request $request, $id)
    {
        $user = DB::table('pengguna')->where('id', $id)->first();
        if (!$user) {
            return redirect('/admin/users')->with('error', 'Pengguna tidak ditemukan');
        }
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email,' . $id,
            'peran' => 'required|in:peserta,admin,staff,kasir,superadmin',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'peran' => $request->peran,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'updated_at' => now(),
        ];
        
        if ($request->password) {
            $validator = Validator::make($request->all(), [
                'password' => 'string|min:6|confirmed',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $data['password'] = Hash::make($request->password);
        }
        
        DB::table('pengguna')->where('id', $id)->update($data);
        
        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'user_id' => session('user_id'),
            'aksi' => 'edit_pengguna',
            'deskripsi' => 'Mengedit data pengguna: ' . $request->nama . ' (ID: ' . $id . ')',
            'tabel_terkait' => 'pengguna',
            'id_terkait' => $id,
            'created_at' => now()
        ]);
        
        return redirect('/admin/users')->with('success', 'Pengguna berhasil diperbarui!');
    }

    /**
     * User delete
     */
    public function usersDestroy($id)
    {
        if ($id == session('user_id')) {
            return redirect('/admin/users')->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        
        $user = DB::table('pengguna')->where('id', $id)->first();
        if (!$user) {
            return redirect('/admin/users')->with('error', 'Pengguna tidak ditemukan');
        }
        
        DB::table('pengguna')->where('id', $id)->delete();
        
        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'user_id' => session('user_id'),
            'aksi' => 'hapus_pengguna',
            'deskripsi' => 'Menghapus pengguna: ' . $user->nama . ' (ID: ' . $id . ')',
            'tabel_terkait' => 'pengguna',
            'id_terkait' => $id,
            'created_at' => now()
        ]);
        
        return redirect('/admin/users')->with('success', 'Pengguna ' . $user->nama . ' berhasil dihapus!');
    }

    // ========== EVENT MANAGEMENT ==========
    // Note: Event management sudah ada di EventController, jadi kita hanya perlu memanggilnya

    // ========== PACKAGE MANAGEMENT ==========

    /**
     * Packages index
     */
    public function packagesIndex()
    {
        $packages = DB::table('paket')
            ->join('lomba', 'paket.lomba_id', '=', 'lomba.id')
            ->select('paket.*', 'lomba.nama as event_nama')
            ->orderBy('paket.created_at', 'desc')
            ->paginate(15);
            
        $stats = [
            'total' => DB::table('paket')->count(),
            'with_race_kit' => DB::table('paket')->where('termasuk_race_kit', 1)->count(),
            'with_medal' => DB::table('paket')->where('termasuk_medali', 1)->count(),
            'with_shirt' => DB::table('paket')->where('termasuk_kaos', 1)->count(),
        ];
        
        return view('admin.packages.index', compact('packages', 'stats'));
    }

    /**
     * Package create form
     */
    public function packagesCreate()
    {
        $events = DB::table('lomba')
            ->where('status', 'mendatang')
            ->orderBy('tanggal', 'asc')
            ->get();
            
        return view('admin.packages.create', compact('events'));
    }

    /**
     * Package store
     */
    public function packagesStore(Request $request)
    {
        $validated = $request->validate([
            'lomba_id' => 'required|exists:lomba,id',
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'termasuk_race_kit' => 'boolean',
            'termasuk_medali' => 'boolean',
            'termasuk_kaos' => 'boolean',
            'deskripsi' => 'nullable|string',
        ]);
        
        DB::table('paket')->insert([
            'lomba_id' => $validated['lomba_id'],
            'nama' => $validated['nama'],
            'harga' => $validated['harga'],
            'termasuk_race_kit' => $validated['termasuk_race_kit'] ?? 0,
            'termasuk_medali' => $validated['termasuk_medali'] ?? 0,
            'termasuk_kaos' => $validated['termasuk_kaos'] ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil ditambahkan!');
    }

    /**
     * Package edit form
     */
    public function packagesEdit($id)
    {
        $package = DB::table('paket')->where('id', $id)->first();
        
        if (!$package) {
            return redirect()->route('admin.packages.index')->with('error', 'Paket tidak ditemukan');
        }
        
        $events = DB::table('lomba')
            ->where('status', 'mendatang')
            ->orderBy('tanggal', 'asc')
            ->get();
            
        return view('admin.packages.edit', compact('package', 'events'));
    }

    /**
     * Package update
     */
    public function packagesUpdate(Request $request, $id)
    {
        $package = DB::table('paket')->where('id', $id)->first();
        
        if (!$package) {
            return redirect()->route('admin.packages.index')->with('error', 'Paket tidak ditemukan');
        }
        
        $validated = $request->validate([
            'lomba_id' => 'required|exists:lomba,id',
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'termasuk_race_kit' => 'boolean',
            'termasuk_medali' => 'boolean',
            'termasuk_kaos' => 'boolean',
            'deskripsi' => 'nullable|string',
        ]);
        
        DB::table('paket')->where('id', $id)->update([
            'lomba_id' => $validated['lomba_id'],
            'nama' => $validated['nama'],
            'harga' => $validated['harga'],
            'termasuk_race_kit' => $validated['termasuk_race_kit'] ?? 0,
            'termasuk_medali' => $validated['termasuk_medali'] ?? 0,
            'termasuk_kaos' => $validated['termasuk_kaos'] ?? 0,
            'updated_at' => now(),
        ]);
        
        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil diperbarui!');
    }

    /**
     * Package delete
     */
    public function packagesDestroy($id)
    {
        $package = DB::table('paket')->where('id', $id)->first();
        
        if (!$package) {
            return redirect()->route('admin.packages.index')->with('error', 'Paket tidak ditemukan');
        }
        
        $hasRegistrations = DB::table('pendaftaran')->where('id_paket', $id)->exists();
        
        if ($hasRegistrations) {
            return redirect()->route('admin.packages.index')->with('error', 'Tidak dapat menghapus paket yang sudah digunakan!');
        }
        
        DB::table('paket')->where('id', $id)->delete();
        
        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil dihapus!');
    }

    // ========== REGISTRATION MANAGEMENT ==========

    /**
     * Registrations index
     */
    public function registrationsIndex(Request $request)
    {
        $status = $request->status;
        $search = $request->search;
        
        $registrations = DB::table('pendaftaran')
            ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->join('paket', 'pendaftaran.id_paket', '=', 'paket.id')
            ->select('pendaftaran.*', 
                     'pengguna.nama as user_nama', 
                     'pengguna.email as user_email',
                     'lomba.nama as event_nama',
                     'paket.nama as package_name',
                     'paket.harga as package_price');
        
        if ($status && in_array($status, ['menunggu', 'disetujui', 'ditolak'])) {
            $registrations->where('pendaftaran.status', $status);
        }
        
        if ($search) {
            $registrations->where(function($query) use ($search) {
                $query->where('pendaftaran.kode_pendaftaran', 'like', "%{$search}%")
                      ->orWhere('pengguna.nama', 'like', "%{$search}%")
                      ->orWhere('pengguna.email', 'like', "%{$search}%");
            });
        }
        
        $registrations = $registrations->orderBy('pendaftaran.created_at', 'desc')->paginate(15);
            
        $stats = [
            'total' => DB::table('pendaftaran')->count(),
            'pending' => DB::table('pendaftaran')->where('status', 'menunggu')->count(),
            'approved' => DB::table('pendaftaran')->where('status', 'disetujui')->count(),
            'rejected' => DB::table('pendaftaran')->where('status', 'ditolak')->count(),
        ];
        
        if ($request->ajax()) {
            return response()->json([
                'registrations' => $registrations,
                'stats' => $stats
            ]);
        }
        
        return view('admin.registrations.index', compact('registrations', 'stats'));
    }

    /**
     * Registration view
     */
    public function registrationsView($id)
    {
        $registration = DB::table('pendaftaran')
            ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->join('paket', 'pendaftaran.id_paket', '=', 'paket.id')
            ->where('pendaftaran.id', $id)
            ->select('pendaftaran.*', 
                     'pengguna.nama as user_nama', 
                     'pengguna.email as user_email',
                     'pengguna.telepon as user_phone',
                     'pengguna.alamat as user_alamat',
                     'lomba.*',
                     'paket.nama as package_name',
                     'paket.harga as package_price',
                     'paket.termasuk_race_kit',
                     'paket.termasuk_medali',
                     'paket.termasuk_kaos')
            ->first();
            
        if (!$registration) {
            return redirect()->route('admin.registrations.index')->with('error', 'Pendaftaran tidak ditemukan');
        }
        
        $payment = DB::table('pembayaran')
            ->where('id_pendaftaran', $id)
            ->first();
        
        return view('admin.registrations.view', compact('registration', 'payment'));
    }

    // ========== PAYMENT MANAGEMENT ==========

    /**
     * Payments index
     */
    public function paymentsIndex()
    {
        $payments = DB::table('pembayaran')
            ->join('pendaftaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')
            ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->select('pembayaran.*',
                     'pendaftaran.nomor_start',
                     'pengguna.nama as user_nama',
                     'lomba.nama as event_nama')
            ->orderBy('pembayaran.created_at', 'desc')
            ->paginate(15);
            
        $stats = [
            'total' => DB::table('pembayaran')->count(),
            'pending' => DB::table('pembayaran')->where('status', 'menunggu')->count(),
            'verified' => DB::table('pembayaran')->where('status', 'terverifikasi')->count(),
            'rejected' => DB::table('pembayaran')->where('status', 'ditolak')->count(),
            'total_amount' => DB::table('pembayaran')->where('status', 'terverifikasi')->sum('jumlah'),
        ];
        
        return view('admin.payments.index', compact('payments', 'stats'));
    }

    /**
     * Payment view
     */
    public function paymentsView($id)
    {
        $payment = DB::table('pembayaran')
            ->join('pendaftaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')
            ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->join('paket', 'pendaftaran.id_paket', '=', 'paket.id')
            ->where('pembayaran.id', $id)
            ->select('pembayaran.*',
                     'pendaftaran.*',
                     'pengguna.nama as user_nama',
                     'pengguna.email as user_email',
                     'lomba.nama as event_nama',
                     'paket.nama as package_name',
                     'paket.harga as package_price')
            ->first();
            
        if (!$payment) {
            return redirect()->route('admin.payments.index')->with('error', 'Pembayaran tidak ditemukan');
        }
        
        return view('admin.payments.view', compact('payment'));
    }

    /**
     * Payment verify
     */
    public function paymentsVerify($id)
    {
        DB::table('pembayaran')->where('id', $id)->update([
            'status' => 'terverifikasi',
            'tanggal_verifikasi' => now(),
            'diverifikasi_oleh' => session('user_id'),
            'updated_at' => now()
        ]);
        
        $payment = DB::table('pembayaran')->where('id', $id)->first();
        if ($payment) {
            DB::table('pendaftaran')->where('id', $payment->id_pendaftaran)->update([
                'status_pembayaran' => 'lunas',
                'updated_at' => now()
            ]);
        }
        
        return back()->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    /**
     * Payment reject
     */
    public function paymentsReject($id)
    {
        DB::table('pembayaran')->where('id', $id)->update([
            'status' => 'ditolak',
            'tanggal_verifikasi' => now(),
            'diverifikasi_oleh' => session('user_id'),
            'updated_at' => now()
        ]);
        
        return back()->with('success', 'Pembayaran berhasil ditolak!');
    }

    // ========== LOG ACTIVITY ==========

    /**
     * Logs index
     */
    public function logsIndex()
    {
        $logs = DB::table('log_aktivitas')
            ->join('pengguna', 'log_aktivitas.user_id', '=', 'pengguna.id')
            ->select('log_aktivitas.*', 'pengguna.nama as user_nama')
            ->orderBy('log_aktivitas.created_at', 'desc')
            ->paginate(20);
            
        return view('admin.logs.index', compact('logs'));
    }

    // ========== ADMIN PROFILE ==========

    /**
     * Profile index
     */
    public function profileIndex()
    {
        $user = DB::table('pengguna')->where('id', session('user_id'))->first();
        
        // Get admin statistics
        $stats = [
            'total_users' => DB::table('pengguna')->count(),
            'total_events' => DB::table('lomba')->count(),
            'total_registrations' => DB::table('pendaftaran')->count(),
            'pending_payments' => DB::table('pembayaran')->where('status', 'menunggu')->count(),
            'pending_registrations' => DB::table('pendaftaran')->where('status_pendaftaran', 'menunggu')->count(),
            'total_revenue' => DB::table('pembayaran')->where('status', 'terverifikasi')->sum('jumlah'),
            'active_staff' => DB::table('pengguna')->where('peran', 'staff')->where('is_active', 1)->count(),
            'active_participants' => DB::table('pengguna')->where('peran', 'peserta')->where('is_active', 1)->count(),
            'total_packages' => DB::table('paket')->count(),
            'verified_payments' => DB::table('pembayaran')->where('status', 'terverifikasi')->count(),
        ];
        
        // Get admin activity
        $admin_activities = DB::table('log_aktivitas')
            ->join('pengguna', 'log_aktivitas.user_id', '=', 'pengguna.id')
            ->where('log_aktivitas.user_id', session('user_id'))
            ->select('log_aktivitas.*', 'pengguna.nama as user_nama')
            ->orderBy('log_aktivitas.created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get recent verifications by this admin
        $recent_verifications = DB::table('pembayaran')
            ->join('pendaftaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')
            ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
            ->where('pembayaran.diverifikasi_oleh', session('user_id'))
            ->where('pembayaran.status', 'terverifikasi')
            ->select('pembayaran.*', 'pendaftaran.nama_lengkap', 'pengguna.nama as user_nama')
            ->orderBy('pembayaran.tanggal_verifikasi', 'desc')
            ->limit(5)
            ->get();
        
        // Calculate admin performance
        $performance = [
            'registrations_today' => DB::table('pendaftaran')
                ->whereDate('created_at', today())
                ->count(),
            'verifications_today' => DB::table('pembayaran')
                ->whereDate('tanggal_verifikasi', today())
                ->where('diverifikasi_oleh', session('user_id'))
                ->count(),
            'verifications_month' => DB::table('pembayaran')
                ->whereMonth('tanggal_verifikasi', date('m'))
                ->whereYear('tanggal_verifikasi', date('Y'))
                ->where('diverifikasi_oleh', session('user_id'))
                ->count(),
        ];
        
        return view('admin.profile', compact(
            'user', 
            'stats', 
            'admin_activities',
            'recent_verifications',
            'performance'
        ));
    }

    /**
     * Profile update
     */
    public function profileUpdate(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);
        
        $user = DB::table('pengguna')->where('id', session('user_id'))->first();
        
        // Check current password if changing password
        if ($request->new_password) {
            if (!$request->current_password || !Hash::check($request->current_password, $user->password)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password saat ini salah!'
                    ], 422);
                }
                return redirect()->back()->with('error', 'Password saat ini salah!');
            }
        }
        
        $data = [
            'nama' => $validated['nama'],
            'telepon' => $validated['telepon'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'updated_at' => now(),
        ];
        
        // Handle password change
        if ($request->new_password) {
            $data['password'] = Hash::make($validated['new_password']);
        }
        
        // Handle photo upload
        $photoPath = $user->foto_profil;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'admin_' . session('user_id') . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('admin/profiles', $filename, 'public');
            $data['foto_profil'] = $path;
            
            // Delete old photo if exists
            if ($photoPath && Storage::exists('public/' . $photoPath)) {
                Storage::delete('public/' . $photoPath);
            }
        }
        
        // Update user data
        DB::table('pengguna')->where('id', session('user_id'))->update($data);
        
        // Update session
        session(['user_nama' => $validated['nama']]);
        
        // Log activity
        DB::table('log_aktivitas')->insert([
            'user_id' => session('user_id'),
            'aksi' => 'update_admin_profile',
            'deskripsi' => 'Admin memperbarui profil' . ($request->hasFile('photo') ? ' dengan foto baru' : ''),
            'tabel_terkait' => 'pengguna',
            'id_terkait' => session('user_id'),
            'created_at' => now()
        ]);
        
        // Get updated user data
        $updatedUser = DB::table('pengguna')->where('id', session('user_id'))->first();
        
        // Return response based on request type
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil admin berhasil diperbarui!',
                'user_name' => $updatedUser->nama,
                'photo_url' => $updatedUser->foto_profil ? Storage::url($updatedUser->foto_profil) : null
            ]);
        }
        
        return redirect()->route('admin.profile')->with('success', 'Profil admin berhasil diperbarui!');
    }
}