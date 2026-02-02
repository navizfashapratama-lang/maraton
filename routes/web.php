<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;

// ==================== PUBLIC ROUTES ====================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ==================== AUTH ROUTES ====================
Route::get('/login', function () {
    if (session('is_logged_in')) {
        $role = session('user_peran');
        if (in_array($role, ['admin', 'superadmin'])) {
            return redirect()->route('admin.dashboard');
        } elseif ($role == 'staff') {
            return redirect()->route('staff.dashboard');
        } elseif ($role == 'kasir') {
            return redirect()->route('kasir.dashboard');
        }
        return redirect()->route('home');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    
    $user = DB::table('pengguna')->where('email', $request->email)->first();
    
    if (!$user) {
        return back()->with('error', 'Email tidak ditemukan!');
    }
    
    // Cek jika user aktif
    if (!$user->is_active) {
        return back()->with('error', 'Akun Anda tidak aktif. Silakan hubungi admin.');
    }
    
    if (Hash::check($request->password, $user->password)) {
        DB::table('pengguna')->where('id', $user->id)->update([
            'terakhir_login' => now()
        ]);
        
        session([
            'user_id' => $user->id,
            'user_nama' => $user->nama,
            'user_email' => $user->email,
            'user_peran' => $user->peran,
            'user_telp' => $user->telepon,
            'user_alamat' => $user->alamat,
            'is_logged_in' => true
        ]);
        
        // Cek jika ada redirect parameter
        $redirect = $request->input('redirect');
        $paket = $request->input('paket');
        
        if ($redirect === 'event-register' && $paket) {
            // Redirect ke form pendaftaran dengan paket
            return redirect()->route('continue.registration', ['paket' => $paket]);
        }
        
        // Redirect sesuai role
        if (in_array($user->peran, ['admin', 'superadmin'])) {
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
        } elseif ($user->peran == 'staff') {
            return redirect()->route('staff.dashboard')->with('success', 'Login berhasil sebagai staff!');
        } elseif ($user->peran == 'kasir') {
            return redirect()->route('kasir.dashboard')->with('success', 'Login berhasil!');
        }
        
        return redirect()->route('home')->with('success', 'Login berhasil!');
    }
    
    return back()->with('error', 'Password salah!');
});

Route::get('/register', function () {
    if (session('is_logged_in')) {
        return redirect()->route('home');
    }
    return view('auth.register');
})->name('register');

Route::post('/register', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:pengguna',
        'password' => 'required|string|min:6|confirmed',
        'telepon' => 'nullable|string|max:20',
        'alamat' => 'nullable|string',
    ], [
        'email.unique' => 'Email ini sudah terdaftar',
    ]);
    
    DB::table('pengguna')->insert([
        'nama' => $request->nama,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'peran' => 'peserta',
        'telepon' => $request->telepon,
        'alamat' => $request->alamat,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    // Login otomatis setelah registrasi
    $user = DB::table('pengguna')->where('email', $request->email)->first();
    
    if ($user) {
        session([
            'user_id' => $user->id,
            'user_nama' => $user->nama,
            'user_email' => $user->email,
            'user_peran' => $user->peran,
            'user_telp' => $user->telepon,
            'user_alamat' => $user->alamat,
            'is_logged_in' => true
        ]);
        
        // Cek jika ada paket yang dipilih sebelum registrasi
        $paket = $request->input('paket');
        
        if ($paket) {
            return redirect()->route('continue.registration', ['paket' => $paket]);
        }
    }
    
    return redirect()->route('home')->with('success', 'Registrasi berhasil!');
});

Route::post('/logout', function () {
    session()->flush();
    session()->regenerate();
    return redirect()->route('login')->with('success', 'Logout berhasil!');
})->name('logout');

Route::get('/logout', function () {
    session()->flush();
    session()->regenerate();
    return redirect()->route('login')->with('success', 'Logout berhasil!');
})->name('logout.get');

// ==================== CHECK LOGIN STATUS ====================
Route::get('/check-login-status', function () {
    return response()->json([
        'isLoggedIn' => session('is_logged_in', false),
        'userName' => session('user_nama', null),
        'userId' => session('user_id', null)
    ]);
});

// ==================== REDIRECT ROUTES ====================
Route::get('/continue-registration', function () {
    if (!session('is_logged_in')) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }
    
    $paket_id = request('paket');
    
    if (!$paket_id) {
        return redirect()->route('home')->with('error', 'Tidak ada paket yang dipilih.');
    }
    
    // Ambil data paket dari database (contoh)
    $paket = DB::table('paket')
        ->join('lomba', 'paket.lomba_id', '=', 'lomba.id')
        ->where('paket.id', $paket_id)
        ->select('paket.*', 'lomba.*', 'lomba.id as event_id')
        ->first();
    
    if (!$paket) {
        return redirect()->route('home')->with('error', 'Paket tidak ditemukan.');
    }
    
    // Check jika sudah terdaftar
    $existing_registration = DB::table('pendaftaran')
        ->where('id_pengguna', session('user_id'))
        ->where('id_lomba', $paket->event_id)
        ->first();
    
    if ($existing_registration) {
        return redirect()->route('event.detail', $paket->event_id)->with('info', 'Anda sudah terdaftar untuk event ini.');
    }
    
    return view('event-register', [
        'event' => (object)[
            'id' => $paket->event_id,
            'nama' => $paket->nama,
            'tanggal' => $paket->tanggal,
            'lokasi' => $paket->lokasi,
            'kategori' => $paket->kategori,
            'deskripsi' => $paket->deskripsi
        ],
        'packages' => [$paket]
    ]);
})->name('continue.registration');

