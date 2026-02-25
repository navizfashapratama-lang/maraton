<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Dashboard') - Marathon Events</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'marathon-blue': '#1e40af',
                        'marathon-light': '#00b4d8',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        .nav-active {
            background: rgba(255, 255, 255, 0.15);
            border-left: 4px solid #00b4d8;
            color: white !important;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full overflow-x-hidden">

    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden transition-opacity lg:hidden"></div>

    <aside id="sidebar" class="fixed top-0 left-0 z-50 h-screen w-64 transition-transform -translate-x-full lg:translate-x-0 bg-gradient-to-b from-marathon-blue to-blue-900 text-white shadow-2xl">
        <div class="flex flex-col h-full">
            <div class="p-6 flex items-center justify-between">
                <a href="{{ route('staff.dashboard') }}" class="flex items-center space-x-3 group">
                    <div class="bg-white p-2 rounded-lg group-hover:rotate-12 transition-transform">
                        <i class="fas fa-running text-marathon-blue text-xl"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tighter">MARATHON<span class="text-marathon-light text-sm block tracking-widest uppercase opacity-80">Staff Panel</span></span>
                </a>
                <button id="closeSidebar" class="lg:hidden text-white/70 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                <p class="px-4 text-[10px] font-semibold text-white/40 uppercase tracking-widest mb-2">Main Menu</p>
                
                <a href="{{ route('staff.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all hover:bg-white/10 @if(request()->routeIs('staff.dashboard')) nav-active @endif">
                    <i class="fas fa-tachometer-alt w-6"></i> Dashboard
                </a>

                <a href="{{ route('staff.profile.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all hover:bg-white/10 @if(request()->routeIs('staff.profile.*')) nav-active @endif">
                    <i class="fas fa-user w-6"></i> Profil Staff
                </a>
                @php
                    $pending_registrations = DB::table('pendaftaran')->where('status_pendaftaran', 'menunggu')->count();
                    $pending_payments = DB::table('pembayaran')->where('status', 'menunggu')->count();
                @endphp

                <a href="{{ route('staff.registrations.index') }}" class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all hover:bg-white/10 @if(request()->routeIs('staff.registrations.*')) nav-active @endif">
                    <div class="flex items-center"><i class="fas fa-user-check w-6"></i> Verifikasi Peserta</div>
                    @if($pending_registrations > 0)
                        <span class="bg-red-500 text-[10px] px-2 py-1 rounded-full text-white">{{ $pending_registrations }}</span>
                    @endif
                </a>

                <a href="{{ route('staff.payments.index') }}" class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all hover:bg-white/10 @if(request()->routeIs('staff.payments.*')) nav-active @endif">
                    <div class="flex items-center"><i class="fas fa-money-check-alt w-6"></i> Verifikasi Bayar</div>
                    @if($pending_payments > 0)
                        <span class="bg-yellow-400 text-blue-900 text-[10px] font-bold px-2 py-1 rounded-full">{{ $pending_payments }}</span>
                    @endif
                </a>

                <a href="{{ route('staff.packages.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all hover:bg-white/10 @if(request()->routeIs('staff.packages.*')) nav-active @endif">
                    <i class="fas fa-box w-6"></i> Paket Lomba
                </a>
<a href="#" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all hover:bg-white/10">
    <i class="fas fa-file-export w-6"></i> Export Data
</a>

                <div class="pt-4 mt-4 border-t border-white/10">
                    <a href="{{ url('/') }}" target="_blank" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all hover:bg-white/10">
                        <i class="fas fa-external-link-alt w-6"></i> Lihat Website
                    </a>
                    <button onclick="confirmLogout()" class="w-full flex items-center px-4 py-3 text-sm font-medium text-orange-300 rounded-xl transition-all hover:bg-white/10">
                        <i class="fas fa-sign-out-alt w-6"></i> Logout
                    </button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                </div>
            </nav>
        </div>
    </aside>

    <div class="lg:ml-64 flex flex-col min-h-screen">
        
        <header class="sticky top-0 z-30 flex items-center justify-between px-6 py-4 bg-white/80 backdrop-blur-md border-b border-slate-200">
            <div class="flex items-center">
                <button id="openSidebar" class="p-2 mr-4 text-slate-600 lg:hidden hover:bg-slate-100 rounded-lg">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h1>
            </div>

            <div class="flex items-center space-x-4">
                <div class="hidden md:flex items-center space-x-6 mr-4 border-r pr-6 border-slate-200">
                    <div class="text-right">
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Event Aktif</p>
                        <p class="text-sm font-bold text-marathon-blue">{{ DB::table('lomba')->where('status', 'mendatang')->count() }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Hari Ini</p>
                        <p class="text-sm font-bold text-marathon-blue">{{ DB::table('pendaftaran')->whereDate('created_at', today())->count() }}</p>
                    </div>
                </div>

                <div class="relative">
                    @php
                        $notifications = DB::table('notifikasi')->where('user_id', session('user_id'))->orWhereNull('user_id')->orderBy('created_at', 'desc')->limit(5)->get();
                        $unread = $notifications->where('dibaca', 0)->count();
                    @endphp
                    <button id="notifBtn" class="relative p-2 text-slate-500 hover:bg-slate-100 rounded-full transition-colors">
                        <i class="fas fa-bell text-xl"></i>
                        @if($unread > 0)
                            <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 border-2 border-white rounded-full text-[8px] text-white flex items-center justify-center">{{ $unread }}</span>
                        @endif
                    </button>
                    <div id="notifDropdown" class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 hidden overflow-hidden transform transition-all">
                        <div class="p-4 border-b border-slate-50 flex justify-between items-center">
                            <span class="font-bold text-slate-700">Notifikasi</span>
                            <a href="#" class="text-xs text-marathon-blue hover:underline">Lihat Semua</a>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @forelse($notifications as $notif)
                                <div class="p-4 border-b border-slate-50 hover:bg-slate-50 transition-colors cursor-pointer">
                                    <div class="flex space-x-3">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                                            <i class="fas fa-info-circle"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-slate-800">{{ $notif->judul }}</p>
                                            <p class="text-[11px] text-slate-500 truncate w-48">{{ $notif->pesan }}</p>
                                            <p class="text-[10px] text-slate-400 mt-1">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-slate-400">
                                    <i class="fas fa-bell-slash block text-2xl mb-2 opacity-20"></i>
                                    <p class="text-xs">Tidak ada notifikasi</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-3 pl-4 border-l border-slate-200">
                    <div class="hidden sm:block text-right">
                        <p class="text-xs font-bold text-slate-700 leading-none">{{ session('user_nama') }}</p>
                        <p class="text-[10px] text-slate-400 uppercase tracking-tighter">{{ session('user_peran') }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-marathon-blue to-marathon-light flex items-center justify-center text-white font-bold shadow-lg shadow-blue-200">
                        {{ strtoupper(substr(session('user_nama'), 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6 flex-1">
            @if(session('success'))
            <div class="mb-6 flex items-center p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl shadow-sm animate-bounce-short">
                <i class="fas fa-check-circle mr-3"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 flex items-center p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-xl shadow-sm">
                <i class="fas fa-exclamation-circle mr-3"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
            @endif

            <div class="animate-fade-in">
                @yield('content')
            </div>
        </main>

        <footer class="p-6 bg-white border-t border-slate-200 text-center">
            <p class="text-xs text-slate-400">&copy; 2026 Marathon Events. Developed with <i class="fas fa-heart text-rose-400"></i> for Runners.</p>
        </footer>
    </div>

    <script>
        // Sidebar Toggle Logic
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        openBtn.addEventListener('click', toggleSidebar);
        closeBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Notif Dropdown
        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');

        notifBtn.addEventListener('click', () => {
            notifDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        window.addEventListener('click', (e) => {
            if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
                notifDropdown.classList.add('hidden');
            }
        });

        function confirmLogout() {
            if (confirm('Apakah Anda yakin ingin keluar dari sistem?')) {
                document.getElementById('logout-form').submit();
            }
        }

        // Auto hide alerts after 5s
        setTimeout(() => {
            const alerts = document.querySelectorAll('.animate-bounce-short');
            alerts.forEach(el => el.style.display = 'none');
        }, 5000);
    </script>
    @stack('scripts')
</body>
</html>