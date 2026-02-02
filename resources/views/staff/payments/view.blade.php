<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembayaran - Staff Area</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .payment-status {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .status-menunggu { background-color: #fff3cd; color: #856404; }
        .status-terverifikasi { background-color: #d4edda; color: #155724; }
        .status-ditolak { background-color: #f8d7da; color: #721c24; }
        .status-kadaluarsa { background-color: #d6d8d9; color: #1b1e21; }
        .info-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .amount-large {
            font-size: 2rem;
            font-weight: 700;
            color: #28a745;
        }
        .payment-proof {
            max-width: 100%;
            border: 2px solid #dee2e6;
            border-radius: 8px;
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
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('staff.payments.index') }}">
                            <i class="fas fa-credit-card"></i> Pembayaran
                        </a>
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
                <i class="fas fa-file-invoice-dollar text-primary"></i> Detail Pembayaran
            </h1>
            <div class="btn-group">
                <a href="{{ route('staff.payments.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                @if($payment->status == 'menunggu')
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verifyModal">
                        <i class="fas fa-check"></i> Verifikasi
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                @endif
            </div>
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

        <div class="row">
            <!-- Left Column - Payment Info -->
            <div class="col-md-8">
                <!-- Payment Status Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">
                                Status Pembayaran
                            </h5>
                            <span class="payment-status status-{{ $payment->status }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                        
                        <div class="text-center mb-4">
                            <div class="amount-large">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</div>
                            <p class="text-muted mb-0">{{ $payment->kode_pembayaran }}</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Metode Pembayaran</label>
                                    <p class="mb-0">
                                        <strong>{{ ucfirst($payment->metode_pembayaran) }}</strong>
                                        @if($payment->bank_tujuan)
                                            <br><small class="text-muted">Bank: {{ $payment->bank_tujuan }}</small>
                                        @endif
                                        @if($payment->nama_rekening)
                                            <br><small class="text-muted">Rekening: {{ $payment->nama_rekening }}</small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Tanggal</label>
                                    <p class="mb-0">
                                        <strong>{{ date('d/m/Y H:i', strtotime($payment->created_at)) }}</strong>
                                        @if($payment->tanggal_bayar)
                                            <br><small class="text-muted">
                                                Dibayar: {{ date('d/m/Y H:i', strtotime($payment->tanggal_bayar)) }}
                                            </small>
                                        @endif
                                        @if($payment->tanggal_verifikasi)
                                            <br><small class="text-muted">
                                                Diverifikasi: {{ date('d/m/Y H:i', strtotime($payment->tanggal_verifikasi)) }}
                                            </small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($payment->catatan_admin)
                            <div class="alert alert-info">
                                <strong><i class="fas fa-sticky-note"></i> Catatan Admin:</strong>
                                <p class="mb-0 mt-2">{{ $payment->catatan_admin }}</p>
                            </div>
                        @endif

                        @if($payment->diverifikasi_oleh)
                            <div class="text-end">
                                <small class="text-muted">
                                    Diverifikasi oleh: {{ $payment->diverifikasi_oleh }}
                                </small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Proof -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-receipt"></i> Bukti Pembayaran
                        </h5>
                        
                        @if($payment->bukti_pembayaran)
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $payment->bukti_pembayaran) }}" 
                                     alt="Bukti Pembayaran" 
                                     class="payment-proof img-fluid mb-3"
                                     style="max-height: 400px;">
                                <br>
                                <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-expand"></i> Lihat Full Size
                                </a>
                                <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" 
                                   download 
                                   class="btn btn-outline-success">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Bukti pembayaran belum diupload oleh peserta
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Payer & Registration Info -->
            <div class="col-md-4">
                <!-- Payer Information -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-user"></i> Informasi Pembayar
                        </h5>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Nama Pembayar</label>
                            <p class="mb-0"><strong>{{ $payment->nama_pembayar }}</strong></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Email</label>
                            <p class="mb-0">{{ $payment->email_pembayar }}</p>
                        </div>
                    </div>
                </div>

                <!-- Registration Information -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-calendar-check"></i> Informasi Pendaftaran
                        </h5>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Event</label>
                            <p class="mb-0"><strong>{{ $payment->event_nama }}</strong></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Tanggal Event</label>
                            <p class="mb-0">{{ date('d/m/Y', strtotime($payment->event_date)) }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Nama Peserta</label>
                            <p class="mb-0"><strong>{{ $payment->peserta_nama }}</strong></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Paket</label>
                            <p class="mb-0">{{ $payment->package_name }}</p>
                            <small class="text-muted">Harga: Rp {{ number_format($payment->package_price, 0, ',', '.') }}</small>
                        </div>

                        @if($payment->nomor_start)
                            <div class="alert alert-success">
                                <i class="fas fa-hashtag"></i> 
                                <strong>Nomor Start:</strong> {{ $payment->nomor_start }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                @if($payment->status == 'menunggu')
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-cogs"></i> Aksi Cepat
                            </h5>
                            
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-success" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#verifyModal">
                                    <i class="fas fa-check"></i> Verifikasi Pembayaran
                                </button>
                                
                                <button type="button" class="btn btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectModal">
                                    <i class="fas fa-times"></i> Tolak Pembayaran
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Verification Modal -->
    <div class="modal fade" id="verifyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verifikasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('staff.payments.verify', $payment->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Verifikasi pembayaran <strong>{{ $payment->kode_pembayaran }}</strong>?</p>
                        <p>Nama: {{ $payment->nama_pembayar }}</p>
                        <p>Jumlah: Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</p>
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Verifikasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('staff.payments.reject', $payment->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Tolak pembayaran <strong>{{ $payment->kode_pembayaran }}</strong>?</p>
                        <p>Nama: {{ $payment->nama_pembayar }}</p>
                        <p>Jumlah: Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</p>
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Alasan Penolakan *</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3" required></textarea>
                            <small class="text-muted">Berikan alasan mengapa pembayaran ditolak</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                    </div>
                </form>
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

        // Image preview error handling
        document.addEventListener('DOMContentLoaded', function() {
            const paymentProofImg = document.querySelector('.payment-proof');
            if (paymentProofImg) {
                paymentProofImg.onerror = function() {
                    this.onerror = null;
                    this.src = 'https://via.placeholder.com/400x300?text=Bukti+Pembayaran+Tidak+Ditemukan';
                    this.alt = 'Gambar tidak ditemukan';
                };
            }
        });
    </script>
</body>
</html>