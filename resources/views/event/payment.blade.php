<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - {{ $registration->event_nama }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #eef2ff;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }
        
        body {
            background-color: #f8fafc;
            font-family: 'Poppins', sans-serif;
        }
        
        .payment-header {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            color: white;
            padding: 2.5rem 0;
            border-radius: 0 0 1.5rem 1.5rem;
            margin-bottom: 2rem;
        }
        
        .payment-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .bank-card {
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .bank-card:hover, .bank-card.selected {
            border-color: var(--primary);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-3px);
            background-color: var(--primary-light);
        }
        
        .bank-logo {
            width: 60px;
            height: 60px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: var(--primary);
            font-size: 1.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        }
        
        .required::after {
            content: " *";
            color: var(--danger);
        }
        
        .amount-box {
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            border: 2px dashed #6366f1;
        }

        .onsite-box {
            background: #fff7ed;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            border: 2px solid #fdba74;
            color: #9a3412;
        }
        
        .info-box {
            background: #f0f9ff;
            border-left: 4px solid #0ea5e9;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .copy-btn {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 2px 10px;
            font-size: 12px;
            margin-left: 5px;
        }

        .instructions-list {
            background: #f1f5f9;
            border-radius: 1rem;
            padding: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="payment-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2">
                        <i class="fas fa-credit-card me-2"></i>
                        Pembayaran Event
                    </h2>
                    <h4 class="mb-0">{{ $registration->event_nama }}</h4>
                    <p class="mb-0">
                        Kode Pendaftaran: <strong>{{ $registration->kode_pendaftaran }}</strong>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ url('/registration/' . $registration->kode_pendaftaran . '/detail') }}" 
                       class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="payment-card">
                    @if(strtolower($registration->metode_pembayaran ?? '') == 'cash')
                        <div class="onsite-box mb-4">
                            <i class="fas fa-hand-holding-dollar fa-3x mb-3"></i>
                            <h3 class="fw-bold">BAYAR DI TEMPAT (ONSITE)</h3>
                            <p class="mb-0">Pendaftaran Anda tercatat dengan metode tunai. Silakan datang ke lokasi event atau kantor kami untuk melakukan pembayaran manual kepada petugas.</p>
                            <hr>
                            <small class="text-muted italic">Status Laporan Staff: <strong>BAYAR ONSITE</strong></small>
                        </div>
                    @else
                        <div class="amount-box mb-4">
                            <h6 class="text-muted mb-2">Total yang harus dibayar</h6>
                            <h1 class="fw-bold text-primary">
                                Rp {{ number_format($registration->paket_harga, 0, ',', '.') }}
                            </h1>
                            <p class="text-muted mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                Transfer sesuai jumlah di atas
                            </p>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-bold mb-0">
                                <i class="fas fa-university me-2 text-primary"></i>
                                Transfer ke Rekening
                            </h4>
                            <button type="button" class="btn btn-sm btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#changeMethodModal">Ganti Metode?</button>
                        </div>
                        
                        <div class="row g-3 mb-4">
                            @php
                                $prefBank = strtoupper($registration->bank_tujuan ?? '');
                                $isFiltered = !empty($prefBank);
                            @endphp

                            @foreach($bankAccounts as $bank)
                                @if(!$isFiltered || $prefBank == strtoupper($bank['bank']))
                                <div class="col-md-{{ $isFiltered ? '12' : '6' }}">
                                    <div class="bank-card {{ $isFiltered ? 'selected' : '' }}">
                                        <div class="bank-logo">
                                            <i class="fas fa-university"></i>
                                        </div>
                                        <h6 class="fw-bold text-center mb-2">{{ $bank['bank'] }}</h6>
                                        <div class="text-center">
                                            <div class="fw-bold text-primary">
                                                <span>{{ $bank['number'] }}</span>
                                                <button onclick="copyText('{{ $bank['number'] }}')" class="copy-btn">SALIN</button>
                                            </div>
                                            <small class="text-muted">a.n {{ $bank['name'] }}</small>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ url('/registration/' . $registration->kode_pendaftaran . '/upload-payment') }}" 
                          method="POST" enctype="multipart/form-data" id="paymentForm">
                        @csrf
                        
                        <h4 class="fw-bold mb-3">
                            <i class="fas fa-file-upload me-2 text-primary"></i>
                            Upload Bukti Pembayaran
                        </h4>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Nama Pembayar</label>
                                <input type="text" name="nama_pembayar" class="form-control" 
                                       value="{{ session('user_nama', $registration->nama_lengkap) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Email</label>
                                <input type="email" name="email_pembayar" class="form-control" 
                                       value="{{ session('user_email', $registration->email) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Metode Pembayaran</label>
                                <select name="metode_pembayaran" class="form-select" required>
                                    <option value="transfer" {{ $registration->metode_pembayaran == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="cash" {{ $registration->metode_pembayaran == 'cash' ? 'selected' : '' }}>Bayar Onsite (Cash)</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Jumlah Transfer</label>
                                <input type="number" name="jumlah" class="form-control" 
                                       value="{{ $registration->paket_harga }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label {{ strtolower($registration->metode_pembayaran ?? '') == 'cash' ? '' : 'required' }}">Bukti Pembayaran</label>
                            <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" 
                                   {{ strtolower($registration->metode_pembayaran ?? '') == 'cash' ? '' : 'required' }}>
                            <small class="text-muted">Format: JPG, PNG (Maks 2MB)</small>
                        </div>

                        <div class="info-box mb-4">
                            <h6><i class="fas fa-lightbulb me-2 text-warning"></i>Cara Pembayaran:</h6>
                            <ol class="mb-0 small">
                                <li>Pilih metode transfer atau bayar onsite sesuai keinginan.</li>
                                <li>Lakukan pembayaran tepat sesuai nominal yang tertera.</li>
                                <li>Upload bukti transfer (khusus metode transfer/QRIS).</li>
                                <li>Staff kami akan memverifikasi dalam 1x24 jam kerja.</li>
                            </ol>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ url('/registration/' . $registration->kode_pendaftaran . '/detail') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Kirim Konfirmasi</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="payment-card">
                    <h5 class="fw-bold mb-3"><i class="fas fa-list-check me-2"></i>Ringkasan</h5>
                    <table class="table table-sm table-borderless small">
                        <tr><td>Event</td><td class="text-end fw-bold">{{ $registration->event_nama }}</td></tr>
                        <tr><td>Paket</td><td class="text-end">{{ $registration->paket_nama ?? 'Reguler' }}</td></tr>
                        <tr class="border-top"><td><strong>Total</strong></td><td class="text-end text-primary fw-bold">Rp {{ number_format($registration->paket_harga, 0, ',', '.') }}</td></tr>
                    </table>
                </div>

                <div class="alert alert-warning small">
                    <i class="fas fa-clock me-2"></i> <strong>Batas Waktu:</strong> Pembayaran harus diselesaikan dalam 24 jam sebelum pendaftaran kedaluwarsa.
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changeMethodModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Metode Lain</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <button type="button" class="list-group-item list-group-item-action" onclick="window.location.href='?method=transfer'">Transfer Bank</button>
                        <button type="button" class="list-group-item list-group-item-action" onclick="window.location.href='?method=cash'">Bayar di Tempat (Cash)</button>
                        <button type="button" class="list-group-item list-group-item-action" onclick="window.location.href='?method=qris'">QRIS</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => { alert('Berhasil disalin!'); });
        }

        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const fileInput = document.querySelector('input[name="bukti_pembayaran"]');
            if (fileInput.hasAttribute('required') && fileInput.files.length === 0) {
                e.preventDefault();
                alert('Wajib upload bukti pembayaran!');
                return;
            }
            this.querySelector('button[type="submit"]').disabled = true;
            this.querySelector('button[type="submit"]').innerHTML = "Memproses...";
        });

        // File Preview Info
        document.querySelector('input[name="bukti_pembayaran"]').addEventListener('change', function() {
            if(this.files[0]) {
                alert('File terpilih: ' + this.files[0].name);
            }
        });
    </script>
</body>
</html>