// ==================== STAFF AREA ====================
Route::prefix('staff')->name('staff.')->group(function () {
    
    $checkStaff = function () {
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Harap login terlebih dahulu!');
        }
        
        if (!in_array(session('user_peran'), ['staff', 'admin', 'superadmin'])) {
            return redirect()->route('home')->with('error', 'Akses ditolak! Hanya untuk staff.');
        }
        
        return null;
    };
    
    // DASHBOARD STAFF
    Route::get('/dashboard', function () use ($checkStaff) {
        $check = $checkStaff();
        if ($check) return $check;
        
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
    })->name('dashboard');
    
    // ========== EVENT MANAGEMENT ==========
    Route::prefix('events')->name('events.')->group(function () use ($checkStaff) {
        
        // Gunakan EventController untuk staff
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{id}', [EventController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EventController::class, 'update'])->name('update');
        Route::delete('/{id}', [EventController::class, 'destroy'])->name('destroy');
        
        // Route tambahan dari controller
        Route::get('/export/csv', [EventController::class, 'export'])->name('export');
        Route::get('/stats', [EventController::class, 'getStats'])->name('stats');
        Route::post('/{id}/toggle-status', [EventController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/kategori/dropdown', [EventController::class, 'getKategoriDropdown'])->name('kategori.dropdown');
    });
    
    // ========== REGISTRATION MANAGEMENT ==========
    Route::prefix('registrations')->name('registrations.')->group(function () use ($checkStaff) {
        Route::get('/', function () use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
            $status = request('status');
            $search = request('search');
            $event_id = request('event_id');
            
            $registrations = DB::table('pendaftaran')
                ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
                ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
                ->join('paket', 'pendaftaran.id_paket', '=', 'paket.id')
                ->select('pendaftaran.*', 
                         'pengguna.nama as user_nama', 
                         'pengguna.email as user_email',
                         'lomba.nama as event_nama',
                         'lomba.tanggal as event_date',
                         'paket.nama as package_name',
                         'paket.harga as package_price');
            
            if ($status && in_array($status, ['menunggu', 'disetujui', 'ditolak'])) {
                $registrations->where('pendaftaran.status_pendaftaran', $status);
            }
            
            if ($event_id) {
                $registrations->where('pendaftaran.id_lomba', $event_id);
            }
            
            if ($search) {
                $registrations->where(function($query) use ($search) {
                    $query->where('pendaftaran.kode_pendaftaran', 'like', "%{$search}%")
                          ->orWhere('pendaftaran.nama_lengkap', 'like', "%{$search}%")
                          ->orWhere('pendaftaran.email', 'like', "%{$search}%")
                          ->orWhere('lomba.nama', 'like', "%{$search}%");
                });
            }
            
            $registrations = $registrations->orderBy('pendaftaran.created_at', 'desc')->paginate(15);
                
            $stats = [
                'total' => DB::table('pendaftaran')->count(),
                'pending' => DB::table('pendaftaran')->where('status_pendaftaran', 'menunggu')->count(),
                'approved' => DB::table('pendaftaran')->where('status_pendaftaran', 'disetujui')->count(),
                'rejected' => DB::table('pendaftaran')->where('status_pendaftaran', 'ditolak')->count(),
            ];
            
            $events = DB::table('lomba')->where('status', 'mendatang')->get();
            
            return view('staff.registrations.index', compact('registrations', 'stats', 'events'));
        })->name('index');
        
        Route::get('/{id}', function ($id) use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
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
                return redirect()->route('staff.registrations.index')->with('error', 'Pendaftaran tidak ditemukan');
            }
            
            $payment = DB::table('pembayaran')
                ->where('id_pendaftaran', $id)
                ->first();
            
            return view('staff.registrations.view', compact('registration', 'payment'));
        })->name('view');
        
        Route::post('/{id}/approve', function ($id) use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
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
        })->name('approve');
        
        Route::post('/{id}/reject', function (\Illuminate\Http\Request $request, $id) use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
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
        })->name('reject');
    });
    
    // ========== PAYMENT MANAGEMENT ==========
    Route::prefix('payments')->name('payments.')->group(function () use ($checkStaff) {
        Route::get('/', function () use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
            $status = request('status');
            $search = request('search');
            
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
        })->name('index');
        
        Route::get('/{id}', function ($id) use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
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
        })->name('view');
        
        Route::post('/{id}/verify', function (\Illuminate\Http\Request $request, $id) use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
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
        })->name('verify');
        
        Route::post('/{id}/reject', function (\Illuminate\Http\Request $request, $id) use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
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
        })->name('reject');
    });
    
    // ========== PACKAGE MANAGEMENT ==========
    Route::prefix('packages')->name('packages.')->group(function () use ($checkStaff) {
        Route::get('/', function () use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
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
        })->name('index');
        
        Route::get('/create', function () use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
            $events = DB::table('lomba')
                ->where('status', 'mendatang')
                ->orderBy('tanggal', 'asc')
                ->get();
                
            return view('staff.packages.create', compact('events'));
        })->name('create');
        
        Route::post('/', function (\Illuminate\Http\Request $request) use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
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
        })->name('store');
        
        Route::get('/{id}/edit', function ($id) use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
            $package = DB::table('paket')->where('id', $id)->first();
            
            if (!$package) {
                return redirect()->route('staff.packages.index')->with('error', 'Paket tidak ditemukan');
            }
            
            $events = DB::table('lomba')
                ->where('status', 'mendatang')
                ->orderBy('tanggal', 'asc')
                ->get();
                
            return view('staff.packages.edit', compact('package', 'events'));
        })->name('edit');
        
        Route::post('/{id}/update', function (\Illuminate\Http\Request $request, $id) use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
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
        })->name('update');
    });
    
    // ========== RESULTS MANAGEMENT ==========
    Route::prefix('results')->name('results.')->group(function () use ($checkStaff) {
        Route::get('/', function () use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
            $event_id = request('event_id');
            $search = request('search');
            
            $results = DB::table('hasil_lomba')
                ->join('pendaftaran', 'hasil_lomba.pendaftaran_id', '=', 'pendaftaran.id')
                ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
                ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
                ->select('hasil_lomba.*',
                         'pendaftaran.nama_lengkap',
                         'pendaftaran.nomor_start',
                         'lomba.nama as event_nama',
                         'pengguna.nama as user_nama');
            
            if ($event_id) {
                $results->where('pendaftaran.id_lomba', $event_id);
            }
            
            if ($search) {
                $results->where(function($query) use ($search) {
                    $query->where('pendaftaran.nama_lengkap', 'like', "%{$search}%")
                          ->orWhere('pendaftaran.nomor_start', 'like', "%{$search}%")
                          ->orWhere('lomba.nama', 'like', "%{$search}%");
                });
            }
            
            $results = $results->orderBy('hasil_lomba.posisi', 'asc')->paginate(15);
            
            $events = DB::table('lomba')
                ->where('status', 'selesai')
                ->orWhere('status', 'mendatang')
                ->orderBy('tanggal', 'desc')
                ->get();
            
            return view('staff.results.index', compact('results', 'events'));
        })->name('index');
        
        Route::get('/create', function () use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
            $events = DB::table('lomba')
                ->where('status', 'selesai')
                ->orderBy('tanggal', 'desc')
                ->get();
            
            $registrations = collect();
            
            if (request('event_id')) {
                $registrations = DB::table('pendaftaran')
                    ->where('id_lomba', request('event_id'))
                    ->where('status_pendaftaran', 'disetujui')
                    ->get();
            }
            
            return view('staff.results.create', compact('events', 'registrations'));
        })->name('create');
        
        Route::post('/', function (\Illuminate\Http\Request $request) use ($checkStaff) {
            $check = $checkStaff();
            if ($check) return $check;
            
            $validated = $request->validate([
                'pendaftaran_id' => 'required|exists:pendaftaran,id',
                'waktu_total' => 'required|date_format:H:i:s',
                'posisi' => 'required|integer|min:1',
                'kategori_umur' => 'nullable|string|max:20',
                'catatan' => 'nullable|string|max:500',
            ]);
            
            // Check if result already exists
            $existing = DB::table('hasil_lomba')
                ->where('pendaftaran_id', $validated['pendaftaran_id'])
                ->first();
            
            if ($existing) {
                return redirect()->back()->with('error', 'Hasil lomba untuk peserta ini sudah ada!');
            }
            
            DB::table('hasil_lomba')->insert([
                'pendaftaran_id' => $validated['pendaftaran_id'],
                'waktu_total' => $validated['waktu_total'],
                'posisi' => $validated['posisi'],
                'kategori_umur' => $validated['kategori_umur'],
                'catatan' => $validated['catatan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'tambah_hasil_lomba',
                'deskripsi' => 'Menambahkan hasil lomba untuk pendaftaran ID: ' . $validated['pendaftaran_id'],
                'tabel_terkait' => 'hasil_lomba',
                'id_terkait' => DB::getPdo()->lastInsertId(),
                'created_at' => now()
            ]);
            
            return redirect()->route('staff.results.index')->with('success', 'Hasil lomba berhasil ditambahkan!');
        })->name('store');
    });
    
    // ========== EXPORT DATA ==========
    Route::get('/export/registrations', function () use ($checkStaff) {
        $check = $checkStaff();
        if ($check) return $check;
        
        $event_id = request('event_id');
        
        $registrations = DB::table('pendaftaran')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->join('paket', 'pendaftaran.id_paket', '=', 'paket.id')
            ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
            ->select('pendaftaran.*',
                     'lomba.nama as event_nama',
                     'paket.nama as package_name',
                     'pengguna.nama as user_name',
                     'pengguna.email as user_email',
                     'pengguna.telepon as user_phone')
            ->when($event_id, function ($query) use ($event_id) {
                return $query->where('pendaftaran.id_lomba', $event_id);
            })
            ->where('pendaftaran.status_pendaftaran', 'disetujui')
            ->orderBy('pendaftaran.created_at', 'desc')
            ->get();
        
        $filename = 'registrations_' . date('Ymd_His') . '.csv';
        
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        
        $callback = function() use ($registrations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Kode Pendaftaran',
                'Nama Lengkap',
                'Email',
                'Telepon',
                'Event',
                'Paket',
                'Nomor Start',
                'Status',
                'Tanggal Daftar'
            ]);
            
            foreach ($registrations as $registration) {
                fputcsv($file, [
                    $registration->kode_pendaftaran,
                    $registration->nama_lengkap,
                    $registration->email,
                    $registration->telepon,
                    $registration->event_nama,
                    $registration->package_name,
                    $registration->nomor_start,
                    $registration->status_pendaftaran,
                    $registration->created_at
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    })->name('export.registrations');
});

