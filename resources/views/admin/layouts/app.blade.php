<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - Marathon Events')</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #4a6bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
        }
        
        .navbar-admin {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2a4bff 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .sidebar {
            background: white;
            min-height: calc(100vh - 56px);
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 56px;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu-item {
            padding: 0;
        }
        
        .sidebar-menu-link {
            display: block;
            padding: 12px 20px;
            color: var(--dark-color);
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }
        
        .sidebar-menu-link:hover {
            background-color: rgba(74, 107, 255, 0.05);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }
        
        .sidebar-menu-link.active {
            background-color: rgba(74, 107, 255, 0.1);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
            font-weight: 500;
        }
        
        .sidebar-menu-link i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }
        
        .page-title {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            color: var(--secondary-color);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1rem 1.5rem;
            font-weight: 600;
        }
        
        .badge-admin { background-color: var(--danger-color); color: white; }
        .badge-staff { background-color: var(--info-color); color: white; }
        .badge-kasir { background-color: var(--warning-color); color: black; }
        .badge-peserta { background-color: var(--secondary-color); color: white; }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(74, 107, 255, 0.25);
        }
        
        .form-text {
            font-size: 0.85rem;
            color: var(--secondary-color);
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-admin navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-running me-2"></i>MARATHON ADMIN
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarAdmin">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="d-none d-md-block">
                                <div class="fw-semibold">{{ session('user_nama') }}</div>
                                <small class="opacity-75">{{ ucfirst(session('user_peran')) }}</small>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Akun</h6></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil Saya</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 d-none d-md-block p-0">
                <div class="sidebar">
                    <ul class="sidebar-menu">
                        <li class="sidebar-menu-item">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="sidebar-menu-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('admin.users.index') }}" 
                               class="sidebar-menu-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i> Pengguna
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('admin.events.index') }}" 
                               class="sidebar-menu-link {{ request()->is('admin/events*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-alt"></i> Event
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('admin.registrations.index') }}" 
                               class="sidebar-menu-link {{ request()->is('admin/registrations*') ? 'active' : '' }}">
                                <i class="fas fa-clipboard-list"></i> Pendaftaran
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('admin.payments.index') }}" 
                               class="sidebar-menu-link {{ request()->is('admin/payments*') ? 'active' : '' }}">
                                <i class="fas fa-money-bill-wave"></i> Pembayaran
                            </a>
                        </li>
                        <li class="sidebar-menu-item">
                            <a href="{{ route('admin.settings.index') }}" 
                               class="sidebar-menu-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                                <i class="fas fa-cog"></i> Pengaturan
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                        <p class="page-subtitle mb-0">@yield('page-subtitle', '')</p>
                    </div>
                    @hasSection('page-actions')
                        <div class="page-actions">
                            @yield('page-actions')
                        </div>
                    @endif
                </div>

                <!-- Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Active sidebar based on current URL
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const sidebarLinks = document.querySelectorAll('.sidebar-menu-link');
            
            sidebarLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>