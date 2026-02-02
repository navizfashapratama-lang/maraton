<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Paket - Staff Area</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .package-card {
            transition: transform 0.3s;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }
        .package-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .price-tag {
            font-size: 1.5rem;
            font-weight: 700;
            color: #28a745;
        }
        .badge-included {
            background-color: #d4edda;
            color: #155724;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
        }
        .badge-excluded {
            background-color: #f8d7da;
            color: #721c24;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
        }
        .event-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('staff.dashboard') }}">
                <i class="fas fa-running"></i> Marathon System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('staff.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('staff.events.index') }}">
                            <i class="fas fa-calendar-alt"></i> Event
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('staff.registrations.index') }}">
                            <i class="fas fa-users"></i> Pendaftaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('staff.payments.index') }}">
                            <i class="fas fa-credit-card"></i> Pembayaran
                        </a>
                    </li>
                    <li class="nav-item dropdown active">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-box"></i> Paket
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item active" href="{{ route('staff.packages.index') }}">Semua Paket</a></li>
                            <li><a class="dropdown-item" href="{{ route('staff.packages.create') }}">Tambah Paket Baru</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-boxes text-primary"></i> Kelola Paket Event
            </h1>
            <a href="{{ route('staff.packages.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Paket Baru
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Total Paket</h6>
                                <h3>{{ $stats['total'] }}</h3>
                            </div>
                            <i class="fas fa-box fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Dengan Race Kit</h6>
                                <h3>{{ $stats['with_race_kit'] }}</h3>
                            </div>
                            <i class="fas fa-gift fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Dengan Medali</h6>
                                <h3>{{ $stats['with_medal'] }}</h3>
                            </div>
                            <i class="fas fa-medal fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Dengan Kaos</h6>
                                <h3>{{ $stats['with_shirt'] }}</h3>
                            </div>
                            <i class="fas fa-tshirt fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('staff.packages.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari nama paket atau event..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Packages Table -->
        <div class="card">
            <div class="card-body">
                @if($packages->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Paket</th>
                                    <th>Event</th>
                                    <th>Fasilitas</th>
                                    <th>Harga</th>
                                    <th>Tanggal Event</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($packages as $package)
                                    <tr>
                                        <td>{{ $loop->iteration + (($packages->currentPage() - 1) * $packages->perPage()) }}</td>
                                        <td>
                                            <strong>{{ $package->nama }}</strong>
                                            <div class="text-muted small">ID: {{ $package->id }}</div>
                                        </td>
                                        <td>
                                            <strong>{{ $package->event_nama }}</strong>
                                            <div class="text-muted small">
                                                {{ date('d/m/Y', strtotime($package->tanggal)) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @if($package->termasuk_race_kit)
                                                    <span class="badge-included">
                                                        <i class="fas fa-gift"></i> Race Kit
                                                    </span>
                                                @else
                                                    <span class="badge-excluded">
                                                        <i class="fas fa-gift"></i> Race Kit
                                                    </span>
                                                @endif
                                                
                                                @if($package->termasuk_medali)
                                                    <span class="badge-included">
                                                        <i class="fas fa-medal"></i> Medali
                                                    </span>
                                                @else
                                                    <span class="badge-excluded">
                                                        <i class="fas fa-medal"></i> Medali
                                                    </span>
                                                @endif
                                                
                                                @if($package->termasuk_kaos)
                                                    <span class="badge-included">
                                                        <i class="fas fa-tshirt"></i> Kaos
                                                    </span>
                                                @else
                                                    <span class="badge-excluded">
                                                        <i class="fas fa-tshirt"></i> Kaos
                                                    </span>
                                                @endif
                                                
                                                @if($package->termasuk_sertifikat)
                                                    <span class="badge-included">
                                                        <i class="fas fa-certificate"></i> Sertifikat
                                                    </span>
                                                @else
                                                    <span class="badge-excluded">
                                                        <i class="fas fa-certificate"></i> Sertifikat
                                                    </span>
                                                @endif
                                                
                                                @if($package->termasuk_snack)
                                                    <span class="badge-included">
                                                        <i class="fas fa-utensils"></i> Snack
                                                    </span>
                                                @else
                                                    <span class="badge-excluded">
                                                        <i class="fas fa-utensils"></i> Snack
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="price-tag">
                                            Rp {{ number_format($package->harga, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            {{ date('d/m/Y', strtotime($package->tanggal)) }}
                                            <div class="text-muted small">
                                                {{ date('H:i', strtotime($package->created_at)) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('staff.packages.edit', $package->id) }}" 
                                                   class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Menampilkan {{ $packages->firstItem() }} - {{ $packages->lastItem() }} dari {{ $packages->total() }} paket
                        </div>
                        <div>
                            {{ $packages->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada paket</h5>
                        <p class="text-muted">Mulai dengan menambahkan paket pertama Anda</p>
                        <a href="{{ route('staff.packages.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Paket Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Summary Card -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-chart-pie text-primary"></i> Ringkasan Fasilitas
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6>Distribusi Fasilitas</h6>
                            <div class="progress mb-2" style="height: 20px;">
                                <div class="progress-bar bg-success" style="width: {{ $stats['total'] > 0 ? ($stats['with_race_kit'] / $stats['total'] * 100) : 0 }}%">
                                    Race Kit: {{ $stats['with_race_kit'] }}
                                </div>
                            </div>
                            <div class="progress mb-2" style="height: 20px;">
                                <div class="progress-bar bg-warning" style="width: {{ $stats['total'] > 0 ? ($stats['with_medal'] / $stats['total'] * 100) : 0 }}%">
                                    Medali: {{ $stats['with_medal'] }}
                                </div>
                            </div>
                            <div class="progress mb-2" style="height: 20px;">
                                <div class="progress-bar bg-info" style="width: {{ $stats['total'] > 0 ? ($stats['with_shirt'] / $stats['total'] * 100) : 0 }}%">
                                    Kaos: {{ $stats['with_shirt'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Informasi Penting</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-info-circle text-primary"></i>
                                <strong>Total Paket:</strong> {{ $stats['total'] }}
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success"></i>
                                <strong>Paket dengan Race Kit:</strong> {{ $stats['with_race_kit'] }}
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-warning"></i>
                                <strong>Paket dengan Medali:</strong> {{ $stats['with_medal'] }}
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-info"></i>
                                <strong>Paket dengan Kaos:</strong> {{ $stats['with_shirt'] }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-5 py-3 bg-light border-top">
        <div class="container text-center">
            <p class="mb-0 text-muted">
                &copy; {{ date('Y') }} Marathon System - Staff Area
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Auto-focus on search input
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.focus();
            }
        });
    </script>
</body>
</html>