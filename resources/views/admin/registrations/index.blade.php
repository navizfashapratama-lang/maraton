<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Pendaftaran - Marathon Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        /* Animations */
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
        
        @keyframes slideInRight {
            from {
                transform: translateX(20px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideInLeft {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }
        
        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: 200px 0; }
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 15px rgba(59, 130, 246, 0.3); }
            50% { box-shadow: 0 0 25px rgba(59, 130, 246, 0.5); }
        }
        
        /* Apply Animations */
        .animate-fade-up { animation: fadeInUp 0.6s ease-out; }
        .animate-slide-right { animation: slideInRight 0.5s ease-out; }
        .animate-slide-left { animation: slideInLeft 0.5s ease-out; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }
        
        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Status Badges */
        .status-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.025em;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .status-menunggu {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
        }
        
        .status-disetujui {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .status-ditolak {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        /* Card Hover Effects */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        /* Table Row Hover */
        .table-row-hover {
            transition: all 0.2s ease;
        }
        
        .table-row-hover:hover {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.05) 0%, transparent 100%);
        }
        
        /* Modal Animation */
        .modal {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        
        .modal.active {
            opacity: 1;
            pointer-events: auto;
        }
        
        .modal-content {
            transform: scale(0.95);
            transition: transform 0.3s ease;
        }
        
        .modal.active .modal-content {
            transform: scale(1);
        }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
            border-radius: 10px;
        }
        
        /* Loading Skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-white z-50 flex flex-col items-center justify-center">
        <div class="relative">
            <div class="w-20 h-20 rounded-full border-4 border-gray-200 border-t-blue-500 animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-clipboard-check text-2xl text-blue-600"></i>
            </div>
        </div>
        <div class="mt-6 text-center">
            <h2 class="text-xl font-bold gradient-text">Marathon Events</h2>
            <p class="text-gray-600 mt-2 animate-pulse">Memuat data verifikasi...</p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="min-h-screen">
          <!-- Header dengan animasi -->
<header class="bg-white shadow-lg sticky top-0 z-40 animate-fadeInUp">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <div class="flex items-center space-x-3 animate-slideInRight">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-3 rounded-xl animate-float">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        Verifikasi Pendaftaran

                    </h1>
                    <p class="text-gray-600 mt-1 text-sm md:text-base">Kelola dan verifikasi semua pendaftaran marathon
</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Tombol Kembali ke Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                    class="group bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-medium py-3 px-5 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 animate-slideInLeft"
                    <i class="fas fa-tachometer-alt mr-2 group-hover:rotate-12 transition-transform duration-300"></i>
                    Kembali 
                </a>
                    
                    <!-- Actions -->
                    <div class="animate-slide-right">
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                            
                            <!-- Admin Info -->
                            <div class="flex items-center space-x-3">
                                <div class="hidden md:flex items-center bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-200">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr(session('user_nama'), 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-800">{{ session('user_nama') }}</p>
                                        <p class="text-xs text-gray-500">Administrator</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Menunggu Card -->
                <div class="bg-white rounded-2xl shadow-lg card-hover" data-aos="fade-up" data-aos-delay="100">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Menunggu Verifikasi</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['pending'] }}</p>
                            </div>
                            <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full" 
                                 style="width: {{ $stats['total'] > 0 ? ($stats['pending'] / $stats['total'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Disetujui Card -->
                <div class="bg-white rounded-2xl shadow-lg card-hover" data-aos="fade-up" data-aos-delay="200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Disetujui</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['approved'] }}</p>
                            </div>
                            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-green-500 to-emerald-500 rounded-full" 
                                 style="width: {{ $stats['total'] > 0 ? ($stats['approved'] / $stats['total'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Ditolak Card -->
                <div class="bg-white rounded-2xl shadow-lg card-hover" data-aos="fade-up" data-aos-delay="300">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Ditolak</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['rejected'] }}</p>
                            </div>
                            <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-times-circle text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-red-500 to-rose-500 rounded-full" 
                                 style="width: {{ $stats['total'] > 0 ? ($stats['rejected'] / $stats['total'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Card -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-lg card-hover" data-aos="fade-up" data-aos-delay="400">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-white/80">Total Pendaftaran</p>
                                <p class="text-3xl font-bold text-white mt-1">{{ $stats['total'] }}</p>
                            </div>
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                <i class="fas fa-clipboard-list text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="h-2 bg-white/30 rounded-full overflow-hidden">
                            <div class="h-full bg-white rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 animate-fade-up">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
                    <div class="mb-4 lg:mb-0">
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Daftar Pendaftaran</h2>
                        <p class="text-gray-600">Filter dan kelola pendaftaran peserta</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                        <!-- Search -->
                        <div class="relative">
                            <input type="text" 
                                   id="searchInput" 
                                   placeholder="Cari kode atau nama..." 
                                   class="pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full lg:w-64 transition-all duration-300">
                            <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                            <button onclick="clearSearch()" class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600 hidden" id="clearSearchBtn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <!-- Status Filter -->
                        <div class="relative">
                            <select id="statusFilter" 
                                    class="pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full appearance-none">
                                <option value="">Semua Status</option>
                                <option value="menunggu">Menunggu</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                            <i class="fas fa-filter absolute left-4 top-3.5 text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Filters -->
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterByStatus('')" 
                            id="filterAll"
                            class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-layer-group mr-2"></i>Semua ({{ $stats['total'] }})
                    </button>
                    
                    <button onclick="filterByStatus('menunggu')" 
                            id="filterPending"
                            class="px-4 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-clock mr-2"></i>Menunggu ({{ $stats['pending'] }})
                    </button>
                    
                    <button onclick="filterByStatus('disetujui')" 
                            id="filterApproved"
                            class="px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-check-circle mr-2"></i>Disetujui ({{ $stats['approved'] }})
                    </button>
                    
                    <button onclick="filterByStatus('ditolak')" 
                            id="filterRejected"
                            class="px-4 py-2.5 bg-gradient-to-r from-red-500 to-rose-500 hover:from-red-600 hover:to-rose-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-times-circle mr-2"></i>Ditolak ({{ $stats['rejected'] }})
                    </button>
                </div>
            </div>

            <!-- Registrations Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-fade-up">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-blue-50">
                                <th class="py-4 px-6 text-left">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</span>
                                        <i class="fas fa-sort text-gray-400 text-xs"></i>
                                    </div>
                                </th>
                                <th class="py-4 px-6 text-left">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Peserta</span>
                                        <i class="fas fa-sort text-gray-400 text-xs"></i>
                                    </div>
                                </th>
                                <th class="py-4 px-6 text-left">
                                    <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Event</span>
                                </th>
                                <th class="py-4 px-6 text-left">
                                    <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</span>
                                </th>
                                <th class="py-4 px-6 text-left">
                                    <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Paket</span>
                                </th>
                                <th class="py-4 px-6 text-left">
                                    <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</span>
                                </th>
                                <th class="py-4 px-6 text-left">
                                    <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($registrations as $registration)
                            <tr class="table-row-hover" 
                                data-status="{{ $registration->status }}" 
                                data-search="{{ strtolower($registration->kode_pendaftaran . ' ' . $registration->user_nama . ' ' . $registration->user_email) }}"
                                onclick="window.location.href='{{ route('admin.registrations.view', $registration->id) }}'"
                                style="cursor: pointer;">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-100 to-blue-50 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-hashtag text-blue-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $registration->kode_pendaftaran ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">ID: #{{ $registration->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                                            {{ strtoupper(substr($registration->user_nama ?? 'N', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $registration->user_nama ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500 mt-0.5">{{ $registration->user_email ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">
                                                <i class="fas fa-phone-alt mr-1"></i>{{ $registration->user_telepon ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-medium text-gray-900">{{ $registration->event_nama ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500 mt-0.5">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ Str::limit($registration->event_lokasi ?? 'N/A', 20) }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-medium text-gray-900">
                                        {{ $registration->created_at ? \Carbon\Carbon::parse($registration->created_at)->format('d M Y') : 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500 mt-0.5">
                                        {{ $registration->created_at ? \Carbon\Carbon::parse($registration->created_at)->format('H:i') : 'N/A' }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-medium text-gray-900">{{ $registration->package_name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500 mt-0.5">
                                        Rp {{ number_format($registration->package_price ?? 0, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @php
                                        $statusConfig = [
                                            'menunggu' => ['class' => 'status-menunggu', 'icon' => 'clock', 'text' => 'Menunggu'],
                                            'disetujui' => ['class' => 'status-disetujui', 'icon' => 'check-circle', 'text' => 'Disetujui'],
                                            'ditolak' => ['class' => 'status-ditolak', 'icon' => 'times-circle', 'text' => 'Ditolak']
                                        ];
                                        $status = $statusConfig[$registration->status] ?? $statusConfig['menunggu'];
                                    @endphp
                                    <span class="status-badge {{ $status['class'] }}">
                                        <i class="fas fa-{{ $status['icon'] }}"></i>
                                        {{ $status['text'] }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.registrations.view', $registration->id) }}" 
                                           class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-200 transition-colors duration-300"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($registration->status == 'menunggu')
                                        <button onclick="event.stopPropagation(); openVerificationModal({{ $registration->id }}, '{{ addslashes($registration->user_nama) }}')"
                                                class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center hover:bg-green-200 transition-colors duration-300"
                                                title="Setujui">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        
                                        <button onclick="event.stopPropagation(); openRejectionModal({{ $registration->id }}, '{{ addslashes($registration->user_nama) }}')"
                                                class="w-10 h-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-200 transition-colors duration-300"
                                                title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                                            <i class="fas fa-clipboard-list text-4xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Data</h3>
                                        <p class="text-gray-600 max-w-md text-center mb-6">Tidak ada data pendaftaran yang ditemukan. Semua pendaftaran akan muncul di sini.</p>
                                        <div class="flex space-x-3">
                                            <button onclick="refreshPage()" 
                                                    class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-300">
                                                <i class="fas fa-sync-alt mr-2"></i>Refresh
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($registrations->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50">
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                        <div class="text-sm text-gray-600">
                            Menampilkan <span class="font-semibold">{{ $registrations->firstItem() }}</span> sampai <span class="font-semibold">{{ $registrations->lastItem() }}</span> dari <span class="font-semibold">{{ $registrations->total() }}</span> data
                        </div>
                        <div class="flex items-center space-x-2">
                            <!-- Previous Button -->
                            @if($registrations->onFirstPage())
                            <button class="w-10 h-10 bg-gray-100 text-gray-400 rounded-lg flex items-center justify-center cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            @else
                            <a href="{{ $registrations->previousPageUrl() }}" 
                               class="w-10 h-10 bg-white border border-gray-300 text-gray-700 rounded-lg flex items-center justify-center hover:bg-gray-50 transition-colors duration-300">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            @endif
                            
                            <!-- Page Numbers -->
                            @php
                                $current = $registrations->currentPage();
                                $last = $registrations->lastPage();
                                $start = max($current - 2, 1);
                                $end = min($current + 2, $last);
                            @endphp
                            
                            @for($i = $start; $i <= $end; $i++)
                                @if($i == $current)
                                <span class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg flex items-center justify-center font-semibold">
                                    {{ $i }}
                                </span>
                                @else
                                <a href="{{ $registrations->url($i) }}" 
                                   class="w-10 h-10 bg-white border border-gray-300 text-gray-700 rounded-lg flex items-center justify-center hover:bg-gray-50 transition-colors duration-300 font-medium">
                                    {{ $i }}
                                </a>
                                @endif
                            @endfor
                            
                            <!-- Next Button -->
                            @if($registrations->hasMorePages())
                            <a href="{{ $registrations->nextPageUrl() }}" 
                               class="w-10 h-10 bg-white border border-gray-300 text-gray-700 rounded-lg flex items-center justify-center hover:bg-gray-50 transition-colors duration-300">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                            @else
                            <button class="w-10 h-10 bg-gray-100 text-gray-400 rounded-lg flex items-center justify-center cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
    </div>

    <!-- Verification Modal -->
    <div id="verificationModal" class="modal fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6">
                <!-- Modal Header -->
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-14 h-14 bg-gradient-to-r from-green-100 to-emerald-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Verifikasi Pendaftaran</h3>
                        <p class="text-gray-600 text-sm">Konfirmasi persetujuan</p>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <div class="mb-6">
                    <p class="text-gray-700 mb-4">
                        Apakah Anda yakin ingin <span class="font-bold text-green-600">menyetujui</span> pendaftaran dari 
                        <span id="verifyParticipantName" class="font-bold text-gray-800"></span>?
                    </p>
                    
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-500 p-4 rounded-r-lg mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-3 text-lg"></i>
                            <div>
                                <p class="text-sm font-medium text-yellow-800 mb-1">Peringatan</p>
                                <p class="text-xs text-yellow-700">Pastikan data peserta dan pembayaran sudah valid sebelum menyetujui.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex space-x-3">
                    <button onclick="closeVerificationModal()" 
                            id="cancelVerifyBtn"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-3 rounded-xl transition duration-300">
                        <div class="flex items-center justify-center space-x-2">
                            <i class="fas fa-times"></i>
                            <span>Batal</span>
                        </div>
                    </button>
                    <button onclick="submitVerification()" 
                            id="confirmVerifyBtn"
                            class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium py-3 rounded-xl transition duration-300 shadow-lg hover:shadow-xl">
                        <div class="flex items-center justify-center space-x-2" id="verifyBtnContent">
                            <i class="fas fa-check"></i>
                            <span>Setujui</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectionModal" class="modal fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="p-6">
                    <!-- Modal Header -->
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-14 h-14 bg-gradient-to-r from-red-100 to-rose-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Tolak Pendaftaran</h3>
                            <p class="text-gray-600 text-sm">Konfirmasi penolakan</p>
                        </div>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="mb-6">
                        <p class="text-gray-700 mb-4">
                            Apakah Anda yakin ingin <span class="font-bold text-red-600">menolak</span> pendaftaran dari 
                            <span id="rejectParticipantName" class="font-bold text-gray-800"></span>?
                        </p>
                        
                        <!-- Reason Input -->
                        <div class="mb-4">
                            <label for="rejectionReason" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-comment-alt mr-2 text-gray-500"></i>Alasan Penolakan
                            </label>
                            <textarea id="rejectionReason" 
                                      name="reason" 
                                      rows="3" 
                                      class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300"
                                      placeholder="Masukkan alasan penolakan (opsional)..."></textarea>
                        </div>
                        
                        <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-3 text-lg"></i>
                                <div>
                                    <p class="text-sm font-medium text-red-800 mb-1">Peringatan</p>
                                    <p class="text-xs text-red-700">Penolakan tidak dapat dibatalkan. Peserta akan diberitahu via email.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="flex space-x-3">
                        <button type="button" 
                                onclick="closeRejectionModal()" 
                                id="cancelRejectBtn"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-3 rounded-xl transition duration-300">
                            <div class="flex items-center justify-center space-x-2">
                                <i class="fas fa-times"></i>
                                <span>Batal</span>
                            </div>
                        </button>
                        <button type="submit" 
                                id="confirmRejectBtn"
                                class="flex-1 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-medium py-3 rounded-xl transition duration-300 shadow-lg hover:shadow-xl">
                            <div class="flex items-center justify-center space-x-2" id="rejectBtnContent">
                                <i class="fas fa-times"></i>
                                <span>Tolak</span>
                            </div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Toast -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-3"></div>

    <!-- Scripts -->
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
                document.getElementById('loadingOverlay').style.opacity = '0';
                setTimeout(() => {
                    document.getElementById('loadingOverlay').style.display = 'none';
                }, 500);
            }, 1000);
        });

        // Global variables
        let currentRegistrationId = null;

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const clearSearchBtn = document.getElementById('clearSearchBtn');

        searchInput.addEventListener('input', function() {
            filterRegistrations();
            if (this.value.trim() !== '') {
                clearSearchBtn.classList.remove('hidden');
            } else {
                clearSearchBtn.classList.add('hidden');
            }
        });

        function clearSearch() {
            searchInput.value = '';
            clearSearchBtn.classList.add('hidden');
            filterRegistrations();
        }

        // Status filter
        document.getElementById('statusFilter').addEventListener('change', function() {
            filterRegistrations();
            updateFilterButtons();
        });

        function filterRegistrations() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('tbody tr[data-status]');
            
            rows.forEach(row => {
                const searchData = row.getAttribute('data-search') || '';
                const status = row.getAttribute('data-status') || '';
                
                const matchesSearch = searchTerm === '' || searchData.includes(searchTerm);
                const matchesStatus = statusFilter === '' || status === statusFilter;
                
                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }

        function filterByStatus(status) {
            document.getElementById('statusFilter').value = status;
            filterRegistrations();
            updateFilterButtons();
        }

        function updateFilterButtons() {
            const status = document.getElementById('statusFilter').value;
            const buttons = {
                '': document.getElementById('filterAll'),
                'menunggu': document.getElementById('filterPending'),
                'disetujui': document.getElementById('filterApproved'),
                'ditolak': document.getElementById('filterRejected')
            };
            
            // Reset all buttons
            Object.values(buttons).forEach(btn => {
                btn.classList.remove('animate-pulse-glow', 'ring-4', 'ring-opacity-30');
            });
            
            // Highlight active button
            if (buttons[status]) {
                buttons[status].classList.add('animate-pulse-glow');
                const colors = {
                    '': 'ring-blue-300',
                    'menunggu': 'ring-yellow-300',
                    'disetujui': 'ring-green-300',
                    'ditolak': 'ring-red-300'
                };
                buttons[status].classList.add('ring-4', 'ring-opacity-30', colors[status]);
            }
        }

        // Initialize filter button
        document.addEventListener('DOMContentLoaded', function() {
            updateFilterButtons();
        });

        // Refresh page
        function refreshPage() {
            window.location.reload();
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            
            toast.id = toastId;
            toast.className = `bg-white rounded-xl shadow-2xl p-4 min-w-[300px] transform transition-all duration-500 translate-x-full border-l-4 ${type === 'success' ? 'border-green-500' : 'border-red-500'}`;
            toast.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full ${type === 'success' ? 'bg-green-100' : 'bg-red-100'} flex items-center justify-center">
                        <i class="fas ${type === 'success' ? 'fa-check text-green-600' : 'fa-exclamation text-red-600'}"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">${type === 'success' ? 'Sukses!' : 'Error!'}</p>
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

        // ============ MODAL FUNCTIONS ============
        // Verification Modal
        function openVerificationModal(id, name) {
            currentRegistrationId = id;
            document.getElementById('verifyParticipantName').textContent = name;
            document.getElementById('verificationModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeVerificationModal() {
            document.getElementById('verificationModal').classList.remove('active');
            currentRegistrationId = null;
            document.body.style.overflow = 'auto';
        }

        async function submitVerification() {
            if (!currentRegistrationId) return;
            
            const button = document.getElementById('confirmVerifyBtn');
            const cancelBtn = document.getElementById('cancelVerifyBtn');
            const buttonContent = document.getElementById('verifyBtnContent');
            const originalContent = buttonContent.innerHTML;
            
            // Disable buttons and show loading
            button.disabled = true;
            cancelBtn.disabled = true;
            buttonContent.innerHTML = `
                <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div>
                <span>Memproses...</span>
            `;
            
            try {
                const response = await fetch(`/admin/registrations/${currentRegistrationId}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast(data.message || 'Pendaftaran berhasil disetujui!', 'success');
                    closeVerificationModal();
                    
                    // Reload page after delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast(error.message || 'Terjadi kesalahan saat menyetujui pendaftaran', 'error');
                
                // Reset button
                button.disabled = false;
                cancelBtn.disabled = false;
                buttonContent.innerHTML = originalContent;
            }
        }

        // Rejection Modal
        function openRejectionModal(id, name) {
            currentRegistrationId = id;
            document.getElementById('rejectParticipantName').textContent = name;
            document.getElementById('rejectForm').action = `/admin/registrations/${id}/reject`;
            document.getElementById('rejectionModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeRejectionModal() {
            document.getElementById('rejectionModal').classList.remove('active');
            document.getElementById('rejectionReason').value = '';
            currentRegistrationId = null;
            document.body.style.overflow = 'auto';
        }

        // Handle reject form submission
        document.getElementById('rejectForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const button = document.getElementById('confirmRejectBtn');
            const cancelBtn = document.getElementById('cancelRejectBtn');
            const buttonContent = document.getElementById('rejectBtnContent');
            const originalContent = buttonContent.innerHTML;
            
            // Disable buttons and show loading
            button.disabled = true;
            cancelBtn.disabled = true;
            buttonContent.innerHTML = `
                <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div>
                <span>Memproses...</span>
            `;
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        reason: document.getElementById('rejectionReason').value
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast(data.message || 'Pendaftaran berhasil ditolak!', 'success');
                    closeRejectionModal();
                    
                    // Reload page after delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast(error.message || 'Terjadi kesalahan saat menolak pendaftaran', 'error');
                
                // Reset button
                button.disabled = false;
                cancelBtn.disabled = false;
                buttonContent.innerHTML = originalContent;
            }
        });

        // Close modals on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeVerificationModal();
                closeRejectionModal();
            }
        });

        // Close modals when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (this.id === 'verificationModal') closeVerificationModal();
                    if (this.id === 'rejectionModal') closeRejectionModal();
                }
            });
        });

        // Check for session messages
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif
            
            @if(session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif
        });
    </script>
</body>
</html>