<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    /**
     * Constructor - cek akses staff
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!session('is_logged_in')) {
                return redirect()->route('login')->with('error', 'Harap login terlebih dahulu!');
            }
            
            if (!in_array(session('user_peran'), ['staff', 'admin', 'superadmin'])) {
                return redirect()->route('home')->with('error', 'Akses ditolak! Hanya untuk staff.');
            }
            
            return $next($request);
        });
    }

    /**
     * Dashboard Staff
     */
    public function dashboard()
    {
        // 1. Ambil data untuk tabel pendaftaran
        $registrations = DB::table('pendaftaran')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->join('pembayaran', 'pendaftaran.id', '=', 'pembayaran.id_pendaftaran')
            ->select(
                'pendaftaran.*', 
                'lomba.nama as nama_event', 
                'pembayaran.metode_pembayaran', 
                'pembayaran.jumlah', 
                'pembayaran.status as status_bayar'
            )
            ->orderBy('pendaftaran.created_at', 'desc')
            ->get();

        // 2. Ambil data untuk Statistik Dashboard
        $total_events = DB::table('lomba')->count();
        $total_registrations = DB::table('pendaftaran')->count();
        $pending_registrations = DB::table('pendaftaran')->where('status_pendaftaran', 'menunggu')->count();
        $today_registrations = DB::table('pendaftaran')->whereDate('created_at', today())->count();
        $active_events = DB::table('lomba')->where('status', 'mendatang')->count();
        $total_revenue = DB::table('pembayaran')->where('status', 'terverifikasi')->sum('jumlah');

        // 3. Ambil data untuk Sidebar (Aktivitas Terakhir)
        $recent_registrations = $registrations->take(5);

        // 4. Kirim semua variabel ke view
        return view('staff.dashboard', compact(
            'registrations', 
            'total_events', 
            'total_registrations', 
            'pending_registrations', 
            'today_registrations',
            'active_events',
            'total_revenue',
            'recent_registrations'
        ));
    }

    /**
     * Dashboard alternatif (untuk route /staff/dashboard)
     */
    public function dashboardAlt()
    {
        $total_events = DB::table('lomba')->count();
        $total_registrations = DB::table('pendaftaran')->count();
        $pending_registrations = DB::table('pendaftaran')->where('status_pendaftaran', 'menunggu')->count();
        $pending_payments = DB::table('pembayaran')->where('status', 'menunggu')->count();
        $total_revenue = DB::table('pembayaran')->where('status', 'terverifikasi')->sum('jumlah');
        $active_events = DB::table('lomba')->where('status', 'mendatang')->count();
        $today_registrations = DB::table('pendaftaran')
            ->whereDate('created_at', today())
            ->count();
        
        $upcoming_events = DB::table('lomba')
            ->where('status', 'mendatang')
            ->orderBy('tanggal', 'asc')
            ->limit(5)
            ->get();
            
        $recent_registrations = DB::table('pendaftaran')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->select('pendaftaran.*', 'lomba.nama as event_nama')
            ->orderBy('pendaftaran.created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('staff.dashboard', compact(
            'total_events',
            'total_registrations',
            'pending_registrations',
            'pending_payments',
            'total_revenue',
            'active_events',
            'today_registrations',
            'upcoming_events',
            'recent_registrations'
        ));
    }

    /**
     * Verifikasi pembayaran cash
     */
    public function verifyCashPayment($id)
    {
        DB::beginTransaction();
        try {
            // 1. Update Tabel Pembayaran
            DB::table('pembayaran')
                ->where('id_pendaftaran', $id)
                ->update([
                    'status' => 'terverifikasi',
                    'diverifikasi_oleh' => session('user_id'),
                    'tanggal_verifikasi' => now(),
                    'updated_at' => now()
                ]);

            // 2. Update Tabel Pendaftaran
            DB::table('pendaftaran')
                ->where('id', $id)
                ->update([
                    'status_pembayaran' => 'lunas',
                    'status_pendaftaran' => 'disetujui',
                    'updated_at' => now()
                ]);

            DB::commit();
            return back()->with('success', 'Pembayaran Cash berhasil diterima & diverifikasi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Index registrations
     */
    public function registrationsIndex(Request $request)
    {
        $status = $request->status;
        $search = $request->search;
        $event_id = $request->event_id;
        
        $registrations = DB::table('pendaftaran')
            ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->join('paket', 'pendaftaran.id_paket', '=', 'paket.id')
            ->select(
                'pendaftaran.*',
                'pengguna.nama as user_nama', 
                'lomba.nama as event_nama',
                'paket.nama as package_name'
            )
            ->orderBy('pendaftaran.created_at', 'desc')
            ->paginate(15);
                    
        $stats = [
            'total' => DB::table('pendaftaran')->count(),
            'pending' => DB::table('pendaftaran')->where('status_pendaftaran', 'menunggu')->count(),
            'approved' => DB::table('pendaftaran')->where('status_pendaftaran', 'disetujui')->count(),
            'rejected' => DB::table('pendaftaran')->where('status_pendaftaran', 'ditolak')->count(),
        ];
        
        $events = DB::table('lomba')->where('status', 'mendatang')->get();
        
        return view('staff.registrations.index', compact('registrations', 'stats', 'events'));
    }

    /**
     * View registration detail
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
                     'lomba.id as lomba_id',
                     'lomba.nama as event_nama',
                     'lomba.tanggal as event_date',
                     'lomba.lokasi as event_lokasi',
                     'lomba.kategori as event_kategori',
                     'paket.nama as package_name',
                     'paket.harga as package_price',
                     'paket.termasuk_race_kit',
                     'paket.termasuk_medali',
                     'paket.termasuk_kaos',
                     'paket.termasuk_sertifikat',
                     'paket.termasuk_snack')
            ->first();
            
        if (!$registration) {
            return redirect()->route('staff.registrations.index')->with('error', 'Pendaftaran tidak ditemukan');
        }
        
        $payment = DB::table('pembayaran')
            ->where('id_pendaftaran', $id)
            ->first();
        
        return view('staff.registrations.view', compact('registration', 'payment'));
    }

    /**
     * Approve registration
     */
    public function registrationsApprove($id)
    {
        try {
            DB::beginTransaction();
            
            DB::table('pendaftaran')->where('id', $id)->update([
                'status' => 'disetujui',
                'status_pendaftaran' => 'disetujui',
                'updated_at' => now()
            ]);
            
            $registration = DB::table('pendaftaran')->where('id', $id)->first();
            if (!$registration->nomor_start && $registration->id_lomba) {
                $event = DB::table('lomba')->where('id', $registration->id_lomba)->first();
                $prefix = strtoupper(substr($event->kategori ?? 'RUN', 0, 3));
                $nomor_start = $prefix . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);
                
                DB::table('pendaftaran')->where('id', $id)->update([
                    'nomor_start' => $nomor_start
                ]);
            }
            
            // Create notification
            DB::table('notifikasi')->insert([
                'user_id' => $registration->id_pengguna,
                'pendaftaran_id' => $id,
                'jenis' => 'pendaftaran_baru',
                'judul' => 'Pendaftaran Disetujui',
                'pesan' => 'Pendaftaran Anda untuk event telah disetujui. Nomor start: ' . ($nomor_start ?? ''),
                'tautan' => '/profile',
                'created_at' => now()
            ]);
            
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'verifikasi_pendaftaran_staff',
                'deskripsi' => 'Staff menyetujui pendaftaran ID: ' . $id,
                'tabel_terkait' => 'pendaftaran',
                'id_terkait' => $id,
                'created_at' => now()
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Pendaftaran berhasil disetujui!' . (isset($nomor_start) ? ' Nomor start: ' . $nomor_start : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyetujui pendaftaran: ' . $e->getMessage());
        }
    }

    /**
     * Reject registration
     */
    public function registrationsReject(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|max:500'
        ]);
        
        try {
            DB::beginTransaction();
            
            $registration = DB::table('pendaftaran')->where('id', $id)->first();
            
            DB::table('pendaftaran')->where('id', $id)->update([
                'status' => 'ditolak',
                'status_pendaftaran' => 'ditolak',
                'alasan_pembatalan' => $request->alasan,
                'updated_at' => now()
            ]);
            
            // Create notification
            DB::table('notifikasi')->insert([
                'user_id' => $registration->id_pengguna,
                'pendaftaran_id' => $id,
                'jenis' => 'peserta_batal',
                'judul' => 'Pendaftaran Ditolak',
                'pesan' => 'Pendaftaran Anda ditolak. Alasan: ' . $request->alasan,
                'tautan' => '/profile',
                'created_at' => now()
            ]);
            
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'tolak_pendaftaran_staff',
                'deskripsi' => 'Staff menolak pendaftaran ID: ' . $id . ' - Alasan: ' . $request->alasan,
                'tabel_terkait' => 'pendaftaran',
                'id_terkait' => $id,
                'created_at' => now()
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Pendaftaran berhasil ditolak!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menolak pendaftaran: ' . $e->getMessage());
        }
    }

    /**
     * Payments index
     */
    public function paymentsIndex(Request $request)
    {
        $status = $request->status;
        $search = $request->search;
        
        $payments = DB::table('pembayaran')
            ->join('pendaftaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')
            ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->select('pembayaran.*',
                     'pendaftaran.nomor_start',
                     'pendaftaran.nama_lengkap as peserta_nama',
                     'pengguna.nama as user_nama',
                     'lomba.nama as event_nama',
                     'lomba.tanggal as event_date');
        
        if ($status && in_array($status, ['menunggu', 'terverifikasi', 'ditolak'])) {
            $payments->where('pembayaran.status', $status);
        }
        
        if ($search) {
            $payments->where(function($query) use ($search) {
                $query->where('pembayaran.kode_pembayaran', 'like', "%{$search}%")
                      ->orWhere('pendaftaran.nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('pengguna.nama', 'like', "%{$search}%")
                      ->orWhere('lomba.nama', 'like', "%{$search}%");
            });
        }
        
        $payments = $payments->orderBy('pembayaran.created_at', 'desc')->paginate(15);
            
        $stats = [
            'total' => DB::table('pembayaran')->count(),
            'pending' => DB::table('pembayaran')->where('status', 'menunggu')->count(),
            'verified' => DB::table('pembayaran')->where('status', 'terverifikasi')->count(),
            'rejected' => DB::table('pembayaran')->where('status', 'ditolak')->count(),
            'total_amount' => DB::table('pembayaran')->where('status', 'terverifikasi')->sum('jumlah'),
        ];
        
        return view('staff.payments.index', compact('payments', 'stats'));
    }

    /**
     * Payment view
     */
    public function paymentsView($id)
    {
        $payment = DB::table('pembayaran')
            ->join('pendaftaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->join('paket', 'pendaftaran.id_paket', '=', 'paket.id')
            ->where('pembayaran.id', $id)
            ->select('pembayaran.*',
                     'pendaftaran.*',
                     'pendaftaran.nama_lengkap as peserta_nama',
                     'lomba.nama as event_nama',
                     'lomba.tanggal as event_date',
                     'paket.nama as package_name',
                     'paket.harga as package_price')
            ->first();
            
        if (!$payment) {
            return redirect()->route('staff.payments.index')->with('error', 'Pembayaran tidak ditemukan');
        }
        
        return view('staff.payments.view', compact('payment'));
    }

    /**
     * Verify payment
     */
    public function paymentsVerify(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500'
        ]);
        
        try {
            DB::beginTransaction();
            
            $payment = DB::table('pembayaran')->where('id', $id)->first();
            
            DB::table('pembayaran')->where('id', $id)->update([
                'status' => 'terverifikasi',
                'tanggal_verifikasi' => now(),
                'diverifikasi_oleh' => session('user_id'),
                'catatan_admin' => $request->catatan,
                'updated_at' => now()
            ]);
            
            if ($payment) {
                DB::table('pendaftaran')->where('id', $payment->id_pendaftaran)->update([
                    'status_pembayaran' => 'lunas',
                    'updated_at' => now()
                ]);
                
                $registration = DB::table('pendaftaran')->where('id', $payment->id_pendaftaran)->first();
                
                // Send notification
                DB::table('notifikasi')->insert([
                    'user_id' => $registration->id_pengguna,
                    'pendaftaran_id' => $payment->id_pendaftaran,
                    'jenis' => 'pembayaran_terverifikasi',
                    'judul' => 'Pembayaran Diverifikasi',
                    'pesan' => 'Pembayaran Anda dengan kode ' . $payment->kode_pembayaran . ' telah diverifikasi.',
                    'tautan' => '/profile',
                    'created_at' => now()
                ]);
            }
            
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'verifikasi_pembayaran_staff',
                'deskripsi' => 'Staff memverifikasi pembayaran ID: ' . $id,
                'tabel_terkait' => 'pembayaran',
                'id_terkait' => $id,
                'created_at' => now()
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Reject payment
     */
    public function paymentsReject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:500'
        ]);
        
        try {
            DB::beginTransaction();
            
            $payment = DB::table('pembayaran')->where('id', $id)->first();
            
            DB::table('pembayaran')->where('id', $id)->update([
                'status' => 'ditolak',
                'tanggal_verifikasi' => now(),
                'diverifikasi_oleh' => session('user_id'),
                'catatan_admin' => $request->catatan,
                'updated_at' => now()
            ]);
            
            if ($payment) {
                $registration = DB::table('pendaftaran')->where('id', $payment->id_pendaftaran)->first();
                
                // Send notification
                DB::table('notifikasi')->insert([
                    'user_id' => $registration->id_pengguna,
                    'pendaftaran_id' => $payment->id_pendaftaran,
                    'jenis' => 'pembayaran_baru',
                    'judul' => 'Pembayaran Ditolak',
                    'pesan' => 'Pembayaran Anda ditolak. Alasan: ' . $request->catatan,
                    'tautan' => '/profile',
                    'created_at' => now()
                ]);
            }
            
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'tolak_pembayaran_staff',
                'deskripsi' => 'Staff menolak pembayaran ID: ' . $id . ' - Alasan: ' . $request->catatan,
                'tabel_terkait' => 'pembayaran',
                'id_terkait' => $id,
                'created_at' => now()
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Pembayaran berhasil ditolak!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Packages index
     */
    public function packagesIndex()
    {
        $packages = DB::table('paket')
            ->join('lomba', 'paket.lomba_id', '=', 'lomba.id')
            ->select('paket.*', 'lomba.nama as event_nama', 'lomba.tanggal')
            ->orderBy('paket.created_at', 'desc')
            ->paginate(15);
            
        $stats = [
            'total' => DB::table('paket')->count(),
            'with_race_kit' => DB::table('paket')->where('termasuk_race_kit', 1)->count(),
            'with_medal' => DB::table('paket')->where('termasuk_medali', 1)->count(),
            'with_shirt' => DB::table('paket')->where('termasuk_kaos', 1)->count(),
        ];
        
        return view('staff.packages.index', compact('packages', 'stats'));
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
            
        return view('staff.packages.create', compact('events'));
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
            'termasuk_race_kit' => 'nullable|boolean',
            'termasuk_medali' => 'nullable|boolean',
            'termasuk_kaos' => 'nullable|boolean',
            'termasuk_sertifikat' => 'nullable|boolean',
            'termasuk_snack' => 'nullable|boolean',
        ]);
        
        DB::table('paket')->insert([
            'lomba_id' => $validated['lomba_id'],
            'nama' => $validated['nama'],
            'harga' => $validated['harga'],
            'termasuk_race_kit' => $validated['termasuk_race_kit'] ?? 0,
            'termasuk_medali' => $validated['termasuk_medali'] ?? 0,
            'termasuk_kaos' => $validated['termasuk_kaos'] ?? 0,
            'termasuk_sertifikat' => $validated['termasuk_sertifikat'] ?? 1,
            'termasuk_snack' => $validated['termasuk_snack'] ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('staff.packages.index')->with('success', 'Paket berhasil ditambahkan!');
    }

    /**
     * Package edit form
     */
    public function packagesEdit($id)
    {
        $package = DB::table('paket')->where('id', $id)->first();
        
        if (!$package) {
            return redirect()->route('staff.packages.index')->with('error', 'Paket tidak ditemukan');
        }
        
        $events = DB::table('lomba')
            ->where('status', 'mendatang')
            ->orderBy('tanggal', 'asc')
            ->get();
            
        return view('staff.packages.edit', compact('package', 'events'));
    }

    /**
     * Package update
     */
    public function packagesUpdate(Request $request, $id)
    {
        $package = DB::table('paket')->where('id', $id)->first();
        
        if (!$package) {
            return redirect()->route('staff.packages.index')->with('error', 'Paket tidak ditemukan');
        }
        
        $validated = $request->validate([
            'lomba_id' => 'required|exists:lomba,id',
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'termasuk_race_kit' => 'nullable|boolean',
            'termasuk_medali' => 'nullable|boolean',
            'termasuk_kaos' => 'nullable|boolean',
            'termasuk_sertifikat' => 'nullable|boolean',
            'termasuk_snack' => 'nullable|boolean',
        ]);
        
        DB::table('paket')->where('id', $id)->update([
            'lomba_id' => $validated['lomba_id'],
            'nama' => $validated['nama'],
            'harga' => $validated['harga'],
            'termasuk_race_kit' => $validated['termasuk_race_kit'] ?? 0,
            'termasuk_medali' => $validated['termasuk_medali'] ?? 0,
            'termasuk_kaos' => $validated['termasuk_kaos'] ?? 0,
            'termasuk_sertifikat' => $validated['termasuk_sertifikat'] ?? 1,
            'termasuk_snack' => $validated['termasuk_snack'] ?? 1,
            'updated_at' => now(),
        ]);
        
        return redirect()->route('staff.packages.index')->with('success', 'Paket berhasil diperbarui!');
    }

    /**
     * Profile index
     */
    public function profileIndex()
    {
        $user = DB::table('pengguna')
            ->where('id', session('user_id'))
            ->first();
        
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
            'recent_activities' => DB::table('log_aktivitas')
                ->where('user_id', session('user_id'))
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->count(),
        ];
        
        // Get recent activities
        $activities = DB::table('log_aktivitas')
            ->where('user_id', session('user_id'))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('staff.profile.index', compact('user', 'stats', 'activities'));
    }

    /**
     * Profile edit form
     */
    public function profileEdit()
    {
        $user = DB::table('pengguna')
            ->where('id', session('user_id'))
            ->first();
        
        return view('staff.profile.edit', compact('user'));
    }

    /**
     * Profile update
     */
    public function profileUpdate(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'current_password' => 'nullable|string|min:6',
            'new_password' => 'nullable|string|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);
        
        $user = DB::table('pengguna')
            ->where('id', session('user_id'))
            ->first();
        
        // Check current password if changing password
        if ($request->new_password) {
            if (!$request->current_password || !Hash::check($request->current_password, $user->password)) {
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
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'staff_' . session('user_id') . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('staff/profiles', $filename, 'public');
            $data['foto_profil'] = $path;
            
            // Update session
            session(['user_foto' => $path]);
        }
        
        // Update user data
        DB::table('pengguna')
            ->where('id', session('user_id'))
            ->update($data);
        
        // Update session name
        session(['user_nama' => $validated['nama']]);
        
        // Log activity
        DB::table('log_aktivitas')->insert([
            'user_id' => session('user_id'),
            'aksi' => 'update_staff_profile',
            'deskripsi' => 'Staff memperbarui profil',
            'tabel_terkait' => 'pengguna',
            'id_terkait' => session('user_id'),
            'created_at' => now()
        ]);
        
        return redirect()->route('staff.profile.index')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Results index
     */
    public function resultsIndex(Request $request)
    {
        $event_id = $request->event_id;
        $search = $request->search;
        
        $results = DB::table('hasil_lomba')
            ->join('pendaftaran', 'hasil_lomba.pendaftaran_id', '=', 'pendaftaran.id')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
            ->select('hasil_lomba.*',
                    'pendaftaran.nomor_start',
                    'pendaftaran.nama_lengkap',
                    'lomba.nama as event_nama',
                    'lomba.tanggal as event_date',
                    'pengguna.email as peserta_email');
        
        if ($event_id) {
            $results->where('lomba.id', $event_id);
        }
        
        if ($search) {
            $results->where(function($query) use ($search) {
                $query->where('pendaftaran.nomor_start', 'like', "%{$search}%")
                      ->orWhere('pendaftaran.nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('lomba.nama', 'like', "%{$search}%");
            });
        }
        
        $results = $results->orderBy('hasil_lomba.waktu_tempuh', 'asc')
                          ->paginate(20);
        
        $events = DB::table('lomba')
            ->where('status', 'selesai')
            ->orWhere('status', 'berlangsung')
            ->orderBy('tanggal', 'desc')
            ->get();
        
        return view('staff.results.index', compact('results', 'events'));
    }

    /**
     * Results create form
     */
    public function resultsCreate()
    {
        $events = DB::table('lomba')
            ->where('status', 'selesai')
            ->orWhere('status', 'berlangsung')
            ->orderBy('tanggal', 'desc')
            ->get();
        
        return view('staff.results.create', compact('events'));
    }

    /**
     * Results store
     */
    public function resultsStore(Request $request)
    {
        $validated = $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id',
            'waktu_tempuh' => 'required|string|max:20',
            'catatan_waktu' => 'nullable|string|max:100',
            'peringkat' => 'required|integer|min:1',
            'kategori_usia' => 'required|string|max:50',
            'kecepatan_rata_rata' => 'nullable|numeric',
            'catatan' => 'nullable|string|max:500',
            'status_hasil' => 'required|in:resmi,sementara,disqualifikasi',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Cek apakah sudah ada hasil untuk pendaftaran ini
            $existing_result = DB::table('hasil_lomba')
                ->where('pendaftaran_id', $validated['pendaftaran_id'])
                ->first();
            
            if ($existing_result) {
                return back()->with('error', 'Hasil untuk peserta ini sudah ada!');
            }
            
            DB::table('hasil_lomba')->insert([
                'pendaftaran_id' => $validated['pendaftaran_id'],
                'waktu_tempuh' => $validated['waktu_tempuh'],
                'catatan_waktu' => $validated['catatan_waktu'],
                'peringkat' => $validated['peringkat'],
                'kategori_usia' => $validated['kategori_usia'],
                'kecepatan_rata_rata' => $validated['kecepatan_rata_rata'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
                'status_hasil' => $validated['status_hasil'],
                'dibuat_oleh' => session('user_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $registration = DB::table('pendaftaran')
                ->where('id', $validated['pendaftaran_id'])
                ->first();
            
            if ($registration) {
                // Update status pendaftaran
                DB::table('pendaftaran')
                    ->where('id', $validated['pendaftaran_id'])
                    ->update([
                        'status' => 'selesai',
                        'updated_at' => now()
                    ]);
                
                // Create notification for participant
                DB::table('notifikasi')->insert([
                    'user_id' => $registration->id_pengguna,
                    'pendaftaran_id' => $validated['pendaftaran_id'],
                    'jenis' => 'hasil_lomba',
                    'judul' => 'Hasil Lomba Tersedia',
                    'pesan' => 'Hasil lomba untuk nomor start ' . $registration->nomor_start . ' telah tersedia.',
                    'tautan' => '/profile',
                    'created_at' => now()
                ]);
            }
            
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'tambah_hasil_lomba',
                'deskripsi' => 'Menambahkan hasil lomba untuk pendaftaran ID: ' . $validated['pendaftaran_id'],
                'tabel_terkait' => 'hasil_lomba',
                'id_terkait' => DB::getPdo()->lastInsertId(),
                'created_at' => now()
            ]);
            
            DB::commit();
            
            return redirect()->route('staff.results.index')->with('success', 'Hasil lomba berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan hasil lomba: ' . $e->getMessage());
        }
    }

    /**
     * Results edit form
     */
    public function resultsEdit($id)
    {
        $result = DB::table('hasil_lomba')
            ->join('pendaftaran', 'hasil_lomba.pendaftaran_id', '=', 'pendaftaran.id')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->where('hasil_lomba.id', $id)
            ->select('hasil_lomba.*',
                    'pendaftaran.nomor_start',
                    'pendaftaran.nama_lengkap',
                    'pendaftaran.id_lomba',
                    'lomba.nama as event_nama')
            ->first();
        
        if (!$result) {
            return redirect()->route('staff.results.index')->with('error', 'Hasil lomba tidak ditemukan');
        }
        
        $registrations = DB::table('pendaftaran')
            ->where('id_lomba', $result->id_lomba)
            ->where('status', 'disetujui')
            ->get();
        
        return view('staff.results.edit', compact('result', 'registrations'));
    }

    /**
     * Results update
     */
    public function resultsUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'waktu_tempuh' => 'required|string|max:20',
            'catatan_waktu' => 'nullable|string|max:100',
            'peringkat' => 'required|integer|min:1',
            'kategori_usia' => 'required|string|max:50',
            'kecepatan_rata_rata' => 'nullable|numeric',
            'catatan' => 'nullable|string|max:500',
            'status_hasil' => 'required|in:resmi,sementara,disqualifikasi',
        ]);
        
        $result = DB::table('hasil_lomba')->where('id', $id)->first();
        
        if (!$result) {
            return redirect()->route('staff.results.index')->with('error', 'Hasil lomba tidak ditemukan');
        }
        
        DB::table('hasil_lomba')->where('id', $id)->update([
            'waktu_tempuh' => $validated['waktu_tempuh'],
            'catatan_waktu' => $validated['catatan_waktu'],
            'peringkat' => $validated['peringkat'],
            'kategori_usia' => $validated['kategori_usia'],
            'kecepatan_rata_rata' => $validated['kecepatan_rata_rata'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
            'status_hasil' => $validated['status_hasil'],
            'updated_at' => now(),
        ]);
        
        DB::table('log_aktivitas')->insert([
            'user_id' => session('user_id'),
            'aksi' => 'edit_hasil_lomba',
            'deskripsi' => 'Mengedit hasil lomba ID: ' . $id,
            'tabel_terkait' => 'hasil_lomba',
            'id_terkait' => $id,
            'created_at' => now()
        ]);
        
        return redirect()->route('staff.results.index')->with('success', 'Hasil lomba berhasil diperbarui!');
    }

    /**
     * Results destroy
     */
    public function resultsDestroy($id)
    {
        $result = DB::table('hasil_lomba')->where('id', $id)->first();
        
        if (!$result) {
            return redirect()->route('staff.results.index')->with('error', 'Hasil lomba tidak ditemukan');
        }
        
        DB::table('hasil_lomba')->where('id', $id)->delete();
        
        // Update registration status back to approved
        DB::table('pendaftaran')
            ->where('id', $result->pendaftaran_id)
            ->update([
                'status' => 'disetujui',
                'updated_at' => now()
            ]);
        
        DB::table('log_aktivitas')->insert([
            'user_id' => session('user_id'),
            'aksi' => 'hapus_hasil_lomba',
            'deskripsi' => 'Menghapus hasil lomba ID: ' . $id,
            'tabel_terkait' => 'hasil_lomba',
            'id_terkait' => $id,
            'created_at' => now()
        ]);
        
        return redirect()->route('staff.results.index')->with('success', 'Hasil lomba berhasil dihapus!');
    }

    /**
     * Results by event
     */
    public function resultsByEvent($event_id)
    {
        $results = DB::table('hasil_lomba')
            ->join('pendaftaran', 'hasil_lomba.pendaftaran_id', '=', 'pendaftaran.id')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
            ->where('lomba.id', $event_id)
            ->select('hasil_lomba.*',
                    'pendaftaran.nomor_start',
                    'pendaftaran.nama_lengkap',
                    'pendaftaran.tanggal_lahir',
                    'pendaftaran.jenis_kelamin',
                    'pengguna.email as peserta_email',
                    'pengguna.telepon as peserta_telepon')
            ->orderBy('hasil_lomba.peringkat', 'asc')
            ->get();
        
        $event = DB::table('lomba')->where('id', $event_id)->first();
        
        return view('staff.results.event-results', compact('results', 'event'));
    }

    /**
     * Export index
     */
    public function exportIndex()
    {
        $events = DB::table('lomba')->get();
        return view('staff.export.index', compact('events'));
    }
}