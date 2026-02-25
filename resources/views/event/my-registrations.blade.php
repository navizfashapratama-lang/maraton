<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Saya - Marathon Events</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #eef2ff;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }
        
        body {
            background-color: #f8fafc;
            font-family: 'Poppins', sans-serif;
        }
        
        .header-section {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        
        .registration-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
        }
        
        .registration-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .status-menunggu {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
        }
        
        .status-disetujui {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
        
        .status-ditolak {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }
        
        .status-lunas {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #3b82f6;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            border: none;
            border-radius: 0.75rem;
            font-weight: 600;
        }
        
        .empty-state {
            background: white;
            border-radius: 1rem;
            padding: 4rem 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
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
            <div class="navbar-nav">
                <a href="{{ url('/') }}" class="nav-link">
                    <i class="fas fa-home me-1"></i> Beranda
                </a>
                <a href="{{ url('/events') }}" class="nav-link">
                    <i class="fas fa-calendar-alt me-1"></i> Event
                </a>
                <a href="{{ url('/profile') }}" class="nav-link">
                    <i class="fas fa-user me-1"></i> Profil
                </a>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-6 fw-bold mb-3">
                        <i class="fas fa-ticket-alt me-3"></i>
                        Pendaftaran Saya
                    </h1>
                    <p class="mb-0">
                        Lihat status dan riwayat pendaftaran event marathon Anda
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ url('/events') }}" class="btn btn-light">
                        <i class="fas fa-plus me-2"></i>Daftar Event Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Stats Summary -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="h3 fw-bold text-primary">
                            {{ $registrations->count() }}
                        </div>
                        <div class="text-muted">Total Pendaftaran</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="h3 fw-bold text-success">
                            {{ $registrations->where('status_pendaftaran', 'disetujui')->count() }}
                        </div>
                        <div class="text-muted">Disetujui</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="h3 fw-bold text-warning">
                            {{ $registrations->where('status_pendaftaran', 'menunggu')->count() }}
                        </div>
                        <div class="text-muted">Menunggu</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="h3 fw-bold text-info">
                            {{ $registrations->where('status_pembayaran', 'lunas')->count() }}
                        </div>
                        <div class="text-muted">Lunas</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registrations List -->
        <div class="row">
            <div class="col-12">
                @if($registrations->count() > 0)
                    @foreach($registrations as $registration)
                        <div class="registration-card">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="p-3">
                                        <div class="fw-bold text-primary">{{ $registration->event_nama }}</div>
                                        <div class="text-muted small">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ date('d F Y', strtotime($registration->tanggal)) }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $registration->lokasi ?? 'Online' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="p-3">
                                        <div class="small text-muted">Kode Pendaftaran</div>
                                        <div class="fw-bold text-dark">{{ $registration->kode_pendaftaran }}</div>
                                        <div class="small text-muted mt-2">Paket</div>
                                        <div class="fw-bold">{{ $registration->paket_nama ?? 'Standard' }}</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="p-3">
                                        <div class="mb-2">
                                            <span class="small text-muted">Status Pendaftaran:</span><br>
                                            <span class="status-badge status-{{ $registration->status_pendaftaran }}">
                                                @if($registration->status_pendaftaran == 'menunggu')
                                                    <i class="fas fa-clock me-1"></i>Menunggu
                                                @elseif($registration->status_pendaftaran == 'disetujui')
                                                    <i class="fas fa-check-circle me-1"></i>Disetujui
                                                @elseif($registration->status_pendaftaran == 'ditolak')
                                                    <i class="fas fa-times-circle me-1"></i>Ditolak
                                                @else
                                                    {{ $registration->status_pendaftaran }}
                                                @endif
                                            </span>
                                        </div>
                                        <div>
                                            <span class="small text-muted">Status Pembayaran:</span><br>
                                            <span class="status-badge status-{{ $registration->status_pembayaran ?? 'menunggu' }}">
                                                @if($registration->status_pembayaran == 'lunas')
                                                    <i class="fas fa-check me-1"></i>Lunas
                                                @elseif($registration->status_pembayaran == 'menunggu')
                                                    <i class="fas fa-clock me-1"></i>Menunggu
                                                @elseif($registration->status_pembayaran == 'dibatalkan')
                                                    <i class="fas fa-times me-1"></i>Dibatalkan
                                                @else
                                                    {{ $registration->status_pembayaran ?? 'Menunggu' }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="p-3 text-end">
                                        <div class="mb-3">
                                            <span class="h5 fw-bold text-primary">
                                                Rp {{ number_format($registration->paket_harga ?? 0, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ url('/registration/' . $registration->kode_pendaftaran . '/detail') }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>Detail
                                            </a>
                                            @if($registration->status_pembayaran == 'menunggu' && $registration->paket_harga > 0)
                                                <a href="{{ url('/registration/' . $registration->kode_pendaftaran . '/payment') }}" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fas fa-credit-card me-1"></i>Bayar
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $registrations->links() }}
                    </div>
                    
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="mb-4">
                            <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
                            <h4 class="fw-bold mb-3">Belum Ada Pendaftaran</h4>
                            <p class="text-muted mb-4">
                                Anda belum mendaftar event marathon apapun. <br>
                                Yuk, cari event menarik dan mulai perjalanan lari Anda!
                            </p>
                            <a href="{{ url('/events') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-running me-2"></i>Cari Event
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Important Info -->
        <div class="alert alert-info mt-4">
            <h6><i class="fas fa-info-circle me-2"></i>Informasi Penting</h6>
            <div class="row">
                <div class="col-md-4">
                    <p class="mb-1 small"><i class="fas fa-clock me-2 text-warning"></i><strong>Menunggu:</strong> Sedang dalam proses verifikasi</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1 small"><i class="fas fa-check me-2 text-success"></i><strong>Disetujui:</strong> Pendaftaran telah diterima</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1 small"><i class="fas fa-credit-card me-2 text-primary"></i><strong>Lunas:</strong> Pembayaran telah diverifikasi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-running me-2"></i>MARATHON EVENTS
                    </h5>
                    <p class="text-muted small">
                        Platform event lari terpercaya di Indonesia.
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ url('/') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-home me-2"></i>Beranda
                    </a>
                    <a href="{{ url('/events') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-calendar-alt me-2"></i>Event
                    </a>
                </div>
            </div>
            <hr class="my-3 bg-secondary">
            <div class="text-center">
                <p class="mb-0 small text-muted">
                    &copy; 2024 Marathon Events. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-refresh page every 30 seconds for status updates
        setTimeout(function() {
            window.location.reload();
        }, 30000);
        
        // Check for session messages
        @if(session('success'))
            alert('{{ session("success") }}');
        @endif
        
        @if(session('error'))
            alert('{{ session("error") }}');
        @endif
    </script>
</body>
</html>