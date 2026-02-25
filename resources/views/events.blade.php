@extends('layouts.app')

@section('title', 'Semua Event Marathon - Marathon Runner')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="display-5 fw-bold mb-3">Semua Event Marathon</h1>
            <p class="lead text-muted">Temukan event lari yang sesuai dengan minat dan kemampuan Anda</p>
        </div>
    </div>

    <div class="card shadow-sm mb-5 border-0">
        <div class="card-body p-4 bg-light rounded">
            <form action="{{ route('events') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label fw-bold small">Cari Nama Event</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" id="search" class="form-control border-start-0" placeholder="Cari event..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="kategori" class="form-label fw-bold small">Kategori Lomba</label>
                    <select name="kategori" id="kategori" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->kategori }}" {{ request('kategori') == $category->kategori ? 'selected' : '' }}>
                                {{ $category->kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label fw-bold small">Status Event</label>
                    <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="mendatang" {{ request('status') == 'mendatang' ? 'selected' : '' }}>Mendatang</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route('events') }}" class="btn btn-outline-secondary"><i class="fas fa-sync-alt"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse($events as $event)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0 event-card">
                <img src="{{ $event->poster_url ? asset('storage/' . $event->poster_url) : 'https://images.unsplash.com/photo-1530549387631-fbb129c1b027?q=80&w=800&auto=format&fit=crop' }}" 
                     class="card-img-top" alt="{{ $event->nama }}" style="height: 200px; object-fit: cover;">
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary">{{ $event->kategori }}</span>
                        <span class="badge bg-{{ $event->status == 'mendatang' ? 'success' : ($event->status == 'selesai' ? 'secondary' : 'danger') }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>
                    <h5 class="card-title fw-bold">{{ $event->nama }}</h5>
                    <p class="card-text text-muted mb-2">
                        <i class="fas fa-map-marker-alt me-2 text-danger"></i>{{ $event->lokasi ?? 'Lokasi Belum Ditentukan' }}
                    </p>
                    <p class="card-text mb-3">
                        <i class="fas fa-calendar-alt me-2 text-primary"></i>
                        {{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d F Y') }}
                    </p>
                    
                    @if($event->has_packages)
                    <div class="bg-light p-3 rounded mb-3">
                        <small class="text-muted d-block">Harga Tiket</small>
                        <div class="d-flex justify-content-between align-items-center">
                            @if($event->harga_standar > 0)
                            <div>
                                <span class="text-muted small">Standar</span>
                                <h6 class="fw-bold mb-0">Rp {{ number_format($event->harga_standar, 0, ',', '.') }}</h6>
                            </div>
                            @endif
                            
                            @if($event->harga_premium > 0)
                            <div class="text-end">
                                <span class="text-muted small">Premium</span>
                                <h6 class="fw-bold mb-0 text-primary">Rp {{ number_format($event->harga_premium, 0, ',', '.') }}</h6>
                            </div>
                            @endif

                            @if($event->harga_standar == 0 && $event->harga_premium == 0)
                            <div>
                                <span class="text-muted small">Mulai dari</span>
                                <h6 class="fw-bold mb-0 text-success">Rp {{ number_format($event->harga_min, 0, ',', '.') }}</h6>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-footer bg-white border-top-0 p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('event.detail', $event->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-info-circle me-2"></i>Detail Event
                        </a>
                        
                        @if($event->status == 'mendatang')
                        <a href="{{ route('event.register.form', $event->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                        </a>
                        @else
                        <button class="btn btn-secondary btn-sm" disabled>
                            <i class="fas fa-times-circle me-2"></i>{{ $event->status == 'selesai' ? 'Event Selesai' : 'Dibatalkan' }}
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="alert alert-info border-0 shadow-sm">
                <i class="fas fa-info-circle me-2"></i>
                Tidak ada event yang ditemukan dengan filter tersebut.
            </div>
            <a href="{{ route('events') }}" class="btn btn-primary mt-3">
                <i class="fas fa-redo me-2"></i>Tampilkan Semua Event
            </a>
        </div>
        @endforelse
    </div>

    @if($events->hasPages())
    <div class="mt-5 d-flex justify-content-center">
        {{ $events->links() }}
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .event-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.5em 0.8em;
    }
</style>
@endsection