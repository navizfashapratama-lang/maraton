<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $peran = $request->get('peran');
        $status = $request->get('status');
        $search = $request->get('search');
        
        $users = Pengguna::query();
        
        // Filter berdasarkan role
        if ($peran && $peran !== 'semua') {
            $users->where('peran', $peran);
        }
        
        // Filter berdasarkan status aktif
        if ($status === 'aktif') {
            $users->where('is_active', true);
        } elseif ($status === 'nonaktif') {
            $users->where('is_active', false);
        }
        
        // Search
        if ($search) {
            $users->where(function($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('telepon', 'like', "%{$search}%");
            });
        }
        
        $users = $users->orderBy('created_at', 'desc')->paginate(10);
        
        // Statistik
        $stats = [
            'total' => Pengguna::count(),
            'superadmin' => Pengguna::where('peran', 'superadmin')->count(),
            'admin' => Pengguna::where('peran', 'admin')->count(),
            'staff' => Pengguna::where('peran', 'staff')->count(),
            'peserta' => Pengguna::where('peran', 'peserta')->count(),
            'aktif' => Pengguna::where('is_active', true)->count(),
            'nonaktif' => Pengguna::where('is_active', false)->count(),
        ];
        
        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = [
            'peserta' => 'Peserta',
            'staff' => 'Staff',
            'admin' => 'Administrator',
            'superadmin' => 'Super Admin'
        ];
        
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|string|min:6|confirmed',
            'peran' => 'required|in:superadmin,admin,staff,peserta',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'is_active' => 'boolean',
            'foto_profil' => 'nullable|image|max:2048',
        ]);
        
        // Handle photo upload
        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('profiles', 'public');
            $validated['foto_profil'] = $path;
        }
        
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? true : false;
        
        Pengguna::create($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = Pengguna::findOrFail($id);
        
        // Ambri data pendaftaran user
        $pendaftaran = $user->pendaftaran()
            ->with('lomba', 'paket')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.users.show', compact('user', 'pendaftaran'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = Pengguna::findOrFail($id);
        $roles = [
            'peserta' => 'Peserta',
            'staff' => 'Staff',
            'admin' => 'Administrator',
            'superadmin' => 'Super Admin'
        ];
        
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        $user = Pengguna::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'peran' => 'required|in:superadmin,admin,staff,peserta',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'is_active' => 'boolean',
            'foto_profil' => 'nullable|image|max:2048',
        ]);
        
        // Jika password diisi
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        // Handle photo upload
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            
            $path = $request->file('foto_profil')->store('profiles', 'public');
            $validated['foto_profil'] = $path;
        }
        
        $validated['is_active'] = $request->has('is_active') ? true : false;
        
        $user->update($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus($id)
    {
        $user = Pengguna::findOrFail($id);
        
        // Jangan nonaktifkan diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat mengubah status akun sendiri!');
        }
        
        $user->update([
            'is_active' => !$user->is_active,
            'updated_at' => now()
        ]);
        
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()
            ->with('success', "Pengguna berhasil {$status}!");
    }

    /**
     * Delete the specified user.
     */
    public function destroy($id)
    {
        $user = Pengguna::findOrFail($id);
        
        // Jangan hapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus akun sendiri!');
        }
        
        // Hapus foto profil jika ada
        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }

    /**
     * Update user's photo.
     */
    public function updatePhoto(Request $request, $id)
    {
        $request->validate([
            'foto_profil' => 'required|image|max:2048'
        ]);
        
        $user = Pengguna::findOrFail($id);
        
        // Hapus foto lama jika ada
        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }
        
        $path = $request->file('foto_profil')->store('profiles', 'public');
        $user->update(['foto_profil' => $path]);
        
        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diupdate',
            'photo_url' => asset('storage/' . $path)
        ]);
    }
}