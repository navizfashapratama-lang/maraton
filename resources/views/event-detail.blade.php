@extends('layouts.app')

@section('title', 'Detail Event - Marathon Runner')

@section('content')
<div class="container py-5">
    @php
        use Illuminate\Support\Facades\DB;
        use Carbon\Carbon;
    @endphp
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('events') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Event
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4 overflow-hidden" style="border-radius: 20px;">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-primary px-3 py-2 rounded-pill me-3">{{ $event->kategori }}</span>
                        <span class="badge px-3 py-2 rounded-pill bg-{{ $event->status == 'mendatang' ? 'success' : ($event->status == 'selesai' ? 'secondary' : 'danger') }}">
                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i> {{ ucfirst($event->status) }}
                        </span>
                    </div>
                    <h1 class="display-5 fw-bold mb-3 text-dark">{{ $event->nama }}</h1>
                    
                    <div class="d-flex flex-wrap gap-4 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="icon-box me-3 bg-light text-primary rounded-3 p-2">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Tanggal Event</small>
                                <strong class="text-dark">{{ Carbon::parse($event->tanggal)->translatedFormat('d F Y') }}</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="icon-box me-3 bg-light text-primary rounded-3 p-2">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Lokasi</small>
                                <strong class="text-dark">{{ $event->lokasi ?? 'Akan diumumkan' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 text-md-end mt-4 mt-md-0">
                    <div class="d-grid">
                        @if($event->status == 'mendatang')
                            @if(session('is_logged_in'))
                                @php
                                    $pendaftaran = DB::table('pendaftaran')
                                        ->where('id_pengguna', session('user_id'))
                                        ->where('id_lomba', $event->id)
                                        ->first();
                                    
                                    // Cek apakah sudah ada pembayaran
                                    $payment = null;
                                    if ($pendaftaran) {
                                        $payment = DB::table('pembayaran')
                                            ->where('id_pendaftaran', $pendaftaran->id)
                                            ->first();
                                    }
                                @endphp
                                
                                @if($pendaftaran)
                                    @if($pendaftaran->status_pembayaran == 'lunas')
                                        <button class="btn btn-success btn-lg rounded-3 shadow-sm" disabled>
                                            <i class="fas fa-check-double me-2"></i>Tiket Sudah Terbeli
                                        </button>
                                        <small class="text-muted mt-2 d-block text-center">Cek notifikasi untuk bukti ambil tiket.</small>
                                    @else
                                        @if($payment)
                                            @if($payment->status == 'menunggu')
                                                <a href="{{ route('payment.instructions', $pendaftaran->id) }}" class="btn btn-info btn-lg rounded-3 shadow-sm">
                                                    <i class="fas fa-clock me-2"></i>Menunggu Verifikasi
                                                </a>
                                                <small class="text-muted mt-2 d-block text-center">Bukti pembayaran sedang diverifikasi</small>
                                            @elseif($payment->status == 'ditolak')
                                                <a href="{{ route('payment.instructions', $pendaftaran->id) }}" class="btn btn-danger btn-lg rounded-3 shadow-sm">
                                                    <i class="fas fa-times me-2"></i>Pembayaran Ditolak
                                                </a>
                                                <small class="text-muted mt-2 d-block text-center">Upload ulang bukti pembayaran</small>
                                            @endif
                                        @else
                                            <a href="{{ route('payment.instructions', $pendaftaran->id) }}" class="btn btn-warning btn-lg rounded-3 shadow-sm">
                                                <i class="fas fa-wallet me-2"></i>Lakukan Pembayaran
                                            </a>
                                            <small class="text-muted mt-2 d-block text-center">Bayar dalam 2x24 jam</small>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('event.register', $event->id) }}" class="btn btn-primary btn-lg rounded-3 shadow-sm py-3 px-5">
                                        <i class="fas fa-user-edit me-2"></i>Daftar Sekarang
                                    </a>
                                    <small class="text-muted mt-2 d-block text-center">
                                        {{ $registered ?? 0 }} dari {{ $event->kuota_peserta ?? 100 }} peserta
                                    </small>
                                @endif
                            @else
                                <a href="{{ route('login', ['redirect' => 'event-detail', 'id' => $event->id]) }}" class="btn btn-primary btn-lg rounded-3 shadow-sm py-3">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login untuk Mendaftar
                                </a>
                            @endif
                        @else
                            <button class="btn btn-secondary btn-lg rounded-3" disabled>
                                <i class="fas fa-calendar-times me-2"></i>Pendaftaran Ditutup
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-info-circle me-2"></i>Tentang Event</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <p class="lead text-dark">{{ $event->deskripsi ?? 'Belum ada deskripsi untuk event ini.' }}</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3"><i class="fas fa-star text-warning me-2"></i>Fasilitas Peserta:</h6>
                            <ul class="list-unstyled custom-list">
                                <li><i class="fas fa-check text-success"></i> Kaos Lari Eksklusif</li>
                                <li><i class="fas fa-check text-success"></i> Medali Finisher (Semua kategori)</li>
                                <li><i class="fas fa-check text-success"></i> BIB Number (Nomor Start)</li>
                                <li><i class="fas fa-check text-success"></i> Refreshment & Snack</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3"><i class="fas fa-clock text-danger me-2"></i>Timeline:</h6>
                            <ul class="list-unstyled custom-list">
                                <li><strong>05:30</strong> - Kumpul di Start Line</li>
                                <li><strong>06:00</strong> - Flag Off (Start)</li>
                                <li><strong>09:00</strong> - Awarding Ceremony</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mb-3 px-2">Pilih Paket Pendaftaran</h5>
            <div class="row g-4">
                @foreach($packages as $package)
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm hover-card {{ $package->nama == 'Premium' ? 'border-start border-primary border-4' : '' }}" style="border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0">{{ $package->nama }}</h5>
                                @if($package->nama == 'Premium')
                                    <span class="badge bg-primary-light text-primary">Best Value</span>
                                @endif
                            </div>
                            <h3 class="text-primary fw-bold mb-4">Rp {{ number_format($package->harga, 0, ',', '.') }}</h3>
                            
                            <ul class="list-unstyled mb-4 text-muted">
                                <li class="mb-2"><i class="fas fa-check-circle {{ $package->termasuk_race_kit ? 'text-success' : 'text-light' }} me-2"></i> Race Kit</li>
                                <li class="mb-2"><i class="fas fa-check-circle {{ $package->termasuk_kaos ? 'text-success' : 'text-light' }} me-2"></i> Kaos Lari</li>
                                <li class="mb-2"><i class="fas fa-check-circle {{ $package->termasuk_medali ? 'text-success' : 'text-light' }} me-2"></i> Medali Finisher</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i> E-Certificate</li>
                            </ul>

                            @if($event->status == 'mendatang' && !isset($alreadyRegistered))
                                <a href="{{ route('event.register', ['id' => $event->id, 'paket' => $package->id]) }}" class="btn btn-outline-primary w-100 rounded-pill fw-bold">Pilih {{ $package->nama }}</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Kuota Pendaftaran</h6>
                    @php
                        $registered = DB::table('pendaftaran')
                            ->where('id_lomba', $event->id)
                            ->where('status_pendaftaran', 'disetujui')
                            ->count();
                        $quota = $event->kuota_peserta ?? 100;
                        $percent = $quota > 0 ? ($registered / $quota) * 100 : 0;
                    @endphp
                    
                    <div class="progress mb-2" style="height: 12px; border-radius: 10px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $percent > 80 ? 'danger' : 'primary' }}" 
                             style="width: {{ $percent }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">{{ $registered }} Terdaftar</small>
                        <small class="fw-bold">{{ max(0, $quota - $registered) }} Slot Tersisa</small>
                    </div>

                    @if($percent >= 90)
                        <div class="alert alert-warning py-2 px-3 mt-3 mb-0 small border-0">
                            <i class="fas fa-fire me-2"></i>Hampir habis! Segera daftar.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h6 class="fw-bold mb-0">Event Mendatang Lainnya</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    @foreach($upcoming_events as $other)
                    <a href="{{ route('event.detail', $other->id) }}" class="text-decoration-none group-item d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-3 p-2 me-3 text-center" style="min-width: 50px;">
                            <span class="d-block fw-bold small">{{ Carbon::parse($other->tanggal)->format('d') }}</span>
                            <span class="d-block small" style="font-size: 10px;">{{ Carbon::parse($other->tanggal)->format('M') }}</span>
                        </div>
                        <div>
                            <h6 class="mb-0 text-dark fw-bold small">{{ $other->nama }}</h6>
                            <small class="text-muted">{{ $other->lokasi }}</small>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .bg-primary-light { background-color: #eef2ff; }
    .custom-list li { margin-bottom: 10px; font-size: 0.95rem; }
    .icon-box { font-size: 1.2rem; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }
    .hover-card { transition: all 0.3s ease; }
    .hover-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; }
    .group-item:hover h6 { color: #4361ee !important; }
    .progress-bar { transition: width 1s ease-in-out; }
</style>
@endsection