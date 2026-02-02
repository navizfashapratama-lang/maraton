
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="display-5 fw-bold mb-3">Semua Event Marathon</h1>
            <p class="lead text-muted">Temukan event lari yang sesuai dengan minat dan kemampuan Anda</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <form action="{{ route('events') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="kategori" class="form-label">Kategori Lomba</label>
                    <select name="kategori" id="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="2K" {{ request('kategori') == '2K' ? 'selected' : '' }}>2K (Family Run)</option>
                        <option value="3K" {{ request('kategori') == '3K' ? 'selected' : '' }}>3K (Fun Run)</option>
                        <option value="5K" {{ request('kategori') == '5K' ? 'selected' : '' }}>5K</option>
                        <option value="10K" {{ request('kategori') == '10K' ? 'selected' : '' }}>10K</option>
                        <option value="21K" {{ request('kategori') == '21K' ? 'selected' : '' }}>Half Marathon (21K)</option>
                        <option value="42K" {{ request('kategori') == '42K' ? 'selected' : '' }}>Full Marathon (42K)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Status Event</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="mendatang" {{ request('status') == 'mendatang' ? 'selected' : '' }}>Mendatang</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <a href="{{ route('events') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="row g-4">
        @forelse($events as $event)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary">{{ $event->kategori }}</span>
                        <span class="badge bg-{{ $event->status == 'mendatang' ? 'success' : ($event->status == 'selesai' ? 'secondary' : 'danger') }}">
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
                        <small class="text-muted d-block">Harga Tiket</small>
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="text-muted">Standar:</span>
                                <h6 class="fw-bold mb-0">Rp {{ number_format($event->harga_standar, 0, ',', '.') }}</h6>
                            </div>
                            <div class="text-end">
                                <span class="text-muted">Premium:</span>
                                <h6 class="fw-bold mb-0">Rp {{ number_format($event->harga_premium, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-info-circle me-2"></i>Detail Event
                        </a>
                        @if($event->status == 'mendatang')
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                        </a>
                        @elseif($event->status == 'selesai')
                        <button class="btn btn-secondary btn-sm" disabled>
                            <i class="fas fa-check-circle me-2"></i>Event Selesai
                        </button>
                        @else
                        <button class="btn btn-danger btn-sm" disabled>
                            <i class="fas fa-times-circle me-2"></i>Dibatalkan
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Tidak ada event yang ditemukan dengan filter tersebut.
            </div>
            <a href="{{ route('events') }}" class="btn btn-primary mt-3">
                <i class="fas fa-redo me-2"></i>Tampilkan Semua Event
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($events->hasPages())
    <div class="mt-5">
        {{ $events->links() }}
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
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
        font-weight: 500;
    }
    
    .pagination .page-link {
        color: #4361ee;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #4361ee;
        border-color: #4361ee;
    }
</style>