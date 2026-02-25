<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - Marathon Runner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .success-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 700px;
            width: 100%;
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            background: #4CAF50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            color: white;
            font-size: 40px;
        }
        
        .details-box {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            border-left: 5px solid #4CAF50;
        }
        
        .detail-item {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaeaea;
        }
        
        .detail-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #555;
            min-width: 150px;
        }
        
        .detail-value {
            color: #333;
            flex: 1;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-custom {
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            border: none;
            color: white;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(76, 175, 80, 0.3);
        }
        
        .btn-secondary-custom {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            color: #495057;
        }
        
        .btn-secondary-custom:hover {
            background: #e9ecef;
            border-color: #adb5bd;
            transform: translateY(-2px);
        }
        
        .kode-pendaftaran {
            font-size: 24px;
            font-weight: bold;
            color: #2E7D32;
            letter-spacing: 2px;
            background: #e8f5e9;
            padding: 10px 20px;
            border-radius: 10px;
            display: inline-block;
            margin: 10px 0;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
            position: relative;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        
        .step-number {
            width: 50px;
            height: 50px;
            background: #dee2e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #6c757d;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        
        .step.active .step-number {
            background: #4CAF50;
            color: white;
            box-shadow: 0 0 0 5px rgba(76, 175, 80, 0.2);
        }
        
        .step-label {
            font-size: 14px;
            color: #6c757d;
            text-align: center;
        }
        
        .step.active .step-label {
            color: #4CAF50;
            font-weight: 600;
        }
        
        .step-line {
            position: absolute;
            top: 25px;
            left: 0;
            right: 0;
            height: 3px;
            background: #dee2e6;
            z-index: 1;
        }
        
        .step-line-fill {
            position: absolute;
            top: 25px;
            left: 0;
            height: 3px;
            background: #4CAF50;
            z-index: 1;
            transition: width 0.5s ease;
        }
        
        .countdown-timer {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
        }
        
        .timer-text {
            font-size: 18px;
            font-weight: 600;
        }
        
        .timer-value {
            font-size: 24px;
            font-weight: bold;
            color: #FFD700;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1 class="text-center mb-3" style="color: #4CAF50;">Pendaftaran Berhasil!</h1>
            <p class="text-center text-muted mb-4">
                Terima kasih telah mendaftar event {{ $registration->event_nama ?? 'Marathon' }}. 
                Berikut adalah detail pendaftaran Anda:
            </p>
            
            <div class="details-box">
                <h4 class="mb-4"><i class="fas fa-info-circle me-2"></i>Detail Pendaftaran</h4>
                
                <div class="detail-item">
                    <div class="detail-label">Kode Pendaftaran:</div>
                    <div class="detail-value">
                        <div class="kode-pendaftaran">{{ $registration->kode_pendaftaran ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Nama Lengkap:</div>
                    <div class="detail-value">{{ $registration->nama_lengkap ?? 'N/A' }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Event:</div>
                    <div class="detail-value">{{ $registration->event_nama ?? 'N/A' }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Tanggal Event:</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($registration->tanggal ?? now())->translatedFormat('d F Y') }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Lokasi:</div>
                    <div class="detail-value">{{ $registration->lokasi ?? 'N/A' }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Paket:</div>
                    <div class="detail-value">{{ $registration->package_name ?? 'N/A' }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Harga:</div>
                    <div class="detail-value">
                        <span class="fw-bold" style="color: #4CAF50;">
                            Rp {{ number_format($registration->package_price ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Status Pendaftaran:</div>
                    <div class="detail-value">
                        @if($registration->status_pendaftaran == 'menunggu')
                            <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                        @elseif($registration->status_pendaftaran == 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                            @if($registration->nomor_start)
                                <span class="ms-2">Nomor Start: <strong>{{ $registration->nomor_start }}</strong></span>
                            @endif
                        @elseif($registration->status_pendaftaran == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-secondary">{{ $registration->status_pendaftaran }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Status Pembayaran:</div>
                    <div class="detail-value">
                        @if($registration->status_pembayaran == 'menunggu')
                            <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                        @elseif($registration->status_pembayaran == 'lunas')
                            <span class="badge bg-success">Lunas</span>
                        @elseif($registration->status_pembayaran == 'gagal')
                            <span class="badge bg-danger">Gagal</span>
                        @else
                            <span class="badge bg-secondary">{{ $registration->status_pembayaran }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Tanggal Daftar:</div>
                    <div class="detail-value">
                        {{ \Carbon\Carbon::parse($registration->created_at ?? now())->translatedFormat('d F Y H:i') }}
                    </div>
                </div>
            </div>
            
            <!-- Progress Steps -->
            <div class="step-indicator">
                <div class="step-line"></div>
                <div class="step-line-fill" id="stepLineFill" style="width: 33%;"></div>
                
                <div class="step active">
                    <div class="step-number">1</div>
                    <div class="step-label">Pendaftaran</div>
                </div>
                
                <div class="step {{ $registration->status_pembayaran == 'lunas' || $registration->status_pembayaran == 'menunggu' ? 'active' : '' }}">
                    <div class="step-number">2</div>
                    <div class="step-label">Pembayaran</div>
                </div>
                
                <div class="step {{ $registration->status_pembayaran == 'lunas' ? 'active' : '' }}">
                    <div class="step-number">3</div>
                    <div class="step-label">Konfirmasi</div>
                </div>
            </div>
            
            <!-- Countdown Timer untuk Pembayaran (jika belum lunas) -->
            @if($registration->status_pembayaran == 'menunggu')
            <div class="countdown-timer">
                <div class="timer-text">Selesaikan pembayaran dalam:</div>
                <div class="timer-value" id="countdownTimer">24:00:00</div>
                <small class="d-block mt-2">Pembayaran setelah 24 jam akan dibatalkan otomatis</small>
            </div>
            @endif
            
            <!-- Informasi Penting -->
            <div class="alert alert-info mt-4">
                <h5><i class="fas fa-exclamation-circle me-2"></i>Informasi Penting:</h5>
                <ul class="mb-0 mt-2">
                    <li>Simpan kode pendaftaran untuk keperluan verifikasi</li>
                    @if($registration->status_pembayaran == 'menunggu')
                    <li>Lakukan pembayaran dalam 24 jam untuk menghindari pembatalan</li>
                    <li>Upload bukti pembayaran di halaman profil atau melalui tombol di bawah</li>
                    @endif
                    <li>Status pendaftaran dapat dipantau di halaman profil Anda</li>
                    <li>Konfirmasi akan dikirim via email setelah pembayaran diverifikasi</li>
                </ul>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons mt-5">
                @if($registration->status_pembayaran == 'menunggu')
                <a href="{{ route('payment.instructions', $registration->id) }}" class="btn btn-primary-custom btn-custom">
                    <i class="fas fa-credit-card me-2"></i>Instruksi Pembayaran
                </a>
                
                <a href="{{ route('payment.upload-proof', $registration->id) }}" class="btn btn-success btn-custom">
                    <i class="fas fa-upload me-2"></i>Upload Bukti Bayar
                </a>
                @endif
                
                <a href="{{ route('profile') }}" class="btn btn-secondary-custom btn-custom">
                    <i class="fas fa-user me-2"></i>Ke Profil Saya
                </a>
                
                <a href="{{ route('events') }}" class="btn btn-light btn-custom">
                    <i class="fas fa-calendar-alt me-2"></i>Event Lainnya
                </a>
                
                <button onclick="window.print()" class="btn btn-outline-primary btn-custom">
                    <i class="fas fa-print me-2"></i>Cetak Halaman
                </button>
            </div>
            
            <!-- Download sebagai PDF (opsional) -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="fas fa-envelope me-1"></i>Detail pendaftaran juga dikirim ke email: 
                    <strong>{{ $registration->email }}</strong>
                </small>
                <br>
                <small class="text-muted">
                    <i class="fas fa-question-circle me-1"></i>Butuh bantuan? 
                    <a href="{{ route('contact') }}" class="text-decoration-none">Hubungi kami</a>
                </small>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Countdown Timer 24 jam
        @if($registration->status_pembayaran == 'menunggu')
        let countdown = 24 * 60 * 60; // 24 jam dalam detik
        const timerElement = document.getElementById('countdownTimer');
        
        function updateTimer() {
            if (countdown <= 0) {
                timerElement.textContent = "WAKTU HABIS";
                timerElement.style.color = "#FF0000";
                return;
            }
            
            const hours = Math.floor(countdown / 3600);
            const minutes = Math.floor((countdown % 3600) / 60);
            const seconds = countdown % 60;
            
            timerElement.textContent = 
                `${hours.toString().padStart(2, '0')}:` +
                `${minutes.toString().padStart(2, '0')}:` +
                `${seconds.toString().padStart(2, '0')}`;
            
            countdown--;
        }
        
        // Update timer setiap detik
        updateTimer();
        setInterval(updateTimer, 1000);
        @endif
        
        // Animasi step progress
        document.addEventListener('DOMContentLoaded', function() {
            const status = '{{ $registration->status_pembayaran }}';
            const stepLineFill = document.getElementById('stepLineFill');
            
            if (status === 'lunas') {
                stepLineFill.style.width = '100%';
            } else if (status === 'menunggu') {
                stepLineFill.style.width = '33%';
            } else {
                stepLineFill.style.width = '0%';
            }
        });
        
        // Simpan kode pendaftaran ke clipboard
        function copyToClipboard() {
            const kode = '{{ $registration->kode_pendaftaran }}';
            navigator.clipboard.writeText(kode).then(() => {
                alert('Kode pendaftaran berhasil disalin!');
            });
        }
    </script>
</body>
</html>