<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->nama }} - Marathon Runner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .event-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }
        .package-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .package-card:hover {
            border-color: #0d6efd;
            transform: translateY(-5px);
        }
        .feature-list {
            list-style: none;
            padding-left: 0;
        }
        .feature-list li {
            padding: 5px 0;
        }
        .feature-list li i {
            color: #28a745;
            margin-right: 10px;
        }
        .event-date-badge {
            background: rgba(255,255,255,0.2);
            border: 2px solid white;
            border-radius: 10px;
            padding: 10px 15px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">Marathon Runner</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('events') }}">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                    @if(session('is_logged_in'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile') }}">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alerts -->
    <div class="container mt-3">
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
        
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Hero Section -->
    <div class="event-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <span class="badge bg-warning mb-3">
                        {{ strtoupper($event->kategori) }}
                    </span>
                    <h1 class="display-5 fw-bold mb-3">{{ $event->nama }}</h1>
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <div class="event-date-badge">
                            <div class="fs-6">TANGGAL</div>
                            <div class="fs-4 fw-bold">{{ date('d', strtotime($event->tanggal)) }}</div>
                            <div class="fs-6">{{ strtoupper(date('M Y', strtotime($event->tanggal))) }}</div>
                        </div>
                        <div>
                            <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i> {{ $event->lokasi }}</p>
                            <p class="mb-0">
                                <span class="badge bg-{{ $event->status == 'mendatang' ? 'success' : ($event->status == 'selesai' ? 'secondary' : 'warning') }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    @if($event->status == 'mendatang' && $event->pendaftaran_dibuka)
                        @if(session('is_logged_in'))
                            <a href="{{ route('event.register', $event->id) }}" class="btn btn-light btn-lg">
                                <i class="fas fa-running me-2"></i> DAFTAR SEKARANG
                            </a>
                        @else
                            <a href="{{ route('login', ['redirect' => 'event-register']) }}" class="btn btn-light btn-lg">
                                <i class="fas fa-running me-2"></i> LOGIN UNTUK DAFTAR
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <!-- Event Details -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title"><i class="fas fa-info-circle me-2 text-primary"></i>Tentang Event</h4>
                        <p class="card-text">{{ $event->deskripsi }}</p>
                        
                        @if($event->keterangan)
                            <div class="mt-3">
                                <h5><i class="fas fa-exclamation-circle me-2 text-primary"></i>Informasi Penting</h5>
                                <p>{{ $event->keterangan }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Event Stats -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-3"><i class="fas fa-chart-bar me-2 text-primary"></i>Statistik Event</h4>
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <div class="display-6 fw-bold text-primary">{{ $event->peserta_terdaftar }}</div>
                                <small>Peserta Terdaftar</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="display-6 fw-bold text-success">{{ $paket->count() }}</div>
                                <small>Paket Tersedia</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="display-6 fw-bold text-info">
                                    @if($event->kuota_peserta)
                                        {{ $event->kuota_peserta }}
                                    @else
                                        ∞
                                    @endif
                                </div>
                                <small>Kuota Peserta</small>
                            </div>
                        </div>
                        
                        @if($event->kuota_peserta && $event->kuota_peserta > 0)
                            @php
                                $percentage = min(100, ($event->peserta_terdaftar / $event->kuota_peserta) * 100);
                            @endphp
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ $percentage }}%" 
                                     aria-valuenow="{{ $percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted">
                                {{ number_format($percentage, 1) }}% kuota terisi
                            </small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Packages Sidebar -->
            <div class="col-lg-4">
                <!-- Packages Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-box me-2"></i>Paket Pendaftaran</h5>
                    </div>
                    <div class="card-body">
                        @if($paket->count() > 0)
                            <div class="row g-3">
                                @foreach($paket as $package)
                                    <div class="col-12">
                                        <div class="package-card p-3">
                                            <h6 class="fw-bold">{{ $package->nama }}</h6>
                                            <h4 class="text-primary fw-bold mb-3">
                                                Rp {{ number_format($package->harga, 0, ',', '.') }}
                                            </h4>
                                            
                                            <ul class="feature-list mb-3">
                                                @if($package->termasuk_race_kit)
                                                    <li><i class="fas fa-check text-success"></i> Race Kit</li>
                                                @endif
                                                @if($package->termasuk_kaos)
                                                    <li><i class="fas fa-check text-success"></i> Kaos Lari</li>
                                                @endif
                                                @if($package->termasuk_medali)
                                                    <li><i class="fas fa-check text-success"></i> Medali Finisher</li>
                                                @endif
                                                @if($package->termasuk_sertifikat ?? true)
                                                    <li><i class="fas fa-check text-success"></i> Sertifikat Digital</li>
                                                @endif
                                                @if($package->termasuk_snack ?? true)
                                                    <li><i class="fas fa-check text-success"></i> Snack & Minuman</li>
                                                @endif
                                            </ul>
                                            
                                            @if($event->status == 'mendatang' && $event->pendaftaran_dibuka)
                                                @if(session('is_logged_in'))
                                                    <a href="{{ route('event.register', ['id' => $event->id]) }}?paket={{ $package->id }}" 
                                                       class="btn btn-primary w-100">
                                                        Pilih Paket Ini
                                                    </a>
                                                @else
                                                    <a href="{{ route('login', ['redirect' => 'event-register', 'paket' => $package->id]) }}" 
                                                       class="btn btn-outline-primary w-100">
                                                        Login untuk Daftar
                                                    </a>
                                                @endif
                                            @else
                                                <button class="btn btn-secondary w-100" disabled>
                                                    {{ $event->status == 'selesai' ? 'Event Selesai' : 'Pendaftaran Ditutup' }}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada paket tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Event Info -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Info Event</h6>
                        
                        <p><strong><i class="fas fa-calendar me-2 text-primary"></i>Tanggal:</strong><br>
                        {{ $event->tanggal_formatted }}</p>
                        
                        <p><strong><i class="fas fa-map-marker-alt me-2 text-primary"></i>Lokasi:</strong><br>
                        {{ $event->lokasi }}</p>
                        
                        <p><strong><i class="fas fa-tag me-2 text-primary"></i>Kategori:</strong><br>
                        {{ $event->kategori }}</p>
                        
                        <p><strong><i class="fas fa-flag me-2 text-primary"></i>Status:</strong><br>
                        <span class="badge bg-{{ $event->status == 'mendatang' ? 'success' : ($event->status == 'selesai' ? 'secondary' : 'warning') }}">
                            {{ ucfirst($event->status) }}
                        </span></p>
                        
                        @if($event->pendaftaran_dibuka)
                            <div class="alert alert-success mt-3">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Pendaftaran Masih Dibuka!</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        @if($upcoming_events->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="mb-4"><i class="fas fa-calendar-alt me-2 text-primary"></i>Event Lainnya</h3>
                </div>
                @foreach($upcoming_events as $up_event)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <span class="badge bg-primary mb-2">{{ strtoupper($up_event->kategori) }}</span>
                                <h5 class="card-title">{{ $up_event->nama }}</h5>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i> 
                                        {{ date('d M Y', strtotime($up_event->tanggal)) }}
                                    </small>
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-map-marker-alt me-1"></i> {{ $up_event->lokasi }}
                                </p>
                                <p class="card-text">
                                    <strong>Rp {{ number_format($up_event->harga_min, 0, ',', '.') }}</strong>
                                </p>
                                <a href="{{ route('event.detail', $up_event->id) }}" class="btn btn-outline-primary">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Marathon Runner</h5>
                    <p>Platform pendaftaran event lari terpercaya di Indonesia.</p>
                </div>
                <div class="col-md-4">
                    <h5>Menu</h5>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-white-50 text-decoration-none">Home</a></li>
                        <li><a href="{{ route('events') }}" class="text-white-50 text-decoration-none">Events</a></li>
                        <li><a href="{{ route('contact') }}" class="text-white-50 text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Follow Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <small>&copy; 2024 Marathon Runner. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>