// ==================== ADMIN AREA ====================
Route::prefix('admin')->name('admin.')->group(function () {
    
    $checkAdmin = function () {
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Harap login terlebih dahulu!');
        }
        
        if (!in_array(session('user_peran'), ['admin', 'superadmin'])) {
            return redirect()->route('home')->with('error', 'Akses ditolak! Hanya untuk admin.');
        }
        
        return null;
    };
    
    // DASHBOARD
    Route::get('/dashboard', function () use ($checkAdmin) {
        $check = $checkAdmin();
        if ($check) return $check;
        
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
        
        return view('admin.dashboard', [
            'total_users' => $total_users,
            'total_events' => $total_events,
            'total_registrations' => $total_registrations,
            'pending_payments' => $pending_payments,
            'pending_registrations' => $pending_registrations,
            'total_revenue' => $total_revenue,
            'recent_events' => $recent_events,
            'recent_registrations' => $recent_registrations
        ]);
    })->name('dashboard');
    
    // ========== PENGGUNA MANAGEMENT ==========
    Route::prefix('users')->name('users.')->group(function () use ($checkAdmin) {
        
        Route::get('/', function () use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
            $search = request('search');
            $role = request('role');
            
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
        })->name('index');
        
        Route::get('/create', function () use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            return view('admin.users.create');
        })->name('create');
        
        Route::post('/store', function (\Illuminate\Http\Request $request) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
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
        })->name('store');
        
        Route::get('/{id}/edit', function ($id) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
            $user = DB::table('pengguna')->where('id', $id)->first();
            if (!$user) {
                return redirect('/admin/users')->with('error', 'Pengguna tidak ditemukan');
            }
            return view('admin.users.edit', compact('user'));
        })->name('edit');
        
        Route::post('/{id}/update', function (\Illuminate\Http\Request $request, $id) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
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
        })->name('update');
        
        Route::get('/{id}/delete', function ($id) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
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
        })->name('destroy');
        
        Route::post('/{id}/toggle-status', function (\Illuminate\Http\Request $request, $id) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
            if ($id == session('user_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa mengubah status akun sendiri!'
                ]);
            }
            
            $user = DB::table('pengguna')->where('id', $id)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan'
                ]);
            }
            
            $newStatus = $user->is_active ? 0 : 1;
            
            DB::table('pengguna')->where('id', $id)->update([
                'is_active' => $newStatus,
                'updated_at' => now()
            ]);
            
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'toggle_status_user',
                'deskripsi' => 'Mengubah status pengguna ID: ' . $id . ' menjadi ' . ($newStatus ? 'aktif' : 'nonaktif'),
                'tabel_terkait' => 'pengguna',
                'id_terkait' => $id,
                'created_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah!',
                'is_active' => $newStatus
            ]);
        })->name('toggle-status');
    });
    
    // ========== EVENT MANAGEMENT ==========
    Route::prefix('events')->name('events.')->group(function () use ($checkAdmin) {
        
        // Gunakan EventController untuk admin
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{id}', [EventController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EventController::class, 'update'])->name('update');
        Route::delete('/{id}', [EventController::class, 'destroy'])->name('destroy');
        
        // Route tambahan dari controller
        Route::get('/export/csv', [EventController::class, 'export'])->name('export');
        Route::get('/stats', [EventController::class, 'getStats'])->name('stats');
        Route::post('/{id}/toggle-status', [EventController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/kategori/dropdown', [EventController::class, 'getKategoriDropdown'])->name('kategori.dropdown');
    });
    
    // ========== PAKET MANAGEMENT ==========
    Route::prefix('packages')->name('packages.')->group(function () use ($checkAdmin) {
        
        Route::get('/', function () use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
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
        })->name('index');
        
        Route::get('/create', function () use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
            $events = DB::table('lomba')
                ->where('status', 'mendatang')
                ->orderBy('tanggal', 'asc')
                ->get();
                
            return view('admin.packages.create', compact('events'));
        })->name('create');
        
        Route::post('/store', function (\Illuminate\Http\Request $request) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
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
        })->name('store');
        
        Route::get('/{id}/edit', function ($id) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
            $package = DB::table('paket')->where('id', $id)->first();
            
            if (!$package) {
                return redirect()->route('admin.packages.index')->with('error', 'Paket tidak ditemukan');
            }
            
            $events = DB::table('lomba')
                ->where('status', 'mendatang')
                ->orderBy('tanggal', 'asc')
                ->get();
                
            return view('admin.packages.edit', compact('package', 'events'));
        })->name('edit');
        
        Route::post('/{id}/update', function (\Illuminate\Http\Request $request, $id) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
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
        })->name('update');
        
        Route::get('/{id}/delete', function ($id) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
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
        })->name('destroy');
    });
    
    // ========== REGISTRATION MANAGEMENT ==========
    Route::prefix('registrations')->name('registrations.')->group(function () use ($checkAdmin) {
        
        Route::get('/', function () use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
            $status = request('status');
            $search = request('search');
            
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
            
            if (request()->ajax()) {
                return response()->json([
                    'registrations' => $registrations,
                    'stats' => $stats
                ]);
            }
            
            return view('admin.registrations.index', compact('registrations', 'stats'));
        })->name('index');
        
        Route::get('/{id}/view', function ($id) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
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
        })->name('view');
        
        Route::post('/{id}/approve', function ($id) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
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
                    $nomor_start = strtoupper(substr($event->kategori ?? 'RUN', 0, 3)) . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);
                    
                    DB::table('pendaftaran')->where('id', $id)->update([
                        'nomor_start' => $nomor_start
                    ]);
                }
                
                DB::table('log_aktivitas')->insert([
                    'user_id' => session('user_id'),
                    'aksi' => 'verifikasi_pendaftaran',
                    'deskripsi' => 'Menyetujui pendaftaran ID: ' . $id,
                    'tabel_terkait' => 'pendaftaran',
                    'id_terkait' => $id,
                    'created_at' => now()
                ]);
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Pendaftaran berhasil disetujui! Nomor start telah dibuat.',
                    'nomor_start' => $nomor_start ?? null
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyetujui pendaftaran: ' . $e->getMessage()
                ], 500);
            }
        })->name('approve');
        
        Route::post('/{id}/reject', function (\Illuminate\Http\Request $request, $id) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
            try {
                DB::beginTransaction();
                
                DB::table('pendaftaran')->where('id', $id)->update([
                    'status' => 'ditolak',
                    'status_pendaftaran' => 'ditolak',
                    'alasan_pembatalan' => $request->reason,
                    'updated_at' => now()
                ]);
                
                DB::table('log_aktivitas')->insert([
                    'user_id' => session('user_id'),
                    'aksi' => 'tolak_pendaftaran',
                    'deskripsi' => 'Menolak pendaftaran ID: ' . $id . ($request->reason ? ' - Alasan: ' . $request->reason : ''),
                    'tabel_terkait' => 'pendaftaran',
                    'id_terkait' => $id,
                    'created_at' => now()
                ]);
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Pendaftaran berhasil ditolak!'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menolak pendaftaran: ' . $e->getMessage()
                ], 500);
            }
        })->name('reject');
        
        Route::post('/{id}/cancel', function (\Illuminate\Http\Request $request, $id) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
            try {
                DB::beginTransaction();
                
                DB::table('pendaftaran')->where('id', $id)->update([
                    'status_pendaftaran' => 'dibatalkan',
                    'dibatalkan_pada' => now(),
                    'alasan_pembatalan' => $request->reason,
                    'updated_at' => now()
                ]);
                
                DB::table('log_aktivitas')->insert([
                    'user_id' => session('user_id'),
                    'aksi' => 'batalkan_pendaftaran',
                    'deskripsi' => 'Membatalkan pendaftaran ID: ' . $id . ($request->reason ? ' - Alasan: ' . $request->reason : ''),
                    'tabel_terkait' => 'pendaftaran',
                    'id_terkait' => $id,
                    'created_at' => now()
                ]);
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Pendaftaran berhasil dibatalkan!'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membatalkan pendaftaran: ' . $e->getMessage()
                ], 500);
            }
        })->name('cancel');
    });
    
    // ========== PAYMENT MANAGEMENT ==========
    Route::prefix('payments')->name('payments.')->group(function () use ($checkAdmin) {
        
        Route::get('/', function () use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
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
        })->name('index');
        
        Route::get('/{id}/view', function ($id) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
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
        })->name('view');
        
        Route::post('/{id}/verify', function ($id) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
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
        })->name('verify');
        
        Route::post('/{id}/reject', function ($id) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
            DB::table('pembayaran')->where('id', $id)->update([
                'status' => 'ditolak',
                'tanggal_verifikasi' => now(),
                'diverifikasi_oleh' => session('user_id'),
                'updated_at' => now()
            ]);
            
            return back()->with('success', 'Pembayaran berhasil ditolak!');
        })->name('reject');
    });
    
    // ========== REPORTS MANAGEMENT ==========
    Route::prefix('reports')->name('reports.')->group(function () use ($checkAdmin) {
        
        Route::get('/', function () use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
            // Get summary data for dashboard
            $totalEvents = DB::table('lomba')->count();
            $totalRegistrations = DB::table('pendaftaran')->count();
            $totalPayments = DB::table('pembayaran')->count();
            $totalUsers = DB::table('pengguna')->count();
            
            // Get revenue data
            $totalRevenue = DB::table('pembayaran')
                ->where('status', 'terverifikasi')
                ->sum('jumlah');
                
            $pendingPayments = DB::table('pembayaran')
                ->where('status', 'menunggu')
                ->count();
                
            $verifiedPayments = DB::table('pembayaran')
                ->where('status', 'terverifikasi')
                ->count();
                
            $rejectedPayments = DB::table('pembayaran')
                ->where('status', 'ditolak')
                ->count();

            // Get recent activities
            $recentActivities = DB::table('log_aktivitas')
                ->join('pengguna', 'log_aktivitas.user_id', '=', 'pengguna.id')
                ->select('log_aktivitas.*', 'pengguna.nama as user_nama')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Get registration stats by month
            $registrationStats = DB::table('pendaftaran')
                ->select(
                    DB::raw('MONTH(created_at) as month'), 
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereYear('created_at', date('Y'))
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            // Get payment stats
            $paymentStats = DB::table('pembayaran')
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get();
                
            // Get monthly revenue
            $monthlyRevenue = DB::table('pembayaran')
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('SUM(jumlah) as total')
                )
                ->where('status', 'terverifikasi')
                ->whereYear('created_at', date('Y'))
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            return view('admin.reports.index', compact(
                'totalEvents',
                'totalRegistrations',
                'totalPayments',
                'totalUsers',
                'totalRevenue',
                'pendingPayments',
                'verifiedPayments',
                'rejectedPayments',
                'recentActivities',
                'registrationStats',
                'paymentStats',
                'monthlyRevenue'
            ));
        })->name('index');
        
        Route::get('/generate', function () use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;

            $type = request('type', 'registrations');
            $startDate = request('start_date');
            $endDate = request('end_date');
            
            $data = [];
            $reportTitle = '';

            switch ($type) {
                case 'registrations':
                    $reportTitle = 'Laporan Pendaftaran';
                    $query = DB::table('pendaftaran')
                        ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
                        ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
                        ->select(
                            'pendaftaran.*',
                            'pendaftaran.created_at as tanggal_daftar',
                            'lomba.nama as event_nama',
                            'pengguna.nama as peserta_nama',
                            'pengguna.email as peserta_email'
                        );
                    
                    if ($startDate && $endDate) {
                        $query->whereBetween('pendaftaran.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                    }
                    
                    $data = $query->orderBy('pendaftaran.created_at', 'desc')->get();
                    break;
                    
                case 'payments':
                    $reportTitle = 'Laporan Pembayaran';
                    $query = DB::table('pembayaran')
                        ->join('pendaftaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')
                        ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
                        ->select(
                            'pembayaran.*',
                            'pembayaran.created_at as tanggal_bayar',
                            'lomba.nama as event_nama',
                            'pendaftaran.nama_lengkap as peserta_nama'
                        );
                    
                    if ($startDate && $endDate) {
                        $query->whereBetween('pembayaran.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                    }
                    
                    $data = $query->orderBy('pembayaran.created_at', 'desc')->get();
                    break;
                    
                case 'events':
                    $reportTitle = 'Laporan Event';
                    $query = DB::table('lomba')
                        ->select('lomba.*', 
                            DB::raw('(SELECT COUNT(*) FROM pendaftaran WHERE id_lomba = lomba.id) as total_pendaftar'),
                            DB::raw('(SELECT SUM(jumlah) FROM pembayaran 
                                      JOIN pendaftaran ON pembayaran.id_pendaftaran = pendaftaran.id 
                                      WHERE pendaftaran.id_lomba = lomba.id 
                                      AND pembayaran.status = "terverifikasi") as total_pendapatan'));
                    
                    if ($startDate && $endDate) {
                        $query->whereBetween('lomba.tanggal', [$startDate, $endDate]);
                    }
                    
                    $data = $query->orderBy('lomba.tanggal', 'desc')->get();
                    break;
            }

            return view('admin.reports.generate', compact('data', 'type', 'reportTitle', 'startDate', 'endDate'));
        })->name('generate');
        
        Route::get('/download/{type}', function ($type) use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;

            // Get data based on type
            $data = [];
            $filename = '';
            
            switch ($type) {
                case 'registrations':
                    $data = DB::table('pendaftaran')
                        ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
                        ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
                        ->select(
                            'pendaftaran.kode_pendaftaran',
                            'pendaftaran.created_at as tanggal_daftar',
                            'lomba.nama as event_nama',
                            'pengguna.nama as peserta_nama',
                            'pengguna.email as peserta_email',
                            'pendaftaran.status'
                        )
                        ->orderBy('pendaftaran.created_at', 'desc')
                        ->get();
                    $filename = 'laporan_pendaftaran_' . date('Ymd_His') . '.pdf';
                    break;
                    
                case 'payments':
                    $data = DB::table('pembayaran')
                        ->join('pendaftaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')
                        ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
                        ->select(
                            'pembayaran.kode_pembayaran',
                            'pembayaran.created_at as tanggal_bayar',
                            'lomba.nama as event_nama',
                            'pendaftaran.nama_lengkap as peserta_nama',
                            'pembayaran.jumlah',
                            'pembayaran.status'
                        )
                        ->orderBy('pembayaran.created_at', 'desc')
                        ->get();
                    $filename = 'laporan_pembayaran_' . date('Ymd_His') . '.pdf';
                    break;
            }

            // Check if DomPDF is installed
            if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
                return redirect()->route('admin.reports.index')
                    ->with('error', 'PDF Generator belum terinstall. Jalankan: composer require barryvdh/laravel-dompdf');
            }

            // Generate PDF
            $pdf = app('dompdf.wrapper');
            $html = view('admin.reports.pdf', compact('data', 'type'))->render();
            $pdf->loadHTML($html);
            
            return $pdf->download($filename);
        })->name('download');
        
        Route::get('/statistics', function () use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return response()->json(['error' => 'Unauthorized'], 401);

            $period = request('period', 'monthly');
            
            $stats = [];
            
            if ($period === 'monthly') {
                // Monthly registration stats
                $stats['registrations'] = DB::table('pendaftaran')
                    ->select(
                        DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), 
                        DB::raw('COUNT(*) as count')
                    )
                    ->whereYear('created_at', date('Y'))
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
                    ->pluck('count', 'month')
                    ->toArray();
                    
                // Monthly revenue stats
                $stats['revenue'] = DB::table('pembayaran')
                    ->select(
                        DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), 
                        DB::raw('SUM(jumlah) as total')
                    )
                    ->where('status', 'terverifikasi')
                    ->whereYear('created_at', date('Y'))
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
                    ->pluck('total', 'month')
                    ->toArray();
            }
            
            return response()->json($stats);
        })->name('statistics');
    });
    
    // ========== LOG ACTIVITY ==========
    Route::prefix('logs')->name('logs.')->group(function () use ($checkAdmin) {
        
        Route::get('/', function () use ($checkAdmin) {
            $check = $checkAdmin();
            if ($check) return $check;
            
            $logs = DB::table('log_aktivitas')
                ->join('pengguna', 'log_aktivitas.user_id', '=', 'pengguna.id')
                ->select('log_aktivitas.*', 'pengguna.nama as user_nama')
                ->orderBy('log_aktivitas.created_at', 'desc')
                ->paginate(20);
                
            return view('admin.logs.index', compact('logs'));
        })->name('index');
    });
    
    // ========== ADMIN PROFILE ==========
    Route::get('/profile', function () use ($checkAdmin) {
        $check = $checkAdmin();
        if ($check) return $check;
        
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
    })->name('profile');
    
    // Update Admin Profile dengan Upload Foto
    Route::post('/profile/update', function (\Illuminate\Http\Request $request) use ($checkAdmin) {
        $check = $checkAdmin();
        if ($check) return $check;
        
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
    })->name('profile.update');
    
    // Generate Avatar (AJAX)
    Route::post('/profile/generate-avatar', function (\Illuminate\Http\Request $request) use ($checkAdmin) {
        $check = $checkAdmin();
        if ($check) return $check;
        
        $request->validate([
            'color' => 'nullable|string'
        ]);
        
        $user = DB::table('pengguna')->where('id', session('user_id'))->first();
        
        // Generate avatar data
        $initials = strtoupper(substr($user->nama, 0, 1));
        $avatarData = [
            'type' => 'generated',
            'initials' => $initials,
            'color' => $request->color ?? 'from-blue-400 to-purple-400',
            'generated_at' => now()->toDateTimeString()
        ];
        
        // Update user with generated avatar data
        DB::table('pengguna')->where('id', session('user_id'))->update([
            'foto_profil' => json_encode($avatarData),
            'updated_at' => now()
        ]);
        
        // Log activity
        DB::table('log_aktivitas')->insert([
            'user_id' => session('user_id'),
            'aksi' => 'generate_avatar',
            'deskripsi' => 'Admin generate avatar baru: ' . $initials,
            'tabel_terkait' => 'pengguna',
            'id_terkait' => session('user_id'),
            'created_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Avatar berhasil digenerate!',
            'initials' => $initials,
            'color' => $request->color ?? 'from-blue-400 to-purple-400'
        ]);
    })->name('profile.generate-avatar');
    
    // Separate Photo Upload (Optional - for standalone photo upload)
    Route::post('/profile/upload-photo', function (\Illuminate\Http\Request $request) use ($checkAdmin) {
        $check = $checkAdmin();
        if ($check) return $check;
        
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);
        
        $user = DB::table('pengguna')->where('id', session('user_id'))->first();
        $oldPhotoPath = $user->foto_profil;
        
        $file = $request->file('photo');
        $filename = 'admin_' . session('user_id') . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('admin/profiles', $filename, 'public');
        
        DB::table('pengguna')->where('id', session('user_id'))->update([
            'foto_profil' => $path,
            'updated_at' => now()
        ]);
        
        // Delete old photo if exists
        if ($oldPhotoPath && Storage::exists('public/' . $oldPhotoPath)) {
            Storage::delete('public/' . $oldPhotoPath);
        }
        
        // Log activity
        DB::table('log_aktivitas')->insert([
            'user_id' => session('user_id'),
            'aksi' => 'upload_admin_photo',
            'deskripsi' => 'Admin mengupload foto profil',
            'tabel_terkait' => 'pengguna',
            'id_terkait' => session('user_id'),
            'created_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diupload!',
            'path' => Storage::url($path)
        ]);
    })->name('profile.upload-photo');
});

