<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Event Marathon | Temukan Tantanganmu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #f0f3ff;
            --primary-dark: #3a0ca3;
            --accent: #4cc9f0;
            --text-main: #2b2d42;
            --text-muted: #6c757d;
            --bg-body: #f8f9fa;
            --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 10px 30px rgba(67, 97, 238, 0.08);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
        }
        
        /* Navbar Styling */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .navbar-brand {
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        /* Page Header */
        .page-header {
            background: var(--gradient-primary);
            color: white;
            padding: 5rem 0;
            margin-bottom: -4rem; /* Overlay filter card */
            position: relative;
        }
        
        .header-content {
            position: relative;
            z-index: 1;
        }

        /* Filter Section */
        .filter-card {
            background: white;
            border: none;
            border-radius: 1.25rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            position: relative;
            z-index: 10;
        }
        
        .form-select, .form-control {
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }

        /* Event Card Modernization */
        .event-card {
            background: white;
            border: none;
            border-radius: 1.25rem;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            height: 100%;
            box-shadow: var(--shadow-sm);
        }
        
        .event-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .image-container {
            position: relative;
            overflow: hidden;
        }

        .event-image {
            height: 220px;
            width: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .event-card:hover .event-image {
            transform: scale(1.05);
        }
        
        .event-category {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            color: var(--primary);
            padding: 0.4rem 1rem;
            border-radius: 2rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: var(--shadow-sm);
        }
        
        .event-details-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .detail-item i {
            width: 20px;
            color: var(--primary);
            margin-right: 10px;
        }

        /* Buttons */
        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background: var(--gradient-primary);
            border: none;
        }

        .btn-primary:hover {
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
        }

        /* Pagination Styling */
        .pagination {
            gap: 8px;
        }

        .page-link {
            border: none;
            background: white;
            color: var(--text-main);
            border-radius: 10px !important;
            font-weight: 600;
            box-shadow: var(--shadow-sm);
        }

        .page-item.active .page-link {
            background: var(--gradient-primary);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }

        /* Empty State */
        .empty-state {
            padding: 8rem 0;
            background: white;
            border-radius: 2rem;
            margin-top: 2rem;
        }

        .search-box i {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
        }
        
        .search-box input {
            padding-left: 3rem;
        }

        footer {
            border-top: 1px solid rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-running"></i>
                MARATHON EVENTS
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/events') }}">Event</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                    
                    @if(session('is_logged_in'))
                        <li class="nav-item dropdown ms-2">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-2"></i>
                                <span>{{ session('user_nama') }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 py-2">
                                @if(session('user_peran') == 'admin' || session('user_peran') == 'superadmin')
                                    <li><a class="dropdown-item py-2" href="{{ url('/admin/dashboard') }}"><i class="fas fa-tachometer-alt me-2 text-accent"></i>Dashboard Admin</a></li>
                                @elseif(session('user_peran') == 'staff')
                                    <li><a class="dropdown-item py-2" href="{{ url('/staff/dashboard') }}"><i class="fas fa-tachometer-alt me-2 text-accent"></i>Dashboard Staff</a></li>
                                @else
                                    <li><a class="dropdown-item py-2" href="{{ url('/my-registrations') }}"><i class="fas fa-ticket-alt me-2 text-accent"></i>Pendaftaran Saya</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ url('/profile') }}"><i class="fas fa-user me-2 text-accent"></i>Profil</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ url('/logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-2">
                            <a class="nav-link" href="{{ url('/login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-primary" href="{{ url('/register') }}">
                                <i class="fas fa-user-plus me-1"></i> Daftar
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>


    <div class="page-header">
        <div class="container header-content">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="display-5 fw-bold mb-2">Explore Events</h1>
                    <p class="opacity-75 lead">Temukan berbagai event lari terbaik dari seluruh Indonesia dan tantang dirimu.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="padding-bottom: 100px;">
        <div class="filter-card mb-5">
            <form method="GET" action="{{ route('events') }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="search-box position-relative">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" class="form-control" placeholder="Cari nama marathon atau lokasi..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="kategori" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->kategori }}" {{ request('kategori') == $category->kategori ? 'selected' : '' }}>
                                    {{ $category->kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Status Event</option>
                            <option value="mendatang" {{ request('status') == 'mendatang' ? 'selected' : '' }}>Mendatang</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        @if($events->count() > 0)
            <div class="row g-4">
                @foreach($events as $event)
                    <?php
                        $totalPendaftar = DB::table('pendaftaran')
                            ->where('id_lomba', $event->id)
                            ->where('status_pendaftaran', 'disetujui')
                            ->count();
                        
                        $kuotaTersedia = $event->kuota_peserta - $totalPendaftar;
                        $isFreeEvent = ($event->harga_min == 0);
                    ?>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="event-card">
                            <div class="image-container">
                                <img src="{{ $event->poster_url ? asset('storage/' . $event->poster_url) : 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=600&q=80' }}" 
                                     alt="{{ $event->nama }}" class="event-image">
                                <span class="event-category">{{ $event->kategori }}</span>
                            </div>
                            
                            <div class="p-4">
                                <h5 class="fw-bold mb-3 line-clamp-1">{{ $event->nama }}</h5>
                                
                                <div class="event-details-grid">
                                    <div class="detail-item">
                                        <i class="far fa-calendar-alt"></i>
                                        {{ date('d M Y', strtotime($event->tanggal)) }}
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $event->lokasi }}
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-user-friends"></i>
                                        <span class="{{ $kuotaTersedia > 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                            {{ $kuotaTersedia }} Kuota Tersedia
                                        </span>
                                    </div>
                                </div>

                                <hr class="my-3 opacity-50">

                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <small class="text-muted d-block">Harga Mulai</small>
                                        <span class="h5 fw-bold mb-0 {{ $isFreeEvent ? 'text-success' : 'text-primary' }}">
                                            @if($isFreeEvent)
                                                GRATIS
                                            @else
                                                Rp {{ number_format($event->harga_min, 0, ',', '.') }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('event.detail', $event->id) }}" class="btn btn-sm btn-outline-primary">
                                            Detail
                                        </a>
                                        @if($kuotaTersedia > 0)
                                            <a href="{{ route('event.register.form', $event->id) }}" 
                                               class="btn btn-sm btn-primary">
                                                Daftar
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>Penuh</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $events->links() }} 
                {{-- Catatan: Di atas adalah standard Laravel pagination. 
                     Jika ingin custom seperti manual Anda, saya lampirkan di bawah: --}}
                
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        @if ($events->onFirstPage())
                            <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $events->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a></li>
                        @endif

                        @foreach(range(1, $events->lastPage()) as $i)
                            <li class="page-item {{ $events->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $events->url($i) }}">{{ $i }}</a>
                            </li>
                        @endforeach

                        @if ($events->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $events->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>
                        @endif
                    </ul>
                </nav>
            </div>
        @else
            <div class="empty-state text-center shadow-sm">
                <div class="mb-4">
                    <i class="fas fa-search-minus fa-4x text-light"></i>
                </div>
                <h3 class="fw-bold">Event Tidak Ditemukan</h3>
                <p class="text-muted mb-4">Maaf, kami tidak menemukan event yang sesuai dengan pencarian Anda.</p>
                <a href="{{ route('events') }}" class="btn btn-primary px-4">
                    Lihat Semua Event
                </a>
            </div>
        @endif
    </div>

    <footer class="bg-white py-5 mt-auto">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <h5 class="fw-bold text-primary mb-1">MARATHON EVENTS</h5>
                    <p class="text-muted small mb-0">Platform event lari nomor satu di Indonesia.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <p class="text-muted small mb-0">&copy; 2024 Marathon Events. Membangun pola hidup sehat melalui lari.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Efek navbar saat scroll
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').classList.add('py-2');
            } else {
                document.querySelector('.navbar').classList.remove('py-2');
            }
        });
    </script>
</body>
</html>