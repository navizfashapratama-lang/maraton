@extends('layouts.app')

@section('title', 'Lari Bersama, Sehat Bersama - Marathon Events')

@section('content')
<div class="container-fluid p-0">
    <!-- Hero Section -->
    <section class="hero-section bg-gradient-primary text-white py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-3">Lari Bersama, <span class="text-warning">Sehat Bersama</span></h1>
                    <p class="lead mb-4">
                        Bergabunglah dengan ribuan pelari dalam event marathon terbaik di Indonesia. 
                        Dari fun run hingga marathon profesional, ada untuk semua level!
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('events') }}" class="btn btn-warning btn-lg px-4 py-3">
                            <i class="fas fa-calendar-alt me-2"></i> Lihat Event
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4 py-3">
                            <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://images.unsplash.com/photo-1552674605-db6ffd8facb5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="Marathon Runners" class="img-fluid rounded-3 shadow-lg" style="max-height: 400px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">Tentang <span class="text-primary">Marathon Events</span></h2>
                    <p class="lead mb-4">
                        Kami adalah platform event lari terpercaya di Indonesia, 
                        menyelenggarakan berbagai jenis lomba lari untuk semua kalangan.
                    </p>
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="fas fa-trophy text-white fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Professional</h5>
                                    <p class="text-muted mb-0">Event dengan standar internasional</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-success rounded-circle p-2 me-3">
                                    <i class="fas fa-users text-white fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Community</h5>
                                    <p class="text-muted mb-0">Komunitas pelari yang solid</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-warning rounded-circle p-2 me-3">
                                    <i class="fas fa-shield-alt text-white fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Safety First</h5>
                                    <p class="text-muted mb-0">Keamanan peserta prioritas utama</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-info rounded-circle p-2 me-3">
                                    <i class="fas fa-certificate text-white fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Sertifikat</h5>
                                    <p class="text-muted mb-0">Sertifikat untuk semua finisher</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://images.unsplash.com/photo-1517344884509-a0c97ec11bcc?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                         alt="About Marathon" class="img-fluid rounded-3 shadow" style="max-height: 400px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section id="events" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Event <span class="text-primary">Terdekat</span></h2>
                <p class="lead text-muted">Pilih event yang sesuai dengan kemampuan dan minat Anda</p>
            </div>
            
            <div class="row g-4">
                @forelse($events as $event)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-primary">{{ $event->kategori }}</span>
                                <span class="badge bg-{{ $event->status == 'mendatang' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </div>
                            <h5 class="card-title fw-bold">{{ $event->nama }}</h5>
                            <p class="card-text text-muted mb-2">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $event->lokasi }}
                            </p>
                            <p class="card-text mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>
                                {{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d F Y') }}
                            </p>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">Standar</small>
                                        <h6 class="fw-bold mb-0">Rp {{ number_format($event->harga_standar, 0, ',', '.') }}</h6>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">Premium</small>
                                        <h6 class="fw-bold mb-0">Rp {{ number_format($event->harga_premium, 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 pt-0">
                            <div class="d-grid gap-2">
                                <a href="#" class="btn btn-outline-primary btn-sm">Detail Event</a>
                                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar Sekarang</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Tidak ada event mendatang saat ini. Silakan cek kembali nanti.
                    </div>
                </div>
                @endforelse
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ route('events') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-list me-2"></i>Lihat Semua Event
                </a>
            </div>
        </div>
    </section>
            </div>
        </div>
    </section>
</div>
@endsection

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
</style>
@endsection