// ==================== KASIR AREA ====================
Route::prefix('kasir')->name('kasir.')->group(function () {
    
    $checkKasir = function () {
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Harap login terlebih dahulu!');
        }
        
        if (!in_array(session('user_peran'), ['kasir'])) {
            return redirect()->route('home')->with('error', 'Akses ditolak! Hanya untuk kasir.');
        }
        
        return null;
    };
    
    Route::get('/dashboard', function () use ($checkKasir) {
        $check = $checkKasir();
        if ($check) return $check;
        
        $pending_payments = DB::table('pembayaran')
            ->where('status', 'menunggu')
            ->count();
            
        $today_payments = DB::table('pembayaran')
            ->whereDate('created_at', today())
            ->where('status', 'terverifikasi')
            ->count();
            
        $today_amount = DB::table('pembayaran')
            ->whereDate('created_at', today())
            ->where('status', 'terverifikasi')
            ->sum('jumlah');
        
        return view('kasir.dashboard', compact('pending_payments', 'today_payments', 'today_amount'));
    })->name('dashboard');
});

// ==================== PUBLIC EVENTS PAGE ====================
Route::get('/events', function () {
    $events = DB::table('lomba')
        ->where('status', 'mendatang')
        ->orderBy('tanggal', 'asc')
        ->paginate(9);
    
    $categories = DB::table('lomba')
        ->select('kategori')
        ->distinct()
        ->get();
    
    return view('events', compact('events', 'categories'));
})->name('events');

