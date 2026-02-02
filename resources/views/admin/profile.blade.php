<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Marathon Events</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <style>
        /* Animasi yang lebih ringan */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        .animate-slide-up {
            animation: slideUp 0.4s ease-out;
        }
        
        /* Custom Styles */
        .gradient-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #667eea, #764ba2);
            border-radius: 10px;
        }
        
        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: 200px 0; }
        }
        
        /* Optimize animations */
        .optimized-float {
            will-change: transform;
            transform: translateZ(0);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Loading Screen yang Lebih Cepat -->
    <div id="loadingScreen" class="fixed inset-0 bg-white z-50 flex flex-col items-center justify-center animate-fade-in">
        <div class="relative">
            <div class="w-16 h-16 rounded-full border-3 border-gray-200 border-t-blue-500 animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-user-circle text-2xl text-blue-600"></i>
            </div>
        </div>
        <div class="mt-6 text-center">
            <h2 class="text-xl font-bold text-gray-800">Memuat Profil</h2>
            <p class="text-gray-600 text-sm mt-1">Mohon tunggu...</p>
        </div>
    </div>

    <!-- Content yang akan dimuat -->
    <div id="content" class="hidden">
        <div class="min-h-screen">
            <!-- Welcome Header -->
            <header class="gradient-header shadow-lg animate-fade-in">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
                        <div class="flex items-center space-x-3">
                            <a href="/admin/dashboard" 
                               class="bg-white/20 hover:bg-white/30 p-2 rounded-xl transition duration-200">
                                <i class="fas fa-arrow-left text-white text-sm"></i>
                            </a>
                            <div>
                                <h1 class="text-xl md:text-2xl font-bold text-white">
                                    Profil <span class="text-yellow-300">{{ session('user_nama') }}</span>
                                </h1>
                                <p class="text-white/80 text-sm">
                                    Dashboard Admin
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <div class="text-white text-right">
                                <div class="text-xs opacity-75">{{ date('d F Y') }}</div>
                                <div class="text-xs opacity-60 flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span id="currentTime">{{ date('H:i') }}</span>
                                </div>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-400 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr(session('user_nama'), 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <!-- Profile Overview - Sederhana dan Cepat -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Profile Card -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-lg overflow-hidden animate-slide-up">
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
                                <!-- Avatar -->
                                <div class="relative">
                                    <div class="w-24 h-24 rounded-full bg-gradient-to-r from-blue-400 to-purple-400 flex items-center justify-center text-white text-3xl font-bold border-4 border-white shadow-md">
                                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                                    </div>
                                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-3 border-white">
                                        <i class="fas fa-check text-xs text-white flex items-center justify-center h-full"></i>
                                    </div>
                                </div>
                                
                                <!-- User Details -->
                                <div class="flex-1 text-center sm:text-left">
                                    <h2 class="text-2xl font-bold text-gray-800">{{ $user->nama }}</h2>
                                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-2">
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                            {{ ucfirst($user->peran) }}
                                        </span>
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 mt-3 flex items-center justify-center sm:justify-start">
                                        <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                        <span>{{ $user->email }}</span>
                                    </p>
                                    
                                    <!-- Quick Stats -->
                                    <div class="grid grid-cols-3 gap-3 mt-6">
                                        <div class="bg-blue-50 rounded-lg p-3 text-center">
                                            <div class="text-lg font-bold text-blue-600">{{ $performance['verifications_today'] ?? 0 }}</div>
                                            <div class="text-xs text-gray-600">Hari Ini</div>
                                        </div>
                                        <div class="bg-green-50 rounded-lg p-3 text-center">
                                            <div class="text-lg font-bold text-green-600">{{ $performance['verifications_month'] ?? 0 }}</div>
                                            <div class="text-xs text-gray-600">Bulan Ini</div>
                                        </div>
                                        <div class="bg-purple-50 rounded-lg p-3 text-center">
                                            <div class="text-lg font-bold text-purple-600">{{ count($admin_activities) ?? 0 }}</div>
                                            <div class="text-xs text-gray-600">Aktivitas</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                                <button onclick="openEditModal()" 
                                        class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-medium hover:opacity-90 transition flex items-center space-x-2">
                                    <i class="fas fa-edit"></i>
                                    <span>Edit Profil</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="space-y-4">
                        <!-- Status -->
                        <div class="bg-white rounded-xl shadow-lg p-4">
                            <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                Status Akun
                            </h3>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Terdaftar</span>
                                    <span class="font-semibold text-blue-600">
                                        {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Login Terakhir</span>
                                    <span class="font-semibold text-green-600">
                                        {{ $user->terakhir_login ? \Carbon\Carbon::parse($user->terakhir_login)->diffForHumans() : 'Belum pernah' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="bg-white rounded-xl shadow-lg p-4">
                            <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-phone text-green-500 mr-2"></i>
                                Kontak
                            </h3>
                            <div class="space-y-2">
                                <div class="text-sm">
                                    <div class="text-gray-600">Telepon</div>
                                    <div class="font-medium text-gray-800">{{ $user->telepon ?: '-' }}</div>
                                </div>
                                <div class="text-sm">
                                    <div class="text-gray-600">Alamat</div>
                                    <div class="font-medium text-gray-800 truncate">{{ $user->alamat ?: '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-slide-up">
                    <div class="p-4 border-b">
                        <h2 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="fas fa-history text-purple-500 mr-2"></i>
                            Aktivitas Terbaru
                        </h2>
                    </div>
                    
                    <div class="p-4">
                        <div class="space-y-3 max-h-60 overflow-y-auto custom-scrollbar pr-2">
                            @forelse($admin_activities as $activity)
                            <div class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded-lg transition">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                                    @php
                                        $icon = 'fa-history';
                                        if (strpos($activity->aksi, 'verif') !== false) $icon = 'fa-check';
                                        if (strpos($activity->aksi, 'edit') !== false) $icon = 'fa-edit';
                                        if (strpos($activity->aksi, 'hapus') !== false) $icon = 'fa-trash';
                                    @endphp
                                    <i class="fas {{ $icon }} text-purple-600 text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-800 font-medium">
                                        {{ ucfirst(str_replace('_', ' ', $activity->aksi)) }}
                                    </p>
                                    <p class="text-xs text-gray-600 mt-1 truncate">
                                        {{ $activity->deskripsi }}
                                    </p>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4 text-gray-400">
                                <i class="fas fa-history text-2xl mb-2"></i>
                                <p class="text-sm">Belum ada aktivitas</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Edit Profile Modal (Sederhana) -->
        <div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
            <div class="bg-white rounded-xl w-full max-w-md transform scale-95 transition-transform duration-200">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Edit Profil</h3>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <form id="editForm" action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" 
                                   name="nama" 
                                   value="{{ $user->nama }}" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                            <input type="text" 
                                   name="telepon" 
                                   value="{{ $user->telepon ?? '' }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                                   placeholder="0812-3456-7890">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea name="alamat" 
                                      rows="2" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                                      placeholder="Jl. Contoh No. 123, Kota">{{ $user->alamat ?? '' }}</textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3 pt-4 border-t">
                            <button type="button" 
                                    onclick="closeEditModal()" 
                                    class="px-4 py-2 text-gray-700 hover:text-gray-900 text-sm font-medium hover:bg-gray-100 rounded-lg transition">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition text-sm flex items-center space-x-2">
                                <i class="fas fa-save"></i>
                                <span>Simpan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- AOS Initialization -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Optimasi: Load konten dengan cepat
        document.addEventListener('DOMContentLoaded', function() {
            // Sembunyikan loading screen dengan cepat
            setTimeout(() => {
                const loadingScreen = document.getElementById('loadingScreen');
                const content = document.getElementById('content');
                
                loadingScreen.style.opacity = '0';
                content.classList.remove('hidden');
                
                setTimeout(() => {
                    loadingScreen.style.display = 'none';
                    content.classList.add('animate-fade-in');
                    
                    // Initialize AOS dengan delay minimal
                    if (typeof AOS !== 'undefined') {
                        AOS.init({
                            duration: 300,
                            once: true,
                            offset: 50
                        });
                    }
                }, 200);
            }, 300); // Waktu loading yang lebih pendek
            
            // Update waktu
            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('id-ID', { 
                    hour: '2-digit', 
                    minute: '2-digit'
                });
                const timeElement = document.getElementById('currentTime');
                if (timeElement) {
                    timeElement.textContent = timeString;
                }
            }
            setInterval(updateTime, 60000); // Update setiap menit
            updateTime();
            
            // Toast notification sederhana
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 px-4 py-3 rounded-lg text-white text-sm font-medium animate-slide-up ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
                toast.textContent = message;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }
            
            // Form submission
            const editForm = document.getElementById('editForm');
            if (editForm) {
                editForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
                    submitBtn.disabled = true;
                    
                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            showToast('Profil berhasil diperbarui!', 'success');
                            closeEditModal();
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showToast(data.message || 'Terjadi kesalahan', 'error');
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    } catch (error) {
                        showToast('Terjadi kesalahan jaringan', 'error');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                });
            }
        });
        
        // Modal functions
        function openEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            setTimeout(() => {
                modal.querySelector('.transform').classList.remove('scale-95');
                modal.querySelector('.transform').classList.add('scale-100');
            }, 10);
        }
        
        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.querySelector('.transform').classList.remove('scale-100');
            modal.querySelector('.transform').classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 200);
        }
        
        // Close modal on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('editModal');
                if (!modal.classList.contains('hidden')) {
                    closeEditModal();
                }
            }
        });
    </script>
</body>
</html>