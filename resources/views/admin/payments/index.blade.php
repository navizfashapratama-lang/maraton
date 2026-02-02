<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Pembayaran - Marathon Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
        
        .status-terverifikasi {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .status-ditolak {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .status-kadaluarsa {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
        }
        
        /* Method Badges */
        .method-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .method-transfer {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }
        
        .method-qris {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            color: white;
        }
        
        .method-cash {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
                       Manajemen Pembayaran


                    </h1>
                    <p class="text-gray-600 mt-1 text-sm md:text-base">Kelola dan verifikasi semua pembayaran marathon

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
                            <!-- Export Button -->
                            <button onclick="exportExcel()" 
                                  class="bg-gradient-to-r from-gray-500/20 to-gray-600/20 hover:from-gray-600/30 hover:to-gray-700/30 backdrop-blur-md border border-gray-400/30 text-gray-800 dark:text-white font-medium py-2.5 px-5 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-1"
                                <i class="fas fa-file-excel mr-2"></i>Export Excel
                            </button>

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
                <div class="bg-white rounded-2xl shadow-lg card-hover animate-fade-up" data-delay="100">
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
                                 style="width: {{ $payments->total() > 0 ? ($stats['pending'] / $payments->total() * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Terverifikasi Card -->
                <div class="bg-white rounded-2xl shadow-lg card-hover animate-fade-up" data-delay="200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Terverifikasi</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['verified'] }}</p>
                            </div>
                            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-green-500 to-emerald-500 rounded-full" 
                                 style="width: {{ $payments->total() > 0 ? ($stats['verified'] / $payments->total() * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Ditolak Card -->
                <div class="bg-white rounded-2xl shadow-lg card-hover animate-fade-up" data-delay="300">
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
                                 style="width: {{ $payments->total() > 0 ? ($stats['rejected'] / $payments->total() * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Pendapatan Card -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-lg card-hover animate-fade-up" data-delay="400">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-medium text-white/80">Total Pendapatan</p>
                                <p class="text-3xl font-bold text-white mt-1">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</p>
                            </div>
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-white text-xl"></i>
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
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Daftar Pembayaran</h2>
                        <p class="text-gray-600">Filter dan kelola pembayaran peserta</p>
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
                                <option value="terverifikasi">Terverifikasi</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="kadaluarsa">Kadaluarsa</option>
                            </select>
                            <i class="fas fa-filter absolute left-4 top-3.5 text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Filters -->
                <div class="flex flex-wrap gap-2 mb-6">
                    <button onclick="filterByStatus('')" 
                            id="filterAll"
                            class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-layer-group mr-2"></i>Semua ({{ $payments->total() }})
                    </button>
                    
                    <button onclick="filterByStatus('menunggu')" 
                            id="filterPending"
                            class="px-4 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-clock mr-2"></i>Menunggu ({{ $stats['pending'] }})
                    </button>
                    
                    <button onclick="filterByStatus('terverifikasi')" 
                            id="filterVerified"
                            class="px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-check-circle mr-2"></i>Terverifikasi ({{ $stats['verified'] }})
                    </button>
                    
                    <button onclick="filterByStatus('ditolak')" 
                            id="filterRejected"
                            class="px-4 py-2.5 bg-gradient-to-r from-red-500 to-rose-500 hover:from-red-600 hover:to-rose-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-times-circle mr-2"></i>Ditolak ({{ $stats['rejected'] }})
                    </button>
                </div>
                
                <!-- Date Range Filter -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="far fa-calendar-alt mr-2"></i>Tanggal Mulai
                        </label>
                        <input type="date" 
                               id="startDate" 
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="far fa-calendar-alt mr-2"></i>Tanggal Akhir
                        </label>
                        <input type="date" 
                               id="endDate" 
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    </div>
                    <div class="flex items-end">
                        <button onclick="applyDateFilter()" 
                                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-filter mr-2"></i>Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-fade-up">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-blue-50">
                                <th class="py-4 px-6 text-left">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-hashtag text-gray-500"></i>
                                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</span>
                                    </div>
                                </th>
                                <th class="py-4 px-6 text-left">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-user text-gray-500"></i>
                                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Peserta</span>
                                    </div>
                                </th>
                                <th class="py-4 px-6 text-left">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-calendar text-gray-500"></i>
                                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Event</span>
                                    </div>
                                </th>
                                <th class="py-4 px-6 text-left">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-money-bill-wave text-gray-500"></i>
                                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</span>
                                    </div>
                                </th>
                                <th class="py-4 px-6 text-left">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-credit-card text-gray-500"></i>
                                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Metode</span>
                                    </div>
                                </th>
                                <th class="py-4 px-6 text-left">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-info-circle text-gray-500"></i>
                                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</span>
                                    </div>
                                </th>
                                <th class="py-4 px-6 text-left">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-cogs text-gray-500"></i>
                                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($payments as $payment)
                            <tr class="table-row-hover" 
                                data-status="{{ $payment->status }}" 
                                data-search="{{ strtolower($payment->kode_pembayaran . ' ' . $payment->user_nama . ' ' . $payment->event_nama) }}"
                                data-date="{{ \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d') }}"
                                onclick="viewPaymentDetail({{ $payment->id }})"
                                style="cursor: pointer;">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-100 to-blue-50 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-hashtag text-blue-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $payment->kode_pembayaran }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                                            {{ strtoupper(substr($payment->user_nama ?? 'N', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $payment->user_nama ?? 'N/A' }}</div>
                                            @if($payment->nomor_start)
                                            <div class="text-sm text-gray-500 mt-0.5">
                                                <i class="fas fa-hashtag text-xs mr-1"></i>No. Start: {{ $payment->nomor_start }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-medium text-gray-900">{{ $payment->event_nama ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500 mt-0.5">
                                        ID: #{{ $payment->id_pendaftaran }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-green-600 text-lg">
                                        Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @php
                                        $methodClasses = [
                                            'transfer' => 'method-transfer',
                                            'qris' => 'method-qris',
                                            'cash' => 'method-cash',
                                            'lainnya' => 'method-transfer'
                                        ];
                                    @endphp
                                    <span class="method-badge {{ $methodClasses[$payment->metode_pembayaran] ?? 'method-transfer' }}">
                                        {{ ucfirst($payment->metode_pembayaran) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    @php
                                        $statusConfig = [
                                            'menunggu' => ['class' => 'status-menunggu', 'icon' => 'clock', 'text' => 'Menunggu'],
                                            'terverifikasi' => ['class' => 'status-terverifikasi', 'icon' => 'check-circle', 'text' => 'Terverifikasi'],
                                            'ditolak' => ['class' => 'status-ditolak', 'icon' => 'times-circle', 'text' => 'Ditolak'],
                                            'kadaluarsa' => ['class' => 'status-kadaluarsa', 'icon' => 'exclamation-circle', 'text' => 'Kadaluarsa']
                                        ];
                                        $status = $statusConfig[$payment->status] ?? $statusConfig['menunggu'];
                                    @endphp
                                    <span class="status-badge {{ $status['class'] }}">
                                        <i class="fas fa-{{ $status['icon'] }}"></i>
                                        {{ $status['text'] }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.payments.view', $payment->id) }}" 
                                           class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-200 transition-colors duration-300"
                                           title="Lihat Detail"
                                           onclick="event.stopPropagation()">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($payment->status == 'menunggu')
                                        <button onclick="event.stopPropagation(); verifyPaymentModal({{ $payment->id }}, '{{ addslashes($payment->kode_pembayaran) }}')"
                                                class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center hover:bg-green-200 transition-colors duration-300"
                                                title="Verifikasi">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        
                                        <button onclick="event.stopPropagation(); rejectPaymentModal({{ $payment->id }}, '{{ addslashes($payment->kode_pembayaran) }}')"
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
                                            <i class="fas fa-money-bill-wave text-4xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Data</h3>
                                        <p class="text-gray-600 max-w-md text-center mb-6">Tidak ada data pembayaran yang ditemukan. Semua pembayaran akan muncul di sini.</p>
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
                @if($payments->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-blue-50">
                    <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                        <div class="text-sm text-gray-600">
                            Menampilkan <span class="font-semibold">{{ $payments->firstItem() }}</span> sampai <span class="font-semibold">{{ $payments->lastItem() }}</span> dari <span class="font-semibold">{{ $payments->total() }}</span> pembayaran
                        </div>
                        <div class="flex items-center space-x-2">
                            <!-- Previous Button -->
                            @if($payments->onFirstPage())
                            <button class="w-10 h-10 bg-gray-100 text-gray-400 rounded-lg flex items-center justify-center cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            @else
                            <a href="{{ $payments->previousPageUrl() }}" 
                               class="w-10 h-10 bg-white border border-gray-300 text-gray-700 rounded-lg flex items-center justify-center hover:bg-gray-50 transition-colors duration-300">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            @endif
                            
                            <!-- Page Numbers -->
                            @php
                                $current = $payments->currentPage();
                                $last = $payments->lastPage();
                                $start = max($current - 2, 1);
                                $end = min($current + 2, $last);
                            @endphp
                            
                            @for($i = $start; $i <= $end; $i++)
                                @if($i == $current)
                                <span class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg flex items-center justify-center font-semibold">
                                    {{ $i }}
                                </span>
                                @else
                                <a href="{{ $payments->url($i) }}" 
                                   class="w-10 h-10 bg-white border border-gray-300 text-gray-700 rounded-lg flex items-center justify-center hover:bg-gray-50 transition-colors duration-300 font-medium">
                                    {{ $i }}
                                </a>
                                @endif
                            @endfor
                            
                            <!-- Next Button -->
                            @if($payments->hasMorePages())
                            <a href="{{ $payments->nextPageUrl() }}" 
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
    </div>

    <!-- Verification Modal -->
    <div id="verificationModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-14 h-14 bg-gradient-to-r from-green-100 to-emerald-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Verifikasi Pembayaran</h3>
                        <p class="text-gray-600 text-sm">Konfirmasi verifikasi</p>
                    </div>
                </div>
                
                <p class="text-gray-700 mb-6">
                    Apakah Anda yakin ingin memverifikasi pembayaran dengan kode 
                    <span id="verifyPaymentCode" class="font-bold text-blue-600"></span>?
                </p>
                
                <div class="flex space-x-3">
                    <button onclick="closeVerifyModal()" 
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-3 rounded-xl transition duration-300">
                        Batal
                    </button>
                    <button onclick="submitVerification()" 
                            id="confirmVerifyBtn"
                            class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium py-3 rounded-xl transition duration-300 shadow-lg hover:shadow-xl">
                        <span id="verifyBtnText">Verifikasi</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectionModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-14 h-14 bg-gradient-to-r from-red-100 to-rose-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Tolak Pembayaran</h3>
                        <p class="text-gray-600 text-sm">Konfirmasi penolakan</p>
                    </div>
                </div>
                
                <p class="text-gray-700 mb-4">
                    Apakah Anda yakin ingin menolak pembayaran dengan kode 
                    <span id="rejectPaymentCode" class="font-bold text-blue-600"></span>?
                </p>
                
                <div class="mb-4">
                    <label for="rejectReason" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Penolakan
                    </label>
                    <textarea id="rejectReason" rows="3" 
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                              placeholder="Masukkan alasan penolakan..."></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <button onclick="closeRejectModal()" 
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-3 rounded-xl transition duration-300">
                        Batal
                    </button>
                    <button onclick="submitRejection()" 
                            id="confirmRejectBtn"
                            class="flex-1 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-medium py-3 rounded-xl transition duration-300 shadow-lg hover:shadow-xl">
                        <span id="rejectBtnText">Tolak</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-3"></div>

    <script>
        // Global variables
        let currentPaymentId = null;

        // Filter functions
        const searchInput = document.getElementById('searchInput');
        const clearSearchBtn = document.getElementById('clearSearchBtn');

        searchInput.addEventListener('input', function() {
            filterPayments();
            if (this.value.trim() !== '') {
                clearSearchBtn.classList.remove('hidden');
            } else {
                clearSearchBtn.classList.add('hidden');
            }
        });

        function clearSearch() {
            searchInput.value = '';
            clearSearchBtn.classList.add('hidden');
            filterPayments();
        }

        function filterByStatus(status) {
            document.getElementById('statusFilter').value = status;
            filterPayments();
            updateFilterButtons();
        }

        function applyDateFilter() {
            filterPayments();
        }

        function filterPayments() {
            const status = document.getElementById('statusFilter').value;
            const search = searchInput.value.toLowerCase().trim();
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            
            const rows = document.querySelectorAll('tbody tr[data-status]');
            
            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                const rowSearch = row.getAttribute('data-search');
                const rowDate = row.getAttribute('data-date');
                
                let show = true;
                
                if (status && rowStatus !== status) show = false;
                if (search && !rowSearch.includes(search)) show = false;
                if (startDate && rowDate < startDate) show = false;
                if (endDate && rowDate > endDate) show = false;
                
                row.style.display = show ? '' : 'none';
            });
        }

        function updateFilterButtons() {
            const status = document.getElementById('statusFilter').value;
            const buttons = {
                '': document.getElementById('filterAll'),
                'menunggu': document.getElementById('filterPending'),
                'terverifikasi': document.getElementById('filterVerified'),
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
                    'terverifikasi': 'ring-green-300',
                    'ditolak': 'ring-red-300'
                };
                buttons[status].classList.add('ring-4', 'ring-opacity-30', colors[status]);
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateFilterButtons();
            
            // Set default dates
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            
            document.getElementById('startDate').value = firstDay.toISOString().split('T')[0];
            document.getElementById('endDate').value = today.toISOString().split('T')[0];
        });

        // Export Excel
        function exportExcel() {
            alert('Export Excel berhasil! Data pembayaran akan didownload.');
            // Here you would typically make an AJAX call to your export endpoint
        }

        // Refresh page
        function refreshPage() {
            window.location.reload();
        }

        // View payment detail
        function viewPaymentDetail(id) {
            window.location.href = `/admin/payments/${id}`;
        }

        // Modal functions
        function verifyPaymentModal(id, code) {
            currentPaymentId = id;
            document.getElementById('verifyPaymentCode').textContent = code;
            document.getElementById('verificationModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeVerifyModal() {
            document.getElementById('verificationModal').classList.add('hidden');
            currentPaymentId = null;
            document.body.style.overflow = 'auto';
        }

        function rejectPaymentModal(id, code) {
            currentPaymentId = id;
            document.getElementById('rejectPaymentCode').textContent = code;
            document.getElementById('rejectionModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRejectModal() {
            document.getElementById('rejectionModal').classList.add('hidden');
            document.getElementById('rejectReason').value = '';
            currentPaymentId = null;
            document.body.style.overflow = 'auto';
        }

        // Submit verification
        async function submitVerification() {
            if (!currentPaymentId) return;
            
            const button = document.getElementById('confirmVerifyBtn');
            const buttonText = document.getElementById('verifyBtnText');
            const originalText = buttonText.textContent;
            
            button.disabled = true;
            buttonText.textContent = 'Memproses...';
            
            try {
                const response = await fetch(`/admin/payments/${currentPaymentId}/verify`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast('Pembayaran berhasil diverifikasi!', 'success');
                    closeVerifyModal();
                    
                    // Reload page after delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast(error.message || 'Gagal memverifikasi pembayaran', 'error');
                
                // Reset button
                button.disabled = false;
                buttonText.textContent = originalText;
            }
        }

        // Submit rejection
        async function submitRejection() {
            if (!currentPaymentId) return;
            
            const button = document.getElementById('confirmRejectBtn');
            const buttonText = document.getElementById('rejectBtnText');
            const reason = document.getElementById('rejectReason').value;
            const originalText = buttonText.textContent;
            
            button.disabled = true;
            buttonText.textContent = 'Memproses...';
            
            try {
                const response = await fetch(`/admin/payments/${currentPaymentId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ reason: reason })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast('Pembayaran berhasil ditolak!', 'success');
                    closeRejectModal();
                    
                    // Reload page after delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast(error.message || 'Gagal menolak pembayaran', 'error');
                
                // Reset button
                button.disabled = false;
                buttonText.textContent = originalText;
            }
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
            
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 10);
            
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

        // Close modals on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeVerifyModal();
                closeRejectModal();
            }
        });

        // Close modals when clicking outside
        document.querySelectorAll('#verificationModal, #rejectionModal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (this.id === 'verificationModal') closeVerifyModal();
                    if (this.id === 'rejectionModal') closeRejectModal();
                }
            });
        });
    </script>
</body>
</html>