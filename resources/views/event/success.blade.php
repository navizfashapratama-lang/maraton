<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - Marathon Events</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .success-card {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .success-header {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }
        
        .registration-code {
            background: white;
            color: #4361ee;
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            font-family: monospace;
            font-size: 1.25rem;
            font-weight: bold;
            display: inline-block;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="success-card">
                    <div class="success-header">
                        <div class="mb-4">
                            <i class="fas fa-check-circle fa-5x"></i>
                        </div>
                        <h2 class="fw-bold">Pendaftaran Berhasil!</h2>
                        <p class="mb-0">Terima kasih telah mendaftar event kami</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold mb-3">{{ $event->nama ?? 'Event Marathon' }}</h4>
                            <p class="text-muted">
                                <i class="fas fa-calendar me-2"></i>
                                {{ date('d F Y', strtotime($event->tanggal ?? now())) }}
                            </p>
                        </div>
                        
                        <div class="text-center mb-4">
                            <h5 class="fw-bold mb-2">Kode Pendaftaran</h5>
                            <div class="registration-code">
                                {{ $pendaftaran->kode_pendaftaran ?? 'REG-' . time() }}
                            </div>
                            <small class="text-muted">Simpan kode ini untuk keperluan verifikasi</small>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Langkah Selanjutnya</h6>
                            <ol class="mb-0">
                                <li>Simpan kode pendaftaran Anda</li>
                                <li>Cek email untuk konfirmasi pendaftaran</li>
                                <li>Ikuti instruksi pembayaran jika event berbayar</li>
                                <li>Datang 1 jam sebelum start untuk race kit collection</li>
                            </ol>
                        </div>
                        
                        @if(($paket->harga ?? 0) > 0)
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Pembayaran</h6>
                                <p class="mb-0">
                                    Event ini berbayar. Silakan lakukan pembayaran dalam 24 jam.
                                    <br>
                                    <strong>Total: Rp {{ number_format($paket->harga ?? 0, 0, ',', '.') }}</strong>
                                </p>
                            </div>
                        @else
                            <div class="alert alert-success">
                                <h6><i class="fas fa-gift me-2"></i>Event Gratis</h6>
                                <p class="mb-0">Pendaftaran Anda sudah disetujui. Tidak perlu pembayaran.</p>
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ url('/my-registrations') }}" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>Lihat Pendaftaran Saya
                            </a>
                            <a href="{{ url('/events') }}" class="btn btn-primary">
                                <i class="fas fa-calendar-alt me-2"></i>Cari Event Lain
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="{{ url('/') }}" class="text-decoration-none">
                        <i class="fas fa-home me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>