Route::get('/event/{id}', function ($id) {
    $event = DB::table('lomba')->where('id', $id)->first();
    
    if (!$event) {
        return redirect()->route('events')->with('error', 'Event tidak ditemukan');
    }
    
    $packages = DB::table('paket')->where('lomba_id', $id)->get();
    
    $upcoming_events = DB::table('lomba')
        ->where('status', 'mendatang')
        ->where('id', '!=', $id)
        ->orderBy('tanggal', 'asc')
        ->limit(3)
        ->get();
    
    return view('event-detail', compact('event', 'packages', 'upcoming_events'));
})->name('event.detail');

// ==================== EVENT REGISTRATION ====================
Route::get('/event/{id}/register', function ($id) {
    if (!session('is_logged_in')) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mendaftar event.');
    }
    
    $event = DB::table('lomba')->where('id', $id)->first();
    
    if (!$event) {
        return redirect()->route('events')->with('error', 'Event tidak ditemukan');
    }
    
    if ($event->status != 'mendatang') {
        return redirect()->route('event.detail', $id)->with('error', 'Pendaftaran untuk event ini sudah ditutup.');
    }
    
    $existing_registration = DB::table('pendaftaran')
        ->where('id_pengguna', session('user_id'))
        ->where('id_lomba', $id)
        ->first();
    
    if ($existing_registration) {
        return redirect()->route('event.detail', $id)->with('info', 'Anda sudah terdaftar untuk event ini.');
    }
    
    $packages = DB::table('paket')->where('lomba_id', $id)->get();
    
    return view('event-register', compact('event', 'packages'));
})->name('event.register');

