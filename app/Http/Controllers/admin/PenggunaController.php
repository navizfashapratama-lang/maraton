<?php

namespace App\Http\Controllers\Admin; 

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $peran = $request->input('peran');
        
        $users = DB::table('pengguna')
            ->when($search, function ($query) use ($search) {
                return $query->where('nama', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('telepon', 'like', "%{$search}%");
            })
            ->when($peran, function ($query) use ($peran) {
                if ($peran && $peran !== 'semua') {
                    return $query->where('peran', $peran);
                }
                return $query;
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // Stats
        $stats = [
            'total' => DB::table('pengguna')->count(),
            'admin' => DB::table('pengguna')->where('peran', 'admin')->count(),
            'staff' => DB::table('pengguna')->where('peran', 'staff')->count(),          
            'aktif' => DB::table('pengguna')->where('is_active', 1)->count(),
            'nonaktif' => DB::table('pengguna')->where('is_active', 0)->count(),
        ];
        
        return view('admin.users.index', compact('users', 'stats', 'search', 'peran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|min:6|confirmed',
            'peran' => 'required|in:admin,staff',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'is_active' => 'boolean'
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

            // Redirect dengan success dan TANPA withInput()
            return redirect()->route('admin.users.create')
                ->with('success', 'Pengguna ' . $request->nama . ' berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = DB::table('pengguna')->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Pengguna tidak ditemukan');
        }
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = DB::table('pengguna')->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Pengguna tidak ditemukan');
        }
        
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = DB::table('pengguna')->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Pengguna tidak ditemukan');
        }

        // Validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:pengguna,email,' . $id,
            'peran' => 'required|in:superadmin,admin,staff,kasir,peserta',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $updateData = [
                'nama' => $request->nama,
                'email' => $request->email,
                'peran' => $request->peran,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'updated_at' => now(),
            ];

            // Jika ada password baru
            if ($request->filled('password')) {
                $request->validate([
                    'password' => 'min:6|confirmed',
                ]);
                $updateData['password'] = Hash::make($request->password);
            }

            DB::table('pengguna')->where('id', $id)->update($updateData);

            // Log aktivitas
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'edit_pengguna',
                'deskripsi' => 'Mengedit data pengguna: ' . $request->nama . ' (ID: ' . $id . ')',
                'tabel_terkait' => 'pengguna',
                'id_terkait' => $id,
                'created_at' => now()
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'Data pengguna berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cegah hapus diri sendiri
        if ($id == session('user_id')) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        $user = DB::table('pengguna')->where('id', $id)->first();
        
        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Pengguna tidak ditemukan');
        }

        try {
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

            return redirect()->route('admin.users.index')
                ->with('success', 'Pengguna berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status aktif pengguna.
     */
    public function toggleStatus(Request $request, string $id)
    {
        // Cegah nonaktifkan diri sendiri
        if ($id == session('user_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa mengubah status akun sendiri!'
            ], 400);
        }

        $user = DB::table('pengguna')->where('id', $id)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        try {
            $newStatus = $user->is_active ? 0 : 1;
            
            DB::table('pengguna')->where('id', $id)->update([
                'is_active' => $newStatus,
                'updated_at' => now()
            ]);

            // Log aktivitas
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'toggle_status_pengguna',
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

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }
}