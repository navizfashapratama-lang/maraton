<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Marathon Events</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <style>
        /* Custom Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(102, 126, 234, 0.3); }
            50% { box-shadow: 0 0 40px rgba(102, 126, 234, 0.6); }
        }
        
        @keyframes slideInFromLeft {
            from { transform: translateX(-30px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideInFromRight {
            from { transform: translateX(30px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes fadeInUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: 200px 0; }
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        @keyframes countdown {
            from { width: 100%; }
            to { width: 0%; }
        }
        
        /* Apply Animations */
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        .animate-pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        .animate-slide-left {
            animation: slideInFromLeft 0.6s ease-out;
        }
        
        .animate-slide-right {
            animation: slideInFromRight 0.6s ease-out;
        }
        
        .animate-fade-up {
            animation: fadeInUp 0.5s ease-out;
        }
        
        .animate-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        .animate-bounce {
            animation: bounce 1s infinite;
        }
        
        .animate-rotate {
            animation: rotate 20s linear infinite;
        }
        
        .animate-countdown {
            animation: countdown 5s linear forwards;
        }
        
        /* Custom Styles */
        .gradient-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 1rem;
            overflow: hidden;
            position: relative;
        }
        
        .stat-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }
        
        .stat-card:hover::before {
            transform: translateX(100%);
        }
        
        .action-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 1rem;
            overflow: hidden;
        }
        
        .action-card:hover {
            transform: translateY(-10px) rotateX(10deg);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .icon-wrapper {
            transition: all 0.3s ease;
        }
        
        .action-card:hover .icon-wrapper {
            transform: scale(1.1) rotate(5deg);
        }
        
        .table-row-hover {
            transition: all 0.2s ease;
        }
        
        .table-row-hover:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.05), transparent);
            transform: translateX(5px);
        }
        
        .badge-pulse {
            position: relative;
        }
        
        .badge-pulse::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 9999px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(var(--color-rgb), 0.4); }
            50% { box-shadow: 0 0 0 10px rgba(var(--color-rgb), 0); }
        }
        
        /* Custom Scrollbar */
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
        
        /* Loading Skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Ripple Effect */
        .ripple {
            position: relative;
            overflow: hidden;
        }
        
        .ripple::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        
        .ripple:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            20% {
                transform: scale(25, 25);
                opacity: 0.3;
            }
            100% {
                opacity: 0;
                transform: scale(40, 40);
            }
        }
        
        /* Logout Modal Styles */
        .modal-backdrop {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        
        .modal-content {
            transform-origin: center;
            transition: all 0.3s ease;
        }
        
        /* Loading Spinner for Logout */
        .logout-spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #dc2626;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Loading Screen -->
    <div id="loadingScreen" class="fixed inset-0 bg-white z-50 flex flex-col items-center justify-center">
        <div class="relative">
            <div class="w-24 h-24 rounded-full border-4 border-gray-200 border-t-blue-500 animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-running text-3xl text-blue-600"></i>
            </div>
        </div>
        <div class="mt-8 text-center">
            <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Marathon Events
            </h2>
            <p class="text-gray-600 mt-2 animate-pulse">Memuat dashboard admin...</p>
        </div>
    </div>

   <!-- Logout Confirmation Modal -->
<div id="logoutModal" class="fixed inset-0 z-[60] modal-backdrop hidden items-center justify-center p-4">
    <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-md scale-95" id="modalContent">
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-sign-out-alt text-red-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Konfirmasi Logout</h3>
                    <p class="text-gray-600 text-sm">Anda akan keluar dari sistem</p>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="mb-6">
                <p class="text-gray-700 mb-3">
                    Apakah Anda yakin ingin keluar dari dashboard admin?
                </p>
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded-r">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-2"></i>
                        <div>
                            <p class="text-sm text-yellow-800 font-medium">Perhatian!</p>
                            <p class="text-xs text-yellow-700">Semua perubahan yang belum disimpan akan hilang.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer dengan Form Laravel -->
            <div class="flex space-x-3">
                <button onclick="closeLogoutModal()" 
                        id="cancelLogoutBtn"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-3 rounded-xl transition duration-300">
                    <div class="flex items-center justify-center space-x-2">
                        <i class="fas fa-times"></i>
                        <span>Batal</span>
                    </div>
                </button>
                
                <!-- Form Logout Laravel -->
                <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            id="confirmLogoutBtn"
                            class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 rounded-xl transition duration-300 shadow-lg hover:shadow-xl">
                        <div class="flex items-center justify-center space-x-2" id="logoutBtnContent">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Ya, Keluar</span>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Logout Loading Overlay -->
<div id="logoutLoading" class="fixed inset-0 bg-black/80 z-[70] hidden flex-col items-center justify-center">
    <div class="text-center">
        <div class="relative mb-6">
            <div class="w-20 h-20 logout-spinner"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-sign-out-alt text-white text-2xl"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold text-white mb-2">Logging Out...</h3>
        <p class="text-gray-300">Mengarahkan ke halaman login</p>
        <div class="mt-4 w-64 bg-gray-700 rounded-full h-2 overflow-hidden">
            <div class="bg-white h-full rounded-full animate-countdown"></div>
        </div>
    </div>
</div>

    <div class="min-h-screen">
        <!-- Welcome Header dengan Animasi -->
        <header class="gradient-header shadow-2xl animate-fade-up" data-aos="fade-down">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-6 md:space-y-0">
                    <div class="animate-slide-left">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="bg-white/20 p-2 rounded-xl">
                                <i class="fas fa-user-shield text-white text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl md:text-4xl font-bold text-white mb-1">
                                    Selamat datang, <span class="text-yellow-300">{{ session('user_nama') }}! 👋</span>
                                </h1>
                                <p class="text-white/80">
                                    Anda login sebagai <span class="font-semibold">{{ ucfirst(session('user_peran')) }}</span> Marathon Events
                                </p>
                                <div class="flex items-center space-x-2 mt-2 text-white/60 text-sm">
                                    <i class="fas fa-envelope"></i>
                                    <span>{{ session('user_email') }}</span>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock"></i>
                                    <span id="currentTime">{{ date('H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
               <div class="animate-slide-right">
    <div class="relative">
        <div class="bg-white/20 p-6 rounded-2xl backdrop-blur-sm">
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-4">
                <div class="relative">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-400 to-purple-400 flex items-center justify-center text-white text-xl font-bold shadow-lg">
                        {{ strtoupper(substr(session('user_nama'), 0, 1)) }}
                    </div>
                    <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white"></div>
                </div>
                
                <div class="text-white text-center md:text-left">
                    <div class="text-xs opacity-75 mb-1">Sesi Aktif</div>
                    <div class="text-lg font-bold mb-1">{{ session('user_nama') }}</div>
                    <div class="text-sm opacity-90 flex items-center justify-center md:justify-start space-x-2 mb-3">
                        <i class="fas fa-shield-alt text-yellow-300"></i>
                        <span>Admin • Full Access</span>
                    </div>
                    
                    <!-- Link Profil yang Dirapikan -->
                    <a href="{{ route('admin.profile') }}" 
                       class="flex items-center space-x-3 px-4 py-3 bg-white/10 hover:bg-white/20 rounded-xl transition duration-300 group">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-400 flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr(session('user_nama'), 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-white">{{ session('user_nama') }}</div>
                            <div class="text-xs opacity-80">Admin Profile</div>
                        </div>
                        <i class="fas fa-chevron-right text-white/50 group-hover:text-white transition-transform group-hover:translate-x-1"></i>
                    </a>
                    
                    <!-- Tombol Logout yang Dirapikan -->
                    <div class="mt-4">
                        <button onclick="confirmLogout()" 
                                class="group relative bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-3 rounded-xl font-medium text-sm shadow-lg transition-all duration-300 overflow-hidden w-full">
                            
                            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                            
                            <div class="relative flex items-center justify-center space-x-2">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Keluar dari Sistem</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Stats Cards dengan Progress Bar STATIS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @php
                    // Progress bar STATIS (tidak berubah berdasarkan data)
                    $default_progress = 75; // 75% untuk semua
                @endphp
                
                @foreach([
                    ['color' => 'blue', 'icon' => 'users', 'count' => $total_users ?? 0, 'label' => 'TOTAL PENGGUNA', 'desc' => 'Pengguna terdaftar'],
                    ['color' => 'green', 'icon' => 'calendar-alt', 'count' => $total_events ?? 0, 'label' => 'TOTAL EVENT', 'desc' => 'Event marathon'],
                    ['color' => 'cyan', 'icon' => 'clipboard-list', 'count' => $total_registrations ?? 0, 'label' => 'TOTAL PENDAFTARAN', 'desc' => 'Pendaftaran event'],
                    ['color' => 'yellow', 'icon' => 'money-bill-wave', 'count' => 'Rp ' . number_format($total_revenue ?? 0, 0, ',', '.'), 'label' => 'TOTAL PENDAPATAN', 'desc' => 'Pembayaran terverifikasi'],
                ] as $index => $stat)
                <div class="bg-white shadow-lg rounded-lg" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stat['count'] }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-{{ $stat['color'] }}-100 flex items-center justify-center">
                                <i class="fas fa-{{ $stat['icon'] }} text-{{ $stat['color'] }}-600"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">{{ $stat['desc'] }}</p>
                        
                        <!-- Progress Bar STATIS (tidak berubah) -->
                        <div class="mt-3">
                            <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-{{ $stat['color'] }}-500 rounded-full" style="width: {{ $default_progress }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-400 mt-1">
                                <span>Status</span>
                                <span>{{ $default_progress }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

           <!-- Quick Actions dengan Animasi -->
<div class="bg-white rounded-2xl shadow-xl mb-8 overflow-hidden" data-aos="fade-up">
    <div class="p-6 border-b border-gray-100">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-2 rounded-lg">
                    <i class="fas fa-bolt text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Aksi Cepat</h2>
                    <p class="text-gray-600 text-sm">Akses cepat ke menu utama</p>
                </div>
            </div>
            <div class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                <i class="fas fa-clock mr-1"></i>
                <span>Real-time</span>
            </div>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            @php
                $actions = [
                    [
                        'color' => 'blue',
                        'icon' => 'users',
                        'title' => 'Kelola Pengguna',
                        'description' => 'Kelola semua user & staff',
                        'badge' => 'Manage',
                        'route' => 'admin.users.index',
                        'gradient' => 'from-blue-500 to-blue-600'
                    ],
                    [
                        'color' => 'green',
                        'icon' => 'calendar-plus',
                        'title' => 'Kelola Event',
                        'description' => 'Lihat semua event marathon',
                        'badge' => 'View',
                        'route' => 'admin.events.index',
                        'gradient' => 'from-green-500 to-green-600'
                    ],
                    [
                        'color' => 'cyan',
                        'icon' => 'clipboard-check',
                        'title' => 'Verifikasi',
                        'description' => 'Verifikasi pendaftaran',
                        'badge' => 'Verify',
                        'route' => 'admin.registrations.index',
                        'gradient' => 'from-cyan-500 to-cyan-600'
                    ],
                    [
                        'color' => 'orange',
                        'icon' => 'chart-line',
                        'title' => 'Laporan',
                        'description' => 'Lihat laporan lengkap',
                        'badge' => 'Report',
                        'route' => 'admin.payments.index',
                        'gradient' => 'from-orange-500 to-orange-600'
                    ],
                    [
                        'color' => 'purple',
                        'icon' => 'external-link-alt',
                        'title' => 'Website',
                        'description' => 'Buka website utama',
                        'badge' => 'Visit',
                        'route' => '/',
                        'gradient' => 'from-purple-500 to-purple-600',
                        'target' => '_blank'
                    ]
                ];
            @endphp
            
            @foreach($actions as $index => $action)
                @php
                    $isExternal = isset($action['target']) && $action['target'] == '_blank';
                    $href = $isExternal ? url($action['route']) : route($action['route']);
                @endphp
                
                <a href="{{ $href }}"
                   @if($isExternal) target="_blank" @endif
                   class="group block bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden hover:-translate-y-1"
                   data-aos="zoom-in"
                   data-aos-delay="{{ $index * 100 }}">
                    
                    <div class="p-6 text-center">
                        <!-- Icon -->
                        <div class="mb-5">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-r {{ $action['gradient'] }} flex items-center justify-center text-white text-2xl shadow-lg mx-auto group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-{{ $action['icon'] }}"></i>
                            </div>
                        </div>
                        
                        <!-- Title -->
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 group-hover:text-gray-900 transition-colors duration-300">
                            {{ $action['title'] }}
                        </h3>
                        
                        <!-- Description -->
                        <p class="text-gray-600 text-sm mb-4 leading-relaxed group-hover:text-gray-700 transition-colors duration-300">
                            {{ $action['description'] }}
                        </p>
                        
                        <!-- Badge -->
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-{{ $action['color'] }}-50 text-{{ $action['color'] }}-700 border border-{{ $action['color'] }}-200 group-hover:bg-{{ $action['color'] }}-100 group-hover:border-{{ $action['color'] }}-300 transition-colors duration-300">
                            {{ $action['badge'] }}
                        </span>
                        
                        <!-- Action Indicator -->
                        <div class="mt-5 pt-4 border-t border-gray-100 group-hover:border-gray-200 transition-colors duration-300">
                            <div class="text-xs text-gray-500 flex items-center justify-center group-hover:text-gray-600 transition-colors duration-300">
                                @if($isExternal)
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    <span>Buka di tab baru</span>
                                @else
                                    <i class="fas fa-arrow-right mr-2"></i>
                                    <span>Klik untuk membuka</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
            <!-- Recent Data Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Events -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden" data-aos="fade-right">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-2 rounded-lg">
                                    <i class="fas fa-calendar text-white text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">Event Mendatang</h2>
                                    <p class="text-gray-600 text-sm">Event marathon terdekat</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.events.index') }}" 
                               class="btn-gradient-blue text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 hover:shadow-lg">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    
                    <div class="overflow-hidden">
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Event</th>
                                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lokasi</th>
                                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($recent_events as $index => $event)
                                    <tr class="table-row-hover hover:bg-blue-50 transition-all duration-200 cursor-pointer"
                                        onclick="window.location='{{ route('admin.events.edit', $event->id ?? '#') }}'"
                                        data-aos="fade-right"
                                        data-aos-delay="{{ $index * 50 }}">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-r from-blue-400 to-blue-500 flex items-center justify-center text-white">
                                                        <i class="fas fa-running"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-800">{{ $event->nama ?? 'N/A' }}</div>
                                                    <div class="text-xs text-gray-500">{{ $event->kategori ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex flex-col">
                                                <span class="font-medium text-gray-800">
                                                    {{ $event->tanggal ? \Carbon\Carbon::parse($event->tanggal)->format('d/m/Y') : 'N/A' }}
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    {{ $event->tanggal ? \Carbon\Carbon::parse($event->tanggal)->diffForHumans() : 'N/A' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-gray-700">
                                                <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                                {{ $event->lokasi ? Str::limit($event->lokasi, 20) : 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="relative">
                                                @php
                                                    $status = $event->status ?? 'unknown';
                                                    $statusColors = [
                                                        'mendatang' => 'bg-green-100 text-green-800',
                                                        'selesai' => 'bg-gray-100 text-gray-800',
                                                        'dibatalkan' => 'bg-red-100 text-red-800',
                                                        'unknown' => 'bg-gray-100 text-gray-800'
                                                    ];
                                                    $statusColor = $statusColors[$status] ?? $statusColors['unknown'];
                                                @endphp
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                                @if($status == 'mendatang')
                                                <span class="absolute -top-1 -right-1 w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-gray-400">
                                                <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                                <p class="text-lg">Tidak ada event mendatang</p>
                                                <p class="text-sm mt-1">Coba tambahkan event baru</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Registrations -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden" data-aos="fade-left">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div class="bg-gradient-to-r from-green-500 to-green-600 p-2 rounded-lg">
                                    <i class="fas fa-user-plus text-white text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">Pendaftaran Terbaru</h2>
                                    <p class="text-gray-600 text-sm">Pendaftaran terbaru yang perlu diverifikasi</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.registrations.index') }}" 
                               class="btn-gradient-green text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 hover:shadow-lg">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    
                    <div class="p-4">
                        <div class="space-y-4 max-h-96 overflow-y-auto custom-scrollbar pr-2">
                            @forelse($recent_registrations as $index => $registration)
                            <div class="bg-gray-50 hover:bg-gradient-to-r hover:from-green-50 hover:to-transparent rounded-xl p-4 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-md cursor-pointer"
                                 onclick="window.location='{{ route('admin.registrations.view', $registration->id ?? '#') }}'"
                                 data-aos="fade-left"
                                 data-aos-delay="{{ $index * 50 }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-start space-x-3">
                                        <div class="relative">
                                            <div class="w-14 h-14 rounded-full bg-gradient-to-r from-green-400 to-green-500 flex items-center justify-center text-white">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            @if(($registration->status ?? '') == 'menunggu')
                                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-yellow-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-exclamation text-white text-xs"></i>
                                            </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800">{{ $registration->user_nama ?? 'N/A' }}</h4>
                                            <p class="text-gray-600 text-sm mb-1">{{ $registration->event_nama ?? 'N/A' }}</p>
                                            <div class="flex items-center space-x-2 text-xs">
                                                <span class="text-gray-500">
                                                    <i class="far fa-clock mr-1"></i>
                                                    {{ $registration->created_at ? \Carbon\Carbon::parse($registration->created_at)->diffForHumans() : 'N/A' }}
                                                </span>
                                                <span class="text-gray-500">•</span>
                                                <span class="text-gray-500">
                                                    ID: #{{ $registration->id ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        @php
                                            $status = $registration->status ?? 'unknown';
                                            $statusColors = [
                                                'disetujui' => 'bg-green-100 text-green-800',
                                                'menunggu' => 'bg-yellow-100 text-yellow-800',
                                                'ditolak' => 'bg-red-100 text-red-800',
                                                'unknown' => 'bg-gray-100 text-gray-800'
                                            ];
                                            $statusColor = $statusColors[$status] ?? $statusColors['unknown'];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                        @if($status == 'menunggu')
                                        <div class="mt-2 text-center">
                                            <button class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg transition duration-200"
                                                    onclick="event.stopPropagation(); verifyRegistration({{ $registration->id ?? 0 }})">
                                                Verifikasi
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-user-clock text-4xl mb-4"></i>
                                    <p class="text-lg">Tidak ada pendaftaran baru</p>
                                    <p class="text-sm mt-1">Semua pendaftaran telah diproses</p>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

    <!-- Notification Toast -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-4"></div>

    <!-- AOS Initialization -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: false,
            offset: 100
        });

        // Hide loading screen
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('loadingScreen').classList.add('opacity-0', 'pointer-events-none');
                setTimeout(() => {
                    document.getElementById('loadingScreen').style.display = 'none';
                }, 500);
            }, 1000);
        });

        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('currentTime').textContent = timeString;
        }
        setInterval(updateTime, 1000);
        updateTime();

        // Toast notification function
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            const toastId = 'toast-' + Date.now();
            
            toast.id = toastId;
            toast.className = `bg-white rounded-xl shadow-2xl p-4 min-w-[300px] transform transition-all duration-500 translate-x-full`;
            toast.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full ${type === 'success' ? 'bg-green-100' : 'bg-red-100'} flex items-center justify-center">
                        <i class="fas ${type === 'success' ? 'fa-check text-green-600' : 'fa-exclamation text-red-600'}"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">${type === 'success' ? 'Sukses!' : 'Perhatian!'}</p>
                        <p class="text-sm text-gray-600">${message}</p>
                    </div>
                    <button onclick="removeToast('${toastId}')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            container.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 10);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                removeToast(toastId);
            }, 5000);
        }
        
        function removeToast(id) {
            const toast = document.getElementById(id);
            if (toast) {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }
        }

        // Simulate verification
        function verifyRegistration(id) {
            if (id === 0) return;
            
            showToast(`Verifikasi pendaftaran #${id} berhasil!`, 'success');
            
            // Add ripple effect to button
            const button = event.target;
            button.classList.add('bg-green-600');
            button.innerHTML = '<i class="fas fa-check mr-1"></i> Terverifikasi';
            button.disabled = true;
            
            // Update status badge
            const badge = button.closest('.bg-gray-50').querySelector('span');
            if (badge) {
                badge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800';
                badge.textContent = 'Disetujui';
            }
            
            // Remove warning icon
            const warningIcon = button.closest('.bg-gray-50').querySelector('.absolute.-top-1.-right-1');
            if (warningIcon) {
                warningIcon.remove();
            }
        }

        // ==================== LOGOUT FUNCTIONS ====================
        function confirmLogout() {
            const modal = document.getElementById('logoutModal');
            const modalContent = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Reset button states
            document.getElementById('cancelLogoutBtn').disabled = false;
            document.getElementById('confirmLogoutBtn').disabled = false;
            document.getElementById('logoutBtnContent').innerHTML = `
                <i class="fas fa-sign-out-alt"></i>
                <span>Ya, Keluar</span>
            `;
            
            // Animation
            setTimeout(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
            
            showToast('Konfirmasi logout diperlukan', 'info');
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            const modalContent = document.getElementById('modalContent');
            
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }

        function performLogout() {
            // Show loading state
            const logoutBtn = document.getElementById('confirmLogoutBtn');
            const cancelBtn = document.getElementById('cancelLogoutBtn');
            
            // Disable buttons
            logoutBtn.disabled = true;
            cancelBtn.disabled = true;
            
            // Show loading in button
            document.getElementById('logoutBtnContent').innerHTML = `
                <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div>
                <span>Memproses...</span>
            `;
            
            // Show loading overlay after 500ms
            setTimeout(() => {
                closeLogoutModal();
                
                setTimeout(() => {
                    const loadingOverlay = document.getElementById('logoutLoading');
                    loadingOverlay.classList.remove('hidden');
                    loadingOverlay.classList.add('flex');
                    
                    // Simulate logout process (5 seconds)
                    setTimeout(() => {
                        // Actually logout - redirect to login page
                        window.location.href = "{{ route('login') }}";
                    }, 5000);
                }, 300);
            }, 500);
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('logoutModal');
                if (!modal.classList.contains('hidden')) {
                    closeLogoutModal();
                }
            }
        });

        // ==================== END LOGOUT FUNCTIONS ====================

        // Check for session messages
        document.addEventListener('DOMContentLoaded', function() {
            // Check for session success/error messages
            @if(session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif
            
            @if(session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif
            
            // Add hover effects to cards
            const cards = document.querySelectorAll('.stat-card, .action-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.zIndex = '10';
                });
                
                card.addEventListener('mouseleave', () => {
                    card.style.zIndex = '1';
                });
            });
            
            // Add click ripple effect
            const links = document.querySelectorAll('a, button');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Add ripple effect
                    const rect = this.getBoundingClientRect();
                    const ripple = document.createElement('span');
                    ripple.className = 'ripple-effect';
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.6);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        width: ${Math.max(rect.width, rect.height)}px;
                        height: ${Math.max(rect.width, rect.height)}px;
                        top: ${e.clientY - rect.top - Math.max(rect.width, rect.height) / 2}px;
                        left: ${e.clientX - rect.left - Math.max(rect.width, rect.height) / 2}px;
                        pointer-events: none;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => ripple.remove(), 600);
                });
            });
            
            // Add CSS for ripple effect
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        });

        // Simulate real-time updates
        setInterval(() => {
            // Update random stats
            const statNumbers = document.querySelectorAll('.text-3xl.font-bold');
            statNumbers.forEach(stat => {
                if (!stat.textContent.includes('Rp')) {
                    const current = parseInt(stat.textContent.replace(/,/g, ''));
                    if (!isNaN(current)) {
                        const change = Math.floor(Math.random() * 3);
                        stat.textContent = (current + change).toLocaleString();
                    }
                }
            });
        }, 30000); // Update every 30 seconds

        // Add gradient button classes
        const buttonStyle = document.createElement('style');
        buttonStyle.textContent = `
            .btn-gradient-blue {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .btn-gradient-green {
                background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            }
            .btn-gradient-blue:hover, .btn-gradient-green:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }
        `;
        document.head.appendChild(buttonStyle);
    </script>
</body>
</html> 