Route::post('/event/{id}/register', function (\Illuminate\Http\Request $request, $id) {
    if (!session('is_logged_in')) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }
    
    $validated = $request->validate([
        'paket_id' => 'required|exists:paket,id',
        'catatan_khusus' => 'nullable|string|max:500',
    ]);
    
    $event = DB::table('lomba')->where('id', $id)->first();
    if (!$event) {
        return back()->with('error', 'Event tidak ditemukan');
    }
    
    $existing_registration = DB::table('pendaftaran')
        ->where('id_pengguna', session('user_id'))
        ->where('id_lomba', $id)
        ->first();
    
    if ($existing_registration) {
        return back()->with('error', 'Anda sudah terdaftar untuk event ini.');
    }
    
    $package = DB::table('paket')->where('id', $validated['paket_id'])->first();
    if (!$package || $package->lomba_id != $id) {
        return back()->with('error', 'Paket tidak valid untuk event ini.');
    }
    
    DB::table('pendaftaran')->insert([
        'id_pengguna' => session('user_id'),
        'id_lomba' => $id,
        'id_paket' => $validated['paket_id'],
        'status' => 'menunggu',
        'status_pembayaran' => 'menunggu',
        'nama_lengkap' => session('user_nama'),
        'email' => session('user_email'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    $registration_id = DB::getPdo()->lastInsertId();
    
    return redirect()->route('registration.success', $registration_id);
})->name('event.register.submit');

// ==================== REGISTRATION SUCCESS ====================
Route::get('/registration/{id}/success', function ($id) {
    if (!session('is_logged_in')) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }
    
    $registration = DB::table('pendaftaran')
        ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
        ->join('paket', 'pendaftaran.id_paket', '=', 'paket.id')
        ->where('pendaftaran.id', $id)
        ->where('pendaftaran.id_pengguna', session('user_id'))
        ->select('pendaftaran.*', 'lomba.nama as event_nama', 'lomba.tanggal', 'lomba.lokasi',
                 'paket.nama as package_name', 'paket.harga as package_price')
        ->first();
        
    if (!$registration) {
        return redirect()->route('events')->with('error', 'Pendaftaran tidak ditemukan');
    }
    
    return view('registration-success', compact('registration'));
})->name('registration.success');

// ==================== USER PROFILE ====================
Route::get('/profile', function () {
    if (!session('is_logged_in')) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }
    
    $user = DB::table('pengguna')->where('id', session('user_id'))->first();
    
    // Data yang berbeda berdasarkan role
    $role = session('user_peran');
    
    if (in_array($role, ['peserta', 'kasir'])) {
        // Untuk peserta & kasir: tampilkan pendaftaran event
        $registrations = DB::table('pendaftaran')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->join('paket', 'pendaftaran.id_paket', '=', 'paket.id')
            ->where('pendaftaran.id_pengguna', session('user_id'))
            ->select('pendaftaran.*', 'lomba.nama as event_nama', 'lomba.tanggal',
                     'paket.nama as package_name', 'paket.harga as package_price')
            ->orderBy('pendaftaran.created_at', 'desc')
            ->get();
            
        return view('profile', compact('user', 'registrations', 'role'));
        
    } elseif (in_array($role, ['admin', 'superadmin', 'staff'])) {
        // Untuk admin/staff: tampilkan statistik
        $totalEvents = DB::table('lomba')->count();
        $totalRegistrations = DB::table('pendaftaran')->count();
        $pendingRegistrations = DB::table('pendaftaran')->where('status_pendaftaran', 'menunggu')->count();
        
        return view('profile', compact('user', 'role', 'totalEvents', 'totalRegistrations', 'pendingRegistrations'));
    }
})->name('profile');

