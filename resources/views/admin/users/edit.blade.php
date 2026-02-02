<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }
        
        .form-card {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }
        
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
        }
        
        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            transition: all 0.3s ease;
        }
        
        .role-badge.admin {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2);
        }
        
        .role-badge.staff {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
        }
        
        .role-badge.kasir {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 4px 6px rgba(245, 158, 11, 0.2);
        }
        
        .role-badge.peserta {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
            box-shadow: 0 4px 6px rgba(107, 114, 128, 0.2);
        }
        
        .user-avatar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.2);
        }
        
        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        
        .status-dot.active {
            background-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }
        
        .status-dot.inactive {
            background-color: #ef4444;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
        }
        
        .info-card {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 1.5rem;
        }
        
        .danger-zone {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            border: 2px dashed #ef4444;
            border-radius: 10px;
            padding: 1.5rem;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                <div>
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-3 transition-colors duration-200 group">
                        <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
                        <span class="font-medium">Kembali ke Daftar Pengguna</span>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Pengguna</h1>
                    <p class="text-gray-600">Perbarui data pengguna: <span class="font-semibold text-blue-600">{{ $user->nama }}</span></p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="flex items-center space-x-4">
                        <div class="flex flex-col items-end">
                            <p class="text-sm text-gray-500">Administrator</p>
                            <p class="font-medium text-gray-800">{{ session('user_nama') ?? 'Admin' }}</p>
                        </div>
                        <div class="w-12 h-12 user-avatar rounded-full flex items-center justify-center">
                            <i class="fas fa-user-cog text-white text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Info Summary -->
            <div class="info-card mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mr-4 shadow-lg">
                            <i class="fas fa-user text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ $user->nama }}</h3>
                            <div class="flex items-center mt-1">
                                <span class="{{ 
                                    $user->peran == 'admin' ? 'role-badge admin' : 
                                    ($user->peran == 'staff' ? 'role-badge staff' : 
                                    ($user->peran == 'kasir' ? 'role-badge kasir' : 'role-badge peserta')) 
                                }}" id="current-role-badge">
                                    {{ ucfirst($user->peran) }}
                                </span>
                                <span class="ml-3 flex items-center">
                                    <span class="status-dot {{ ($user->is_active ?? 1) ? 'active' : 'inactive' }}"></span>
                                    <span class="text-sm {{ ($user->is_active ?? 1) ? 'text-green-600' : 'text-red-600' }}">
                                        {{ ($user->is_active ?? 1) ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-500">ID Pengguna</p>
                                <p class="font-mono font-semibold text-gray-800">{{ $user->id }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Terakhir Login</p>
                                <p class="font-medium text-gray-800">
                                    @if($user->terakhir_login)
                                        {{ \Carbon\Carbon::parse($user->terakhir_login)->format('d M Y, H:i') }}
                                    @else
                                        <span class="text-gray-400">Belum pernah</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Form Card -->
        <div class="form-card max-w-4xl mx-auto overflow-hidden">
            <!-- Card Header -->
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-blue-500 rounded-lg flex items-center justify-center mr-4 shadow-sm">
                        <i class="fas fa-user-edit text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Form Edit Pengguna</h2>
                        <p class="text-gray-600">Update informasi pengguna sesuai kebutuhan</p>
                    </div>
                </div>
            </div>
            
            <!-- Card Body -->
            <div class="p-8">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" id="editUserForm">
                    @csrf
                    
                    <!-- Alert for editing own account -->
                    @if($user->id == session('user_id'))
                    <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-blue-400 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-blue-800 mb-2">Anda sedang mengedit akun sendiri</h3>
                                <p class="text-blue-700">
                                    Perubahan yang Anda lakukan akan berlaku setelah logout dan login kembali. 
                                    Hati-hati saat mengubah password atau peran untuk akun sendiri.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Error Messages -->
                    @if($errors->any())
                    <div class="mb-8 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-xl p-6 shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-red-800 mb-2">Terjadi {{ $errors->count() }} kesalahan:</h3>
                                <div class="space-y-2">
                                    @foreach($errors->all() as $error)
                                    <div class="flex items-start">
                                        <i class="fas fa-times-circle text-red-400 mt-1 mr-2 flex-shrink-0"></i>
                                        <p class="text-red-700">{{ $error }}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Personal Information Section -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Informasi Pribadi</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama -->
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" 
                                           class="w-full px-4 py-3 pl-11 border {{ $errors->has('nama') ? 'border-red-300' : 'border-gray-300' }} rounded-lg shadow-sm input-focus transition-all duration-200"
                                           placeholder="Masukkan nama lengkap" required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                </div>
                                @error('nama')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                                           class="w-full px-4 py-3 pl-11 border {{ $errors->has('email') ? 'border-red-300' : 'border-gray-300' }} rounded-lg shadow-sm input-focus transition-all duration-200"
                                           placeholder="contoh@email.com" required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Security Section -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Keamanan Akun</h3>
                            <span class="ml-3 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Opsional</span>
                        </div>
                        
                        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <i class="fas fa-info-circle text-yellow-500 mt-1 mr-3"></i>
                                <p class="text-sm text-yellow-700">
                                    Kosongkan field password jika tidak ingin mengubah password pengguna.
                                </p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Baru
                                </label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" 
                                           class="w-full px-4 py-3 pl-11 pr-10 border {{ $errors->has('password') ? 'border-red-300' : 'border-gray-300' }} rounded-lg shadow-sm input-focus transition-all duration-200"
                                           placeholder="Kosongkan jika tidak diubah">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-gray-400"></i>
                                    </div>
                                    <button type="button" onclick="togglePassword('password')" 
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="fas fa-eye" id="password-eye-icon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-gray-500">Minimal 6 karakter</p>
                            </div>
                            
                            <!-- Konfirmasi Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Password
                                </label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation" 
                                           class="w-full px-4 py-3 pl-11 pr-10 border {{ $errors->has('password_confirmation') ? 'border-red-300' : 'border-gray-300' }} rounded-lg shadow-sm input-focus transition-all duration-200"
                                           placeholder="Ulangi password baru">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-redo text-gray-400"></i>
                                    </div>
                                    <button type="button" onclick="togglePassword('password_confirmation')" 
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="fas fa-eye" id="confirm-password-eye-icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Role & Contact Section -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Role & Kontak</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Peran -->
                            <div>
                                <label for="peran" class="block text-sm font-medium text-gray-700 mb-2">
                                    Peran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="peran" name="peran" 
                                            class="w-full px-4 py-3 pl-11 border {{ $errors->has('peran') ? 'border-red-300' : 'border-gray-300' }} rounded-lg shadow-sm input-focus transition-all duration-200 appearance-none" required>
                                        <option value="peserta" {{ old('peran', $user->peran) == 'peserta' ? 'selected' : '' }}>Peserta</option>
                                        <option value="staff" {{ old('peran', $user->peran) == 'staff' ? 'selected' : '' }}>Staff</option>
                                        <option value="kasir" {{ old('peran', $user->peran) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                        <option value="admin" {{ old('peran', $user->peran) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user-tag text-gray-400"></i>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="flex items-center space-x-2">
                                        <span class="role-badge peserta" id="role-badge-preview">
                                            {{ ucfirst($user->peran) }}
                                        </span>
                                        <span class="text-xs text-gray-500" id="role-change-indicator"></span>
                                    </div>
                                </div>
                                @error('peran')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Telepon -->
                            <div>
                                <label for="telepon" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Telepon
                                </label>
                                <div class="relative">
                                    <input type="text" id="telepon" name="telepon" value="{{ old('telepon', $user->telepon) }}" 
                                           class="w-full px-4 py-3 pl-11 border {{ $errors->has('telepon') ? 'border-red-300' : 'border-gray-300' }} rounded-lg shadow-sm input-focus transition-all duration-200"
                                           placeholder="0812 3456 7890">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Format: 0812 3456 7890 (opsional)</p>
                                @error('telepon')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Alamat -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Alamat</h3>
                        </div>
                        
                        <div class="relative">
                            <textarea id="alamat" name="alamat" rows="3" 
                                      class="w-full px-4 py-3 pl-11 border {{ $errors->has('alamat') ? 'border-red-300' : 'border-gray-300' }} rounded-lg shadow-sm input-focus transition-all duration-200"
                                      placeholder="Masukkan alamat lengkap (opsional)">{{ old('alamat', $user->alamat) }}</textarea>
                            <div class="absolute top-3 left-3">
                                <i class="fas fa-home text-gray-400"></i>
                            </div>
                        </div>
                        @error('alamat')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Status Aktif -->
                    <div class="mb-10 p-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                        <div class="flex items-center">
                            <div class="relative">
                                <input type="checkbox" id="is_active_edit" name="is_active" value="1" 
                                       {{ ($user->is_active ?? 1) ? 'checked' : '' }}
                                       class="w-5 h-5 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                <label for="is_active_edit" class="ml-3 text-sm font-medium text-gray-900">
                                    Aktifkan akun pengguna
                                </label>
                            </div>
                            <div class="ml-4 px-3 py-1 {{ ($user->is_active ?? 1) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-xs font-semibold rounded-full">
                                {{ ($user->is_active ?? 1) ? 'Aktif' : 'Nonaktif' }}
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Jika tidak aktif, pengguna tidak dapat login ke sistem.
                        </p>
                    </div>
                    
                    <!-- Danger Zone (Delete User) -->
                    @if($user->id != session('user_id'))
                    <div class="danger-zone mb-10">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-red-100 text-red-600 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-red-800">Zona Berbahaya</h3>
                                <p class="text-sm text-red-600">Hati-hati dengan aksi ini</p>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 border border-red-200">
                            <div class="flex flex-col md:flex-row md:items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-800 mb-1">Hapus Pengguna</h4>
                                    <p class="text-sm text-gray-600">
                                        Menghapus pengguna akan menghapus semua data terkait termasuk pendaftaran dan pembayaran.
                                        <span class="font-semibold text-red-600">Aksi ini tidak dapat dibatalkan!</span>
                                    </p>
                                </div>
                                <div class="mt-4 md:mt-0">
                                    <button type="button" onclick="confirmDeleteUser()" 
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                                        <i class="fas fa-trash mr-2"></i>
                                        Hapus Pengguna
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Form Actions -->
                    <div class="flex flex-col md:flex-row justify-between items-center pt-6 border-t border-gray-200">
                        <div class="mb-4 md:mb-0">
                            <a href="{{ route('admin.users.index') }}" 
                               class="inline-flex items-center px-5 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Daftar
                            </a>
                        </div>
                        
                        <div class="flex space-x-4">
                            <button type="button" onclick="resetForm()" 
                                    class="inline-flex items-center px-5 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                                <i class="fas fa-redo mr-2"></i>
                                Reset Perubahan
                            </button>
                            
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Card Footer -->
            <div class="px-8 py-4 border-t border-gray-100 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-500">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                        <span>Dibuat: {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-history mr-2 text-blue-500"></i>
                        <span>Diperbarui: {{ \Carbon\Carbon::parse($user->updated_at)->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-database mr-2 text-blue-500"></i>
                        <span>ID: {{ $user->id }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const iconId = fieldId === 'password' ? 'password-eye-icon' : 'confirm-password-eye-icon';
            const icon = document.getElementById(iconId);
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Update role badge preview
        function updateRoleBadgePreview(role) {
            const badge = document.getElementById('role-badge-preview');
            const indicator = document.getElementById('role-change-indicator');
            const originalRole = "{{ $user->peran }}";
            
            badge.textContent = role ? role.charAt(0).toUpperCase() + role.slice(1) : 'Peserta';
            
            // Remove all classes
            badge.className = 'role-badge';
            
            // Add appropriate class
            if (role === 'admin') {
                badge.classList.add('admin');
            } else if (role === 'staff') {
                badge.classList.add('staff');
            } else if (role === 'kasir') {
                badge.classList.add('kasir');
            } else {
                badge.classList.add('peserta');
            }
            
            // Show change indicator
            if (role !== originalRole) {
                indicator.textContent = '⭮ Berubah';
                indicator.className = 'text-xs text-yellow-600 font-medium';
            } else {
                indicator.textContent = '';
            }
        }
        
        // Auto format phone number
        function formatPhoneNumber(value) {
            let numbers = value.replace(/\D/g, '');
            let formatted = '';
            
            if (numbers.length > 0) {
                formatted = numbers.substring(0, 4);
                if (numbers.length > 4) {
                    formatted += ' ' + numbers.substring(4, 8);
                }
                if (numbers.length > 8) {
                    formatted += ' ' + numbers.substring(8, 12);
                }
            }
            
            return formatted;
        }
        
        // Reset form
        function resetForm() {
            if (confirm('Apakah Anda yakin ingin mereset perubahan? Semua perubahan yang belum disimpan akan hilang.')) {
                const originalData = {
                    nama: "{{ $user->nama }}",
                    email: "{{ $user->email }}",
                    peran: "{{ $user->peran }}",
                    telepon: "{{ $user->telepon ?? '' }}",
                    alamat: "{{ $user->alamat ?? '' }}",
                    is_active: {{ ($user->is_active ?? 1) ? 'true' : 'false' }}
                };
                
                document.getElementById('nama').value = originalData.nama;
                document.getElementById('email').value = originalData.email;
                document.getElementById('peran').value = originalData.peran;
                document.getElementById('telepon').value = originalData.telepon;
                document.getElementById('alamat').value = originalData.alamat;
                document.getElementById('is_active_edit').checked = originalData.is_active;
                document.getElementById('password').value = '';
                document.getElementById('password_confirmation').value = '';
                
                updateRoleBadgePreview(originalData.peran);
                
                // Show success message
                showToast('Form telah direset ke data asli', 'info');
            }
        }
        
        // Show toast notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-4 py-3 rounded-lg shadow-lg text-white ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 
                type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
            }`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'times-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
        
        // Confirm delete user
        function confirmDeleteUser() {
            const userName = document.getElementById('nama').value;
            const userRole = document.getElementById('peran').value;
            
            Swal.fire({
                title: 'Hapus Pengguna?',
                html: `
                    <div class="text-left">
                        <p class="mb-2">Anda akan menghapus pengguna:</p>
                        <div class="bg-red-50 p-3 rounded-lg mb-4">
                            <p class="font-semibold text-red-700">${userName}</p>
                            <p class="text-sm text-gray-600">Role: ${userRole.charAt(0).toUpperCase() + userRole.slice(1)}</p>
                        </div>
                        <p class="text-red-600 font-semibold mb-2">⚠️ Aksi ini tidak dapat dibatalkan!</p>
                        <p class="text-sm text-gray-600">Semua data terkait akan dihapus permanen.</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('admin.users.destroy', $user->id) }}";
                }
            });
        }
        
        // Form validation
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password || confirmPassword) {
                if (password.length > 0 && password.length < 6) {
                    e.preventDefault();
                    showToast('Password minimal 6 karakter!', 'error');
                    document.getElementById('password').focus();
                    return false;
                }
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    showToast('Password dan konfirmasi password tidak cocok!', 'error');
                    document.getElementById('password_confirmation').focus();
                    return false;
                }
            }
            
            // Show loading
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            submitButton.disabled = true;
            
            return true;
        });
        
        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Role change handler
            const roleField = document.getElementById('peran');
            if (roleField) {
                roleField.addEventListener('change', function() {
                    updateRoleBadgePreview(this.value);
                });
                // Initialize role badge
                updateRoleBadgePreview(roleField.value);
            }
            
            // Phone number formatting
            const phoneField = document.getElementById('telepon');
            if (phoneField) {
                phoneField.addEventListener('input', function(e) {
                    this.value = formatPhoneNumber(e.target.value);
                });
            }
            
            // Password strength indicator for new password
            const passwordField = document.getElementById('password');
            if (passwordField) {
                passwordField.addEventListener('input', function() {
                    const password = this.value;
                    if (password.length > 0 && password.length < 6) {
                        this.style.borderColor = '#ef4444';
                    } else if (password.length >= 6) {
                        this.style.borderColor = '#10b981';
                    } else {
                        this.style.borderColor = '#d1d5db';
                    }
                });
            }
        });
    </script>
    
    <!-- SweetAlert2 for beautiful confirm dialogs -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>