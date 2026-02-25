<?php

namespace App\Http\Controllers\Admin; 

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminProfileController extends Controller
{
    public function show()
    {
        // Mengambil ID dari session atau auth standar
        $userId = session('user_id') ?? auth()->id();
        $user = User::find($userId);
        
        if (!$user) {
            return redirect('/login')->with('error', 'Silakan login kembali.');
        }

        // Pengaman jika tabel activities belum dibuat
        $admin_activities = [];
        try {
            if (DB::getSchemaBuilder()->hasTable('activities')) {
                $admin_activities = DB::table('activities')
                    ->where('user_id', $user->id)
                    ->latest()
                    ->take(5)
                    ->get();
            }
        } catch (\Exception $e) {
            Log::warning("Tabel activities tidak ditemukan: " . $e->getMessage());
        }
        
        $performance = [
            'verifications_today' => 5,
            'verifications_month' => 120
        ];

        return view('admin.profile', compact('user', 'admin_activities', 'performance'));
    }

    public function update(Request $request)
    {
        try {
            $userId = session('user_id') ?? auth()->id();
            $user = User::find($userId);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User tidak ditemukan!'], 404);
            }

            // Validasi Input
            $request->validate([
                'nama' => 'required|string|max:255',
                'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'telepon' => 'nullable|string|max:15',
                'alamat' => 'nullable|string',
                'password' => 'nullable|min:6'
            ]);

            // Proses Upload Foto Profil
            if ($request->hasFile('foto_profil')) {
                // Hapus foto lama jika ada di storage
                if ($user->foto_profil && Storage::disk('public')->exists('profiles/' . $user->foto_profil)) {
                    Storage::disk('public')->delete('profiles/' . $user->foto_profil);
                }

                $file = $request->file('foto_profil');
                $nama_file = time() . '_' . $file->getClientOriginalName();
                
                // Simpan file ke storage/app/public/profiles
                $file->storeAs('profiles', $nama_file, 'public');
                $user->foto_profil = $nama_file;
            }

            // Update Field Lainnya
            $user->nama = $request->nama;
            $user->telepon = $request->telepon;
            $user->alamat = $request->alamat;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            // Update Session Nama agar Navbar berubah
            session(['user_nama' => $user->nama]);

            return response()->json([
                'success' => true, 
                'message' => 'Profil berhasil diperbarui!',
                'new_photo' => asset('storage/profiles/' . $user->foto_profil)
            ]);

        } catch (\Exception $e) {
            Log::error("Gagal Update Profil: " . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}