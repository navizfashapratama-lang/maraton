<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->nama }} - Marathon Events</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #eef2ff;
            --primary-dark: #3a0ca3;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --light: #f8fafc;
            --dark: #1e293b;
        }
        
        body {
            background-color: var(--light);
            font-family: 'Poppins', sans-serif;
        }
        
        .event-header {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('{{ $event->poster_url ? asset("storage/" . $event->poster_url) : "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80" }}');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 5rem 0;
            position: relative;
        }
        
        .event-content {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-top: -3rem;
            position: relative;
        }
        
        .package-card {
            border: 2px solid #e5e7eb;
            border-radius: 1rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .package-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .package-card.popular {
            border-color: var(--primary);
            background: var(--primary-light);
            position: relative;
            overflow: hidden;
        }
        
        .popular-badge {
            position: absolute;
            top: 10px;
            right: -30px;
            background: var(--primary);
            color: white;
            padding: 0.25rem 2rem;
            transform: rotate(45deg);
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
        }
        
        .feature-list li {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .feature-list i {
            color: var(--primary);
            margin-right: 0.5rem;
        }
        
        .stat-badge {
            background: var(--primary-light);
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            display: inline-block;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fas fa-running me-2"></i>
                <span style="color: #4361ee;">MARATHON</span>EVENTS
            </a>
            <a href="{{ url('/events') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </nav>

    <!-- Event Header -->
    <div class="event-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <span class="stat-badge mb-3">
                        <i class="fas fa-tag me-2"></i>{{ $event->kategori ?? 'Marathon' }}
                    </span>
                    <h1 class="display-4 fw-bold mb-3">{{ $event->nama }}</h1>
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar me-2"></i>
                            <span>{{ date('d F Y', strtotime($event->tanggal)) }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span>{{ $event->lokasi ?? 'Lokasi akan diumumkan' }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users me-2"></i>
                            <span>{{ $kuotaTersedia ?? 0 }} kuota tersedia</span>
                        </div>
                    </div>
                    
                    @if($event->status !== 'mendatang')
                        <div class="alert alert-warning d-inline-block">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Event ini sudah {{ $event->status }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row">
            <!-- Event Details -->
            <div class="col-lg-8">
                <div class="event-content p-4 mb-4">
                    <h3 class="fw-bold mb-4">Tentang Event</h3>
                    <div class="mb-4">
                        {!! nl2br(e($event->deskripsi ?? 'Deskripsi event akan segera diupdate.')) !!}
                    </div>
                    
                    <!-- Event Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Informasi Event</h5>
                            <ul class="feature-list">
                                <li><i class="fas fa-flag-checkered"></i> <strong>Jarak:</strong> {{ $event->jarak ?? '5K/10K/21K/42K' }}</li>
                                <li><i class="fas fa-clock"></i> <strong>Waktu Start:</strong> {{ $event->waktu_start ?? '06:00 WIB' }}</li>
                                <li><i class="fas fa-medal"></i> <strong>Hadiah:</strong> {{ $event->hadiah ?? 'Medali & Sertifikat' }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3"><i class="fas fa-map-signs me-2 text-primary"></i>Rute & Fasilitas</h5>
                            <ul class="feature-list">
                                <li><i class="fas fa-road"></i> Rute aman dan terukur</li>
                                <li><i class="fas fa-tint"></i> Hydration station setiap 2.5K</li>
                                <li><i class="fas fa-ambulance"></i> Tim medis & ambulance standby</li>
                                <li><i class="fas fa-tshirt"></i> Kaos event & race bib</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Requirements -->
                    @if($event->syarat_ketentuan)
                    <div class="alert alert-info">
                        <h5 class="fw-bold mb-2"><i class="fas fa-exclamation-circle me-2"></i>Syarat & Ketentuan</h5>
                        {!! nl2br(e($event->syarat_ketentuan)) !!}
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Packages & Registration -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 20px;">
                    <div class="event-content p-4 mb-4">
                        <h3 class="fw-bold mb-4">Pilihan Paket</h3>
                        
                        @if(count($packages) > 0)
                            <div class="row g-3">
                                @foreach($packages as $package)
                                    <div class="col-12">
                                        <div class="package-card {{ $loop->first ? 'popular' : '' }}">
                                            @if($loop->first)
                                                <div class="popular-badge">POPULAR</div>
                                            @endif
                                            <h5 class="fw-bold mb-2">{{ $package->nama }}</h5>
                                            <div class="mb-3">
                                                <span class="h3 fw-bold text-primary">Rp {{ number_format($package->harga, 0, ',', '.') }}</span>
                                                @if($package->harga == 0)
                                                    <span class="badge bg-success ms-2">GRATIS</span>
                                                @endif
                                            </div>
                                            
                                            <ul class="feature-list mb-4">
                                                @if($package->termasuk_kaos)
                                                    <li><i class="fas fa-check text-success"></i> Kaos Event</li>
                                                @endif
                                                @if($package->termasuk_medali)
                                                    <li><i class="fas fa-check text-success"></i> Medali Finisher</li>
                                                @endif
                                                @if($package->termasuk_race_kit)
                                                    <li><i class="fas fa-check text-success"></i> Race Kit</li>
                                                @endif
                                                @if($package->termasuk_sertifikat)
                                                    <li><i class="fas fa-check text-success"></i> Sertifikat Digital</li>
                                                @endif
                                                @if($package->termasuk_snack)
                                                    <li><i class="fas fa-check text-success"></i> Snack & Minuman</li>
                                                @endif
                                            </ul>
                                            
                                            @if($event->status === 'mendatang' && $kuotaTersedia > 0)
                                                @if(!$alreadyRegistered)
                                                    <a href="{{ url('/event/' . $event->id . '/register') }}" class="btn btn-primary w-100">
                                                        @if($package->harga == 0)
                                                            <i class="fas fa-user-plus me-2"></i>Daftar Gratis
                                                        @else
                                                            <i class="fas fa-running me-2"></i>Daftar Sekarang
                                                        @endif
                                                    </a>
                                                @else
                                                    <button class="btn btn-success w-100" disabled>
                                                        <i class="fas fa-check-circle me-2"></i>Sudah Terdaftar
                                                    </button>
                                                @endif
                                            @elseif($kuotaTersedia <= 0)
                                                <button class="btn btn-danger w-100" disabled>
                                                    <i class="fas fa-times-circle me-2"></i>Kuota Habis
                                                </button>
                                            @else
                                                <button class="btn btn-secondary w-100" disabled>
                                                    <i class="fas fa-calendar-times me-2"></i>Pendaftaran Ditutup
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Belum ada paket tersedia untuk event ini.
                            </div>
                        @endif
                        
                        <!-- Event Stats -->
                        <div class="mt-4 pt-4 border-top">
                            <h5 class="fw-bold mb-3"><i class="fas fa-chart-bar me-2 text-primary"></i>Statistik Event</h5>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="h4 fw-bold text-primary">{{ $event->kuota_peserta ?? 0 }}</div>
                                    <small class="text-muted">Kuota</small>
                                </div>
                                <div class="col-4">
                                    <div class="h4 fw-bold text-primary">{{ $event->kuota_peserta - $kuotaTersedia }}</div>
                                    <small class="text-muted">Terdaftar</small>
                                </div>
                                <div class="col-4">
                                    <div class="h4 fw-bold text-primary">{{ $kuotaTersedia ?? 0 }}</div>
                                    <small class="text-muted">Tersedia</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Important Info -->
                    <div class="alert alert-info">
                        <h6 class="fw-bold mb-2"><i class="fas fa-lightbulb me-2"></i>Informasi Penting</h6>
                        <ul class="mb-0 small">
                            <li>Pendaftaran bisa dibatalkan maksimal 7 hari sebelum event</li>
                            <li>Pembayaran harus dilakukan dalam 24 jam setelah pendaftaran</li>
                            <li>Pastikan data yang diisi sesuai dengan KTP</li>
                            <li>Bawa identitas asli saat race kit collection</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="text-center">
                <p class="mb-0">
                    &copy; 2024 <span class="fw-bold">Marathon Events</span>. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Smooth scroll untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const target = document.querySelector(targetId);
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Alert untuk session messages
        @if(session('success'))
            alert('{{ session("success") }}');
        @endif
        
        @if(session('error'))
            alert('{{ session("error") }}');
        @endif
    </script>
</body>
</html>