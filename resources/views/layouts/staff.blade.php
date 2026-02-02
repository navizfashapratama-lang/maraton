<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Staff Dashboard') - Marathon Events</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Staff CSS -->
    <style>
        :root {
            --sidebar-width: 220px;
            --header-height: 70px;
            --primary-color: #00b4d8;
            --secondary-color: #0077b6;
            --sidebar-bg: #1e3a8a;
            --sidebar-color: #cbd5e1;
            --sidebar-active: #00b4d8;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f1f5f9;
            color: #334155;
        }
        
        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--sidebar-bg);
            color: var(--sidebar-color);
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header .logo {
            color: white;
            font-weight: 700;
            font-size: 18px;
            text-decoration: none;
        }
        
        .sidebar-header .logo span {
            color: var(--primary-color);
        }
        
        .sidebar-menu {
            padding: 15px 0;
        }
        
        .nav-link {
            color: var(--sidebar-color);
            padding: 10px 15px;
            margin: 3px 10px;
            border-radius: 8px;
            transition: all 0.3s;
            font-size: 14px;
        }
        
        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link.active {
            color: white;
            background: var(--sidebar-active);
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 15px;
            min-height: 100vh;
        }
        
        /* Header */
        .header {
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 0 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        /* Cards */
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
            margin-bottom: 20px;
        }
        
        .card:hover {
            transform: translateY(-3px);
        }
        
        .stat-card {
            border-top: 4px solid var(--primary-color);
        }
        
        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            font-weight: 500;
            padding: 8px 16px;
            font-size: 14px;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        /* Badges */
        .badge {
            font-size: 11px;
            padding: 4px 8px;
        }
        
        /* Table */
        .table {
            font-size: 14px;
        }
        
        .table th {
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid var(--primary-color);
            font-size: 13px;
            text-transform: uppercase;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
                overflow: hidden;
            }
            
            .sidebar .nav-text,
            .sidebar-header .logo span {
                display: none;
            }
            
            .sidebar:hover {
                width: var(--sidebar-width);
            }
            
            .sidebar:hover .nav-text,
            .sidebar:hover .logo span {
                display: inline;
            }
            
            .main-content {
                margin-left: 60px;
            }
            
            .sidebar:hover ~ .main-content {
                margin-left: var(--sidebar-width);
            }
        }
        
        /* Avatar Circle */
        .avatar-circle {
            width: 32px;
            height: 32px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('staff.dashboard') }}" class="logo d-flex align-items-center">
                <i class="fas fa-running me-2"></i>
                <span class="nav-text">MARATHON</span>
            </a>
            <small class="text-muted d-block mt-1" style="font-size: 11px;">Staff Panel</small>
        </div>
        
        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('staff.dashboard')) active @endif" 
                       href="{{ route('staff.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('staff.events.*')) active @endif" 
                       href="{{ route('staff.events.index') }}">
                        <i class="fas fa-calendar-check"></i>
                        <span class="nav-text">Kelola Event</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('staff.registrations.*')) active @endif" 
                       href="{{ route('staff.registrations.index') }}">
                        <i class="fas fa-user-check"></i>
                        <span class="nav-text">Verifikasi Peserta</span>
                        @if(($pending_registrations ?? 0) > 0)
                        <span class="badge bg-danger float-end">{{ $pending_registrations ?? 0 }}</span>
                        @endif
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('staff.payments.*')) active @endif" 
                       href="{{ route('staff.payments.index') }}">
                        <i class="fas fa-money-check-alt"></i>
                        <span class="nav-text">Verifikasi Pembayaran</span>
                        @if(($pending_payments ?? 0) > 0)
                        <span class="badge bg-warning float-end">{{ $pending_payments ?? 0 }}</span>
                        @endif
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('staff.packages.*')) active @endif" 
                       href="{{ route('staff.packages.index') }}">
                        <i class="fas fa-box"></i>
                        <span class="nav-text">Paket Lomba</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('staff.results.*')) active @endif" 
                       href="{{ route('staff.results.index') }}">
                        <i class="fas fa-list-ol"></i>
                        <span class="nav-text">Hasil Lomba</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('staff.export.registrations') }}">
                        <i class="fas fa-file-export"></i>
                        <span class="nav-text">Export Data</span>
                    </a>
                </li>
                
                <hr class="text-muted mx-2 my-2">
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        <span class="nav-text">Website</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link text-danger" href="#" onclick="confirmLogout()">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="nav-text">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-primary btn-sm d-md-none me-2" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="mb-0">@yield('page-title', 'Dashboard Staff')</h5>
            </div>
            
            <div class="d-flex align-items-center">
                <!-- Quick Stats -->
                <div class="d-none d-md-flex me-3">
                    <div class="text-end">
                        <small class="text-muted d-block">Event Aktif</small>
                        <span class="fw-bold">{{ $active_events ?? 0 }}</span>
                    </div>
                    <div class="vr mx-3"></div>
                    <div class="text-end">
                        <small class="text-muted d-block">Pendaftaran Hari Ini</small>
                        <span class="fw-bold">{{ $today_registrations ?? 0 }}</span>
                    </div>
                </div>
                
                <!-- Notifications -->
                <div class="dropdown me-2">
                    <button class="btn btn-outline-primary btn-sm position-relative" type="button" 
                            data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        @if(($notification_count ?? 0) > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px;">
                            {{ $notification_count ?? 0 }}
                        </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0" style="width: 280px;">
                        <div class="dropdown-header bg-light">
                            <h6 class="mb-0">Notifikasi</h6>
                            <small class="text-muted">Pembaruan terbaru</small>
                        </div>
                        <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                            @php
                                $notifications = DB::table('notifikasi')
                                    ->where('user_id', session('user_id'))
                                    ->orWhereNull('user_id')
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
                            @endphp
                            
                            @if($notifications->count() > 0)
                                @foreach($notifications as $notification)
                                <a href="{{ $notification->tautan ?? '#' }}" 
                                   class="list-group-item list-group-item-action border-0 {{ $notification->dibaca ? '' : 'bg-light' }}">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            @switch($notification->jenis)
                                                @case('pendaftaran_baru')
                                                    <i class="fas fa-user-plus text-success"></i>
                                                    @break
                                                @case('pembayaran_baru')
                                                    <i class="fas fa-credit-card text-warning"></i>
                                                    @break
                                                @case('pembayaran_terverifikasi')
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    @break
                                                @default
                                                    <i class="fas fa-info-circle text-primary"></i>
                                            @endswitch
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0" style="font-size: 13px;">{{ $notification->judul }}</h6>
                                            <p class="mb-0 small text-muted">{{ Str::limit($notification->pesan, 40) }}</p>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            @else
                                <div class="list-group-item border-0 text-center py-3">
                                    <i class="fas fa-bell-slash text-muted mb-2"></i>
                                    <p class="mb-0 text-muted small">Tidak ada notifikasi</p>
                                </div>
                            @endif
                        </div>
                        <div class="dropdown-footer text-center py-2 bg-light">
                            <a href="#" class="text-decoration-none small">Lihat semua</a>
                        </div>
                    </div>
                </div>
                
                <!-- User Profile -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm d-flex align-items-center" 
                            type="button" data-bs-toggle="dropdown">
                        <div class="me-2">
                            <div class="avatar-circle">
                                {{ strtoupper(substr(session('user_nama'), 0, 1)) }}
                            </div>
                        </div>
                        <div class="text-start d-none d-md-block">
                            <small class="d-block" style="font-size: 12px; line-height: 1.2;">
                                {{ Str::limit(session('user_nama'), 15) }}
                            </small>
                            <small class="text-muted" style="font-size: 10px;">
                                {{ ucfirst(session('user_peran')) }}
                            </small>
                        </div>
                        <i class="fas fa-chevron-down ms-1"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user me-2"></i>Profil
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cog me-2"></i>Pengaturan
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="#" onclick="confirmLogout()">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="container-fluid">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            <!-- Main Content -->
            @yield('content')
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.style.width = sidebar.style.width === '220px' ? '60px' : '220px';
        });
        
        // Auto dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Confirm logout
        function confirmLogout() {
            if (confirm('Yakin ingin logout?')) {
                document.getElementById('logout-form').submit();
            }
        }
        
        // Mark notifications as read
        document.querySelectorAll('.list-group-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (!this.href || this.href === '#') {
                    e.preventDefault();
                }
                this.classList.remove('bg-light');
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>