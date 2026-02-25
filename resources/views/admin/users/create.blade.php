<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna Baru - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }
        
        .password-strength-bar {
            height: 4px;
            border-radius: 2px;
            transition: width 0.3s ease, background-color 0.3s ease;
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
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .role-badge.superadmin {
            background-color: #dc2626;
            color: white;
        }
        
        .role-badge.admin {
            background-color: #fef2f2;
            color: #dc2626;
        }
        
        .role-badge.staff {
            background-color: #eff6ff;
            color: #2563eb;
        }
        
        .role-badge.kasir {
            background-color: #fffbeb;
            color: #d97706;
        }
        
        .role-badge.peserta {
            background-color: #f9fafb;
            color: #6b7280;
        }
        
        .success-animation {
            animation: slideInDown 0.5s ease-out;
        }
        
        .reset-animation {
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes slideInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        .shake {
            animation: shake 0.5s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                <div>
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-3 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span class="font-medium">Kembali ke Daftar Pengguna</span>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Tambah Pengguna Baru</h1>
                    <p class="text-gray-600">Lengkapi form berikut untuk menambahkan pengguna baru ke sistem</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Administrator</p>
                            <p class="font-medium text-gray-800">{{ session('user_nama') }}</p>
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
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center mr-4 shadow-sm">
                        <i class="fas fa-user-edit text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Form Tambah Pengguna</h2>
                        <p class="text-gray-600">Semua field bertanda <span class="text-red-500">*</span> wajib diisi</p>
                    </div>
                </div>
            </div>
            
            <!-- Card Body -->
            <div class="p-8">
                <!-- Pesan Reset Berhasil -->
                <div id="reset-notification" class="hidden reset-animation mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-sync-alt text-blue-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-blue-800 mb-2">Form Telah Direset!</h3>
                            <p class="text-blue-700">Form telah dikosongkan. Anda dapat menambahkan pengguna baru lainnya.</p>
                        </div>
                    </div>
                </div>
                
                @if(session('success'))
                <div id="success-message" class="success-animation mb-8 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-green-800 mb-2">Berhasil!</h3>
                            <p class="text-green-700">{{ session('success') }}</p>
                            <div class="mt-3 text-sm text-green-600">
                                <i class="fas fa-sync-alt mr-2"></i>
                                <span>Form akan direset otomatis...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Auto reset form setelah 2 detik
                        setTimeout(function() {
                            resetFormAfterSubmit();
                        }, 2000);
                    });
                </script>
                @endif

                @if(session('error'))
                <div class="mb-8 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-xl p-6 shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-red-800 mb-2">Error!</h3>
                            <p class="text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
                @endif
                
                <form method="POST" action="{{ route('admin.users.store') }}" id="createUserForm">
                    @csrf
                    
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
                                    <input type="text" id="nama" name="nama" value="{{ old('nama', '') }}" 
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
                                    <input type="email" id="email" name="email" value="{{ old('email', '') }}"
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
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" 
                                           class="w-full px-4 py-3 pl-11 pr-10 border {{ $errors->has('password') ? 'border-red-300' : 'border-gray-300' }} rounded-lg shadow-sm input-focus transition-all duration-200"
                                           placeholder="Minimal 6 karakter" required minlength="6">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-gray-400"></i>
                                    </div>
                                    <button type="button" onclick="togglePassword('password')" 
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="fas fa-eye" id="password-eye-icon"></i>
                                    </button>
                                </div>
                                <!-- Password Strength Indicator -->
                                <div class="mt-3">
                                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                                        <span>Kekuatan password:</span>
                                        <span id="password-strength-text" class="font-medium">-</span>
                                    </div>
                                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="password-strength-bar" class="password-strength-bar h-full w-0"></div>
                                    </div>
                                    <div class="mt-2 grid grid-cols-4 gap-2 text-xs">
                                        <div class="text-center" id="length-check">
                                            <i class="fas fa-times text-red-400"></i>
                                            <span class="ml-1">6+ karakter</span>
                                        </div>
                                        <div class="text-center" id="lowercase-check">
                                            <i class="fas fa-times text-red-400"></i>
                                            <span class="ml-1">Huruf kecil</span>
                                        </div>
                                        <div class="text-center" id="uppercase-check">
                                            <i class="fas fa-times text-red-400"></i>
                                            <span class="ml-1">Huruf besar</span>
                                        </div>
                                        <div class="text-center" id="number-check">
                                            <i class="fas fa-times text-red-400"></i>
                                            <span class="ml-1">Angka</span>
                                        </div>
                                    </div>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Konfirmasi Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation" 
                                        class="w-full px-4 py-3 pl-11 pr-10 border {{ $errors->has('password_confirmation') ? 'border-red-300' : 'border-gray-300' }} rounded-lg shadow-sm input-focus transition-all duration-200"
                                        placeholder="Ulangi password" required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-redo text-gray-400"></i>
                                    </div>
                                    <button type="button" onclick="togglePassword('password_confirmation')" 
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="fas fa-eye" id="confirm-password-eye-icon"></i>
                                    </button>
                                </div>
                                <div class="mt-3" id="password-match-indicator">
                                    <div class="flex items-center text-sm">
                                        <i class="fas fa-times text-red-400 mr-2" id="match-icon"></i>
                                        <span id="match-text" class="text-gray-600">Password belum cocok</span>
                                    </div>
                                </div>
                                @error('password_confirmation')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                                        <option value="">Pilih Peran</option>
                                        <option value="admin" {{ old('peran', '') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="staff" {{ old('peran', '') == 'staff' ? 'selected' : '' }}>Staff</option>
                                    </select>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user-tag text-gray-400"></i>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                <div class="mt-3" id="role-preview">
                                    <span class="role-badge peserta" id="role-badge">Belum dipilih</span>
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
                                    <input type="text" id="telepon" name="telepon" value="{{ old('telepon', '') }}" 
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
                                      placeholder="Masukkan alamat lengkap (opsional)">{{ old('alamat', '') }}</textarea>
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
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}
                                       class="w-5 h-5 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                <label for="is_active" class="ml-3 text-sm font-medium text-gray-900">
                                    Aktifkan akun pengguna
                                </label>
                            </div>
                            <div class="ml-4 px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                Direkomendasikan
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Jika tidak aktif, pengguna tidak dapat login ke sistem. Anda dapat mengubah status ini nanti.
                        </p>
                    </div>
                
                    <!-- Form Actions -->
                    <div class="flex flex-col md:flex-row justify-between items-center pt-6 border-t border-gray-200">
                        <div class="mb-4 md:mb-0">
                            <a href="{{ route('admin.users.index') }}" 
                               class="inline-flex items-center px-5 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <i class="fas fa-times mr-2"></i>
                                Batalkan
                            </a>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="button" onclick="resetFormManually()" 
                                    class="inline-flex items-center px-5 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <i class="fas fa-redo mr-2"></i>
                                Reset Form
                            </button>
                            
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Pengguna Baru
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Card Footer -->
            <div class="px-8 py-4 border-t border-gray-100 bg-gray-50">
                <div class="flex flex-col md:flex-row md:items-center justify-between text-sm text-gray-500">
                    <div class="flex items-center mb-2 md:mb-0">
                        <i class="fas fa-shield-alt mr-2 text-blue-500"></i>
                        <span>Data akan disimpan dengan aman dan terenkripsi</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-sync-alt mr-2 text-blue-500"></i>
                        <span>Form akan direset otomatis setelah penyimpanan berhasil</span>
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
        
        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            
            // Length check
            if (password.length >= 6) {
                strength += 25;
                updateCheck('length-check', true);
            } else {
                updateCheck('length-check', false);
            }
            
            // Lowercase check
            if (/[a-z]/.test(password)) {
                strength += 25;
                updateCheck('lowercase-check', true);
            } else {
                updateCheck('lowercase-check', false);
            }
            
            // Uppercase check
            if (/[A-Z]/.test(password)) {
                strength += 25;
                updateCheck('uppercase-check', true);
            } else {
                updateCheck('uppercase-check', false);
            }
            
            // Number check
            if (/[0-9]/.test(password)) {
                strength += 25;
                updateCheck('number-check', true);
            } else {
                updateCheck('number-check', false);
            }
            
            // Update strength bar
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');
            
            strengthBar.style.width = strength + '%';
            
            if (strength <= 25) {
                strengthBar.style.backgroundColor = '#ef4444';
                strengthText.textContent = 'Lemah';
                strengthText.className = 'font-medium text-red-600';
            } else if (strength <= 50) {
                strengthBar.style.backgroundColor = '#f59e0b';
                strengthText.textContent = 'Cukup';
                strengthText.className = 'font-medium text-yellow-600';
            } else if (strength <= 75) {
                strengthBar.style.backgroundColor = '#3b82f6';
                strengthText.textContent = 'Baik';
                strengthText.className = 'font-medium text-blue-600';
            } else {
                strengthBar.style.backgroundColor = '#10b981';
                strengthText.textContent = 'Sangat Baik';
                strengthText.className = 'font-medium text-green-600';
            }
            
            return strength;
        }
        
        function updateCheck(elementId, isValid) {
            const element = document.getElementById(elementId);
            const icon = element.querySelector('i');
            const text = element.querySelector('span');
            
            if (isValid) {
                icon.className = 'fas fa-check text-green-500';
                icon.style.color = '#10b981';
                text.style.color = '#10b981';
            } else {
                icon.className = 'fas fa-times text-red-400';
                icon.style.color = '#ef4444';
                text.style.color = '#6b7280';
            }
        }
        
        // Check password match
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchIcon = document.getElementById('match-icon');
            const matchText = document.getElementById('match-text');
            
            if (!confirmPassword) {
                matchIcon.className = 'fas fa-times text-red-400';
                matchText.textContent = 'Password belum cocok';
                matchText.className = 'text-gray-600';
                return false;
            }
            
            if (password === confirmPassword) {
                matchIcon.className = 'fas fa-check text-green-500';
                matchText.textContent = 'Password cocok';
                matchText.className = 'text-green-600';
                return true;
            } else {
                matchIcon.className = 'fas fa-times text-red-400';
                matchText.textContent = 'Password tidak cocok';
                matchText.className = 'text-red-600';
                return false;
            }
        }
        
        // Update role badge
        function updateRoleBadge(role) {
            const badge = document.getElementById('role-badge');
            
            if (role) {
                let roleText = '';
                switch(role) {
                    case 'superadmin':
                        roleText = 'Super Admin';
                        break;
                    case 'admin':
                        roleText = 'Admin';
                        break;
                    case 'staff':
                        roleText = 'Staff';
                        break;
                    case 'kasir':
                        roleText = 'Kasir';
                        break;
                    case 'peserta':
                        roleText = 'Peserta';
                        break;
                    default:
                        roleText = role;
                }
                badge.textContent = roleText;
            } else {
                badge.textContent = 'Belum dipilih';
            }
            
            // Remove all classes
            badge.className = 'role-badge';
            
            // Add appropriate class
            if (role === 'superadmin') {
                badge.classList.add('superadmin');
            } else if (role === 'admin') {
                badge.classList.add('admin');
            } else if (role === 'staff') {
                badge.classList.add('staff');
            } else if (role === 'kasir') {
                badge.classList.add('kasir');
            } else if (role === 'peserta') {
                badge.classList.add('peserta');
            } else {
                badge.classList.add('peserta');
                badge.style.opacity = '0.5';
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
        
        // Reset form function
        function resetFormAfterSubmit() {
            const form = document.getElementById('createUserForm');
            if (form) {
                // Reset semua input ke nilai default
                form.reset();
                
                // Reset checkbox is_active ke checked
                document.getElementById('is_active').checked = true;
                
                // Reset role badge
                updateRoleBadge('');
                
                // Reset semua indikator password
                checkPasswordStrength('');
                checkPasswordMatch();
                
                // Reset semua old value
                const inputs = form.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    if (input.name) {
                        input.value = input.defaultValue || '';
                        if (input.type === 'checkbox' && input.name === 'is_active') {
                            input.checked = true;
                        }
                    }
                });
                
                // Hapus pesan sukses jika ada
                const successMessage = document.getElementById('success-message');
                if (successMessage) {
                    successMessage.style.display = 'none';
                }
                
                // Tampilkan notifikasi reset
                showResetNotification();
                
                // Fokus ke field pertama
                document.getElementById('nama').focus();
                
                // Scroll ke atas form dengan smooth
                setTimeout(() => {
                    window.scrollTo({
                        top: document.querySelector('.form-card').offsetTop - 100,
                        behavior: 'smooth'
                    });
                }, 300);
            }
        }
        
        // Manual reset form
        function resetFormManually() {
            if (confirm('Apakah Anda yakin ingin mengosongkan semua field?')) {
                // Tambahkan efek animasi
                const form = document.getElementById('createUserForm');
                form.classList.add('shake');
                
                setTimeout(() => {
                    form.classList.remove('shake');
                }, 500);
                
                resetFormAfterSubmit();
            }
        }
        
        // Tampilkan notifikasi reset form
        function showResetNotification() {
            const notification = document.getElementById('reset-notification');
            if (notification) {
                notification.classList.remove('hidden');
                
                // Auto hide setelah 5 detik
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        notification.classList.add('hidden');
                        notification.style.opacity = '1';
                    }, 500);
                }, 5000);
            }
        }
        
        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Password strength checking
            const passwordField = document.getElementById('password');
            if (passwordField) {
                passwordField.addEventListener('input', function() {
                    checkPasswordStrength(this.value);
                    checkPasswordMatch();
                });
            }
            
            // Password confirmation checking
            const confirmPasswordField = document.getElementById('password_confirmation');
            if (confirmPasswordField) {
                confirmPasswordField.addEventListener('input', checkPasswordMatch);
            }
            
            // Role change handler
            const roleField = document.getElementById('peran');
            if (roleField) {
                roleField.addEventListener('change', function() {
                    updateRoleBadge(this.value);
                });
            }
            
            // Phone number formatting
            const phoneField = document.getElementById('telepon');
            if (phoneField) {
                phoneField.addEventListener('input', function(e) {
                    this.value = formatPhoneNumber(e.target.value);
                });
            }
            
            // Initialize role badge dengan old value
            updateRoleBadge(document.getElementById('peran').value);
            
            // Cek jika ada session success, maka auto reset
            @if(session('success'))
                // Tunggu 1 detik lalu reset form
                setTimeout(() => {
                    resetFormAfterSubmit();
                }, 1000);
            @endif
            
            // Validasi form sebelum submit
            const form = document.getElementById('createUserForm');
            form.addEventListener('submit', function(e) {
                // Validasi password match
                if (!checkPasswordMatch()) {
                    e.preventDefault();
                    alert('Password dan Konfirmasi Password tidak cocok. Silakan periksa kembali.');
                    
                    // Tambahkan efek pada password field
                    const passwordFields = [document.getElementById('password'), 
                                          document.getElementById('password_confirmation')];
                    passwordFields.forEach(field => {
                        field.style.borderColor = '#ef4444';
                        field.classList.add('shake');
                        setTimeout(() => {
                            field.classList.remove('shake');
                        }, 500);
                    });
                    return false;
                }
                
                // Validasi strength password (opsional)
                const password = document.getElementById('password').value;
                if (password.length < 6) {
                    e.preventDefault();
                    alert('Password minimal 6 karakter.');
                    return false;
                }
                
                return true;
            });
        });
    </script>
</body>
</html>