<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLoginForm(Request $request)
    {
        // Check if user is already logged in
        if (session('is_logged_in')) {
            return redirect()->route('home');
        }
        
        // Check for pending event registration
        $redirect = $request->redirect;
        $eventId = $request->event_id;
        $eventName = $request->event_name;
        
        return view('auth.login', compact('redirect', 'eventId', 'eventName'));
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = DB::table('pengguna')->where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Set session
            session([
                'user_id' => $user->id,
                'user_nama' => $user->nama,
                'user_email' => $user->email,
                'user_peran' => $user->peran,
                'user_telp' => $user->telepon,
                'user_alamat' => $user->alamat,
                'is_logged_in' => true
            ]);

            // Cek jika ada pending event registration
            $redirect = $request->redirect;
            $eventId = $request->event_id;

            if ($redirect === 'event-registration' && $eventId) {
                return redirect()->route('event.register.create', ['id' => $eventId])
                    ->with('success', 'Login berhasil! Silakan lanjutkan pendaftaran.');
            }

            // Redirect berdasarkan role
            if ($user->peran == 'admin' || $user->peran == 'superadmin') {
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
            } elseif ($user->peran == 'staff') {
                return redirect()->route('staff.dashboard')->with('success', 'Login berhasil!');
            } elseif ($user->peran == 'kasir') {
                return redirect()->route('kasir.dashboard')->with('success', 'Login berhasil!');
            }

            return redirect()->route('home')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    /**
     * Tampilkan form register
     */
    public function showRegisterForm(Request $request)
    {
        // Check if user is already logged in
        if (session('is_logged_in')) {
            return redirect()->route('home');
        }
        
        // Check for pending event registration
        $redirect = $request->redirect;
        $eventId = $request->event_id;
        $eventName = $request->event_name;
        
        return view('auth.register', compact('redirect', 'eventId', 'eventName'));
    }

    /**
     * Proses register
     */
    public function register(Request $request)
    {
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
            'is_active' => 1,
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
            
            // Cek jika ada pending event registration
            $pendingEventId = session('pending_event_id');
            
            if ($pendingEventId) {
                // Clear session
                session()->forget(['pending_event_id', 'pending_event_name']);
                
                // Redirect to event registration
                return redirect()->route('event.register.create', ['id' => $pendingEventId])
                    ->with('success', 'Registrasi berhasil! Silakan lanjutkan pendaftaran event.');
            }
            
            // Cek jika ada redirect parameter dari modal
            $redirect = $request->input('redirect');
            $eventId = $request->input('event_id');
            
            if ($redirect === 'event-registration' && $eventId) {
                return redirect()->route('event.register.create', ['id' => $eventId])
                    ->with('success', 'Registrasi berhasil! Silakan lanjutkan pendaftaran event.');
            }
        }
        
        return redirect()->route('home')->with('success', 'Registrasi berhasil!');
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        session()->flush();
        session()->regenerate();
        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }

    /**
     * Check login status (AJAX)
     */
    public function checkLoginStatus()
    {
        return response()->json([
            'isLoggedIn' => session('is_logged_in', false),
            'userName' => session('user_nama', null),
            'userId' => session('user_id', null)
        ]);
    }
}