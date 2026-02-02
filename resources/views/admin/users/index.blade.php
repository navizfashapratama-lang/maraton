<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna</title>
    <!-- CSRF Token untuk AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css untuk animasi tambahan -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- jQuery (diperlukan untuk AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Custom Animations */
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
        
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 5px rgba(59, 130, 246, 0.5);
            }
            50% {
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.8);
            }
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        /* Apply Animations */
        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .animate-scaleIn {
            animation: scaleIn 0.4s ease-out;
        }
        
        .animate-slideInRight {
            animation: slideInRight 0.5s ease-out;
        }
        
        .animate-pulse-glow {
            animation: pulse-glow 2s infinite;
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        /* Hover Effects */
        .stats-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center;
        }
        
        .stats-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .table-row-hover {
            transition: all 0.2s ease;
        }
        
        .table-row-hover:hover {
            background-color: #f1f5f9;
            transform: translateX(5px);
        }
        
        .btn-action {
            transition: all 0.2s ease;
            transform-origin: center;
        }
        
        .btn-action:hover {
            transform: scale(1.1);
        }
        
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .badge:hover {
            transform: scale(1.05);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .user-avatar:hover {
            transform: rotate(15deg) scale(1.1);
        }
        
        /* Pagination Styles */
        .pagination-link {
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .pagination-link:hover {
            background-color: #3b82f6;
            color: white;
            transform: translateY(-2px);
        }
        
        .pagination-link:hover::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shine 0.6s ease;
        }
        
        @keyframes shine {
            to {
                left: 100%;
            }
        }
        
        /* Loading Skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }
        
        /* Toast Animation */
        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes toastOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100px);
            }
        }
        
        .toast-enter {
            animation: toastIn 0.5s ease-out forwards;
        }
        
        .toast-exit {
            animation: toastOut 0.5s ease-out forwards;
        }
        
        /* Gradient Border */
        .gradient-border {
            position: relative;
            background: white;
            border-radius: 0.5rem;
        }
        
        .gradient-border::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #3b82f6, #8b5cf6, #ec4899);
            border-radius: 0.6rem;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .gradient-border:hover::before {
            opacity: 1;
        }
        
        /* Shimmer Effect */
        .shimmer {
            position: relative;
            overflow: hidden;
        }
        
        .shimmer::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            100% {
                left: 100%;
            }
        }
        
        /* Spinner Animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .fa-spinner {
            animation: spin 1s linear infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
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
                        Manajemen Pengguna
                    </h1>
                    <p class="text-gray-600 mt-1 text-sm md:text-base">Kelola semua pengguna sistem dengan mudah</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Tombol Kembali ke Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="group bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-medium py-3 px-5 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 animate-slideInLeft"
                    <i class="fas fa-tachometer-alt mr-2 group-hover:rotate-12 transition-transform duration-300"></i>
                    Kembali 
                </a>
                
                <!-- Tombol Tambah Pengguna -->
                <a href="{{ route('admin.users.create') }}" 
                   class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-3 px-6 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 animate-pulse-glow">
                    <i class="fas fa-user-plus mr-2"></i>Tambah Pengguna
                </a>
            </div>
        </div>
    </div>
</header>

        <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Stats Cards dengan animasi berurutan -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5 mb-10">
                @php
                    $delay = 0;
                    $cards = [
                        ['color' => 'blue', 'count' => $stats['total'] ?? 0, 'label' => 'Total Pengguna', 'icon' => 'users'],
                        ['color' => 'green', 'count' => ($stats['superadmin'] ?? 0) + ($stats['admin'] ?? 0), 'label' => 'Admin', 'icon' => 'user-shield'],
                        ['color' => 'cyan', 'count' => $stats['staff'] ?? 0, 'label' => 'Staff', 'icon' => 'user-tie'],
                        ['color' => 'yellow', 'count' => $stats['peserta'] ?? 0, 'label' => 'Peserta', 'icon' => 'user-graduate'],
                        ['color' => 'emerald', 'count' => $stats['aktif'] ?? 0, 'label' => 'Aktif', 'icon' => 'user-check'],
                        ['color' => 'gray', 'count' => $stats['nonaktif'] ?? 0, 'label' => 'Nonaktif', 'icon' => 'user-slash'],
                    ];
                @endphp
                
                @foreach($cards as $index => $card)
                <div class="stats-card bg-white rounded-xl border-l-4 border-{{ $card['color'] }}-500 p-5 shadow-md hover:shadow-xl animate-fadeInUp"
                     style="animation-delay: {{ $delay }}ms">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-gray-800">{{ $card['count'] }}</div>
                            <div class="text-sm text-gray-600 mt-2">{{ $card['label'] }}</div>
                        </div>
                        <div class="bg-{{ $card['color'] }}-100 p-3 rounded-lg">
                            <i class="fas fa-{{ $card['icon'] }} text-{{ $card['color'] }}-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-{{ $card['color'] }}-500 rounded-full" 
                             style="width: {{ $stats['total'] > 0 ? ($card['count'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                @php $delay += 100; @endphp
                @endforeach
            </div>

            <!-- Filter Section dengan animasi -->
            <div class="gradient-border shadow-lg mb-8 p-6 animate-scaleIn">
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-1 rounded-xl mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 px-2">
                        <i class="fas fa-filter mr-2 text-blue-600"></i>Filter & Pencarian
                    </h2>
                </div>
                <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="transform transition-all duration-300 hover:scale-105">
                        <div class="flex items-center space-x-2 mb-2">
                            <i class="fas fa-search text-blue-600"></i>
                            <label class="text-sm font-medium text-gray-700">Cari Penggu </label>
                        </div>
                        <input type="text" name="search" 
                               class="w-full p-3 border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300"
                               placeholder="Nama, email, telepon..." 
                               value="{{ request('search') }}">
                    </div>
                    
                    <div class="transform transition-all duration-300 hover:scale-105">
                        <div class="flex items-center space-x-2 mb-2">
                            <i class="fas fa-user-tag text-purple-600"></i>
                            <label class="text-sm font-medium text-gray-700">Filter Peran</label>
                        </div>
                        <select name="role" 
                                class="w-full p-3 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-300 appearance-none bg-white">
                            <option value="all">Semua Peran</option>
                            <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="peserta" {{ request('role') == 'peserta' ? 'selected' : '' }}>Peserta</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2 flex space-x-4">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium p-3 rounded-xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <i class="fas fa-search mr-2"></i>Cari Pengguna
                        </button>
                        <a href="{{ route('admin.users.index') }}" 
                           class="flex-1 bg-gradient-to-r from-gray-200 to-gray-300 hover:from-gray-300 hover:to-gray-400 text-gray-800 font-medium p-3 rounded-xl transition-all duration-300 flex items-center justify-center shadow hover:shadow-md">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Users Table dengan animasi -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden animate-fadeInUp" style="animation-delay: 200ms">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-list mr-2 text-blue-600"></i>Daftar Pengguna
                        </h3>
                        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ $users->total() }} pengguna ditemukan
                        </span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                            <tr>
                               <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
    <div class="flex items-center">
        <i class="fas fa-list-ol mr-2 text-blue-600"></i>No
    </div>
</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-user mr-2 text-blue-600"></i>Nama
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope mr-2 text-blue-600"></i>Email
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-tag mr-2 text-blue-600"></i>Peran
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-phone mr-2 text-blue-600"></i>Telepon
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-circle mr-2 text-blue-600"></i>Status
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2 text-blue-600"></i>Terakhir Login
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-cogs mr-2 text-blue-600"></i>Aksi
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="users-table-body">
                            @forelse($users as $index => $user)
                            <tr class="table-row-hover hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent user-row"
                                data-user-id="{{ $user->id }}"
                                style="animation-delay: {{ $index * 50 }}ms">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block bg-gray-100 text-gray-800 text-sm font-mono px-3 py-1 rounded-full">
                                        #{{ $user->id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="user-avatar bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-md mr-3">
                                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $user->nama }}</div>
                                            <div class="text-xs text-gray-500 flex items-center mt-1">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                        {{ $user->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->peran == 'superadmin')
                                        <span class="badge bg-gradient-to-r from-red-100 to-red-50 text-red-800 border border-red-200 shadow-sm">
                                            <i class="fas fa-crown mr-1"></i>Super Admin
                                        </span>
                                    @elseif($user->peran == 'admin')
                                        <span class="badge bg-gradient-to-r from-green-100 to-green-50 text-green-800 border border-green-200 shadow-sm">
                                            <i class="fas fa-user-shield mr-1"></i>Admin
                                        </span>
                                    @elseif($user->peran == 'staff')
                                        <span class="badge bg-gradient-to-r from-cyan-100 to-cyan-50 text-cyan-800 border border-cyan-200 shadow-sm">
                                            <i class="fas fa-user-tie mr-1"></i>Staff
                                        </span>
                                    @else
                                        <span class="badge bg-gradient-to-r from-yellow-100 to-yellow-50 text-yellow-800 border border-yellow-200 shadow-sm">
                                            <i class="fas fa-user-graduate mr-1"></i>Peserta
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center text-sm text-gray-900">
                                        <i class="fas fa-phone text-gray-400 mr-2"></i>
                                        {{ $user->telepon ?? '<span class="text-gray-400">-</span>' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->is_active)
                                        <span class="badge bg-gradient-to-r from-green-100 to-green-50 text-green-800 border border-green-200 shadow-sm flex items-center w-20 justify-center status-badge" id="status-badge-{{ $user->id }}">
                                            <i class="fas fa-circle text-green-500 text-xs mr-2 animate-pulse"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-gradient-to-r from-gray-100 to-gray-50 text-gray-800 border border-gray-200 shadow-sm flex items-center w-20 justify-center status-badge" id="status-badge-{{ $user->id }}">
                                            <i class="fas fa-circle text-gray-400 text-xs mr-2"></i>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex flex-col">
                                        @if($user->terakhir_login)
                                            <span class="text-gray-900 font-medium">
                                                {{ \Carbon\Carbon::parse($user->terakhir_login)->format('d/m/Y') }}
                                            </span>
                                            <span class="text-gray-500 text-xs">
                                                {{ \Carbon\Carbon::parse($user->terakhir_login)->format('H:i') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">Belum login</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2 items-center">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                           class="btn-action bg-gradient-to-r from-blue-100 to-blue-50 hover:from-blue-200 hover:to-blue-100 text-blue-800 p-2 rounded-xl transition-all duration-300 shadow-sm hover:shadow-md"
                                           title="Edit Pengguna">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        
                                        @if($user->id != session('user_id'))
                                            <!-- Tombol Toggle Status dengan AJAX -->
                                            <button type="button" 
                                                    class="toggle-status-btn inline-flex items-center justify-center px-3 py-1.5 rounded-lg transition-all duration-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-1 min-w-[100px]"
                                                    title="{{ $user->is_active ? 'Nonaktifkan Pengguna' : 'Aktifkan Pengguna' }}"
                                                    data-user-id="{{ $user->id }}"
                                                    data-is-active="{{ $user->is_active }}"
                                                    data-user-name="{{ $user->nama }}">
                                                @if($user->is_active)
                                                    <span class="toggle-status-content flex items-center justify-center w-full">
                                                        <i class="fas fa-toggle-on text-green-600 mr-1.5 text-sm"></i>
                                                        <span class="toggle-text">Aktif</span>
                                                    </span>
                                                @else
                                                    <span class="toggle-status-content flex items-center justify-center w-full">
                                                        <i class="fas fa-toggle-off text-gray-500 mr-1.5 text-sm"></i>
                                                        <span class="toggle-text">Nonaktif</span>
                                                    </span>
                                                @endif
                                                <span class="toggle-status-loading hidden items-center justify-center w-full">
                                                    <i class="fas fa-spinner fa-spin text-blue-500 mr-1.5 text-xs"></i>
                                                    <span class="text-xs">Memproses...</span>
                                                </span>
                                            </button>
                                            
                                            <a href="{{ route('admin.users.destroy', $user->id) }}" 
                                               class="btn-action bg-gradient-to-r from-red-100 to-red-50 hover:from-red-200 hover:to-red-100 text-red-800 p-2 rounded-xl transition-all duration-300 shadow-sm hover:shadow-md"
                                               title="Hapus Pengguna"
                                               onclick="return confirmDelete('{{ $user->id }}', '{{ $user->nama }}')">
                                                <i class="fas fa-trash text-sm"></i>
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400 italic px-3 py-1.5 bg-gray-100 rounded-lg">Anda</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center animate-pulse">
                                        <div class="bg-gradient-to-r from-blue-100 to-purple-100 p-6 rounded-full mb-4">
                                            <i class="fas fa-users text-4xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada data pengguna</h3>
                                        <p class="text-gray-500 mb-6">Coba ubah filter pencarian Anda atau tambahkan pengguna baru</p>
                                        <a href="{{ route('admin.users.create') }}" 
                                           class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-2 px-6 rounded-lg transition-all duration-300 flex items-center shadow hover:shadow-lg">
                                            <i class="fas fa-user-plus mr-2"></i>Tambah Pengguna Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination dengan animasi -->
                @if($users->hasPages())
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-blue-50 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                        <div class="text-sm text-gray-700">
                            Menampilkan <span class="font-semibold">{{ $users->firstItem() }}</span> - 
                            <span class="font-semibold">{{ $users->lastItem() }}</span> dari 
                            <span class="font-semibold">{{ $users->total() }}</span> pengguna
                        </div>
                        <div class="flex space-x-2">
                            {{ $users->withQueryString()->links('vendor.pagination.tailwind-custom') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </main>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
// Toast notification system
function showToast(message, type = 'success') {
    // Hapus toast lama
    $('.custom-toast').remove();
    
    const toastClass = type === 'success' 
        ? 'bg-green-500 border-green-600' 
        : 'bg-red-500 border-red-600';
    const icon = type === 'success' 
        ? '<i class="fas fa-check-circle mr-2"></i>' 
        : '<i class="fas fa-exclamation-circle mr-2"></i>';
    
    const toast = $(`
        <div class="custom-toast fixed top-4 right-4 z-50 animate__animated animate__fadeInRight">
            <div class="${toastClass} text-white px-4 py-3 rounded-lg shadow-lg border flex items-center max-w-sm">
                ${icon}
                <span class="text-sm">${message}</span>
            </div>
        </div>
    `);
    
    $('body').append(toast);
    
    // Auto remove setelah 3 detik
    setTimeout(() => {
        toast.addClass('animate__animated animate__fadeOutRight');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Confirm delete function
function confirmDelete(userId, userName) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        html: `Anda yakin ingin menghapus pengguna <strong>${userName}</strong>?<br><br>
              <span class="text-red-600 text-sm">Tindakan ini tidak dapat dibatalkan!</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ url('admin/users') }}/" + userId + "/delete";
        }
    });
    return false;
}

// Toggle status dengan AJAX
$(document).ready(function() {
    // Setup CSRF token untuk semua AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Apply initial styles to toggle buttons
    $('.toggle-status-btn').each(function() {
        const isActive = $(this).data('is-active') == 1;
        if (isActive) {
            $(this).addClass('bg-green-50 text-green-700 hover:bg-green-100 border border-green-200');
        } else {
            $(this).addClass('bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300');
        }
    });

    // Toggle status button click handler
    $(document).on('click', '.toggle-status-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const button = $(this);
        const userId = button.data('user-id');
        const isActive = button.data('is-active') == 1;
        const userName = button.data('user-name');
        
        // Tampilkan loading dan nonaktifkan tombol
        button.find('.toggle-status-content').addClass('hidden');
        button.find('.toggle-status-loading').removeClass('hidden');
        button.prop('disabled', true).css('opacity', '0.7');
        
        // Kirim AJAX request
        $.ajax({
            url: `/admin/users/${userId}/toggle-status`,
            type: 'POST',
            dataType: 'json',
            timeout: 3000, // Timeout 3 detik
            success: function(response) {
                if (response.success) {
                    // Update status pada button
                    const newIsActive = response.is_active == 1;
                    
                    // Update data attributes
                    button.data('is-active', newIsActive ? 1 : 0);
                    
                    // Update tampilan button
                    if (newIsActive) {
                        button.find('.toggle-status-content').html(`
                            <i class="fas fa-toggle-on text-green-600 mr-1.5 text-sm"></i>
                            <span class="toggle-text">Aktif</span>
                        `);
                        button.attr('title', 'Nonaktifkan Pengguna');
                        button.removeClass('bg-gray-100 text-gray-700 hover:bg-gray-200 border-gray-300')
                              .addClass('bg-green-50 text-green-700 hover:bg-green-100 border border-green-200');
                    } else {
                        button.find('.toggle-status-content').html(`
                            <i class="fas fa-toggle-off text-gray-500 mr-1.5 text-sm"></i>
                            <span class="toggle-text">Nonaktif</span>
                        `);
                        button.attr('title', 'Aktifkan Pengguna');
                        button.removeClass('bg-green-50 text-green-700 hover:bg-green-100 border-green-200')
                              .addClass('bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-300');
                    }
                    
                    // Update badge status di tabel
                    const statusBadge = $(`#status-badge-${userId}`);
                    if (newIsActive) {
                        statusBadge.removeClass('bg-gradient-to-r from-gray-100 to-gray-50 text-gray-800 border-gray-200')
                                  .addClass('bg-gradient-to-r from-green-100 to-green-50 text-green-800 border-green-200');
                        statusBadge.find('i').removeClass('text-gray-400').addClass('text-green-500 animate-pulse');
                        statusBadge.find('span').text('Aktif');
                    } else {
                        statusBadge.removeClass('bg-gradient-to-r from-green-100 to-green-50 text-green-800 border-green-200')
                                  .addClass('bg-gradient-to-r from-gray-100 to-gray-50 text-gray-800 border-gray-200');
                        statusBadge.find('i').removeClass('text-green-500 animate-pulse').addClass('text-gray-400');
                        statusBadge.find('span').text('Nonaktif');
                    }
                    
                    // Tampilkan notifikasi sukses
                    showToast(response.message, 'success');
                    
                    // Highlight row efek
                    button.closest('tr').addClass('bg-green-50');
                    setTimeout(() => {
                        button.closest('tr').removeClass('bg-green-50');
                    }, 1000);
                } else {
                    showToast(response.message || 'Terjadi kesalahan!', 'error');
                }
            },
            error: function(xhr, status, error) {
                if (status === 'timeout') {
                    showToast('Request timeout! Silakan coba lagi.', 'error');
                } else {
                    showToast('Terjadi kesalahan jaringan!', 'error');
                }
            },
            complete: function() {
                // Kembalikan tombol ke keadaan normal
                button.find('.toggle-status-loading').addClass('hidden');
                button.find('.toggle-status-content').removeClass('hidden');
                button.prop('disabled', false).css('opacity', '1');
            }
        });
    });

    // Check for session messages
    @if(session('success'))
        showToast("{{ session('success') }}", 'success');
    @endif
    
    @if(session('error'))
        showToast("{{ session('error') }}", 'error');
    @endif

    // Animate stats cards sequentially
    const cards = document.querySelectorAll('.stats-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('opacity-100', 'translate-y-0');
        }, index * 100);
    });
    
    // Animate table rows
    const rows = document.querySelectorAll('.table-row-hover');
    rows.forEach((row, index) => {
        row.style.animationDelay = `${index * 50}ms`;
    });
});

// Search input focus effect
document.querySelector('input[name="search"]')?.addEventListener('focus', function() {
    this.parentElement.classList.add('scale-105');
});

document.querySelector('input[name="search"]')?.addEventListener('blur', function() {
    this.parentElement.classList.remove('scale-105');
});

// Real-time character counter for search
const searchInput = document.querySelector('input[name="search"]');
if (searchInput) {
    const counter = document.createElement('div');
    counter.className = 'text-xs text-gray-500 mt-1 hidden';
    searchInput.parentElement.appendChild(counter);
    
    searchInput.addEventListener('input', function() {
        const length = this.value.length;
        counter.textContent = `${length} karakter`;
        counter.classList.toggle('hidden', length === 0);
    });
}
</script>
</body>
</html>