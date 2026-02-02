<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Hasil Lomba - Staff Area</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .position-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 1.1rem;
        }
        .position-1 { background: linear-gradient(45deg, #FFD700, #FFA500); }
        .position-2 { background: linear-gradient(45deg, #C0C0C0, #A0A0A0); }
        .position-3 { background: linear-gradient(45deg, #CD7F32, #A0522D); }
        .position-other { background: linear-gradient(45deg, #6c757d, #495057); }
        .time-badge {
            background: #e9ecef;
            color: #495057;
            padding: 5px 10px;
            border-radius: 20px;
            font-family: monospace;
            font-weight: 600;
        }
        .category-badge {
            background: #d1ecf1;
            color: #0c5460;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
        }
        .results-table tr:hover {
            background-color: #f8f9fa;
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
                        <a class="nav-link" href="{{ route('staff.payments.index') }}">
                            <i class="fas fa-credit-card"></i> Pembayaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('staff.packages.index') }}">
                            <i class="fas fa-box"></i> Paket
                        </a>
                    </li>
                    <li class="nav-item dropdown active">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-trophy"></i> Hasil
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item active" href="{{ route('staff.results.index') }}">Semua Hasil</a></li>
                            <li><a class="dropdown-item" href="{{ route('staff.results.create') }}">Tambah Hasil</a></li>
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
                <i class="fas fa-trophy text-primary"></i> Kelola Hasil Lomba
            </h1>
            <a href="{{ route('staff.results.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Hasil
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
                                <h6 class="card-title">Total Hasil</h6>
                                <h3>{{ $results->total() }}</h3>
                            </div>
                            <i class="fas fa-trophy fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Juara 1</h6>
                                <h3>{{ DB::table('hasil_lomba')->where('posisi', 1)->count() }}</h3>
                            </div>
                            <i class="fas fa-medal fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Juara 2</h6>
                                <h3>{{ DB::table('hasil_lomba')->where('posisi', 2)->count() }}</h3>
                            </div>
                            <i class="fas fa-award fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Juara 3</h6>
                                <h3>{{ DB::table('hasil_lomba')->where('posisi', 3)->count() }}</h3>
                            </div>
                            <i class="fas fa-star fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('staff.results.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari nama peserta, nomor start, atau event..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="event_id" class="form-select">
                                <option value="">Semua Event</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->nama }} ({{ date('d/m/Y', strtotime($event->tanggal)) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Table -->
        <div class="card">
            <div class="card-body">
                @if($results->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover results-table">
                            <thead>
                                <tr>
                                    <th width="80">Posisi</th>
                                    <th>Nama Peserta</th>
                                    <th>Nomor Start</th>
                                    <th>Event</th>
                                    <th>Waktu</th>
                                    <th>Kategori</th>
                                    <th>Catatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                    <tr>
                                        <td>
                                            @php
                                                $positionClass = 'position-other';
                                                if ($result->posisi == 1) $positionClass = 'position-1';
                                                elseif ($result->posisi == 2) $positionClass = 'position-2';
                                                elseif ($result->posisi == 3) $positionClass = 'position-3';
                                            @endphp
                                            <div class="position-badge {{ $positionClass }}">
                                                {{ $result->posisi }}
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $result->nama_lengkap }}</strong>
                                            <div class="text-muted small">{{ $result->user_nama }}</div>
                                        </td>
                                        <td>
                                            @if($result->nomor_start)
                                                <span class="badge bg-info">{{ $result->nomor_start }}</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $result->event_nama }}
                                            <div class="text-muted small">
                                                {{ date('d/m/Y', strtotime($result->created_at)) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="time-badge">
                                                {{ $result->waktu_total }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($result->kategori_umur)
                                                <span class="category-badge">
                                                    {{ $result->kategori_umur }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($result->catatan)
                                                <small class="text-muted">{{ Str::limit($result->catatan, 50) }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
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
                            Menampilkan {{ $results->firstItem() }} - {{ $results->lastItem() }} dari {{ $results->total() }} hasil
                        </div>
                        <div>
                            {{ $results->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada hasil lomba</h5>
                        <p class="text-muted">
                            @if(request('event_id') || request('search'))
                                Coba ubah filter pencarian Anda
                            @else
                                Mulai dengan menambahkan hasil lomba pertama
                            @endif
                        </p>
                        <a href="{{ route('staff.results.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Hasil Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Performers -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-crown text-warning"></i> Top 3 Performers
                        </h5>
                        @php
                            $topPerformers = DB::table('hasil_lomba')
                                ->join('pendaftaran', 'hasil_lomba.pendaftaran_id', '=', 'pendaftaran.id')
                                ->whereIn('posisi', [1, 2, 3])
                                ->orderBy('posisi', 'asc')
                                ->limit(3)
                                ->get();
                        @endphp
                        
                        @if($topPerformers->count() > 0)
                            <div class="list-group">
                                @foreach($topPerformers as $performer)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    @if($performer->posisi == 1)
                                                        <i class="fas fa-crown text-warning me-2"></i>
                                                    @elseif($performer->posisi == 2)
                                                        <i class="fas fa-medal text-secondary me-2"></i>
                                                    @else
                                                        <i class="fas fa-award text-warning me-2"></i>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $performer->nama_lengkap }}</strong>
                                                        <div class="text-muted small">
                                                            Posisi: {{ $performer->posisi }} | Waktu: {{ $performer->waktu_total }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="badge bg-primary">{{ $performer->posisi }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center mb-0">Belum ada data top performers</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-chart-line text-primary"></i> Statistik Hasil
                        </h5>
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center p-3">
                                    <div class="display-6 text-primary">
                                        {{ DB::table('hasil_lomba')->count() }}
                                    </div>
                                    <small class="text-muted">Total Hasil</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3">
                                    <div class="display-6 text-success">
                                        {{ DB::table('hasil_lomba')->distinct('pendaftaran_id')->count() }}
                                    </div>
                                    <small class="text-muted">Peserta</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3">
                                    <div class="display-6 text-warning">
                                        {{ DB::table('hasil_lomba')->distinct('kategori_umur')->count() }}
                                    </div>
                                    <small class="text-muted">Kategori Umur</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3">
                                    <div class="display-6 text-info">
                                        {{ DB::table('hasil_lomba')->distinct('pendaftaran_id')->join('pendaftaran', 'hasil_lomba.pendaftaran_id', '=', 'pendaftaran.id')->count('pendaftaran.id_lomba') }}
                                    </div>
                                    <small class="text-muted">Event</small>
                                </div>
                            </div>
                        </div>
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