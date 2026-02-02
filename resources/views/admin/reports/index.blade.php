<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan & Analitik - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-running text-2xl text-purple-600 mr-2"></i>
                        <span class="text-xl font-bold text-gray-800">Maraton Admin</span>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="{{ route('admin.dashboard') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.payments.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-money-bill-wave mr-2"></i> Pembayaran
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="border-purple-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-chart-bar mr-2"></i> Laporan
                        </a>
                        <a href="{{ route('admin.events.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-calendar-alt mr-2"></i> Event
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-users mr-2"></i> Pengguna
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="relative mr-4">
                        <i class="fas fa-bell text-gray-500 text-xl hover:text-gray-700 cursor-pointer"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Admin</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Laporan & Analitik</h1>
            <p class="text-gray-600">Analisis data lengkap sistem maraton</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @php
                $stats = [
                    'total_participants' => DB::table('pendaftaran')->count(),
                    'total_revenue' => DB::table('pembayaran')->where('status', 'terverifikasi')->sum('jumlah'),
                    'active_events' => DB::table('lomba')->where('status', 'mendatang')->count(),
                    'pending_payments' => DB::table('pembayaran')->where('status', 'menunggu')->count(),
                ];
            @endphp
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Peserta</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_participants']) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-running text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Event Aktif</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['active_events'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 card-hover">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Pembayaran Tertunda</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_payments'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 lg:mb-0">Filter Laporan</h2>
                <div class="flex flex-wrap gap-3">
                    <button onclick="exportPDF()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i> Export PDF
                    </button>
                    <button onclick="exportExcel()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition flex items-center">
                        <i class="fas fa-file-excel mr-2"></i> Export Excel
                    </button>
                    <button onclick="printReport()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition flex items-center">
                        <i class="fas fa-print mr-2"></i> Print
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" id="startDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                    <input type="date" id="endDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Laporan</label>
                    <select id="reportType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="overview">Overview</option>
                        <option value="financial">Finansial</option>
                        <option value="participants">Peserta</option>
                        <option value="events">Event</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button onclick="applyFilter()" class="w-full px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:opacity-90 transition font-medium">
                        <i class="fas fa-filter mr-2"></i> Terapkan
                    </button>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Revenue Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pendapatan per Bulan</h3>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Participants Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Peserta per Event</h3>
                <div class="h-64">
                    <canvas id="participantsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Reports -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Laporan Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="#" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-file-invoice-dollar text-blue-600"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800">Laporan Finansial</h4>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Laporan pendapatan dan transaksi lengkap</p>
                    <div class="flex items-center text-sm text-blue-600">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </div>
                </a>
                
                <a href="#" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-users text-green-600"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800">Laporan Peserta</h4>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Data peserta dan statistik demografi</p>
                    <div class="flex items-center text-sm text-green-600">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </div>
                </a>
                
                <a href="#" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-check text-purple-600"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800">Laporan Event</h4>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Performansi event dan partisipasi</p>
                    <div class="flex items-center text-sm text-purple-600">
                        <span>Lihat Detail</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </div>
                </a>
            </div>
        </div>

        <!-- Detailed Reports Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Detail Pembayaran Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $recentPayments = DB::table('pembayaran')
                                ->join('pendaftaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')
                                ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
                                ->select('pembayaran.*', 'pendaftaran.nama_lengkap', 'lomba.nama as event_nama')
                                ->orderBy('pembayaran.created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        
                        @forelse($recentPayments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $payment->kode_pembayaran }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $payment->nama_lengkap }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $payment->event_nama }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-green-600">
                                    Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                                        'terverifikasi' => 'bg-green-100 text-green-800',
                                        'ditolak' => 'bg-red-100 text-red-800',
                                        'kadaluarsa' => 'bg-gray-100 text-gray-800'
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$payment->status] ?? 'bg-gray-100' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-money-bill-wave text-3xl mb-2 text-gray-300"></i>
                                <p>Belum ada data pembayaran</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Payment Methods -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="font-semibold text-gray-800 mb-4">Metode Pembayaran</h4>
                @php
                    $paymentMethods = DB::table('pembayaran')
                        ->select('metode_pembayaran', DB::raw('COUNT(*) as count'))
                        ->groupBy('metode_pembayaran')
                        ->get();
                @endphp
                
                <div class="space-y-4">
                    @foreach($paymentMethods as $method)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-gray-700">{{ ucfirst($method->metode_pembayaran) }}</span>
                            <span class="text-sm font-medium">{{ $method->count }} transaksi</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($method->count / max($paymentMethods->sum('count'), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Gender Distribution -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="font-semibold text-gray-800 mb-4">Distribusi Gender</h4>
                @php
                    $genderStats = DB::table('pendaftaran')
                        ->select('jenis_kelamin', DB::raw('COUNT(*) as count'))
                        ->groupBy('jenis_kelamin')
                        ->get();
                @endphp
                
                <div class="flex items-center justify-center h-40">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>

            <!-- Event Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="font-semibold text-gray-800 mb-4">Statistik Event</h4>
                @php
                    $eventStats = DB::table('lomba')
                        ->select('status', DB::raw('COUNT(*) as count'))
                        ->groupBy('status')
                        ->get();
                @endphp
                
                <div class="space-y-3">
                    @foreach($eventStats as $stat)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700">{{ ucfirst($stat->status) }}</span>
                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                            {{ $stat->count }} event
                        </span>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Total Event:</span>
                        <span class="font-semibold">{{ $eventStats->sum('count') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center text-gray-500 text-sm">
                <p>© 2024 Sistem Maraton. All rights reserved.</p>
                <p class="mt-1">Dashboard Laporan & Analitik v1.0</p>
            </div>
        </div>
    </footer>

    <script>
        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: [5000000, 7500000, 12000000, 8500000, 15000000, 18000000],
                        borderColor: '#8B5CF6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000) + 'Jt';
                                    }
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    }
                }
            });

            // Participants Chart
            const participantsCtx = document.getElementById('participantsChart').getContext('2d');
            new Chart(participantsCtx, {
                type: 'bar',
                data: {
                    labels: ['5K Run', '10K Run', 'Half Marathon', 'Marathon'],
                    datasets: [{
                        label: 'Jumlah Peserta',
                        data: [45, 30, 25, 15],
                        backgroundColor: [
                            '#3B82F6',
                            '#10B981',
                            '#F59E0B',
                            '#8B5CF6'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Gender Chart
            const genderCtx = document.getElementById('genderChart').getContext('2d');
            new Chart(genderCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Laki-laki', 'Perempuan'],
                    datasets: [{
                        data: [65, 35],
                        backgroundColor: ['#3B82F6', '#EC4899'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });

        // Filter Functions
        function applyFilter() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const reportType = document.getElementById('reportType').value;
            
            // Show loading
            showNotification('Memproses filter...', 'info');
            
            // In a real app, this would be an AJAX request
            setTimeout(() => {
                showNotification('Filter diterapkan!', 'success');
            }, 1000);
        }

        function exportPDF() {
            showNotification('Menyiapkan PDF...', 'info');
            setTimeout(() => {
                showNotification('PDF berhasil diunduh!', 'success');
            }, 1500);
        }

        function exportExcel() {
            showNotification('Menyiapkan Excel...', 'info');
            setTimeout(() => {
                showNotification('Excel berhasil diunduh!', 'success');
            }, 1500);
        }

        function printReport() {
            window.print();
        }

        function showNotification(message, type = 'info') {
            // Simple notification
            alert(message);
        }

        // Set default dates
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        
        document.getElementById('startDate').value = firstDay.toISOString().split('T')[0];
        document.getElementById('endDate').value = today.toISOString().split('T')[0];
    </script>
</body>
</html>