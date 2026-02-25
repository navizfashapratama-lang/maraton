<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instruksi Pembayaran - Marathon Runner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .payment-container {
            max-width: 1000px;
            margin: 40px auto;
        }
        
        .payment-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .payment-header {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .payment-body {
            padding: 40px;
        }
        
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin: 40px 0;
        }
        
        .method-card {
            border: 2px solid #eaeaea;
            border-radius: 15px;
            padding: 25px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .method-card:hover {
            border-color: #4CAF50;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(76, 175, 80, 0.1);
        }
        
        .method-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 30px;
        }
        
        .bank-details {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin: 20px 0;
            border-left: 5px solid #4CAF50;
        }
        
        .detail-item {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #dee2e6;
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
            font-family: monospace;
            font-size: 16px;
        }
        
        .copy-btn {
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 15px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .copy-btn:hover {
            background: #388E3C;
        }
        
        .amount-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            margin: 30px 0;
        }
        
        .amount-label {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .amount-value {
            font-size: 36px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .qr-code-container {
            text-align: center;
            margin: 30px 0;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .qr-code-placeholder {
            width: 200px;
            height: 200px;
            background: #f0f0f0;
            border-radius: 10px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 14px;
        }
        
        .instructions-list {
            background: #e8f5e9;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
        }
        
        .instructions-list ol {
            padding-left: 20px;
        }
        
        .instructions-list li {
            margin-bottom: 15px;
            padding-left: 10px;
        }
        
        .upload-section {
            background: #fff3cd;
            border: 2px dashed #ffc107;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin: 40px 0;
        }
        
        .upload-icon {
            font-size: 50px;
            color: #ffc107;
            margin-bottom: 20px;
        }
        
        .upload-btn {
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .upload-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 152, 0, 0.3);
        }
        
        .countdown-box {
            background: #dc3545;
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
        }
        
        .timer-value {
            font-size: 24px;
            font-weight: bold;
            color: #FFD700;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }
        
        .btn-custom {
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            min-width: 180px;
        }
        
        .btn-success-custom {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            border: none;
            color: white;
        }
        
        .btn-success-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(76, 175, 80, 0.3);
        }
        
        .btn-outline-custom {
            border: 2px solid #4CAF50;
            color: #4CAF50;
            background: transparent;
        }
        
        .btn-outline-custom:hover {
            background: #4CAF50;
            color: white;
        }
        
        .alert-note {
            background: #e3f2fd;
            border-left: 5px solid #2196F3;
            padding: 20px;
            border-radius: 10px;
            margin: 25px 0;
        }
        
        .payment-steps {
            display: flex;
            justify-content: space-between;
            margin: 40px 0;
            position: relative;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
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
            margin-bottom: 15px;
        }
        
        .step.active .step-number {
            background: #4CAF50;
            color: white;
            box-shadow: 0 0 0 5px rgba(76, 175, 80, 0.2);
        }
        
        .step-label {
            font-size: 14px;
            color: #6c757d;
            max-width: 150px;
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
        
        .verified-badge {
            background: #4CAF50;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container payment-container">
        <div class="payment-card">
            <!-- Header -->
            <div class="payment-header">
                <h1 class="mb-3"><i class="fas fa-credit-card me-2"></i>Instruksi Pembayaran</h1>
                <p class="mb-0">Silakan selesaikan pembayaran untuk pendaftaran event Anda</p>
            </div>
            
            <!-- Body -->
            <div class="payment-body">
                <!-- Registration Info -->
                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <div class="detail-label">Kode Pendaftaran:</div>
                            <div class="detail-value">
                                <strong>{{ $registration->kode_pendaftaran ?? 'N/A' }}</strong>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Event:</div>
                            <div class="detail-value">{{ $registration->event_nama ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <div class="detail-label">Nama Peserta:</div>
                            <div class="detail-value">{{ $registration->nama_lengkap ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Tanggal Daftar:</div>
                            <div class="detail-value">
                                {{ \Carbon\Carbon::parse($registration->created_at ?? now())->translatedFormat('d F Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Progress Steps -->
                <div class="payment-steps">
                    <div class="step-line"></div>
                    <div class="step-line-fill" id="stepLineFill" style="width: 33%;"></div>
                    
                    <div class="step active">
                        <div class="step-number">1</div>
                        <div class="step-label">Pendaftaran<br>Selesai</div>
                    </div>
                    
                    <div class="step active">
                        <div class="step-number">2</div>
                        <div class="step-label">Pembayaran</div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-label">Verifikasi<br>Pembayaran</div>
                    </div>
                </div>
                
                <!-- Amount Box -->
                <div class="amount-box">
                    <div class="amount-label">Total yang harus dibayar:</div>
                    <div class="amount-value">Rp {{ number_format($registration->package_price ?? 0, 0, ',', '.') }}</div>
                    <div class="amount-label">
                        <small>Untuk: {{ $registration->package_name ?? 'Paket Event' }}</small>
                    </div>
                </div>
                
                <!-- Countdown Timer -->
                <div class="countdown-box">
                    <div><i class="fas fa-clock me-2"></i>Selesaikan pembayaran dalam:</div>
                    <div class="timer-value" id="countdownTimer">24:00:00</div>
                    <small>Pembayaran setelah 24 jam akan dibatalkan otomatis</small>
                </div>
                
                <!-- Payment Methods -->
                <h3 class="mb-4"><i class="fas fa-money-bill-wave me-2"></i>Metode Pembayaran</h3>
                <div class="payment-methods">
                    <!-- Transfer Bank -->
                    <div class="method-card">
                        <div class="method-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <h4 class="text-center mb-4">Transfer Bank</h4>
                        
                        <div class="bank-details">
                            <div class="detail-item">
                                <div class="detail-label">Bank:</div>
                                <div class="detail-value">
                                    BCA (Bank Central Asia)
                                    <span class="verified-badge">
                                        <i class="fas fa-check-circle"></i> Terverifikasi
                                    </span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Nomor Rekening:</div>
                                <div class="detail-value">
                                    123-456-7890
                                    <button onclick="copyText('123-456-7890')" class="copy-btn ms-2">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Nama Penerima:</div>
                                <div class="detail-value">
                                    PT Marathon Runner Indonesia
                                    <button onclick="copyText('PT Marathon Runner Indonesia')" class="copy-btn ms-2">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Kode Bank:</div>
                                <div class="detail-value">014 (BCA)</div>
                            </div>
                        </div>
                        
                        <div class="alert-note">
                            <strong><i class="fas fa-info-circle me-2"></i>Catatan Penting:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Transfer tepat sesuai nominal: <strong>Rp {{ number_format($registration->package_price ?? 0, 0, ',', '.') }}</strong></li>
                                <li>Tambahkan 3 digit unik: <strong id="uniqueDigits">123</strong></li>
                                <li>Total transfer: <strong id="totalTransfer">Rp {{ number_format(($registration->package_price ?? 0) + 123, 0, ',', '.') }}</strong></li>
                                <li>Simpan bukti transfer untuk upload</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- E-Wallet / QRIS -->
                    <div class="method-card">
                        <div class="method-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h4 class="text-center mb-4">QRIS / E-Wallet</h4>
                        
                        <div class="qr-code-container">
                            <div class="qr-code-placeholder">
                                <div>
                                    <i class="fas fa-qrcode fa-3x mb-3"></i>
                                    <div>QR Code akan muncul<br>setelah tombol dibawah ditekan</div>
                                </div>
                            </div>
                            <p class="mb-3">Scan QR Code dengan aplikasi:</p>
                            <div class="d-flex justify-content-center gap-3 mb-3">
                                <span class="badge bg-primary">Dana</span>
                                <span class="badge bg-success">OVO</span>
                                <span class="badge bg-info">Gopay</span>
                                <span class="badge bg-warning text-dark">ShopeePay</span>
                                <span class="badge bg-dark">LinkAja</span>
                            </div>
                            <button class="btn btn-primary" onclick="generateQRCode()">
                                <i class="fas fa-sync-alt me-2"></i>Generate QR Code
                            </button>
                        </div>
                        
                        <div class="alert-note">
                            <strong><i class="fas fa-lightbulb me-2"></i>Tips:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Pastikan saldo e-wallet mencukupi</li>
                                <li>Screenshot halaman konfirmasi pembayaran</li>
                                <li>QR Code berlaku 24 jam</li>
                                <li>Pembayaran otomatis diverifikasi</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Instructions -->
                <div class="instructions-list">
                    <h4><i class="fas fa-list-ol me-2"></i>Cara Pembayaran</h4>
                    <ol>
                        <li><strong>Pilih metode pembayaran</strong> di atas yang Anda inginkan</li>
                        <li><strong>Transfer atau bayar</strong> sesuai nominal yang tertera</li>
                        <li><strong>Upload bukti pembayaran</strong> melalui tombol di bawah</li>
                        <li><strong>Tunggu verifikasi</strong> dari admin (maksimal 2x24 jam)</li>
                        <li><strong>Status akan berubah</strong> menjadi "Lunas" setelah diverifikasi</li>
                        <li><strong>Konfirmasi email</strong> akan dikirim setelah pembayaran berhasil</li>
                    </ol>
                </div>
                
                <!-- Upload Section -->
                <div class="upload-section">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <h4>Upload Bukti Pembayaran</h4>
                    <p class="mb-4">Setelah melakukan pembayaran, upload bukti transfer atau screenshot pembayaran</p>
                    
                    <form action="{{ route('payment.upload-proof', $registration->id) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="bukti_pembayaran" class="form-label">File Bukti Pembayaran</label>
                                    <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*,.pdf" required>
                                    <div class="form-text">Format: JPG, PNG, PDF (maks. 2MB)</div>
                                </div>
                                <div class="mb-3">
                                    <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                                    <select class="form-select" id="metode_pembayaran" name="metode_pembayaran" required>
                                        <option value="">Pilih metode...</option>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="bca">BCA Mobile</option>
                                        <option value="mandiri">Mandiri Online</option>
                                        <option value="bri">BRI Mobile</option>
                                        <option value="dana">Dana</option>
                                        <option value="ovo">OVO</option>
                                        <option value="gopay">GoPay</option>
                                        <option value="shopeepay">ShopeePay</option>
                                        <option value="linkaja">LinkAja</option>
                                        <option value="qris">QRIS</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="jumlah" class="form-label">Jumlah Dibayar</label>
                                    <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                           value="{{ $registration->package_price ?? 0 }}" min="{{ $registration->package_price ?? 0 }}" required>
                                    <div class="form-text">Isi sesuai nominal yang Anda transfer</div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="upload-btn">
                            <i class="fas fa-upload me-2"></i>Upload Bukti Pembayaran
                        </button>
                    </form>
                </div>
                
                <!-- Important Notes -->
                <div class="alert alert-warning">
                    <h5><i class="fas fa-exclamation-triangle me-2"></i>Perhatian!</h5>
                    <ul class="mb-0 mt-2">
                        <li>Pembayaran akan otomatis dibatalkan jika melebihi 24 jam</li>
                        <li>Pastikan bukti pembayaran jelas dan terbaca</li>
                        <li>Nomor referensi/transaksi harus terlihat jelas</li>
                        <li>Pembayaran palsu akan dikenakan sanksi</li>
                        <li>Hubungi customer service jika mengalami kendala</li>
                    </ul>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="submit" form="uploadForm" class="btn btn-success-custom btn-custom">
                        <i class="fas fa-paper-plane me-2"></i>Submit Pembayaran
                    </button>
                    
                    <a href="{{ route('profile') }}" class="btn btn-outline-custom btn-custom">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Profil
                    </a>
                    
                    <a href="{{ route('registration.success', $registration->id) }}" class="btn btn-info btn-custom">
                        <i class="fas fa-receipt me-2"></i>Detail Pendaftaran
                    </a>
                    
                    <button onclick="window.print()" class="btn btn-secondary btn-custom">
                        <i class="fas fa-print me-2"></i>Cetak Halaman
                    </button>
                </div>
                
                <!-- Contact Support -->
                <div class="text-center mt-5">
                    <p class="text-muted">
                        <i class="fas fa-headset me-2"></i>Butuh bantuan? 
                        <a href="{{ route('contact') }}" class="text-decoration-none fw-bold">Hubungi Customer Service</a>
                        <br>
                        <small>Jam operasional: 08:00 - 17:00 WIB (Senin - Jumat)</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Countdown Timer 24 jam
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
            
            // Update progress bar based on time remaining
            const progressFill = document.getElementById('stepLineFill');
            const progressPercent = 33 + ((24 - hours) / 24 * 33);
            progressFill.style.width = `${Math.min(progressPercent, 66)}%`;
            
            countdown--;
        }
        
        // Update timer setiap detik
        updateTimer();
        setInterval(updateTimer, 1000);
        
        // Copy text to clipboard
        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Berhasil disalin: ' + text);
            }).catch(err => {
                console.error('Gagal menyalin: ', err);
            });
        }
        
        // Generate unique 3 digits for transfer
        function generateUniqueDigits() {
            const unique = Math.floor(Math.random() * 900) + 100; // 100-999
            document.getElementById('uniqueDigits').textContent = unique;
            
            const packagePrice = {{ $registration->package_price ?? 0 }};
            const total = packagePrice + unique;
            document.getElementById('totalTransfer').textContent = 
                'Rp ' + total.toLocaleString('id-ID');
        }
        
        // Generate QR Code placeholder
        function generateQRCode() {
            const qrPlaceholder = document.querySelector('.qr-code-placeholder');
            qrPlaceholder.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div>Membuat QR Code...</div>
                </div>
            `;
            
            // Simulate API call
            setTimeout(() => {
                qrPlaceholder.innerHTML = `
                    <div class="text-center">
                        <div style="width: 180px; height: 180px; background: #000; margin: 0 auto 10px; position: relative;">
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 12px;">
                                QR CODE<br>SIMULASI
                            </div>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            QR Code berhasil digenerate
                        </div>
                        <small class="text-muted">Scan dengan aplikasi e-wallet</small>
                    </div>
                `;
                
                alert('QR Code berhasil digenerate! QR Code berlaku selama 24 jam.');
            }, 2000);
        }
        
        // Form validation
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('bukti_pembayaran');
            const methodSelect = document.getElementById('metode_pembayaran');
            const amountInput = document.getElementById('jumlah');
            const minAmount = {{ $registration->package_price ?? 0 }};
            
            // Validate file
            if (fileInput.files.length === 0) {
                e.preventDefault();
                alert('Silakan pilih file bukti pembayaran!');
                return;
            }
            
            const file = fileInput.files[0];
            const maxSize = 2 * 1024 * 1024; // 2MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            
            if (file.size > maxSize) {
                e.preventDefault();
                alert('Ukuran file maksimal 2MB!');
                return;
            }
            
            if (!allowedTypes.includes(file.type)) {
                e.preventDefault();
                alert('Format file harus JPG, PNG, atau PDF!');
                return;
            }
            
            // Validate payment method
            if (!methodSelect.value) {
                e.preventDefault();
                alert('Silakan pilih metode pembayaran!');
                return;
            }
            
            // Validate amount
            if (parseFloat(amountInput.value) < minAmount) {
                e.preventDefault();
                alert(`Jumlah pembayaran minimal Rp ${minAmount.toLocaleString('id-ID')}!`);
                return;
            }
            
            // Show loading
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupload...';
            submitBtn.disabled = true;
        });
        
        // Initialize unique digits on page load
        document.addEventListener('DOMContentLoaded', function() {
            generateUniqueDigits();
            
            // Auto-select payment method based on URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            const method = urlParams.get('method');
            if (method) {
                const methodSelect = document.getElementById('metode_pembayaran');
                const option = Array.from(methodSelect.options).find(opt => opt.value === method);
                if (option) {
                    methodSelect.value = method;
                }
            }
        });
        
        // File preview
        document.getElementById('bukti_pembayaran').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // You can add preview functionality here
                    console.log('File selected:', file.name);
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>