<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Marathon Events</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Admin CSS -->
    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 70px;
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --sidebar-bg: #2b2d42;
            --sidebar-color: #adb5bd;
            --sidebar-active: #4361ee;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fb;
            color: #333;
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
        }
        
        .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header .logo {
            color: white;
            font-weight: 700;
            font-size: 20px;
            text-decoration: none;
        }
        
        .sidebar-header .logo span {
            color: var(--primary-color);
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .nav-link {
            color: var(--sidebar-color);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
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
            width: 24px;
            margin-right: 10px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
        }
        
        /* Header */
        .header {
            height: var(--header-height);
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        /* Cards */
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card {
            border-left: 5px solid var(--primary-color);
        }
        
        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            font-weight: 500;
            padding: 10px 20px;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        /* Table */
        .table th {
            font-weight: 600;
            color: var(--dark-color);
            border-bottom: 2px solid var(--primary-color);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }
            
            .sidebar .nav-text {
                display: none;
            }
            
            .sidebar:hover {
                width: var(--sidebar-width);
            }
            
            .sidebar:hover .nav-text {
                display: inline;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .sidebar:hover ~ .main-content {
                margin-left: var(--sidebar-width);
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="logo d-flex align-items-center">
                <i class="fas fa-running me-2"></i>
                <span class="nav-text">MARATHON</span>
            </a>
            <small class="text-muted d-block mt-1">Admin Panel</small>
        </div>
        
        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('admin.events*')) active @endif" 
                       href="#">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="nav-text">Event Management</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('admin.registrations*')) active @endif" 
                       href="#">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Pendaftaran</span>
                        <span class="badge bg-danger float-end">5</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('admin.payments*')) active @endif" 
                       href="#">
                        <i class="fas fa-credit-card"></i>
                        <span class="nav-text">Pembayaran</span>
                        <span class="badge bg-warning float-end">12</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-user-friends"></i>
                        <span class="nav-text">User Management</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-chart-bar"></i>
                        <span class="nav-text">Reports</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-certificate"></i>
                        <span class="nav-text">Sertifikat</span>
                    </a>
                </li>
                
                <hr class="text-muted mx-3 my-3">
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        <span class="nav-text">Website Public</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link text-danger" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
                <button class="btn btn-outline-primary d-md-none me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
            </div>
            
            <div class="d-flex align-items-center">
                <!-- Notifications -->
                <div class="dropdown me-3">
                    <button class="btn btn-outline-primary position-relative" type="button" 
                            data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            5
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0" style="width: 300px;">
                        <div class="dropdown-header">
                            <h6 class="mb-0">Notifikasi</h6>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-plus text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small class="text-muted float-end">2 menit lalu</small>
                                        <h6 class="mb-0">Pendaftaran Baru</h6>
                                        <p class="mb-0 small">Budi Santoso mendaftar Jakarta Fun Run</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-credit-card text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <small class="text-muted float-end">1 jam lalu</small>
                                        <h6 class="mb-0">Pembayaran Baru</h6>
                                        <p class="mb-0 small">Sari Dewi mengupload bukti pembayaran</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="dropdown-footer text-center py-2">
                            <a href="#" class="text-decoration-none small">Lihat semua notifikasi</a>
                        </div>
                    </div>
                </div>
                
                <!-- User Profile -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary d-flex align-items-center" 
                            type="button" data-bs-toggle="dropdown">
                        <div class="me-2">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 36px; height: 36px;">
                                {{ Auth::user()->initials }}
                            </div>
                        </div>
                        <div class="text-start">
                            <small class="d-block">{{ Auth::user()->nama }}</small>
                            <small class="text-muted">{{ Auth::user()->role_name }}</small>
                        </div>
                        <i class="fas fa-chevron-down ms-2"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user me-2"></i>Profil Saya
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cog me-2"></i>Pengaturan
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="#" 
   onclick="if(confirm('Yakin logout?')) { window.location.href='{{ route('logout') }}'; }">
    <i class="fas fa-sign-out-alt me-2"></i>Logout
</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div class="container-fluid mt-4">
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
            
            <!-- Main Content -->
            @yield('content')
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
        
        // Auto dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>