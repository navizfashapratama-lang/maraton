<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Marathon Events Admin')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#06b6d4',
                        success: '#10b981',
                        danger: '#ef4444',
                        warning: '#f59e0b',
                        dark: '#1f2937',
                        light: '#f9fafb'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                    }
                }
            }
        }
    </script>
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .sidebar-link {
            @apply flex items-center px-4 py-3 text-gray-700 hover:bg-primary hover:text-white transition-all duration-300 rounded-lg;
        }
        
        .sidebar-link.active {
            @apply bg-primary text-white shadow-lg;
        }
        
        .card-hover {
            @apply transition-all duration-300 hover:scale-[1.02] hover:shadow-xl;
        }
        
        .badge-event {
            @apply px-3 py-1 rounded-full text-xs font-semibold;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-xl animate-slide-down">
            <div class="p-6 border-b">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-primary to-secondary rounded-lg flex items-center justify-center">
                        <i class="fas fa-running text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-dark">Marathon</h2>
                        <p class="text-xs text-gray-500">Admin Panel</p>
                    </div>
                </div>
            </div>
            
            <div class="p-4">
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt w-6"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" 
                       class="sidebar-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                        <i class="fas fa-users w-6"></i>
                        <span>Pengguna</span>
                    </a>
                    
                    <a href="{{ route('admin.events.index') }}" 
                       class="sidebar-link {{ request()->is('admin/events*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt w-6"></i>
                        <span>Event</span>
                        <span class="ml-auto bg-primary text-white text-xs px-2 py-1 rounded-full">
                            {{ DB::table('lomba')->count() }}
                        </span>
                    </a>
                    
                    <a href="{{ route('admin.registrations.index') }}" 
                       class="sidebar-link {{ request()->is('admin/registrations*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list w-6"></i>
                        <span>Pendaftaran</span>
                    </a>
                    
                    <a href="{{ route('admin.payments.index') }}" 
                       class="sidebar-link {{ request()->is('admin/payments*') ? 'active' : '' }}">
                        <i class="fas fa-money-bill-wave w-6"></i>
                        <span>Pembayaran</span>
                    </a>
                </nav>
            </div>
            
            <div class="absolute bottom-0 w-64 p-4 border-t">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold">{{ strtoupper(substr(session('user_nama'), 0, 1)) }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-sm">{{ session('user_nama') }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(session('user_peran')) }}</p>
                    </div>
                    <a href="{{ route('logout') }}" class="text-gray-400 hover:text-danger transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm border-b px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-dark">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-gray-600">@yield('page-subtitle', '')</p>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <i class="fas fa-bell text-gray-500 text-xl cursor-pointer hover:text-primary transition-colors"></i>
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-danger text-white text-xs rounded-full flex items-center justify-center">
                                3
                            </span>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-r from-primary to-secondary rounded-full"></div>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 animate-fade-in">
                        <div class="bg-success/10 border border-success/20 text-success px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-check-circle mr-3"></i>
                            {{ session('success') }}
                            <button class="ml-auto text-success/60 hover:text-success" onclick="this.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 animate-fade-in">
                        <div class="bg-danger/10 border border-danger/20 text-danger px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-exclamation-circle mr-3"></i>
                            {{ session('error') }}
                            <button class="ml-auto text-danger/60 hover:text-danger" onclick="this.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                <!-- Page Content -->
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Alpine.js for interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <script>
        // Auto dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('[class*="bg-"]').forEach(alert => {
                if (alert.classList.contains('px-4')) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            });
        }, 5000);
        
        // Search functionality
        function searchEvents() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const events = document.querySelectorAll('.event-card');
            
            events.forEach(event => {
                const title = event.querySelector('.event-title').textContent.toLowerCase();
                const category = event.querySelector('.event-category').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || category.includes(searchTerm)) {
                    event.classList.remove('hidden');
                    event.classList.add('animate-fade-in');
                } else {
                    event.classList.add('hidden');
                }
            });
        }
        
        // Filter by category
        function filterByCategory(category) {
            const events = document.querySelectorAll('.event-card');
            const filterButtons = document.querySelectorAll('.filter-btn');
            
            // Update active button
            filterButtons.forEach(btn => {
                if (btn.dataset.category === category) {
                    btn.classList.add('bg-primary', 'text-white');
                    btn.classList.remove('bg-gray-100', 'text-gray-700');
                } else {
                    btn.classList.remove('bg-primary', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-gray-700');
                }
            });
            
            // Filter events
            events.forEach(event => {
                const eventCategory = event.querySelector('.event-category').textContent;
                
                if (category === 'all' || eventCategory === category) {
                    event.classList.remove('hidden');
                    event.classList.add('animate-fade-in');
                } else {
                    event.classList.add('hidden');
                }
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>