// ==================== PAYMENT INSTRUCTIONS ====================
Route::get('/payment/instructions/{registration_id}', function ($registration_id) {
    if (!session('is_logged_in')) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }
    
    $registration = DB::table('pendaftaran')
        ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
        ->join('paket', 'pendaftaran.id_paket', '=', 'paket.id')
        ->where('pendaftaran.id', $registration_id)
        ->where('pendaftaran.id_pengguna', session('user_id'))
        ->select('pendaftaran.*', 'lomba.nama as event_nama', 'paket.harga as package_price')
        ->first();
        
    if (!$registration) {
        return redirect()->route('profile')->with('error', 'Pendaftaran tidak ditemukan');
    }
    
    return view('payment-instructions', compact('registration'));
})->name('payment.instructions');

Route::post('/payment/upload-proof/{registration_id}', function (\Illuminate\Http\Request $request, $registration_id) {
    if (!session('is_logged_in')) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }
    
    $registration = DB::table('pendaftaran')
        ->where('id', $registration_id)
        ->where('id_pengguna', session('user_id'))
        ->first();
        
    if (!$registration) {
        return redirect()->route('profile')->with('error', 'Pendaftaran tidak ditemukan');
    }
    
    $request->validate([
        'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'metode_pembayaran' => 'required|string',
        'jumlah' => 'required|numeric|min:' . DB::table('paket')->where('id', $registration->id_paket)->value('harga'),
    ]);
    
    $file = $request->file('bukti_pembayaran');
    $filename = 'payment_' . time() . '.' . $file->getClientOriginalExtension();
    $path = $file->storeAs('payments', $filename, 'public');
    
    DB::table('pembayaran')->insert([
        'id_pendaftaran' => $registration_id,
        'kode_pembayaran' => 'PAY-' . date('Ymd') . '-' . strtoupper(uniqid()),
        'nama_pembayar' => session('user_nama'),
        'email_pembayar' => session('user_email'),
        'jumlah' => $request->jumlah,
        'metode_pembayaran' => $request->metode_pembayaran,
        'bukti_pembayaran' => $path,
        'status' => 'menunggu',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    return redirect()->route('profile')->with('success', 'Bukti pembayaran berhasil diupload! Tunggu verifikasi dari admin.');
})->name('payment.upload-proof');