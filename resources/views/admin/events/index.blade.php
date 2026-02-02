<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Event - Marathon Events</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
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
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
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
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
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
        
        @keyframes shimmer {
            0% {
                background-position: -200px 0;
            }
            100% {
                background-position: 200px 0;
            }
        }
        
        /* Apply Animations */
        .animate-fade-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .animate-slide-left {
            animation: slideInLeft 0.5s ease-out;
        }
        
        .animate-slide-right {
            animation: slideInRight 0.5s ease-out;
        }
        
        .animate-pulse {
            animation: pulse 2s infinite;
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        /* Custom Styles */
        .event-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 1rem;
            overflow: hidden;
            position: relative;
        }
        
        .event-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .event-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.6s ease;
        }
        
        .event-card:hover::before {
            transform: scaleX(1);
        }
        
        .table-row-hover {
            transition: all 0.2s ease;
        }
        
        .table-row-hover:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.05), transparent);
            transform: translateX(5px);
        }
        
        .badge-animate {
            transition: all 0.3s ease;
        }
        
        .badge-animate:hover {
            transform: scale(1.05);
        }
        
        .progress-bar {
            transition: width 1s ease-in-out;
        }
        
        .action-btn {
            transition: all 0.3s ease;
            transform-origin: center;
        }
        
        .action-btn:hover {
            transform: scale(1.1);
        }
        
        .empty-state {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
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
            background: linear-gradient(to bottom, #667eea, #764ba2);
            border-radius: 10px;
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
            background: rgba(255, 255, 255, 0.6);
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
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Loading Screen -->
    <div id="loadingScreen" class="fixed inset-0 bg-white z-50 flex flex-col items-center justify-center">
        <div class="relative">
            <div class="w-24 h-24 rounded-full border-4 border-gray-200 border-t-blue-500 animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-running text-3xl text-blue-600 animate-float"></i>
            </div>
        </div>
        <div class="mt-8 text-center">
            <h2 class="text-2xl font-bold gradient-text">Marathon Events</h2>
            <p class="text-gray-600 mt-2 animate-pulse">Memuat data event...</p>
        </div>
    </div>

    <div class="min-h-screen">
        <!-- Header dengan Animasi -->
<header class="glass-effect shadow-xl sticky top-0 z-40" data-aos="fade-down">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <div class="animate-slide-left">
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 p-2 rounded-xl">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Manajemen Event</h1>
                        <p class="text-gray-600 text-sm">Kelola semua event marathon</p>
                    </div>
                </div>
            </div>
            
            <div class="animate-slide-right">
                <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <div class="hidden md:block text-right">
                        <div class="text-sm text-gray-500">Total Event</div>
                        <div class="text-xl font-bold text-gray-800">{{ $events->total() }}</div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                <!-- Tombol Kembali ke Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="group bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-medium py-3 px-5 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 animate-slideInLeft"
                    <i class="fas fa-tachometer-alt mr-2 group-hover:rotate-12 transition-transform duration-300"></i>
                    Kembali 
                </a>
                    
                    <!-- Tombol Tambah Event -->
                    <a href="{{ route('admin.events.create') }}" 
                       class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-2.5 px-5 rounded-xl transition-all duration-300 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 ripple">
                        <i class="fas fa-plus mr-2"></i>Tambah Event
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" data-aos="fade-up">
                @php
                    $stats = [
                        [
                            'title' => 'Event Mendatang',
                            'count' => DB::table('lomba')->where('status', 'mendatang')->count(),
                            'icon' => 'clock',
                            'color' => 'green',
                            'gradient' => 'from-green-500 to-green-600'
                        ],
                        [
                            'title' => 'Event Selesai',
                            'count' => DB::table('lomba')->where('status', 'selesai')->count(),
                            'icon' => 'check-circle',
                            'color' => 'gray',
                            'gradient' => 'from-gray-500 to-gray-600'
                        ],
                        [
                            'title' => 'Event Dibatalkan',
                            'count' => DB::table('lomba')->where('status', 'dibatalkan')->count(),
                            'icon' => 'times-circle',
                            'color' => 'red',
                            'gradient' => 'from-red-500 to-red-600'
                        ],
                        [
                            'title' => 'Total Pendaftar',
                            'count' => DB::table('pendaftaran')->count(),
                            'icon' => 'users',
                            'color' => 'blue',
                            'gradient' => 'from-blue-500 to-blue-600'
                        ]
                    ];
                @endphp
                
                @foreach($stats as $index => $stat)
                <div class="event-card bg-white shadow-lg animate-fade-up" 
                     data-aos="fade-up" 
                     data-aos-delay="{{ $index * 100 }}">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-2xl font-bold text-gray-800 mb-1">{{ $stat['count'] }}</div>
                                <div class="text-sm text-gray-600">{{ $stat['title'] }}</div>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r {{ $stat['gradient'] }} flex items-center justify-center text-white text-lg shadow">
                                <i class="fas fa-{{ $stat['icon'] }}"></i>
                            </div>
                        </div>
                        <div class="mt-4 h-1 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r {{ $stat['gradient'] }} rounded-full"
                                 style="width: {{ $events->total() > 0 ? ($stat['count'] / $events->total() * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Event Table -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden" data-aos="fade-up">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                        <div class="flex items-center space-x-3">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-2 rounded-lg">
                                <i class="fas fa-list text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Daftar Event Marathon</h2>
                                <p class="text-gray-600 text-sm">Total: {{ $events->total() }} event ditemukan</p>
                            </div>
                        </div>
                        
                        <!-- Search & Filter -->
                        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                            <div class="relative">
                                <input type="text" 
                                       id="searchInput"
                                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                       placeholder="Cari event...">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <select id="statusFilter" 
                                    class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="all">Semua Status</option>
                                <option value="mendatang">Mendatang</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-hashtag mr-2 text-blue-600"></i>#
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-running mr-2 text-blue-600"></i>Nama Event
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-tag mr-2 text-blue-600"></i>Kategori
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-2 text-blue-600"></i>Tanggal
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>Lokasi
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-money-bill-wave mr-2 text-blue-600"></i>Harga
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-users mr-2 text-blue-600"></i>Kuota
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-circle mr-2 text-blue-600"></i>Status
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-cogs mr-2 text-blue-600"></i>Aksi
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($events as $index => $event)
                            @php
                                // Hitung jumlah pendaftar untuk event ini
                                $registered = DB::table('pendaftaran')
                                    ->where('id_lomba', $event->id)
                                    ->count();
                                    
                                // Tentukan kolom harga berdasarkan database Anda
                                // Database Anda punya harga_standar dan harga_premium
                                // Tapi di kode asli ada harga_reguler, jadi kita sesuaikan
                                $harga_reguler = $event->harga_standar ?? $event->harga_reguler ?? 0;
                                $harga_premium = $event->harga_premium ?? 0;
                                
                                // Tentukan kuota
                                $kuota = $event->kuota_peserta ?? 0;
                                
                                // Hitung persentase dan tentukan warna
                                $percentage = $kuota > 0 ? min(100, ($registered / $kuota) * 100) : 0;
                                
                                // FIXED: Use parentheses for nested ternary operator
                                if ($percentage < 70) {
                                    $color = 'from-green-400 to-green-500';
                                } elseif ($percentage < 90) {
                                    $color = 'from-yellow-400 to-yellow-500';
                                } else {
                                    $color = 'from-red-400 to-red-500';
                                }
                            @endphp
                            <tr class="table-row-hover hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent transition-all duration-200"
                                data-aos="fade-up"
                                data-aos-delay="{{ $index * 50 }}"
                                data-status="{{ $event->status }}"
                                data-name="{{ strtolower($event->nama) }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block bg-gray-100 text-gray-800 text-sm font-mono px-3 py-1 rounded-full">
                                        {{ $loop->iteration + ($events->currentPage() - 1) * $events->perPage() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 rounded-lg bg-gradient-to-r from-blue-400 to-blue-500 flex items-center justify-center text-white">
                                                <i class="fas fa-running"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800">{{ $event->nama }}</div>
                                            @if($event->deskripsi ?? false)
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ Str::limit($event->deskripsi, 40) }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge-animate inline-block px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-blue-50 text-blue-800 border border-blue-200 shadow-sm">
                                        {{ $event->kategori }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-800">
                                            {{ $event->tanggal ? \Carbon\Carbon::parse($event->tanggal)->format('d/m/Y') : 'N/A' }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $event->tanggal ? \Carbon\Carbon::parse($event->tanggal)->diffForHumans() : '' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-700">
                                        <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                        {{ $event->lokasi ? Str::limit($event->lokasi, 15) : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center">
                                            <i class="fas fa-money-bill text-green-500 text-xs mr-2"></i>
                                            <span class="text-sm font-medium text-gray-800">
                                                Rp {{ number_format($harga_reguler, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        @if($harga_premium > 0)
                                        <div class="flex items-center">
                                            <i class="fas fa-crown text-yellow-500 text-xs mr-2"></i>
                                            <span class="text-xs font-medium text-purple-800">
                                                Rp {{ number_format($harga_premium, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-xs text-gray-600">
                                            <span>{{ $registered }} terdaftar</span>
                                            <span>{{ $kuota }} kuota</span>
                                        </div>
                                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r {{ $color }} rounded-full progress-bar"
                                                 style="width: {{ $percentage }}%"
                                                 data-width="{{ $percentage }}"
                                                 title="{{ $percentage }}% terisi">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="relative">
                                        @php
                                            $statusColors = [
                                                'mendatang' => 'bg-gradient-to-r from-green-100 to-green-50 text-green-800 border border-green-200',
                                                'selesai' => 'bg-gradient-to-r from-gray-100 to-gray-50 text-gray-800 border border-gray-200',
                                                'dibatalkan' => 'bg-gradient-to-r from-red-100 to-red-50 text-red-800 border border-red-200'
                                            ];
                                            $statusIcon = [
                                                'mendatang' => 'clock',
                                                'selesai' => 'check-circle',
                                                'dibatalkan' => 'times-circle'
                                            ];
                                            $statusColor = $statusColors[$event->status] ?? $statusColors['selesai'];
                                            $statusIconClass = $statusIcon[$event->status] ?? 'circle';
                                        @endphp
                                        <span class="badge-animate px-3 py-1 rounded-full text-xs font-semibold flex items-center {{ $statusColor }}">
                                            <i class="fas fa-{{ $statusIconClass }} mr-1 text-xs"></i>
                                            {{ ucfirst($event->status) }}
                                        </span>
                                        @if($event->status == 'mendatang')
                                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.events.edit', $event->id) }}" 
                                           class="action-btn bg-gradient-to-r from-blue-100 to-blue-50 hover:from-blue-200 hover:to-blue-100 text-blue-800 p-2 rounded-lg transition-all duration-300 shadow-sm hover:shadow-md"
                                           title="Edit Event"
                                           onclick="animateButton(this)">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        
                                        <a href="{{ route('admin.events.destroy', $event->id) }}" 
                                           class="action-btn bg-gradient-to-r from-red-100 to-red-50 hover:from-red-200 hover:to-red-100 text-red-800 p-2 rounded-lg transition-all duration-300 shadow-sm hover:shadow-md"
                                           title="Hapus Event"
                                           onclick="return confirmDelete('{{ $event->nama }}', this)">
                                            <i class="fas fa-trash text-sm"></i>
                                        </a>
                                        
                                        <a href="{{ route('admin.events.show', $event->id) }}" 
                                           class="action-btn bg-gradient-to-r from-green-100 to-green-50 hover:from-green-200 hover:to-green-100 text-green-800 p-2 rounded-lg transition-all duration-300 shadow-sm hover:shadow-md"
                                           title="Detail Event">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        
                                        <a href="{{ route('admin.registrations.index') }}?event={{ $event->id }}" 
                                           class="action-btn bg-gradient-to-r from-purple-100 to-purple-50 hover:from-purple-200 hover:to-purple-100 text-purple-800 p-2 rounded-lg transition-all duration-300 shadow-sm hover:shadow-md"
                                           title="Lihat Pendaftaran">
                                            <i class="fas fa-users text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12">
                                    <div class="empty-state flex flex-col items-center justify-center text-gray-400">
                                        <div class="bg-gradient-to-r from-blue-100 to-purple-100 p-6 rounded-full mb-4">
                                            <i class="fas fa-calendar-times text-4xl"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada event</h3>
                                        <p class="text-gray-500 mb-6">Mulai dengan membuat event marathon pertama Anda</p>
                                        <a href="{{ route('admin.events.create') }}" 
                                           class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-2.5 px-6 rounded-lg transition-all duration-300 flex items-center shadow hover:shadow-lg ripple">
                                            <i class="fas fa-plus mr-2"></i>Tambah Event Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($events->hasPages())
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-blue-50 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                        <div class="text-sm text-gray-700">
                            Menampilkan <span class="font-semibold">{{ $events->firstItem() }}</span> - 
                            <span class="font-semibold">{{ $events->lastItem() }}</span> dari 
                            <span class="font-semibold">{{ $events->total() }}</span> event
                        </div>
                        <div class="flex items-center space-x-2">
                            @php
                                // Custom pagination untuk Tailwind
                                $paginator = $events;
                                $elements = $paginator->elements();
                            @endphp
                            
                            @if(is_array($elements))
                                <nav class="flex items-center space-x-2">
                                    @foreach ($elements as $element)
                                        {{-- "Three Dots" Separator --}}
                                        @if (is_string($element))
                                            <span class="px-3 py-1 text-gray-500">...</span>
                                        @endif
                                        
                                        {{-- Array Of Links --}}
                                        @if (is_array($element))
                                            @foreach ($element as $page => $url)
                                                @if ($page == $paginator->currentPage())
                                                    <span class="px-3 py-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-medium">
                                                        {{ $page }}
                                                    </span>
                                                @else
                                                    <a href="{{ $url }}" 
                                                       class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition duration-200">
                                                        {{ $page }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </nav>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <!-- Upcoming Events -->
                <div class="bg-white rounded-2xl shadow-xl p-6" data-aos="fade-right">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Event Mendatang</h3>
                    <div class="space-y-4">
                        @php
                            $upcoming = DB::table('lomba')
                                ->where('status', 'mendatang')
                                ->where('tanggal', '>=', now()->format('Y-m-d'))
                                ->orderBy('tanggal', 'asc')
                                ->limit(3)
                                ->get();
                        @endphp
                        
                        @forelse($upcoming as $event)
                        <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg hover:bg-green-100 transition duration-200">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-400 to-green-500 flex items-center justify-center text-white">
                                    <i class="fas fa-running"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-800">{{ $event->nama }}</h4>
                                <p class="text-xs text-gray-600">
                                    {{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-calendar-times text-xl mb-2"></i>
                            <p>Tidak ada event mendatang</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Registrations -->
                <div class="bg-white rounded-2xl shadow-xl p-6" data-aos="fade-up">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Pendaftaran Terbaru</h3>
                    <div class="space-y-4">
                        @php
                            $recentRegs = DB::table('pendaftaran')
                                ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
                                ->select('pendaftaran.created_at', 'lomba.nama as event_nama')
                                ->orderBy('pendaftaran.created_at', 'desc')
                                ->limit(4)
                                ->get();
                        @endphp
                        
                        @forelse($recentRegs as $reg)
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-400 to-blue-500 flex items-center justify-center text-white">
                                    <i class="fas fa-user text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $reg->event_nama }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($reg->created_at)->diffForHumans() }}
                            </span>
                        </div>
                        @empty
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-user-clock text-xl mb-2"></i>
                            <p>Belum ada pendaftaran</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-xl p-6" data-aos="fade-left">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.events.create') }}" 
                           class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition duration-200 group">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white group-hover:scale-110 transition duration-200">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Tambah Event</p>
                                    <p class="text-xs text-gray-600">Buat event baru</p>
                                </div>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-600"></i>
                        </a>
                        
                        <a href="{{ route('admin.registrations.index') }}" 
                           class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition duration-200 group">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center text-white group-hover:scale-110 transition duration-200">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Verifikasi</p>
                                    <p class="text-xs text-gray-600">Kelola pendaftaran</p>
                                </div>
                            </div>
                            <i class="fas fa-arrow-right text-gray-400 group-hover:text-green-600"></i>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Notification Toast -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-4"></div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
                <div class="p-6">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <h3 id="modalTitle" class="text-xl font-bold text-gray-900 text-center mb-2"></h3>
                    <p id="modalMessage" class="text-gray-600 text-center mb-6"></p>
                    <div class="flex space-x-3">
                        <button id="modalCancel" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 rounded-xl transition duration-200">
                            Batal
                        </button>
                        <button id="modalConfirm" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium py-3 rounded-xl transition duration-200">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
            
            // Animate progress bars
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const width = bar.getAttribute('data-width') || 0;
                bar.style.width = width + '%';
            });
        });

        // Confirmation modal
        function confirmDelete(eventName, link) {
            showConfirmation(
                'Konfirmasi Hapus',
                `Anda yakin ingin menghapus event "${eventName}"? Tindakan ini tidak dapat dibatalkan.`,
                () => {
                    window.location.href = link.href;
                }
            );
            return false;
        }

        let confirmCallback = null;
        
        function showConfirmation(title, message, callback) {
            const modal = document.getElementById('confirmationModal');
            const modalContent = document.getElementById('modalContent');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalCancel = document.getElementById('modalCancel');
            const modalConfirm = document.getElementById('modalConfirm');
            
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            confirmCallback = callback;
            
            modal.classList.remove('hidden');
            
            // Animate in
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
            
            // Close handlers
            modalCancel.onclick = () => hideConfirmation();
            modalConfirm.onclick = () => {
                if (confirmCallback) {
                    confirmCallback();
                }
                hideConfirmation();
            };
        }
        
        function hideConfirmation() {
            const modal = document.getElementById('confirmationModal');
            const modalContent = document.getElementById('modalContent');
            
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Button animation
        function animateButton(button) {
            button.classList.add('scale-110');
            setTimeout(() => {
                button.classList.remove('scale-110');
            }, 300);
        }

        // Search and filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const tableRows = document.querySelectorAll('tbody tr');
            
            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                
                tableRows.forEach(row => {
                    const name = row.getAttribute('data-name') || '';
                    const status = row.getAttribute('data-status') || '';
                    
                    const nameMatch = name.includes(searchTerm);
                    const statusMatch = statusValue === 'all' || status === statusValue;
                    
                    if (nameMatch && statusMatch) {
                        row.style.display = '';
                        // Add animation
                        row.style.animation = 'fadeInUp 0.3s ease-out';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            searchInput.addEventListener('input', filterTable);
            statusFilter.addEventListener('change', filterTable);
            
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
            
            window.removeToast = function(id) {
                const toast = document.getElementById(id);
                if (toast) {
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => {
                        toast.remove();
                    }, 500);
                }
            };
            
            // Check for session messages
            @if(session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif
            
            @if(session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif
            
            // Add ripple effect to buttons
            document.querySelectorAll('.ripple').forEach(button => {
                button.addEventListener('click', function(e) {
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
            
            // Add hover effect to cards
            const cards = document.querySelectorAll('.event-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.zIndex = '10';
                });
                
                card.addEventListener('mouseleave', () => {
                    card.style.zIndex = '1';
                });
            });
            
            // Auto-refresh events every 30 seconds
            setInterval(() => {
                // Simulate data refresh
                const countElements = document.querySelectorAll('.text-2xl.font-bold.text-gray-800');
                countElements.forEach(el => {
                    const current = parseInt(el.textContent);
                    if (!isNaN(current)) {
                        const change = Math.floor(Math.random() * 3);
                        el.textContent = current + change;
                    }
                });
            }, 30000);
        });
    </script>
</body>
</html>